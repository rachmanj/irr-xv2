<?php

namespace App\Imports;

use App\Models\AdditionalDocument;
use App\Models\AdditionalDocumentType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class ItoImport implements ToModel, WithHeadingRow
{
    public $itoTypeId;
    public $batchNo;

    public function __construct()
    {
        $this->itoTypeId = $this->getItoTypeId();
        $this->batchNo = $this->getBatchNo();
    }

    public function model(array $row)
    {
        return new AdditionalDocument([
            'type_id' => $this->itoTypeId,
            'document_number' => $row['ito_no'],
            'document_date' => $this->convert_date($row['ito_date']),
            'po_no' => $row['po_no'],
            'created_by' => Auth::user()->id,
            'remarks' => $row['ito_remarks'],
            'ito_creator' => $row['ito_created_by'],
            'grpo_no' => $row['grpo_no'],
            'origin_wh' => $row['origin_whs'],
            'destination_wh' => $row['destination_whs'],
            'batch_no' => $this->batchNo,
        ]);
    }

    private function convert_date($date)
    {
        if ($date) {
            $year = substr($date, 6, 4);
            $month = substr($date, 3, 2);
            $day = substr($date, 0, 2);
            $new_date = $year . '-' . $month . '-' . $day;
            return $new_date;
        } else {
            return null;
        }
    }

    private function getItoTypeId()
    {
        $ito_type = AdditionalDocumentType::where('type_name', 'ITO')->first();
        return $ito_type->id;
    }

    private function getBatchNo()
    {
        $batch_no = AdditionalDocument::max('batch_no');
        return $batch_no + 1;
    }
}
