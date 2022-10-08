<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Servises\AnalysisServise;
use App\Servises\DecileServise;
use App\Servises\RFMServise;
use Illuminate\Support\Facades\Log; 

use Illuminate\Support\Facades\DB;


class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        $subQuery = Order::betweenDate($request->startDate, $request->endDate);

        if ($request->type === 'perDay') {
            list($data, $labels, $totals) = AnalysisServise::perDay($subQuery);
        }

        if ($request->type === 'perMonth') {
            list($data, $labels, $totals) = AnalysisServise::perMonth($subQuery);
        }

        if ($request->type === 'perYear') {
            list($data, $labels, $totals) = AnalysisServise::perYear($subQuery);
        }

        if ($request->type === 'decile') {
            list($data, $labels, $totals) = DecileServise::decile($subQuery);
        }

        if($request->type === 'rfm'){
            list($data, $totals, $eachCount) = RFMServise::rfm($subQuery, $request->rfmPrms);
         
            return response()->json([
                'data' => $data,
                'type' => $request->type,
                'eachCount' => $eachCount,
                'totals' => $totals,
            ], Response::HTTP_OK);
        }

        return response()->json(
            [
                'data' => $data,
                'type' => $request->type,
                'labels' => $labels,
                'totals' => $totals
            ],
            Response::HTTP_OK
        );
    }
}
