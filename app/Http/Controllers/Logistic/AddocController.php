<?php

namespace App\Http\Controllers\Logistic;

use App\Http\Controllers\Controller;
use App\Models\AdditionalDocument;
use App\Models\AdditionalDocumentType;
use Illuminate\Http\Request;

class AddocController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'logistic.addoc.dashboard',
            'create' => 'logistic.addoc.create',
            'list' => 'logistic.addoc.list',
            'search' => 'logistic.addoc.search',
        ];

        if ($page === 'search') {
            $documentTypes = AdditionalDocumentType::orderBy('type_name')->get();

            return view($views[$page], compact('documentTypes'));
        }

        return view($views[$page]);
    }

    public function data()
    {
        $documents = AdditionalDocument::query();
        // Start of Selection
        $documents = $documents->whereHas('type', function ($query) {
            $query->where('type_name', 'ito');
        })
            // ->whereNull('invoice_id')
            ->whereNull('receive_date')
            ->where('cur_loc', '000H-LOG')
            ->where('status', '!=', 'cancel');

        return datatables()->of($documents)
            ->editColumn('document_date', function ($row) {
                return $row->document_date ? '<small>' . \Carbon\Carbon::parse($row->document_date)->format('d M Y') . '<br>' . $row->created_at->format('d M Y') . '</small>' : 'N/A';
            })
            ->addColumn('document_type', function ($row) {
                return $row->type ? $row->type->type_name : 'n/a';
            })
            ->addColumn('invoice_number', function ($row) {
                return $row->invoice ? $row->invoice->invoice_number : 'n/a';
            })
            ->addColumn('days', function ($row) {
                return (int) \Carbon\Carbon::parse($row->created_at)->diffInDays(now());
            })
            ->addIndexColumn()
            ->addColumn('action', 'logistic.addoc.action')
            ->rawColumns(['action', 'document_date'])
            ->toJson();
    }

    public function searchData(Request $request)
    {
        $query = AdditionalDocument::with('type');

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('type', function ($row) {
                return $row->type ? $row->type->type_name : 'N/A';
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

    public function getReadyToSendDocuments()
    {
        // Start of Selection
        $documents = AdditionalDocument::where('cur_loc', '000H-LOG') // Filter to ensure cur_loc is 000H-LOG
            ->where('status', '!=', 'cancel') // Exclude documents with status 'cancel'
            ->get();

        return $documents;
    }
}
