<?php

namespace App\Http\Controllers\Logistic;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ToolController;
use App\Models\Lpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\Department;
use Illuminate\Support\Facades\Log;

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

        if ($page == 'create') {
            $projects = Project::orderBy('code', 'asc')->get();
            return view($views[$page], compact('projects'));
        }

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

    public function create()
    {
        $projects = Project::orderBy('code')->get();
        return view('logistic.lpd.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lpd_number' => 'required|string|max:50|unique:lpds,nomor',
            'date' => 'required|date',
            'destination_department' => 'required|integer|exists:departments,id',
            'attention_person' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'documents' => 'required|json'
        ]);

        $lpd = new Lpd();
        $lpd->nomor = $validated['lpd_number'];
        $lpd->date = $validated['date'];
        $lpd->origin = Auth::user()->department_id;
        $lpd->destination = $validated['destination_department'];
        $lpd->attention_person = $validated['attention_person'];
        $lpd->notes = $validated['notes'];
        $lpd->created_by = Auth::user()->id;
        $lpd->status = 'draft';
        $lpd->save();

        // Handle documents...
        $documentIds = json_decode($validated['documents']);
        // Your existing document handling code...

        return response()->json([
            'message' => 'LPD created successfully',
            'redirect' => route('logistic.lpd.index', ['page' => 'list'])
        ]);
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

    public function show($id)
    {
        $lpd = Lpd::with(['documents.type', 'documents.invoice.supplier', 'createdBy'])->findOrFail($id);
        return view('logistic.lpd.show', compact('lpd'));
    }

    public function edit(Lpd $lpd)
    {
        $projects = Project::orderBy('code')->get();
        return view('logistic.lpd.edit', compact('lpd', 'projects'));
    }

    public function update(Request $request, Lpd $lpd)
    {
        $validated = $request->validate([
            'lpd_number' => 'required|string|max:50|unique:lpds,nomor,' . $lpd->id,
            'date' => 'required|date',
            'destination_department' => 'required|integer|exists:departments,id',
            'attention_person' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'documents' => 'required|json'
        ]);

        $lpd->nomor = $validated['lpd_number'];
        $lpd->date = $validated['date'];
        $lpd->destination = $validated['destination_department'];
        $lpd->attention_person = $validated['attention_person'];
        $lpd->notes = $validated['notes'];
        $lpd->save();

        // Handle documents...
        $documentIds = json_decode($validated['documents']);
        // Your existing document handling code...

        return response()->json([
            'message' => 'LPD updated successfully',
            'redirect' => route('logistic.lpd.index', ['page' => 'list'])
        ]);
    }

    public function print($id)
    {
        $lpd = Lpd::with(['documents.type', 'documents.invoice.supplier', 'createdBy'])->findOrFail($id);
        return view('logistic.lpd.print', compact('lpd'));
    }

    public function send($id)
    {
        $lpd = Lpd::findOrFail($id);

        if ($lpd->status !== 'draft') {
            return response()->json([
                'message' => 'This LPD has already been sent'
            ], 422);
        }

        try {
            $lpd->update([
                'status' => 'sent',
                'sent_at' => Carbon::now()
            ]);

            return response()->json([
                'message' => 'LPD has been sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while sending LPD: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $lpd = Lpd::findOrFail($id);

        if ($lpd->status !== 'draft') {
            return response()->json([
                'message' => 'Only draft LPDs can be deleted'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Detach all documents first
            $lpd->documents()->detach();

            // Delete the LPD
            $lpd->delete();

            DB::commit();

            return response()->json([
                'message' => 'LPD deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while deleting LPD: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDepartments($projectCode)
    {
        try {
            Log::info('Fetching departments for project: ' . $projectCode);

            // First check if project exists
            $project = Project::where('code', $projectCode)->first();
            if (!$project) {
                Log::warning('Project not found: ' . $projectCode);
                return response()->json([
                    'error' => 'Project not found'
                ], 404);
            }

            // Get departments
            $departments = DB::table('departments')
                ->select('id', 'project', 'department_name', 'akronim')
                ->where('project', $projectCode)
                ->orderBy('department_name')
                ->get()
                ->toArray(); // Convert to array

            Log::info('Found departments:', [
                'count' => count($departments),
                'data' => $departments
            ]);

            return response()->json($departments); // Return array directly
        } catch (\Exception $e) {
            Log::error('Error fetching departments: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to fetch departments: ' . $e->getMessage()
            ], 500);
        }
    }
}
