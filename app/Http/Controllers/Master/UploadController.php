<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use App\Imports\ItoImport;

class UploadController extends Controller
{
    public function index()
    {
        $page = request()->query('page', 'ito');

        $views = [
            'dashboard' => 'master.upload.dashboard',
            'ito' => 'master.upload.ito',
            'list' => 'master.upload.list',
        ];

        return view($views[$page]);
    }
}
