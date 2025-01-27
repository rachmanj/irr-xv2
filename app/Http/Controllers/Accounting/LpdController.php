<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ToolController;
use App\Models\AdditionalDocument;
use App\Models\Delivery;
use App\Models\Project;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LpdController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'accounting.lpd.dashboard',
            'search' => 'accounting.lpd.search',
            'create' => 'accounting.lpd.create',
            'list' => 'accounting.lpd.list',
        ];

        if ($page == 'create') {
            $projects = Project::orderBy('code', 'asc')->get();
            return view($views[$page], compact('projects'));
        }

        return view($views[$page]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'lpd_number' => 'required|string|unique:deliveries,nomor',
                'date' => 'required|date',
                'destination' => 'required|string',
                'attention_person' => 'required|string',
                'documents' => 'required|string',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $lpd = Delivery::create([
                'nomor' => $validated['lpd_number'],
                'type' => 'LPD',
                'date' => $validated['date'],
                'origin' => Auth::user()->project,
                'destination' => $validated['destination'],
                'attention_person' => $validated['attention_person'],
                'notes' => Auth::user()->username . ' says: ' . ($validated['notes'] ?? '') . '; ',
                'created_by' => Auth::id(),
            ]);

            $documentIds = json_decode($validated['documents'], true);
            if (!is_array($documentIds)) {
                throw new \Exception('Invalid document data format');
            }

            $lpd->attachDocuments($documentIds, AdditionalDocument::class);
            saveLog('delivery', $lpd->id, 'create', Auth::id(), 15);

            DB::commit();

            return response()->json(['message' => 'LPD created successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return response()->json(['message' => 'LPD Number already exists'], 422);
            }
            return response()->json(['message' => 'Database error occurred'], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function readyToDeliverData()
    {
        try {
            // Get documents that have receive_date and not sent yet
            $documents = AdditionalDocument::with(['invoice.supplier', 'type'])
                ->whereNotNull('receive_date')
                ->whereDoesntHave('deliveryDocuments', function ($query) {
                    $query->whereHas('delivery', function ($q) {
                        $q->where('type', 'LPD');
                    });
                });

            // If editing, include current LPD's documents
            if (request()->has('include_current') && request('current_lpd_id')) {
                $currentLpd = Delivery::find(request('current_lpd_id'));
                if ($currentLpd) {
                    $currentDocIds = $currentLpd->documents()
                        ->where('documentable_type', AdditionalDocument::class)
                        ->pluck('documentable_id');

                    // Modify query to include current documents
                    $documents = AdditionalDocument::with(['invoice.supplier', 'type'])
                        ->where(function ($query) use ($documents, $currentDocIds) {
                            $query->whereIn('id', $documents->pluck('id'))
                                ->orWhereIn('id', $currentDocIds);
                        });
                }
            }

            return datatables()->of($documents)
                ->addColumn('invoice_number', function ($doc) {
                    return $doc->invoice->invoice_number ?? '';
                })
                ->addColumn('supplier_name', function ($doc) {
                    return $doc->invoice->supplier->name ?? '';
                })
                ->addColumn('document_type', function ($doc) {
                    return $doc->type->type_name ?? '';
                })
                ->addColumn('document_number', function ($doc) {
                    return $doc->document_number;
                })
                ->addColumn('document_date', function ($doc) {
                    return $doc->document_date ? \Carbon\Carbon::parse($doc->document_date)->format('d M Y') : '';
                })
                ->addColumn('receive_date', function ($doc) {
                    return $doc->receive_date ? \Carbon\Carbon::parse($doc->receive_date)->format('d M Y') : '';
                })
                ->addColumn('days', function ($doc) {
                    return (int)($doc->receive_date ? \Carbon\Carbon::parse($doc->receive_date)->diffInDays(now()) : 0);
                })
                ->addIndexColumn()
                ->toJson();
        } catch (\Exception $e) {
            // Log::error('Error in readyToDeliverData: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function data()
    {
        $deliveries = Delivery::where('type', 'LPD')->get();

        return datatables()->of($deliveries)
            ->addColumn('document_count', function ($delivery) {
                return $delivery->documents()->count();
            })
            ->addColumn('status', function ($delivery) {
                return $delivery->sent_date ? 'Sent' : 'Pending';
            })
            ->addColumn('action', function ($delivery) {
                return view('accounting.lpd.action', compact('delivery'))->render();
            })
            ->addColumn('formatted_date', function ($delivery) {
                return $delivery->date ? \Carbon\Carbon::parse($delivery->date)->format('d M Y') : '';
            })
            ->addIndexColumn()
            ->toJson();
    }

    public function show($id)
    {
        $lpd = Delivery::with([
            'documents.documentable.invoice.supplier',
            'documents.documentable.type',
        ])->findOrFail($id);

        return view('accounting.lpd.show', compact('lpd'));
    }

    public function printPreview($id)
    {
        $lpd = Delivery::with([
            'documents' => function ($query) {
                $query->whereHasMorph('documentable', [AdditionalDocument::class]);
            },
            'documents.documentable',
            'documents.documentable.invoice',
            'documents.documentable.invoice.supplier',
            'documents.documentable.type'
        ])->findOrFail($id);

        return view('accounting.lpd.print-preview', compact('lpd'));
    }

    public function edit($id)
    {
        $lpd = Delivery::with([
            'documents.documentable.invoice.supplier',
            'documents.documentable.type'
        ])->findOrFail($id);

        if ($lpd->sent_date) {
            Alert::error('Error', 'Cannot edit a sent LPD');
            return redirect()->route('accounting.lpd.show', $lpd->id);
        }

        $projects = Project::orderBy('code', 'asc')->get();

        return view('accounting.lpd.edit', compact('lpd', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $lpd = Delivery::findOrFail($id);

        if ($lpd->sent_date) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update a sent LPD'
            ], 403);
        }

        $validated = $request->validate([
            'lpd_number' => 'required|string',
            'date' => 'required|date',
            'destination' => 'required|string',
            'attention_person' => 'required|string',
            'documents' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $lpd->update([
                'nomor' => $validated['lpd_number'],
                'date' => $validated['date'],
                'destination' => $validated['destination'],
                'attention_person' => $validated['attention_person'],
                'notes' => Auth::user()->username . ' updated: ' . ($validated['notes'] ?? '') . ';',
            ]);

            // decode JSON string to array
            $documentIds = json_decode($validated['documents'], true);

            if (!is_array($documentIds)) {
                throw new \Exception('Invalid document data format');
            }

            // Detach all existing documents
            $lpd->documents()->delete();

            // Attach new documents
            $lpd->attachDocuments($documentIds, AdditionalDocument::class);

            saveLog('delivery', $lpd->id, 'update', Auth::id(), 15);

            DB::commit();

            Alert::success('Success', 'LPD updated successfully');
            return redirect()->route('accounting.lpd.show', $lpd->id);
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Failed to update LPD: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $lpd = Delivery::findOrFail($id);

            // Check if LPD is already sent
            if ($lpd->sent_date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete a sent LPD'
                ], 422);
            }

            // Delete associated documents first
            $lpd->documents()->delete();

            // Delete the LPD
            $lpd->delete();

            saveLog('delivery', $lpd->id, 'delete', Auth::id(), 15);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'LPD deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete LPD: ' . $e->getMessage()
            ], 500);
        }
    }

    public function send($id)
    {
        $lpd = Delivery::with('documents.documentable')->findOrFail($id);

        DB::beginTransaction();

        try {
            // Update delivery sent_date only
            $lpd->update([
                'sent_date' => now()
            ]);

            // Update all associated documents status
            foreach ($lpd->documents as $document) {
                if ($document->documentable_type === 'App\Models\AdditionalDocument') {
                    $document->documentable->update([
                        'status' => 'sent',
                        'cur_loc' => app(ToolController::class)->getTransitLocationName($lpd->destination),
                    ]);
                }
            }

            saveLog('delivery', $lpd->id, 'send', Auth::id(), 5);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'LPD has been sent successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to send LPD: ' . $e->getMessage()
            ], 500);
        }
    }
}
