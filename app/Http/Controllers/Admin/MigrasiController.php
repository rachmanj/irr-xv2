<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MigrasiController extends Controller
{
    public function index()
    {
        return view('admin.migrasi.index');
    }

    public function copyInvoiceIRR5()
    {
        // Add your logic to copy the invoice here
        return response()->json(['message' => 'Invoice copied successfully!']);
    }
}
