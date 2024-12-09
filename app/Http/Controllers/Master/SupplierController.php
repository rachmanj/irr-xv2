<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
