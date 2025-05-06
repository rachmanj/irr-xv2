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
            // Ensure we return JSON errors, not HTML
            config(['app.debug' => false]);
            
            // Add debug logging
            Log::info('Import request received', [
                'contentType' => $request->header('Content-Type'),
                'authId' => Auth::id() 
            ]);
            
            // Check if the user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthorized: You must be logged in to import suppliers'
                ], 401);
            }
            
            // Check if the request is JSON and handle accordingly
            if ($request->isJson() || $request->header('Content-Type') == 'application/json') {
                $data = $request->json()->all();
                $suppliers = $data['customers'] ?? [];
                
                if (isset($data['debug'])) {
                    Log::info('Debug mode enabled via JSON');
                }
            } else {
                $suppliers = $request->input('customers', []);
                
                if ($request->has('debug')) {
                    Log::info('Debug mode enabled via form data');
                }
            }
            
            if (empty($suppliers)) {
                Log::warning('No suppliers data found in request');
                return response()->json(['success' => false, 'message' => 'No suppliers data found in request'], 400);
            }
            
            Log::info('Processing ' . count($suppliers) . ' suppliers');
            
            DB::beginTransaction();
            
            try {
                $createdCount = 0;
                $updatedCount = 0;
                $batchSize = 50;
                $totalCount = count($suppliers);
                
                // Process in smaller batches to prevent memory issues
                for ($i = 0; $i < $totalCount; $i += $batchSize) {
                    $batch = array_slice($suppliers, $i, $batchSize);
                    
                    foreach ($batch as $supplier) {
                        // Handle different formats - supplier might be an array or object
                        $supplierName = is_array($supplier) ? ($supplier['name'] ?? null) : ($supplier->name ?? null);
                        $supplierCode = is_array($supplier) ? ($supplier['code'] ?? null) : ($supplier->code ?? null);
                        $supplierType = is_array($supplier) ? ($supplier['type'] ?? 'vendor') : ($supplier->type ?? 'vendor');
                        $supplierProject = is_array($supplier) ? ($supplier['project'] ?? '001H') : ($supplier->project ?? '001H');
                        
                        if (!$supplierName) {
                            Log::warning('Supplier name is missing', ['code' => $supplierCode]);
                            continue;
                        }
                        
                        $existingSupplier = Supplier::where('sap_code', $supplierCode)->first();
                        
                        if (!$existingSupplier) {
                            // Create new supplier
                            Supplier::create([
                                'sap_code' => $supplierCode,
                                'name' => $supplierName,
                                'type' => $supplierType,
                                'payment_project' => $supplierProject,
                                'created_by' => Auth::id(),
                            ]);
                            $createdCount++;
                        } else {
                            // Update existing supplier
                            $existingSupplier->update([
                                'name' => $supplierName,
                                'type' => $supplierType,
                                'payment_project' => $supplierProject ?? $existingSupplier->payment_project,
                            ]);
                            $updatedCount++;
                        }
                    }
                    
                    // Log progress
                    Log::info("Processed batch: {$i} to " . min($i + $batchSize, $totalCount) . " of {$totalCount}");
                }
                
                DB::commit();
                
                // Alert success
                $message = "Data suppliers berhasil diimport. Total created: $createdCount, updated: $updatedCount";
                Log::info($message);
                Alert::success('Success', $message);

                return response()->json(['success' => true, 'message' => $message]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to import suppliers: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to import suppliers: ' . $e->getMessage()], 500);
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
