<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'master.suppliers.dashboard',
            'list' => 'master.suppliers.list',
            'sync' => 'master.suppliers.sync',
        ];

        return view($views[$page]);
    }

    public function import(Request $request)
    {
        try {
            $suppliers = $request->input('customers', []);

            // Insert data to suppliers table
            foreach ($suppliers as $supplier) {
                DB::table('suppliers')->insert([
                    'sap_code' => $supplier['code'],
                    'name' => $supplier['name'],
                    'type' => $supplier['type'],
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Alert success
            Alert::success('Success', 'Data suppliers berhasil diimport');

            return response()->json(['success' => true, 'message' => 'Data suppliers berhasil diimport']);
        } catch (\Exception $e) {
            Log::error('Failed to import suppliers: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to import suppliers'], 500);
        }
    }
}
