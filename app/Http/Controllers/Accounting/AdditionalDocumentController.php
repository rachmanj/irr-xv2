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
            // $invoices = Invoice::with('supplier')
            //     ->join('suppliers', 'invoices.supplier_id', '=', 'suppliers.id')
            //     ->orderBy('suppliers.name')
            //     ->select('invoices.*')
            //     ->get();

            $additionalDocumentTypes = AdditionalDocumentType::orderBy('type_name')->get();

            return view($views[$page], compact('additionalDocumentTypes'));
        } elseif ($page === 'dashboard') {
            $dashboardData = [
                'outs' => $this->outs_addoc(),
                'type' => $this->outs_addocs_by_type(),
            ];

            return view($views[$page], compact('dashboardData'));
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
        AdditionalDocument::create($validatedData);

        Alert::success('Success', 'Additional Document created successfully.');

        return redirect()->back();
    }

    public function update(Request $request, AdditionalDocument $additionalDocument)
    {
        $validatedData = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'document_type' => 'required|string|max:255',
            'document_number' => 'required|string|max:255',
            'document_date' => 'required|date',
        ]);

        $additionalDocument->update($validatedData);

        Alert::success('Success', 'Additional Document updated successfully.');

        return redirect()->back();
    }

    public function destroy(AdditionalDocument $additionalDocument)
    {
        $additionalDocument->delete();

        Alert::success('Success', 'Additional Document deleted successfully.');

        return redirect()->route('additional-documents.index');
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
        $invoices = Invoice::with('supplier')->where('po_no', $poNo)->get();
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

    public function searchAdditionalDocuments(Request $request)
    {
        $query = $request->query('query');
        $documents = AdditionalDocument::where('document_number', 'LIKE', "%{$query}%")
            ->orWhere('po_no', 'LIKE', "%{$query}%")
            ->get()
            ->map(function ($document) {
                $document->receive_date = $document->receive_date ? $document->receive_date->format('d-M-Y') : 'not received';
                return $document;
            });

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
}
