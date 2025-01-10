<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Project;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class SpiController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'accounting.spi.dashboard',
            'search' => 'accounting.spi.search',
            'create' => 'accounting.spi.create',
            'list' => 'accounting.spi.list',
        ];

        if ($page == 'create') {
            $projects = Project::orderBy('code', 'asc')->get();
            return view($views[$page], compact('projects'));
        }

        return view($views[$page]);
    }
}
