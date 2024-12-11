<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\AdditionalDocumentType;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdditionalDocumentTypeController extends Controller
{
    public function index()
    {
        return view('master.additional-document-types.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        AdditionalDocumentType::create($validatedData);

        Alert::success('Success', 'Additional Document Type created successfully.');

        return redirect()->route('master.additional-document-types.index');
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $additionalDocumentType = AdditionalDocumentType::findOrFail($id);
        $additionalDocumentType->update($validatedData);

        Alert::success('Success', 'Additional Document Type updated successfully.');

        return redirect()->route('master.additional-document-types.index');
    }

    public function destroy(string $id)
    {
        $additionalDocumentType = AdditionalDocumentType::findOrFail($id);
        $additionalDocumentType->delete();

        Alert::success('Success', 'Additional Document Type deleted successfully.');

        return redirect()->route('master.additional-document-types.index');
    }

    public function data()
    {
        $additionalDocumentTypes = AdditionalDocumentType::orderBy('type_name', 'asc')->get();

        return datatables()->of($additionalDocumentTypes)
            ->addIndexColumn()
            ->addColumn('action', 'master.additional-document-types.action')
            ->toJson();
    }
}
