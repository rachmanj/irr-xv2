<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Delivery;
use App\Models\Invoice;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'notes' => Auth::user()->username . ' says: ' . ($validated['notes'] ?? '') . '; ',
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
        try {
            // Get base query from InvoiceController
            $invoices = app(InvoiceController::class)->getReadyToDeliverInvoices();

            // If editing, include current SPI's invoices
            if (request()->has('include_current') && request('current_spi_id')) {
                $currentSpi = Delivery::find(request('current_spi_id'));
                if ($currentSpi) {
                    $currentInvoiceIds = $currentSpi->documents()
                        ->where('documentable_type', Invoice::class)
                        ->pluck('documentable_id');

                    // Modify query to include current invoices
                    $invoices = Invoice::where(function ($query) use ($invoices, $currentInvoiceIds) {
                        $query->whereIn('id', $invoices->pluck('id'))
                            ->orWhereIn('id', $currentInvoiceIds);
                    });
                }
            }

            return datatables()->of($invoices)
                ->addColumn('supplier_name', function ($invoice) {
                    return $invoice->supplier->name ?? '';
                })
                ->addColumn('project_code', function ($invoice) {
                    return $invoice->invoice_project ?? '';
                })
                ->addColumn('invoice_date', function ($invoice) {
                    return $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') : '';
                })
                ->addColumn('amount', function ($invoice) {
                    return number_format($invoice->amount, 2);
                })
                ->addColumn('days', function ($invoice) {
                    return (int)($invoice->receive_date ? \Carbon\Carbon::parse($invoice->receive_date)->diffInDays(now()) : 0);
                })
                ->addIndexColumn()
                ->toJson();
        } catch (\Exception $e) {
            Log::error('Error in readyToDeliverData: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function data()
    {
        $deliveries = Delivery::where('type', 'SPI')->get();

        return datatables()->of($deliveries)
            ->addColumn('document_count', function ($delivery) {
                return $delivery->documents()->count();
            })
            ->addColumn('status', function ($delivery) {
                return $delivery->sent_date ? 'Sent' : 'Pending';
            })
            ->addColumn('action', function ($delivery) {
                return view('accounting.spi.action', compact('delivery'))->render();
            })
            ->addColumn('formatted_date', function ($delivery) {
                return $delivery->date ? \Carbon\Carbon::parse($delivery->date)->format('d M Y') : '';
            })
            ->addIndexColumn()
            ->toJson();
    }

    public function printPreview($id)
    {
        $spi = Delivery::with([
            'documents.documentable.supplier',
            'documents.documentable.additionalDocuments.type'
        ])
            ->findOrFail($id);

        return view('accounting.spi.print-preview', compact('spi'));
    }

    public function printContent($id)
    {
        $spi = Delivery::with([
            'documents.documentable.supplier'
        ])->findOrFail($id);

        return view('accounting.spi.print-content', compact('spi'));
    }

    public function send($id)
    {
        $spi = Delivery::with('documents.documentable')->findOrFail($id);

        DB::beginTransaction();

        try {
            // Update delivery sent_date only
            $spi->update([
                'sent_date' => now()
            ]);

            // Update all associated invoices status and duration1
            foreach ($spi->documents as $document) {
                if ($document->documentable_type === 'App\Models\Invoice') {
                    $document->documentable->update([
                        'status' => 'sent',
                        'duration1' => (int) \Carbon\Carbon::parse($document->documentable->receive_date)->diffInDays(now()),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'SPI has been sent successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to send SPI: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $spi = Delivery::with([
            'documents.documentable.supplier',
            'documents.documentable.additionalDocuments.type',
        ])->findOrFail($id);

        return view('accounting.spi.show', compact('spi'));
    }

    public function edit($id)
    {
        $spi = Delivery::with([
            'documents.documentable.supplier',
            'documents.documentable.additionalDocuments.type'
        ])->findOrFail($id);

        if ($spi->sent_date) {
            Alert::error('Error', 'Cannot edit a sent SPI');
            return redirect()->route('accounting.spi.show', $spi->id);
        }

        $projects = Project::orderBy('code', 'asc')->get();

        return view('accounting.spi.edit', compact('spi', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $spi = Delivery::findOrFail($id);

        if ($spi->sent_date) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update a sent SPI'
            ], 403);
        }

        $validated = $request->validate([
            'spi_number' => 'required|string',
            'date' => 'required|date',
            'destination' => 'required|string',
            'attention_person' => 'required|string',
            'invoices' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $spi->update([
                'nomor' => $validated['spi_number'],
                'date' => $validated['date'],
                'destination' => $validated['destination'],
                'attention_person' => $validated['attention_person'],
                'notes' => Auth::user()->username . ' updated: ' . ($validated['notes'] ?? '') . ';',
            ]);

            // decode JSON string to array
            $invoiceIds = json_decode($validated['invoices'], true);

            if (!is_array($invoiceIds)) {
                throw new \Exception('Invalid invoice data format');
            }

            // Detach all existing documents
            $spi->documents()->delete();

            // Attach new invoices
            $spi->attachDocuments($invoiceIds, Invoice::class);

            DB::commit();

            Alert::success('Success', 'SPI updated successfully');
            return redirect()->route('accounting.spi.show', $spi->id);
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Failed to update SPI: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        $spi = Delivery::findOrFail($id);

        if ($spi->sent_date) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a sent SPI'
            ], 403);
        }

        DB::beginTransaction();

        try {
            // Delete all related documents first
            $spi->documents()->delete();

            // Delete the SPI
            $spi->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'SPI deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete SPI: ' . $e->getMessage()
            ], 500);
        }
    }
}
