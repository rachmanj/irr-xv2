<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class LpdController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'accounting.lpd.dashboard',
            'search' => 'accounting.lpd.search',
            'create' => 'accounting.lpd.create',
            'list' => 'accounting.lpd.list',
        ];

        if ($page == 'create') {
            $projects = Project::orderBy('code', 'asc')->get();

            return view($views[$page], compact('projects'));
        }

        return view($views[$page]);
    }

    public function readyToDeliverData()
    {
        $documents = app(AdditionalDocumentController::class)->getReadyToSendDocuments();

        return datatables()->of($documents)
            ->addIndexColumn()
            ->addColumn('type', function ($row) {
                return $row->type ? $row->type->type_name : 'N/A';
            })
            ->addColumn('invoice_number', function ($row) {
                return $row->invoice ? $row->invoice->invoice_number : 'N/A';
            })
            ->addColumn('action', 'accounting.lpd.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
