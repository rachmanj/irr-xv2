<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use App\Imports\ItoImport;
use App\Models\AdditionalDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ItoController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:xlsx,xls',
        ]);

        // Ensure uploads directory exists
        $uploadPath = public_path('uploads');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $file = $request->file('attachment');
        $filename = 'ito_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $filename);

        try {
            $import = new ItoImport(true);
            Excel::import($import, public_path('uploads/' . $filename));
            
            // Check if there were any errors during import
            if (!empty($import->getErrors())) {
                $errorMessage = 'File uploaded with warnings: ' . implode(', ', $import->getErrors());
                // Alert::warning('Warning', $errorMessage);
                session()->flash('warning', $errorMessage);
                \Log::warning('ITO Import Warnings: ' . $errorMessage);
            } else {
                // Alert::success('Success', 'File berhasil diupload. ' . $import->getSuccessCount() . ' records imported, ' . $import->getSkippedCount() . ' records skipped.');
                session()->flash('success', 'File berhasil diupload. ' . $import->getSuccessCount() . ' records imported, ' . $import->getSkippedCount() . ' records skipped.');
            }
        } catch (\Exception $e) {
            // Alert::error('Error', 'Failed to upload file: ' . $e->getMessage());
            session()->flash('error', 'Failed to upload file: ' . $e->getMessage());
            \Log::error('ITO Import Error: ' . $e->getMessage());
        } finally {
            if (File::exists(public_path('uploads/' . $filename))) {
                unlink(public_path('uploads/' . $filename));
            }
        }

        try {
            // Check if saveLog function exists and use it
            if (function_exists('saveLog')) {
                saveLog('additional_document', null, 'upload', Auth::user()->id, 20);
            }
        } catch (\Exception $e) {
            \Log::error('Error saving log: ' . $e->getMessage());
        }
        
        return redirect()->back();
    }

    public function data()
    {
        $itos = AdditionalDocument::query();
        $itos = $itos->whereHas('type', function ($query) {
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
            ->whereHas('type', function ($query) {
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
