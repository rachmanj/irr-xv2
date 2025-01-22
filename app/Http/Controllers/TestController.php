<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Accounting\InvoiceController;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {

        $test = app(InvoiceController::class)->monthly_summary();

        return $test;
    }
}
