<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessInstrumentUpload;
use App\Jobs\SendEmailInstrumentsJob;
use App\Models\UploadHistory;
use App\Services\InstrumentsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Throwable;

/**
 * @group Instruments
 * 
 * Group for handling the instruments.
 */
class UploadController extends Controller
{

    /**
     * Upload Instruments
     * This endpoint receives the file, verifies if it's a valid document, if the reference date matches the file, and runs background jobs to save the data. Finally, it sends an email informing the upload status.
     * * @authenticated
     * * @bodyParam file file required The instruments file from B3 (CSV, XLSX). Example: Instuments.csv 
     * * @bodyParam reference_date date required The reference date for the file data (YYYY-MM-DD). Example: 2026-03-22
     * * @response 202 {
     *      "message": "File received and being processed; you will be notified by email upon completion."
     * }
     * 
     * * @response 422 {
     *      "message": "Date mismatch: File is for 2026-01-01, but reference date is 2026-03-23."
     * }
     */
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

            Bus::chain([
                new ProcessInstrumentUpload($history, $archive['path']),
                new SendEmailInstrumentsJob($history, null),
            ])->catch(function (Throwable $e) use ($history) {
                SendEmailInstrumentsJob::dispatch($history, $e->getMessage());
            })->dispatch();

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
