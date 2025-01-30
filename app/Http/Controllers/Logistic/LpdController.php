<?php

namespace App\Http\Controllers\Logistic;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ToolController;
use App\Models\Lpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LpdController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'logistic.lpd.dashboard',
            'create' => 'logistic.lpd.create',
            'list' => 'logistic.lpd.list',
            'search' => 'logistic.lpd.search',
        ];

        return view($views[$page]);
    }

    public function getReadyToSendDocuments()
    {
        $documents = app(AddocController::class)->getReadyToSendDocuments();
        // Start of Selection
        return datatables()->of($documents)
            ->addColumn('document_type', function ($row) {
                return $row->type->type_name;
            })
            ->addColumn('document_date', function ($row) {
                return $row->document_date ? \Carbon\Carbon::parse($row->document_date)->format('d M Y') : 'N/A';
            })
            ->addColumn('supplier_name', function ($row) {
                return $row->invoice?->supplier?->name ?? 'N/A';
            })
            // Start of Selection
            ->addColumn('invoice_number', function ($row) {
                return $row->invoice?->invoice_number ?? 'N/A';
            })
            // Start of Selection
            ->addColumn('days', function ($row) {
                $today = now();
                if ($row->invoice_id) {
                    return $row->invoice->receive_date ? (int) abs($today->diffInDays($row->invoice->receive_date)) : 'N/A';
                }
                return $row->document_date ? (int) abs($today->diffInDays($row->document_date)) : 'N/A';
            })
            ->addIndexColumn()
            ->addColumn('action', 'logistic.addoc.action')
            ->rawColumns(['action'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lpd_number' => 'required|string|unique:lpds,nomor',
            'date' => 'required|date',
            'destination' => 'required|string',
            'attention_person' => 'required|string',
            'notes' => 'nullable|string',
            'documents' => 'required|string',
        ]);

        try {
            // Decode the JSON string of document IDs
            $documentIds = json_decode($validated['documents'], true);

            if (empty($documentIds)) {
                return response()->json([
                    'message' => 'Please select at least one document'
                ], 422);
            }

            // Create the LPD record
            $lpd = Lpd::create([
                'nomor' => $validated['lpd_number'],
                'date' => $validated['date'],
                'destination' => app(ToolController::class)->getTransitLocationName($validated['destination']),
                'attention_person' => $validated['attention_person'],
                'notes' => $validated['notes'],
                'created_by' => Auth::user()->id,
                'origin' => '000H-LOG',
                'status' => 'draft'
            ]);

            // Attach the selected documents to the LPD using the existing pivot table
            $lpd->documents()->attach($documentIds);

            return response()->json([
                'message' => 'LPD created successfully',
                'lpd' => $lpd
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating LPD: ' . $e->getMessage()
            ], 500);
        }
    }

    public function data()
    {
        $lpds = Lpd::get();

        return datatables()->of($lpds)
            ->addColumn('document_count', function ($lpd) {
                return $lpd->documents()->count();
            })
            ->addColumn('status', function ($lpd) {
                return $lpd->status;
            })
            ->addColumn('action', function ($lpd) {
                return view('logistic.lpd.action', compact('lpd'))->render();
            })
            ->addColumn('formatted_date', function ($lpd) {
                return $lpd->date ? \Carbon\Carbon::parse($lpd->date)->format('d M Y') : '';
            })
            ->addIndexColumn()
            ->toJson();
    }
}
