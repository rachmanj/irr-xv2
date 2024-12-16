<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Accounting\AdditionalDocumentController;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $test = app(AdditionalDocumentController::class)->outs_addoc();

        return $test;
    }
}
