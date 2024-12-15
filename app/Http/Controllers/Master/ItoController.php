<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use App\Imports\ItoImport;
use App\Models\AdditionalDocument;

class ItoController extends Controller
{
    public function itoUpload(Request $request)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('attachment');
        $filename = 'ito_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);

        Excel::import(new ItoImport, public_path('uploads/' . $filename));
        unlink(public_path('uploads/' . $filename));

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
}
