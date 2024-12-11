<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'accounting.invoices.dashboard',
            'search' => 'accounting.invoices.search',
            'create' => 'accounting.invoices.create',
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
                        $fail('The combination of invoice number and supplier ID already exists.');
                    }
                },
            ],
            'supplier_id' => 'required',
            'invoice_date' => 'required|date',
            'receive_date' => 'required|date',
            'receive_project' => 'required',
            'amount' => 'required|numeric',
        ]);

        $invoice = new Invoice();
        $invoice->supplier_id = $validatedData['supplier_id'];
        $invoice->invoice_number = $validatedData['invoice_number'];
        $invoice->invoice_date = $validatedData['invoice_date'];
        $invoice->receive_project = $validatedData['receive_project'];
        $invoice->receive_date = $validatedData['receive_date'];
        $invoice->amount = $validatedData['amount'];
        $invoice->created_by = Auth::user()->id;
        $invoice->save();

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
}
