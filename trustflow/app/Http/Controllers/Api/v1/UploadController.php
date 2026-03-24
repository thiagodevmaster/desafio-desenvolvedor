<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessInstrumentUpload;
use App\Models\UploadHistory;
use App\Services\InstrumentsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:csv,txt,xlsx,xls',
                'max:102400' // 100 MB
            ],
            'reference_date' => "required|date"    
        ]);

        try{
            $service = new InstrumentsService($request->file('file'), $request->reference_date);
            $archive = $service->saveFile();

            $history = UploadHistory::create([
                'user_id' => auth()->user()->id,
                'file_name' => $archive['fileName'],
                'file_hash' => $archive['hash'],
                'reference_date' => $archive['date'],
                'status' => 'pending'
            ]);

            ProcessInstrumentUpload::dispatch($history, $archive['path']);

            return response()->json([
                "message" => "File received and being processed; you will be notified by email upon completion.",
            ], 202);
        }catch(Exception $error) {
            return response()->json([
                "message" => $error->getMessage(),
            ], $error->getCode() ?: 400);
        }

        
    }
}
