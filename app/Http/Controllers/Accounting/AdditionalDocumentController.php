<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\AdditionalDocument;
use App\Models\AdditionalDocumentType;
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
            'dashboard' => 'accounting.additional-documents.dashboard',
            'search' => 'accounting.additional-documents.search',
            'create' => 'accounting.additional-documents.create',
            'list' => 'accounting.additional-documents.list',
        ];

        if ($page === 'create') {
            $additionalDocumentTypes = AdditionalDocumentType::orderBy('type_name')->get();

            return view($views[$page], compact('additionalDocumentTypes'));
        } elseif ($page === 'dashboard') {
            $dashboardData = [
                'outs' => $this->outs_addoc(),
                'type' => $this->outs_addocs_by_type(),
            ];

            return view($views[$page], compact('dashboardData'));
        } elseif ($page === 'search') {
            $documentTypes = AdditionalDocumentType::orderBy('type_name')->get();
            return view($views[$page], compact('documentTypes'));
        }

        return view($views[$page]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type_id' => 'required|string|max:255',
            'document_number' => 'required|string|max:255',
            'document_date' => 'required|date',
        ]);

        $validatedData['po_no'] = $request->po_no;
        $validatedData['created_by'] = Auth::user()->id;
        $additionalDocument = AdditionalDocument::create($validatedData);

        saveLog('additional_document', $additionalDocument->id, 'create',  Auth::user()->id, 10);
        Alert::success('Success', 'Additional Document created successfully.');

        return redirect()->back();
    }

    public function edit($id)
    {
        $additionalDocument = AdditionalDocument::findOrFail($id);
        $additionalDocumentTypes = AdditionalDocumentType::all();
        $invoices = Invoice::with('supplier')->where('status', 'open')->get(); // Ensure invoices are fetched with suppliers

        return view('accounting.additional-documents.edit', compact('additionalDocument', 'additionalDocumentTypes', 'invoices'));
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

        return redirect()->route('accounting.additional-documents.index', ['page' => 'search']);
    }

    public function destroy($id)
    {
        $additionalDocument = AdditionalDocument::findOrFail($id);
        $additionalDocument->delete();

        saveLog('additional_document', $additionalDocument->id, 'delete', Auth::user()->id, 5);
        Alert::success('Success', 'Additional Document deleted successfully.');

        return redirect()->route('additional-documents.index');
    }

    public function show($id)
    {
        $additionalDocument = AdditionalDocument::findOrFail($id);

        return view('accounting.additional-documents.show', compact('additionalDocument'));
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

        $openInvoices = Invoice::where('status', 'open')->get();
        $InvoicesWithSamePoNo = Invoice::where('po_no', $poNo)->get();
        $invoices = array_merge($openInvoices, $InvoicesWithSamePoNo);

        $documents = AdditionalDocument::where('po_no', $poNo)
            ->whereNull('invoice_id')
            ->with('documentType')
            ->get();

        return response()->json([
            'invoices' => $invoices,
            'documents' => $documents,
        ]);
    }

    public function searchAdditionalDocumentsByPo(Request $request)
    {
        $poNo = $request->query('po_no');
        $documents = AdditionalDocument::where('po_no', $poNo)->with('documentType')->get();

        return response()->json($documents);
    }

    public function data()
    {
        $documents = AdditionalDocument::query();
        $documents = $documents->whereHas('documentType', function ($query) {
            $query->where('type_name', 'ito');
        })
            // ->whereNull('invoice_id')
            ->whereNull('receive_date');

        return datatables()->of($documents)
            ->editColumn('document_date', function ($row) {
                return $row->document_date ? \Carbon\Carbon::parse($row->document_date)->format('d-M-Y') : 'N/A';
            })
            ->addColumn('document_type', function ($row) {
                return $row->documentType ? $row->documentType->type_name : 'N/A';
            })
            ->addColumn('invoice_number', function ($row) {
                return $row->invoice ? $row->invoice->invoice_number : 'N/A';
            })
            ->addColumn('days', function ($row) {
                return (int) \Carbon\Carbon::parse($row->created_at)->diffInDays(now());
            })
            ->addIndexColumn()
            ->addColumn('action', 'accounting.additional-documents.action')
            ->rawColumns(['action'])
            ->toJson();
    }

    public function outs_addoc()
    {
        $addocs = AdditionalDocument::whereNull('invoice_id')->orWhereNull('receive_date')->get();
        $orphan = $addocs->whereNull('invoice_id')->count();
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
        $addocs = AdditionalDocument::whereNull('invoice_id')->orWhereNull('receive_date')->get();

        $data = [];

        foreach ($addoc_types as $type) {
            $count = $addocs->where('type_id', $type->id)->count();
            if ($count > 0) {
                $data[] = [
                    'type' => $type->type_name,
                    'count' => $count,
                ];
            }
        }

        return $data;
    }

    public function searchData(Request $request)
    {
        $query = AdditionalDocument::with('documentType');

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('type', function ($row) {
                return $row->documentType ? $row->documentType->type_name : 'N/A';
            })
            ->addColumn('invoice_number', function ($row) {
                return $row->invoice ? $row->invoice->invoice_number : '-';
            })
            ->editColumn('receive_date', function ($row) {
                return $row->receive_date ? \Carbon\Carbon::parse($row->receive_date)->format('d-M-Y') : '-';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                        <a href="' . route('accounting.additional-documents.edit', $row->id) . '" class="btn btn-xs btn-warning mr-2" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="' . route('accounting.additional-documents.show', $row->id) . '" class="btn btn-xs btn-info" title="View">
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
            })
            ->toJson();
    }
}
