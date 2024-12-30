<?php

namespace App\Http\Controllers;

use App\Models\DocumentLog;
use Illuminate\Http\Request;

class DocumentLogController extends Controller
{
    public function saveLog($model, $recordId, $type, $userId, $points = null)
    {
        $points = $points ?? 5;

        $documentLog = DocumentLog::create([
            'model' => $model,
            'record_id' => $recordId,
            'type' => $type,
            'user_id' => $userId,
            'points' => $points,
        ]);

        return response()->json($documentLog, 201);
    }
}
