<?php

namespace App\Http\Controllers;

use App\ReturnStock;
use App\StockOut;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function profit(Request $request){
        $fromDate = Carbon::parse($request->get('fromDate'))->format('Y-m-d');
        $toDate = Carbon::parse($request->get('toDate'))->format('Y-m-d');
        $stockOut = StockOut::with('product','customer')->whereBetween('date', [$fromDate, $toDate])->get();
        $returnStock = ReturnStock::with('product','customer')->whereBetween('date', [$fromDate, $toDate])->get();
        return response()->json(array('stockOut' => $stockOut, 'returnStocks' => $returnStock));
    }
}
