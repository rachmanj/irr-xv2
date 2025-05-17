<?php

namespace App\Http\Controllers;

use App\Models\DocumentDistribution;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get recent document distributions
        $recentDistributions = DocumentDistribution::with(['document', 'fromDepartment', 'toDepartment'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.index', compact('user', 'recentDistributions'));
    }
}
