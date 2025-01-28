<?php

namespace App\Http\Controllers\Logistic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddocController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'dashboard');

        $views = [
            'dashboard' => 'logistic.addoc.dashboard',
            'create' => 'logistic.addoc.create',
            'list' => 'logistic.addoc.list',
            'search' => 'logistic.addoc.search',
        ];

        return view($views[$page]);
    }
}
