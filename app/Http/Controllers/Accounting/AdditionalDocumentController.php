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
            $invoices = Invoice::all();
            $additionalDocumentTypes = AdditionalDocumentType::orderBy('type_name')->get();

            return view($views[$page], compact('invoices', 'additionalDocumentTypes'));
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
        $invoices = Invoice::where('po_no', $poNo)->get();
        $documents = AdditionalDocument::where('po_no', $poNo)->with('documentType')->get();

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
}
