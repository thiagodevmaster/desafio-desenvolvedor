<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Instrument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InstrumentsController extends Controller
{
    public function index(Request $request)
    {
        $ticker = $request->query('TckrSymb');
        $referenceDate = $request->query('RptDt');
        $format = $request->query('format', 'summary');
        $page = $request->query('page', 1);

        if($referenceDate){
            $request->validate([
                "RptDt" => "required|date_format:Y-m-d"
            ]);
        }

        $cacheKey = "global_history_t:{$ticker}_d:{$referenceDate}_p:{$page}_f{$format}";

        return Cache::tags(['instruments_data'])->remember($cacheKey, 60, function() use($ticker, $referenceDate, $format){
            
            $query = Instrument::query()
                ->when($ticker, fn($q) => $q->where('TckrSymb', 'like', "%{$ticker}%"))
                ->when($referenceDate, fn($q) => $q->where('RptDt', $referenceDate))
                ->latest();

            if (strtolower($format) !== "full") {
                $query->select(["RptDt", "TckrSymb", "MktNm", "SctyCtgyNm", "ISIN", "CrpnNm"]);
            }

            return $query->paginate(10)->toArray();
        });
    }

}
