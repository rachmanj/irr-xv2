<?php

namespace App\Http\Controllers;

use App\Models\AdditionalDocument;
use App\Models\AdditionalDocumentType;
use App\Models\Department;
use App\Models\DocumentDistribution;
use App\Models\Invoice;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentDistributionController extends Controller
{
    /**
     * Display a listing of document distributions.
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 'index');

        $views = [
            'dashboard' => 'document-distributions.dashboard',
            'search' => 'document-distributions.search-history',
            'index' => 'document-distributions.index',
            'history' => 'document-distributions.history',
        ];

        // Make sure we have a valid view
        if (!isset($views[$page])) {
            $page = 'index';
        }

        // Get recent distributions for dashboard widget
        $recentDistributions = DocumentDistribution::with(['document', 'fromDepartment', 'toDepartment'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($page === 'index') {
            $query = DocumentDistribution::with(['sender', 'receiver', 'fromDepartment', 'toDepartment']);
            
            // Filter by document type
            if ($request->has('document_type')) {
                $query->where('document_type', $request->document_type);
            }
            
            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            // Filter by location code
            if ($request->has('location_code')) {
                $query->where(function($q) use ($request) {
                    $q->where('from_location_code', $request->location_code)
                      ->orWhere('to_location_code', $request->location_code);
                });
            }
            
            $distributions = $query->orderBy('created_at', 'desc')->paginate(10);
            
            return view($views[$page], compact('distributions', 'recentDistributions'));
        }

        if ($page === 'search') {
            return view($views[$page], compact('recentDistributions'));
        }

        if ($page === 'dashboard') {
            return view($views[$page], compact('recentDistributions'));
        }

        return view($views[$page], compact('recentDistributions'));
    }
    
    /**
     * Show the form for creating a new distribution.
     */
    public function create(Request $request)
    {
        $departments = Department::all();
        $document = null;
        
        if ($request->has('document_type') && $request->has('document_id')) {
            $document = app($request->document_type)->find($request->document_id);
        }
        
        $recentDistributions = DocumentDistribution::latest()->take(5)->get();
        
        return view('document-distributions.create', compact('departments', 'document', 'recentDistributions'));
    }
    
    /**
     * Store a newly created distribution in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:App\\Models\\Invoice,App\\Models\\AdditionalDocument',
            'document_id' => 'required|integer',
            'from_location_code' => 'nullable|string|exists:departments,location_code',
            'to_location_code' => 'required|string|exists:departments,location_code',
            'sender_id' => 'nullable|integer|exists:users,id',
            'receiver_id' => 'nullable|integer|exists:users,id',
            'remarks' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create the distribution record
            $distribution = DocumentDistribution::create([
                'document_type' => $validated['document_type'],
                'document_id' => $validated['document_id'],
                'from_location_code' => $validated['from_location_code'],
                'to_location_code' => $validated['to_location_code'],
                'sender_id' => $validated['sender_id'] ?? Auth::id(),
                'receiver_id' => $validated['receiver_id'],
                'sent_at' => now(),
                'status' => 'in_transit',
                'remarks' => $validated['remarks'],
            ]);
            
            // Update the document's current location
            if ($validated['document_type'] === 'App\\Models\\Invoice') {
                $document = Invoice::findOrFail($validated['document_id']);
                $document->update(['cur_loc' => $validated['to_location_code']]);
                
                // If distributing an invoice, also distribute its additional documents
                foreach ($document->additionalDocuments as $additionalDocument) {
                    DocumentDistribution::create([
                        'document_type' => 'App\\Models\\AdditionalDocument',
                        'document_id' => $additionalDocument->id,
                        'from_location_code' => $validated['from_location_code'],
                        'to_location_code' => $validated['to_location_code'],
                        'sender_id' => $validated['sender_id'] ?? Auth::id(),
                        'receiver_id' => $validated['receiver_id'],
                        'sent_at' => now(),
                        'status' => 'in_transit',
                        'remarks' => "Distributed with Invoice #{$document->invoice_number}",
                    ]);
                    
                    // Update additional document's current location
                    $additionalDocument->update(['cur_loc' => $validated['to_location_code']]);
                }
            } elseif ($validated['document_type'] === 'App\\Models\\AdditionalDocument') {
                $document = AdditionalDocument::findOrFail($validated['document_id']);
                $document->update(['cur_loc' => $validated['to_location_code']]);
            }
            
            DB::commit();
            
            return redirect()->route('document-distributions.index')
                ->with('success', 'Document distribution created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'Failed to create document distribution: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Display the specified distribution.
     */
    public function show(DocumentDistribution $documentDistribution)
    {
        $distribution = $documentDistribution;
        $document = $distribution->document;
        $recentDistributions = DocumentDistribution::latest()->take(5)->get();
        
        return view('document-distributions.show', compact('distribution', 'document', 'recentDistributions'));
    }
    
    /**
     * Mark a distribution as received.
     */
    public function receive(DocumentDistribution $documentDistribution, Request $request)
    {
        $documentDistribution->status = 'received';
        $documentDistribution->receiver_id = auth()->id();
        $documentDistribution->received_at = now();
        $documentDistribution->remarks = $request->remarks;
        $documentDistribution->save();
        
        // Update document location
        $document = $documentDistribution->document;
        if ($document) {
            $document->cur_loc = $documentDistribution->to_location_code;
            $document->save();
        }
        
        return redirect()->route('document-distributions.show', $documentDistribution)
            ->with('success', 'Document has been marked as received.');
    }
    
    /**
     * Reject a distribution.
     */
    public function reject(DocumentDistribution $documentDistribution, Request $request)
    {
        $request->validate([
            'remarks' => 'required|string|max:255',
        ]);
        
        $documentDistribution->status = 'rejected';
        $documentDistribution->receiver_id = auth()->id();
        $documentDistribution->received_at = now();
        $documentDistribution->remarks = $request->remarks;
        $documentDistribution->save();
        
        return redirect()->route('document-distributions.show', $documentDistribution)
            ->with('success', 'Document distribution has been rejected.');
    }
    
    /**
     * Show form to search for document distribution history.
     */
    public function searchHistory()
    {
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        $recentDistributions = DocumentDistribution::latest()->take(5)->get();
        
        return view('document-distributions.search-history', compact('suppliers', 'recentDistributions'));
    }
    
    /**
     * Get the distribution history for a specific document.
     */
    public function history(Request $request)
    {
        $document_type = $request->document_type;
        $document_id = $request->document_id;
        
        $distributions = DocumentDistribution::where('document_type', $document_type)
            ->where('document_id', $document_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $document = null;
        $latestDistribution = null;
        
        if ($document_type && $document_id) {
            $document = app($document_type)->find($document_id);
            $latestDistribution = $distributions->first();
        }
        
        $recentDistributions = DocumentDistribution::latest()->take(5)->get();
        
        return view('document-distributions.history', compact('distributions', 'document', 'document_type', 'document_id', 'latestDistribution', 'recentDistributions'));
    }
    
    /**
     * Get all suppliers for the dropdown.
     */
    public function getSuppliers()
    {
        $suppliers = Supplier::select('id', 'name')->orderBy('name')->get();
        return response()->json($suppliers);
    }
    
    /**
     * Get all additional document types for the dropdown.
     */
    public function getAdditionalDocumentTypes()
    {
        $types = AdditionalDocumentType::select('id', 'name')->orderBy('name')->get();
        return response()->json($types);
    }
    
    /**
     * Search for documents based on criteria.
     */
    public function search(Request $request)
    {
        $document_type = $request->document_type;
        $results = collect();
        
        if ($document_type == 'App\Models\Invoice') {
            $query = \App\Models\Invoice::query();
            
            if ($request->invoice_number) {
                $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
            }
            
            if ($request->supplier_id) {
                $query->where('supplier_id', $request->supplier_id);
            }
            
            if ($request->po_number) {
                $query->where('po_number', 'like', '%' . $request->po_number . '%');
            }
            
            $results = $query->get();
        } elseif ($document_type == 'App\Models\AdditionalDocument') {
            $query = \App\Models\AdditionalDocument::query();
            
            if ($request->document_number) {
                $query->where('document_number', 'like', '%' . $request->document_number . '%');
            }
            
            if ($request->supplier_id) {
                $query->where('supplier_id', $request->supplier_id);
            }
            
            if ($request->po_number) {
                $query->where('po_number', 'like', '%' . $request->po_number . '%');
            }
            
            $results = $query->get();
        }
        
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        $recentDistributions = DocumentDistribution::latest()->take(5)->get();
        
        return view('document-distributions.search-history', compact('results', 'suppliers', 'recentDistributions'));
    }

    /**
     * Display the document view.
     */
    public function document()
    {
        $recentDistributions = DocumentDistribution::latest()->take(5)->get();
        return view('document-distributions.document', compact('recentDistributions'));
    }
} 