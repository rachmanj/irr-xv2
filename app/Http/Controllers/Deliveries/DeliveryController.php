<?php

namespace App\Http\Controllers\Deliveries;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Project;
use App\Models\User;
use App\Models\Invoice;
use App\Models\AdditionalDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'deliveries.deliveries.dashboard',
            'search' => 'deliveries.deliveries.search',
            'create' => 'deliveries.deliveries.create',
            'list' => 'deliveries.deliveries.list',
        ];

        if ($page === 'dashboard') {
            $deliveries = Delivery::with([
                'originProject',
                'destinationProject',
                'creator',
                'receiver',
                'invoices',
                'additionalDocuments'
            ])->latest()->paginate(10);

            return view($views[$page], compact('deliveries'));
        } elseif ($page === 'search') {
            return view($views[$page]);
        } elseif ($page === 'create') {
            return $this->create();
        } elseif ($page === 'list') {
            return view($views[$page]);
        }

        return view($views[$page]);
    }

    public function create()
    {
        $projects = Project::all();
        $users = User::all();
        $invoices = Invoice::all();
        $additionalDocuments = AdditionalDocument::all();

        return view('deliveries.deliveries.create', compact('projects', 'users', 'invoices', 'additionalDocuments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_number' => 'required|unique:deliveries,delivery_number',
            'date_sent' => 'required|date',
            'destination_project' => 'required|exists:projects,code',
            'attention_person' => 'required|string',
            'delivery_type' => 'required|in:full,documents_only',
            'notes' => 'nullable|string',
            'invoices' => 'required|json'
        ]);

        // Get logged in user's project
        $userProject = Auth::user()->project->code;

        $delivery = Delivery::create([
            'delivery_number' => $validated['delivery_number'],
            'date_sent' => $validated['date_sent'],
            'origin_project' => $userProject,
            'destination_project' => $validated['destination_project'],
            'attention_person' => $validated['attention_person'],
            'delivery_type' => $validated['delivery_type'],
            'notes' => $validated['notes'],
            'creator_id' => Auth::id(),
        ]);

        // Attach invoices using the pivot table
        $invoiceIds = json_decode($validated['invoices'], true);
        $delivery->invoices()->attach($invoiceIds, [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('deliveries.deliveries.show', $delivery)
            ->with('success', 'Delivery created successfully');
    }

    public function show(Delivery $delivery)
    {
        $delivery->load([
            'originProject',
            'destinationProject',
            'creator',
            'receiver',
            'invoices',
            'additionalDocuments'
        ]);

        return view('deliveries.deliveries.show', compact('delivery'));
    }

    public function markAsReceived(Delivery $delivery)
    {
        $delivery->update([
            'date_received' => now(),
            'receiver_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Delivery marked as received');
    }

    public function readyToDeliverData()
    {
        $invoices = app(InvoiceController::class)->getReadyToDeliverInvoices();

        return datatables()->of($invoices)
            ->addColumn('supplier_name', function ($invoice) {
                return $invoice->supplier->name ?? '';
            })
            ->addColumn('project_code', function ($invoice) {
                return $invoice->invoice_project->code ?? '';
            })
            ->addColumn('amount', function ($invoice) {
                return number_format($invoice->amount, 2);
            })
            ->addColumn('status', function ($invoice) {
                return ucfirst($invoice->status);
            })
            ->toJson();
    }
} 