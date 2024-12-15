<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\AdditionalDocument;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

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
            'amount' => 'required|numeric',
            'invoice_type' => 'required',
        ]);

        $invoice = new Invoice();
        $invoice->supplier_id = $validatedData['supplier_id'];
        $invoice->invoice_number = $validatedData['invoice_number'];
        $invoice->invoice_date = $validatedData['invoice_date'];
        $invoice->receive_project = $validatedData['receive_project'];
        $invoice->receive_date = $validatedData['receive_date'];
        $invoice->amount = $validatedData['amount'];
        $invoice->po_no = $request->po_no;
        $invoice->type_id = $request->invoice_type;
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

        Alert::success('Success', 'Invoice created successfully');

        return redirect()->route('accounting.invoices.index', ['page' => 'create']);
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
            ->addIndexColumn()
            ->addColumn('action', 'accounting.invoices.action')
            ->rawColumns(['action'])
            ->toJson();
    }

    public function searchInvoices(Request $request)
    {
        $query = $request->query('query');
        $invoices = Invoice::where('invoice_number', 'LIKE', "%{$query}%")
            ->orWhere('po_no', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($invoices);
    }
}
