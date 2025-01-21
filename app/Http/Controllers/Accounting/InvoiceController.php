<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\AdditionalDocument;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Supplier;
use App\Models\InvoiceType;
use App\Models\AdditionalDocumentType;
use Illuminate\Support\Facades\Storage;
use App\Models\Attachment;

class InvoiceController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'accounting.invoices.dashboard',
            'search' => 'accounting.invoices.search',
            'create' => 'accounting.invoices.create',
            'list' => 'accounting.invoices.list',
        ];

        if ($page === 'create') {
            $projects = Project::orderBy('code', 'asc')->get();

            return view($views[$page], compact('projects'));
        } elseif ($page === 'search') {
            $suppliers = Supplier::orderBy('name')->get();
            $invoiceTypes = InvoiceType::orderBy('type_name')->get();
            $projects = Project::orderBy('code')->get();
            return view($views[$page], compact('suppliers', 'invoiceTypes', 'projects'));
        } elseif ($page === 'dashboard') {
            $data['monthly_summary'] = $this->monthly_summary();

            return view($views[$page], compact('data'));
        }

        return view($views[$page]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'invoice_number' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (Invoice::where('invoice_number', $value)
                        ->where('supplier_id', $request->supplier_id)
                        ->exists()
                    ) {
                        $fail('Nomor invoice untuk vendor tsb sudah ada');
                    }
                },
            ],
            'supplier_id' => 'required',
            'invoice_date' => 'required|date',
            'receive_date' => 'required|date',
            'receive_project' => 'required',
            'amount' => 'required',
            'invoice_type' => 'required',
        ]);

        $invoice = new Invoice();
        $invoice->supplier_id = $validatedData['supplier_id'];
        $invoice->invoice_number = $validatedData['invoice_number'];
        $invoice->invoice_date = $validatedData['invoice_date'];
        $invoice->receive_project = $validatedData['receive_project'];
        $invoice->receive_date = $validatedData['receive_date'];
        $invoice->amount = str_replace(',', '', $validatedData['amount']);
        $invoice->po_no = $request->po_no;
        $invoice->type_id = $request->invoice_type;
        $invoice->invoice_project = $request->invoice_project; // Added field
        $invoice->payment_project = $request->payment_project; // Added field
        $invoice->remarks = $request->remarks; // Added field for remarks
        $invoice->cur_loc = $request->receive_project;
        $invoice->created_by = Auth::user()->id;
        $invoice->save();

        if ($request->has('selected_documents')) {
            foreach ($request->selected_documents as $documentId) {
                $document = AdditionalDocument::find($documentId);
                if ($document) {
                    $document->invoice_id = $invoice->id;
                    $document->save();
                }
            }
        }

        saveLog('invoice', $invoice->id, 'create', Auth::user()->id, 15);

        Alert::success('Success', 'Invoice created successfully');

        return redirect()->route('accounting.invoices.index', ['page' => 'create']);
    }

    public function edit(Invoice $invoice)
    {
        $documentTypes = AdditionalDocumentType::orderBy('type_name')->get();
        $projects = Project::all();
        $suppliers = Supplier::all();
        $invoiceTypes = InvoiceType::all();

        // Get all additional documents related to the PO number with documentType relationship
        $orphanAdditionalDocuments = AdditionalDocument::where('invoice_id', null)
            ->where('po_no', $invoice->po_no)
            ->with('documentType') // Added documentType relationship
            ->get();

        $invoiceAdditionalDocuments = AdditionalDocument::with('documentType')
            ->where('invoice_id', $invoice->id)
            ->get();

        $additionalDocuments = $orphanAdditionalDocuments->merge($invoiceAdditionalDocuments);

        // Get IDs of documents already connected to this invoice
        $connectedDocumentIds = AdditionalDocument::where('invoice_id', $invoice->id)
            ->pluck('id')
            ->toArray();

        $invoice->invoice_date = \Carbon\Carbon::parse($invoice->invoice_date);
        $invoice->receive_date = \Carbon\Carbon::parse($invoice->receive_date);

        return view('accounting.invoices.edit', compact(
            'invoice',
            'additionalDocuments',
            'connectedDocumentIds',
            'documentTypes',
            'projects',
            'suppliers',
            'invoiceTypes'
        ));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'invoice_number' => [
                'required',
                function ($attribute, $value, $fail) use ($request, $id) {
                    if (Invoice::where('invoice_number', $value)
                        ->where('supplier_id', $request->supplier_id)
                        ->where('id', '!=', $id) // Ensure the current invoice is excluded
                        ->exists()
                    ) {
                        $fail('Nomor invoice untuk vendor tsb sudah ada');
                    }
                },
            ],
            'supplier_id' => 'required',
            'invoice_date' => 'required|date',
            'receive_date' => 'required|date',
            'receive_project' => 'required',
            'amount' => 'required',
            'invoice_type' => 'required',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->supplier_id = $validatedData['supplier_id'];
        $invoice->invoice_number = $validatedData['invoice_number'];
        $invoice->invoice_date = $validatedData['invoice_date'];
        $invoice->receive_project = $validatedData['receive_project'];
        $invoice->receive_date = $validatedData['receive_date'];
        $invoice->amount = str_replace(',', '', $validatedData['amount']);
        $invoice->po_no = $request->po_no;
        $invoice->type_id = $request->invoice_type;
        $invoice->invoice_project = $request->invoice_project; // Added field
        $invoice->payment_project = $request->payment_project; // Added field
        $invoice->remarks = $request->remarks; // Added field for remarks
        $invoice->save();

        // Update document connections
        // First, disconnect all documents from this invoice
        AdditionalDocument::where('invoice_id', $id)
            ->update(['invoice_id' => null]);

        // Then connect the selected documents
        if ($request->has('selected_documents')) {
            AdditionalDocument::whereIn('id', $request->selected_documents)
                ->update(['invoice_id' => $id]);
        }

        saveLog('invoice', $invoice->id, 'update', Auth::user()->id, 5);

        Alert::success('Success', 'Invoice updated successfully');

        return redirect()->route('accounting.invoices.index', ['page' => 'search']);
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        // Convert date fields to Carbon instances
        $invoice->invoice_date = \Carbon\Carbon::parse($invoice->invoice_date);
        $invoice->receive_date = \Carbon\Carbon::parse($invoice->receive_date);
        $invoice->additionalDocs = $invoice->additionalDocuments;

        return view('accounting.invoices.show', compact('invoice'));
    }

    public function checkInvoiceNumber(Request $request)
    {
        $invoiceNumber = $request->query('invoice_number');
        $supplierId = $request->query('supplier_id');
        $exists = Invoice::where('invoice_number', $invoiceNumber)
            ->where('supplier_id', $supplierId)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function data()
    {
        $invoices = Invoice::orderBy('receive_date', 'asc')
            ->whereNotIn('status', ['close', 'return'])
            ->get();

        return datatables()->of($invoices)
            ->addColumn('vendor', function ($invoice) {
                return $invoice->supplier->name;
            })
            ->addColumn('days', function ($invoice) {
                $receiveDate = \Carbon\Carbon::parse($invoice->receive_date)->startOfDay();
                $currentDate = \Carbon\Carbon::now()->startOfDay();
                return $receiveDate->diffInDays($currentDate);
            })
            ->addColumn('amount', function ($invoice) {
                return number_format($invoice->amount, 2, '.', ',');
            })
            ->addColumn('invoice_date', function ($invoice) {
                return \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y');
            })
            ->addIndexColumn()
            ->addColumn('action', 'accounting.invoices.action')
            ->rawColumns(['action'])
            ->toJson();
    }

    public function search(Request $request)
    {
        $query = Invoice::with(['supplier', 'invoiceType']);

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('supplier_name', function ($invoice) {
                return $invoice->supplier->name ?? 'N/A';
            })
            ->addColumn('invoice_type_name', function ($invoice) {
                return $invoice->invoiceType->type_name ?? 'N/A';
            })
            ->addColumn('formatted_amount', function ($invoice) {
                return number_format($invoice->amount, 2, '.', ',');
            })
            ->addColumn('action', function ($invoice) {
                return '<div class="btn-group">
                        <a href="' . route('accounting.invoices.edit', $invoice->id) . '" class="btn btn-xs btn-warning mr-2" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="' . route('accounting.invoices.show', $invoice->id) . '" class="btn btn-xs btn-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>';
            })
            ->rawColumns(['action'])
            ->filter(function ($query) use ($request) {
                if ($request->filled('invoice_number')) {
                    $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
                }
                if ($request->filled('po_no')) {
                    $query->where('po_no', 'like', '%' . $request->po_no . '%');
                }
                if ($request->filled('supplier_id')) {
                    $query->where('supplier_id', $request->supplier_id);
                }
                if ($request->filled('type_id')) {
                    $query->where('type_id', $request->type_id);
                }
                if ($request->filled('invoice_project')) {
                    $query->where('invoice_project', $request->invoice_project);
                }
            })
            ->toJson();
    }

    public function monthly_summary()
    {
        // Get all years from receive_date
        $years = Invoice::selectRaw('YEAR(receive_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $data = [];

        foreach ($years as $year) {
            // Calculate year statistics
            $yearData = Invoice::whereYear('receive_date', $year);

            $yearStats = [
                'year' => $year,
                'year_average_duration' => round($yearData->avg('duration1'), 1),
                'year_invoice_count' => $yearData->count(),
                'monthly_data' => []
            ];

            // Calculate monthly statistics for current year
            for ($month = 1; $month <= 12; $month++) {
                $monthlyData = Invoice::whereYear('receive_date', $year)
                    ->whereMonth('receive_date', $month);

                $count = $monthlyData->count();

                $yearStats['monthly_data'][] = [
                    'month' => $month,
                    'month_name' => date('M', mktime(0, 0, 0, $month, 1)),
                    'receive_count' => $count,
                    'average_duration' => $count > 0 ? round($monthlyData->avg('duration1'), 1) : 0
                ];
            }

            $data[] = $yearStats;
        }

        return $data;
    }

    public function getCompleteInvoices()
    {
        return Invoice::with(['additionalDocuments' => function ($query) {
            $query->whereNotNull('receive_date');
        }])
            ->whereHas('additionalDocuments', function ($query) {
                $query->whereNotNull('receive_date');
            })
            ->whereDoesntHave('additionalDocuments', function ($query) {
                $query->whereNull('receive_date');
            })
            ->get();
    }

    public function uploadAttachments(Request $request, Invoice $invoice)
    {
        $request->validate([
            'attachments.*' => 'required|file|mimes:pdf,jpg,jpeg,png,gif|max:10240', // 10MB max
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('invoice-attachments', 'public');

                $invoice->attachments()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => Auth::user()->id,
                ]);
            }

            saveLog('invoice', $invoice->id, 'upload-attachments', Auth::user()->id, 15);
            return response()->json([
                'success' => true,
                'message' => 'Attachments uploaded successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No files were uploaded'
        ], 400);
    }

    public function deleteAttachment(Attachment $attachment)
    {
        try {
            // Delete the file from storage
            Storage::disk('public')->delete($attachment->file_path);

            // Delete the database record
            $attachment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attachment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting attachment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAttachments(Invoice $invoice)
    {
        $attachments = $invoice->attachments()->latest()->get();

        return response()->json([
            'success' => true,
            'attachments' => $attachments
        ]);
    }

    public function getReadyToDeliverInvoices()
    {
        $userProject = Auth::user()->project;

        // Get invoices where all additional documents have receive_date and status open
        $invoicesWithDocuments = Invoice::whereHas('additionalDocuments')
            ->whereDoesntHave('additionalDocuments', function ($query) {
                $query->whereNull('receive_date')
                    ->orWhere('status', '!=', 'open');
            })
            ->where('receive_project', $userProject)
            ->whereDoesntHave('deliveryDocuments', function ($query) {
                $query->where('documentable_type', Invoice::class);
            })
            ->get();

        // Get invoices that have no additional documents and are not in deliveries
        $invoicesWithoutDocuments = Invoice::doesntHave('additionalDocuments')
            ->where('receive_project', $userProject)
            ->whereDoesntHave('deliveryDocuments', function ($query) {
                $query->where('documentable_type', Invoice::class);
            })
            ->get();

        // Merge both collections
        $readyToDeliverInvoices = $invoicesWithDocuments->merge($invoicesWithoutDocuments);

        return $readyToDeliverInvoices;
    }
}
