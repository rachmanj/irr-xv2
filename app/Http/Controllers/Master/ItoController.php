<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use App\Imports\ItoImport;
use App\Models\AdditionalDocument;
use Illuminate\Support\Facades\Auth;

class ItoController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('attachment');
        $filename = 'ito_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);

        Excel::import(new ItoImport, public_path('uploads/' . $filename));
        unlink(public_path('uploads/' . $filename));

        saveLog('additional_document', null, 'upload', Auth::user()->id, 20);
        Alert::success('Success', 'File berhasil diupload');

        return redirect()->back();
    }

    public function data()
    {
        $itos = AdditionalDocument::query();
        $itos = $itos->whereHas('documentType', function ($query) {
            $query->where('type_name', 'ito');
        })->whereNull('invoice_id')
            ->whereNull('receive_date');

        return datatables()->of($itos)
            ->addColumn('upload_at', function ($row) {
                return $row->created_at->format('d-M-Y');
            })
            ->addIndexColumn()
            ->toJson();
    }

    public function search()
    {
        return view('master.upload.search');
    }

    public function searchData(Request $request)
    {
        // Return empty result set for initial load or reset
        if (!$request->search_clicked) {
            return datatables()->of([])
                ->addIndexColumn()
                ->toJson();
        }

        $query = AdditionalDocument::query()
            ->whereHas('documentType', function ($query) {
                $query->where('type_name', 'ito');
            });

        // Apply filters if they are provided
        if ($request->filled('document_number')) {
            $query->where('document_number', 'like', '%' . $request->document_number . '%');
        }

        if ($request->filled('po_no')) {
            $query->where('po_no', 'like', '%' . $request->po_no . '%');
        }

        if ($request->filled('destination_wh')) {
            $query->where('destination_wh', 'like', '%' . $request->destination_wh . '%');
        }

        if ($request->filled('document_date')) {
            $query->whereDate('document_date', $request->document_date);
        }

        if ($request->filled('remarks')) {
            $query->where('remarks', 'like', '%' . $request->remarks . '%');
        }

        return datatables()->of($query)
            ->addColumn('document_date_formatted', function ($row) {
                return $row->document_date ? date('d-M-Y', strtotime($row->document_date)) : '';
            })
            ->addIndexColumn()
            ->toJson();
    }
}
