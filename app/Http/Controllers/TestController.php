<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Accounting\AdditionalDocumentController;
use App\Http\Controllers\Accounting\InvoiceController;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        // $test = app(AdditionalDocumentController::class)->getReadyToSendDocuments();
        // $test = app(InvoiceController::class)->monthly_summary();
        // $test = app(ToolController::class)->getLocationName('000H');
        $test = app(InvoiceController::class)->getReadyToDeliverInvoices();
        return $test;
    }
}
