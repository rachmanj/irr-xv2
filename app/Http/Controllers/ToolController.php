<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function getLocationName($code)
    {
        $locations = [
            '000H-ACC' => 'Accounting HO Balikpapan',
            '000H-ACC-TRN' => 'Accounting HO Balikpapan',
            '001H-FIN' => 'Finance BO Jakarta',
            '001H-FIN-TRN' => 'Finance BO Jakarta',
            '000H-LOG' => 'Logistic HO Balikpapan',
            '000H-LOG-TRN' => 'Logistic HO Balikpapan',
            '017C' => '017C',
            '017C-TRN' => '017C',
            '021C' => '021C',
            '021C-TRN' => '021C',
            '022C' => '022C',
            '022C-TRN' => '022C',
            '023C' => '023C',
            '023C-TRN' => '023C',
        ];

        return $locations[$code] ?? '';
    }

    public function getTransitLocationName($code)
    {
        $transitLocations = [
            '000H-ACC' => '000H-ACC-TRN',
            '001H-FIN' => '001H-FIN-TRN',
            '000H-LOG' => '000H-LOG-TRN',
            '017C' => '017C-TRN',
            '021C' => '021C-TRN',
            '022C' => '022C-TRN',
            '023C' => '023C-TRN',
        ];

        return $transitLocations[$code] ?? '';
    }
}
