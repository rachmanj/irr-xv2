<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
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

        if ($page === 'dashboard') {
            $dashboard_data = [
                'total_customers' => Supplier::where('type', 'customer')->count(),
                'total_vendors' => Supplier::where('type', 'vendor')->count(),
            ];

            return view($views[$page], compact('dashboard_data'));
        }

        return view($views[$page]);
    }

    public function import(Request $request)
    {
        try {
            $suppliers = $request->input('customers', []);
            $createdCount = 0;

            // Insert data to suppliers table
            foreach ($suppliers as $supplier) {
                $existingSupplier = DB::table('suppliers')->where('sap_code', $supplier['code'])->first();
                if (!$existingSupplier) {
                    DB::table('suppliers')->insert([
                        'sap_code' => $supplier['code'],
                        'name' => $supplier['name'],
                        'type' => $supplier['type'],
                        'created_by' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $createdCount++;
                }
            }

            // Alert success
            Alert::success('Success', "Data suppliers berhasil diimport. Total created: $createdCount");

            return response()->json(['success' => true, 'message' => "Data suppliers berhasil diimport. Total created: $createdCount"]);
        } catch (\Exception $e) {
            Log::error('Failed to import suppliers: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to import suppliers'], 500);
        }
    }

    public function data()
    {
        $suppliers = Supplier::orderBy('name', 'asc')
            ->get();

        return datatables()->of($suppliers)
            ->addIndexColumn()
            ->toJson();
    }

    public function getPaymentProject(Request $request)
    {
        try {
            $supplierId = $request->query('supplier_id');
            $supplier = Supplier::find($supplierId);

            if ($supplier) {
                return response()->json(['payment_project' => $supplier->payment_project]);
            }

            return response()->json(['payment_project' => null]);
        } catch (\Exception $e) {
            Log::error('Failed to get payment project: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get payment project'], 500);
        }
    }
}
