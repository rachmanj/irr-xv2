<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Delivery;
use App\Models\Invoice;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpiController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'accounting.spi.dashboard',
            'search' => 'accounting.spi.search',
            'create' => 'accounting.spi.create',
            'list' => 'accounting.spi.list',
        ];

        if ($page == 'create') {
            $projects = Project::orderBy('code', 'asc')->get();
            return view($views[$page], compact('projects'));
        }

        return view($views[$page]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spi_number' => 'required|string',
            'date' => 'required|date',
            'destination' => 'required|string',
            'attention_person' => 'required|string',
            'invoices' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $spi = Delivery::create([
            'nomor' => $validated['spi_number'],
            'type' => 'SPI',
            'date' => $validated['date'],
            'origin' => Auth::user()->project,
            'destination' => $validated['destination'],
            'attention_person' => $validated['attention_person'],
            'notes' => Auth::user()->username . ' says: ' . ($validated['notes'] ?? '') . ';',
            'created_by' => Auth::id(),
        ]);

        // decode JSON string to array
        $invoiceIds = json_decode($validated['invoices'], true);

        if (!is_array($invoiceIds)) {
            return back()->withErrors(['invoices' => 'Invalid invoice data format']);
        }

        // Attach invoices
        $spi->attachDocuments($invoiceIds, Invoice::class);

        Alert::success('Success', 'SPI created successfully');

        return redirect()->route('accounting.spi.index')->with('success', 'SPI created successfully');
    }

    public function readyToDeliverData()
    {
        $invoices = app(InvoiceController::class)->getReadyToDeliverInvoices();

        $response = datatables()->of($invoices)
            ->addColumn('supplier_name', function ($invoice) {
                return $invoice->supplier->name ?? '';
            })
            ->addColumn('project_code', function ($invoice) {
                return $invoice->invoice_project->code ?? '';
            })
            ->addColumn('invoice_date', function ($invoice) {
                return $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') : '';
            })
            ->addColumn('receive_date', function ($invoice) {
                return $invoice->receive_date ? \Carbon\Carbon::parse($invoice->receive_date)->format('d M Y') : '';
            })
            ->addColumn('amount', function ($invoice) {
                return number_format($invoice->amount, 2);
            })
            ->addColumn('days', function ($invoice) {
                return (int)($invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->diffInDays(now()) : 0);
            })
            ->addColumn('additional_documents', function ($invoice) {
                $documents = $invoice->additionalDocuments ?? collect();
                $mapped = $documents->map(function ($doc) {
                    $documentType = \App\Models\AdditionalDocumentType::find($doc->type_id);
                    return [
                        'type' => $documentType ? $documentType->type_name : 'Unknown Type',
                        'number' => $doc->document_number,
                        'receive_date' => $doc->receive_date ? \Carbon\Carbon::parse($doc->receive_date)->format('d M Y') : '',
                        'document_date' => $doc->document_date ? \Carbon\Carbon::parse($doc->document_date)->format('d M Y') : '',
                    ];
                })->toArray();

                return $mapped;
            })
            ->toJson();

        return $response;
    }
}
