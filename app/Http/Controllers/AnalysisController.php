<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AnalysisController extends Controller
{
    public function index()
    {
        $startDate = '2022-08-01'; $endDate = '2022-08-31';
        // 日別
        $subQuery = Order::betweenDate($startDate, $endDate)
        ->where('status', true)->groupBy('id')
        ->selectRaw('id, SUM(subtotal) as totalPerPurchase,
        DATE_FORMAT(created_at, "%Y%m%d") as date');
       
        $data = DB::table($subQuery)
        ->groupBy('date')
        ->selectRaw('date, sum(totalPerPurchase) as total')
        ->get();

        return Inertia::render('Analysis');
    }
}
