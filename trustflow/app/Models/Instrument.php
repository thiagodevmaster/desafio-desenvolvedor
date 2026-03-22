<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = [
        "upload_history_id",
        "RptDt",
        "TckrSymb",
        "MktNm",
        "SctyCtgyNm",
        "ISIN",
        "CrpnNm"
    ];

    protected $hidden = [
        'upload_history_id'
    ];

    protected $casts = [
        "RptDt" => 'date'
    ];

    public function uploadHistory()
    {
        return $this->belongsTo(UploadHistory::class);
    }
    
}
