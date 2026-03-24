<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessInstrumentUpload;
use App\Models\UploadHistory;
use Carbon\Carbon;
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

        $file = $request->file('file');
        $fileHash = md5_file($file->getRealPath());

        $date = Carbon::createFromFormat('Y-m-d', $request->reference_date)
            ->startOfDay();
        
        if(UploadHistory::where('file_hash', $fileHash)->exists()){
            return response()->json([
                "message" => "File already processed previously.",
            ], 422);
        }
        
        $path = $file->storeAs('uploads', time() . $file->getClientOriginalName());

        $history = UploadHistory::create([
            'user_id' => auth()->user()->id,
            'file_name' => $file->getClientOriginalName(),
            'file_hash' => $fileHash,
            'reference_date' => $date,
            'status' => 'pending'
        ]);

        ProcessInstrumentUpload::dispatch($history, $path);

        return response()->json([
            "message" => "File received and being processed; you will be notified by email upon completion.",
        ], 202);
    }
}
