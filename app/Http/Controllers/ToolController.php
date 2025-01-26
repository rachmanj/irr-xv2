<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function getLocationName($code)
    {
        $locations = [
            '000H' => 'HO Balikpapan',
            '000H-TRN' => 'HO Transit',
            '001H' => 'BO Jakarta',
            '001H-TRN' => 'BO Transit',
            '000H-LOG' => 'HO Logistic',
            '000H-LOG-TRN' => 'HO Logistic Transit',
            '017C' => '017C',
            '017C-TRN' => '017C Transit',
            '021C' => '021C',
            '021C-TRN' => '021C Transit',
            '022C' => '022C',
            '022C-TRN' => '022C Transit',
            '023C' => '023C',
            '023C-TRN' => '023C Transit',
        ];

        return $locations[$code] ?? '';
    }

    public function getTransitLocationName($code)
    {
        $transitLocations = [
            '000H' => '000H-TRN',
            '001H' => '001H-TRN',
            '000H-LOG' => '000H-LOG-TRN',
            '017C' => '017C-TRN',
            '021C' => '021C-TRN',
            '022C' => '022C-TRN',
            '023C' => '023C-TRN',
        ];

        return $transitLocations[$code] ?? '';
    }
}
