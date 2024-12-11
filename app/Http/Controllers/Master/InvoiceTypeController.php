<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\InvoiceType;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class InvoiceTypeController extends Controller
{
    public function index()
    {
        return view('master.invoice-types.index');
    }

    public function create()
    {
        return view('master.invoice-types.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        InvoiceType::create($validatedData);

        Alert::success('Success', 'Invoice Type created successfully.');

        return redirect()->route('master.invoice-types.index');
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $invoiceType = InvoiceType::findOrFail($id);
        $invoiceType->update($validatedData);

        Alert::success('Success', 'Invoice Type updated successfully.');

        return redirect()->route('master.invoice-types.index');
    }

    public function destroy(string $id)
    {
        $invoiceType = InvoiceType::findOrFail($id);
        $invoiceType->delete();

        Alert::success('Success', 'Invoice Type deleted successfully.');

        return redirect()->route('master.invoice-types.index');
    }

    public function data()
    {
        $invoiceTypes = InvoiceType::orderBy('type_name', 'asc')->get();

        return datatables()->of($invoiceTypes)
            ->addIndexColumn()
            ->addColumn('action', 'master.invoice-types.action')
            ->toJson();
    }
}
