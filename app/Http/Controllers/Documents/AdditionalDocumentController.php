<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\AdditionalDocument;
use App\Models\AdditionalDocumentType;
use App\Models\Department;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdditionalDocumentController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'documents.additional-documents.dashboard',
            'search' => 'documents.additional-documents.search',
            'create' => 'documents.additional-documents.create',
            'list' => 'documents.additional-documents.list',
        ];

        if ($page === 'create') {
            $additionalDocumentTypes = AdditionalDocumentType::orderBy('type_name')->get();
            $departments = Department::whereNotNull('location_code')
                ->orderBy('department_name')
                ->get();

            return view($views[$page], compact('additionalDocumentTypes', 'departments'));
        } elseif ($page === 'dashboard') {
            $dashboardData = [
                'outs' => $this->outs_addoc(),
                'type' => $this->outs_addocs_by_type(),
            ];

            return view($views[$page], compact('dashboardData'));
        } elseif ($page === 'search') {
            $documentTypes = AdditionalDocumentType::orderBy('type_name')->get();
            $locationCodes = Department::whereNotNull('location_code')
                ->select('location_code', 'project', 'department_name')
                ->distinct('location_code')
                ->get();

            return view($views[$page], compact('documentTypes', 'locationCodes'));
        }

        return view($views[$page]);
    }

    public function edit($id)
    {
        $additionalDocument = AdditionalDocument::findOrFail($id);
        $additionalDocumentTypes = AdditionalDocumentType::all();
        $invoices = Invoice::with('supplier')->where('status', 'open')->get(); // Ensure invoices are fetched with suppliers

        return view('documents.additional-documents.edit', compact('additionalDocument', 'additionalDocumentTypes', 'invoices'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'type_id' => 'required|exists:additional_document_types,id',
            'document_number' => 'required|string|max:255',
            'document_date' => 'required|date',
        ]);

        $additionalDocument = AdditionalDocument::findOrFail($id);
        if ($request->has('receive_date')) {
            $validatedData['receive_date'] = $request->receive_date;
        }

        if ($request->has('invoice_id')) {
            $validatedData['invoice_id'] = $request->invoice_id;
        }
        $additionalDocument->update($validatedData);

        saveLog('additional_document', $additionalDocument->id, 'update', Auth::user()->id, 5);
        Alert::success('Success', 'Additional Document updated successfully.');

        return redirect()->route('documents.additional-documents.index', ['page' => 'search']);
    }

    public function destroy($id)
    {
        $additionalDocument = AdditionalDocument::findOrFail($id);
        $additionalDocument->delete();

        saveLog('additional_document', $additionalDocument->id, 'delete', Auth::user()->id, 5);
        Alert::success('Success', 'Additional Document deleted successfully.');

        return redirect()->route('documents.additional-documents.index');
    }

    public function show($id)
    {
        $additionalDocument = AdditionalDocument::findOrFail($id);

        return view('documents.additional-documents.show', compact('additionalDocument'));
    }

    public function checkDocumentCombination(Request $request)
    {
        $exists = AdditionalDocument::where('type_id', $request->type_id)
            ->where('document_number', $request->document_number)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function searchInvoicesByPo(Request $request)
    {
        $poNo = $request->query('po_no');

        // Get invoices with the same PO number and eager load the supplier relationship
        $invoices = Invoice::with('supplier')
            ->where('po_no', $poNo)
            ->get();

        // Get additional documents with the same PO number that are not attached to any invoice
        $documents = AdditionalDocument::where('po_no', $poNo)
            ->whereDoesntHave('invoices')
            ->with(['type', 'invoices'])  // Eager load both type and invoices relationships
            ->get();

        return response()->json([
            'invoices' => $invoices,
            'documents' => $documents,
        ]);
    }

    public function searchAdditionalDocumentsByPo(Request $request)
    {
        $poNo = $request->query('po_no');

        $documents = AdditionalDocument::where('po_no', $poNo)
            ->with(['type', 'invoices'])
            ->get();

        return response()->json($documents);
    }

    public function data()
    {
        $documents = AdditionalDocument::query();
        $documents = $documents->whereHas('type', function ($query) {
            $query->where('type_name', 'ito');
        })
            // ->whereNull('invoice_id')
            ->whereNull('receive_date');

        return datatables()->of($documents)
            ->editColumn('document_date', function ($row) {
                return $row->document_date ? \Carbon\Carbon::parse($row->document_date)->format('d-M-Y') : 'N/A';
            })
            ->addColumn('document_type', function ($row) {
                return $row->type ? $row->type->type_name : 'N/A';
            })
            ->addColumn('invoice_number', function ($row) {
                return $row->invoices->pluck('invoice_number')->implode(', ') ?: '-';
            })
            ->addColumn('days', function ($row) {
                return (int) \Carbon\Carbon::parse($row->created_at)->diffInDays(now());
            })
            ->addIndexColumn()
            ->addColumn('action', 'documents.additional-documents.action')
            ->rawColumns(['action'])
            ->toJson();
    }

    public function outs_addoc()
    {
        $addocs = AdditionalDocument::whereDoesntHave('invoices')
            ->orWhereNull('receive_date')
            ->get();
            
        $orphan = $addocs->filter(function ($addoc) {
            return $addoc->invoices->isEmpty();
        })->count();
        
        $not_receive = $addocs->whereNull('receive_date')->count();
        $last_added_count = AdditionalDocument::whereDate('created_at', \Carbon\Carbon::parse(AdditionalDocument::max('created_at'))->toDateString())->count();

        $lest_than_a_week = $addocs->filter(function ($addoc) {
            return \Carbon\Carbon::parse($addoc->created_at)->diffInDays(now()) < 7;
        })->count();

        $older_than_week = $addocs->filter(function ($addoc) {
            return \Carbon\Carbon::parse($addoc->created_at)->diffInDays(now()) > 7;
        })->count();

        $older_than_a_month = $addocs->filter(function ($addoc) {
            return \Carbon\Carbon::parse($addoc->created_at)->diffInDays(now()) > 30;
        })->count();

        return [
            [
                'description' => 'Orphan',
                'count' => $orphan,
            ],
            [
                'description' => 'Dokumen belum diterima',
                'count' => $not_receive,
            ],
            [
                'description' => 'New additional documents',
                'count' => $last_added_count,
            ],
            [
                'description' => 'Less than a week',
                'count' => $lest_than_a_week,
            ],
            [
                'description' => 'Older than a week',
                'count' => $older_than_week,
            ],
            [
                'description' => 'Older than a month',
                'count' => $older_than_a_month,
            ],
        ];
    }

    public function outs_addocs_by_type()
    {
        $addoc_types = AdditionalDocumentType::orderBy('type_name', 'asc')->get();
        $addocs = AdditionalDocument::whereDoesntHave('invoices')
            ->orWhereNull('receive_date')
            ->with('type')
            ->get();

        $data = [];

        foreach ($addoc_types as $type) {
            $count = $addocs->filter(function ($addoc) use ($type) {
                return $addoc->type_id === $type->id;
            })->count();
            
            if ($count > 0) {
                $data[] = [
                    'type' => $type->type_name,
                    'count' => $count,
                ];
            }
        }

        $totalCount = array_sum(array_column($data, 'count'));

        return [
            'data' => $data,
            'total_count' => $totalCount,
        ];
    }

    public function searchData(Request $request)
    {
        $query = AdditionalDocument::with('type');

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('type', function ($row) {
                return $row->type ? $row->type->type_name : 'N/A';
            })
            ->addColumn('invoice_number', function ($row) {
                return $row->invoices->pluck('invoice_number')->implode(', ') ?: '-';
            })
            ->editColumn('receive_date', function ($row) {
                return $row->receive_date ? \Carbon\Carbon::parse($row->receive_date)->format('d-M-Y') : '-';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                        <a href="' . route('documents.additional-documents.edit', $row->id) . '" class="btn btn-xs btn-warning mr-2" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="' . route('documents.additional-documents.show', $row->id) . '" class="btn btn-xs btn-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>';
            })
            ->rawColumns(['action'])
            ->filter(function ($query) use ($request) {
                if ($request->filled('document_number')) {
                    $query->where('document_number', 'like', '%' . $request->document_number . '%');
                }
                if ($request->filled('type_id')) {
                    $query->where('type_id', $request->type_id);
                }
                if ($request->filled('po_no')) {
                    $query->where('po_no', 'like', '%' . $request->po_no . '%');
                }
                if ($request->filled('invoice_number')) {
                    $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
                }
                if ($request->filled('cur_loc')) {
                    $query->where('cur_loc', $request->cur_loc);
                }
            })
            ->toJson();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_id' => 'required|exists:additional_document_types,id',
            'document_number' => 'required|string|max:255',
            'document_date' => 'required|date',
            'receive_date' => 'nullable|date',
            'po_no' => 'nullable|string|max:50',
            'project' => 'nullable|string|max:50',
            'remarks' => 'nullable|string|max:255',
            'invoice_id' => 'nullable|exists:invoices,id',
            'status' => 'nullable|string|max:20',
            'cur_loc' => 'nullable|string|max:30',
        ]);

        // Remove invoice_id from the data to be inserted
        $createData = collect($validated)->except('invoice_id')->toArray();
        $createData['created_by'] = Auth::user()->id;
        
        // Set default values if not provided
        $createData['status'] = $createData['status'] ?? 'open';
        $createData['cur_loc'] = $createData['cur_loc'] ?? Auth::user()->department_id;

        $additionalDocument = AdditionalDocument::create($createData);
        
        // If invoice_id is provided, attach it to the document
        if (!empty($validated['invoice_id'])) {
            $additionalDocument->invoices()->attach($validated['invoice_id']);
        }

        saveLog('additional_document', $additionalDocument->id, 'create', Auth::user()->id, 10);

        // Check if the request is AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $additionalDocument->id,
                    'document_type' => $additionalDocument->type->type_name,
                    'document_number' => $additionalDocument->document_number,
                    'document_date' => \Carbon\Carbon::parse($additionalDocument->document_date)->format('d M Y'),
                    'receive_date' => $additionalDocument->receive_date,
                    'po_no' => $additionalDocument->po_no ?? '-',
                    'project' => $additionalDocument->project ?? '-',
                    'status' => $additionalDocument->status,
                    'cur_loc' => $additionalDocument->cur_loc,
                ]
            ]);
        }

        // For regular form submissions
        Alert::success('Success', 'Additional Document created successfully');
        return redirect()->route('documents.additional-documents.index', ['page' => 'search']);
    }

    public function updateReceiveDate(Request $request, AdditionalDocument $document)
    {
        try {
            $request->validate([
                'receive_date' => 'required|date'
            ]);

            $document->receive_date = $request->receive_date;
            $document->save();

            // Log the change if you have a logging system
            saveLog('additional_document', $document->id, 'update-receive-date', Auth::user()->id, 5);

            return response()->json([
                'success' => true,
                'message' => 'Receive date updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating receive date: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getReadyToSendDocuments()
    {
        $documents = AdditionalDocument::whereNotNull('receive_date')
            ->whereHas('invoice', function ($query) {
                $query->whereDoesntHave('delivery'); // Filter to ensure the invoice has no delivery yet
            })
            ->get();

        return $documents;
    }
} 