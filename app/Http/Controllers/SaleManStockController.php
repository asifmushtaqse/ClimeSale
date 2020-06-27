<?php

namespace App\Http\Controllers;

use App\SaleManStock;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleManStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $saleManStocks = SaleManStock::with('product.stock');
        if($request->has('date')){
            $date = Carbon::parse($request->get('date'))->format('Y-m-d');
            $saleManStocks = $saleManStocks->where('date', '=', $date);
        }
        return response()->json($saleManStocks->get()->groupBy('product_id'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $stockData = json_decode($request->getContent(), TRUE);
        $quantity = ltrim($stockData['quantity'], '+');
        $quantity = rtrim($stockData['quantity'], '+');

        $saleManStock = SaleManStock::create([
            'product_id' => $stockData['product_id'],
            'quantity' => $quantity,
            'date' => Carbon::parse($stockData['date'])->format('Y-m-d')
        ]);
        return response()->json(($saleManStock));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleManStock  $saleManStock
     * @return \Illuminate\Http\Response
     */
    public function show(SaleManStock $saleManStock)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SaleManStock  $saleManStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleManStock  $saleManStock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $saleManStock = SaleManStock::find($id);
        return response()->json($saleManStock->delete());
    }
}
