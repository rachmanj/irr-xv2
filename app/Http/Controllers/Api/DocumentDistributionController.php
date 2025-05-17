<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\AdditionalDocument;
use App\Models\Supplier;

class DocumentDistributionController extends Controller
{
    public function searchDocuments(Request $request)
    {
        $documentType = $request->input('document_type');
        $query = $request->input('query');
        
        if ($documentType === 'App\\Models\\Invoice') {
            $invoices = Invoice::where(function($q) use ($query) {
                $q->where('invoice_number', 'like', '%' . $query . '%')
                  ->orWhere('po_number', 'like', '%' . $query . '%');
            })
            ->with('supplier')
            ->limit(20)
            ->get();
            
            $results = $invoices->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'supplier_name' => optional($invoice->supplier)->name,
                    'po_number' => $invoice->po_number,
                    'amount' => $invoice->amount,
                    'cur_loc' => $invoice->cur_loc,
                ];
            });
            
            return response()->json($results);
        } elseif ($documentType === 'App\\Models\\AdditionalDocument') {
            $documents = AdditionalDocument::where(function($q) use ($query) {
                $q->where('document_number', 'like', '%' . $query . '%')
                  ->orWhere('document_type', 'like', '%' . $query . '%')
                  ->orWhere('po_number', 'like', '%' . $query . '%');
            })
            ->with('supplier')
            ->limit(20)
            ->get();
            
            $results = $documents->map(function ($document) {
                return [
                    'id' => $document->id,
                    'document_number' => $document->document_number,
                    'document_type' => $document->document_type,
                    'supplier_name' => optional($document->supplier)->name,
                    'po_number' => $document->po_number,
                    'cur_loc' => $document->cur_loc,
                ];
            });
            
            return response()->json($results);
        }
        
        return response()->json([]);
    }
    
    public function getSuppliers()
    {
        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);
        return response()->json($suppliers);
    }
    
    public function getDocumentTypes()
    {
        $types = [
            'Purchase Order',
            'Delivery Receipt',
            'Packing List',
            'Bill of Lading',
            'Certificate of Origin',
            'Quality Certificate',
            'Insurance Document',
            'Export License',
            'Import License',
            'Customs Declaration',
            'Other'
        ];
        
        return response()->json($types);
    }
} 