<?php

namespace App\Http\Controllers;

use App\PriceAdjustment;
use App\Product;
use App\Stock;
use App\StockIn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stockIns = StockIn::with('product');
        if($request->has('fromDate')){
            $fromDate = Carbon::parse($request->get('fromDate'))->format('Y-m-d');
            $toDate = Carbon::parse($request->get('toDate'))->format('Y-m-d');
            $stockIns = $stockIns->whereBetween('date', [$fromDate, $toDate]);
        }
        return response()->json($stockIns->get());
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
        return DB::transaction(function () use ($stockData){
            $quantity = $stockData["quantity"];
            $date = $stockData["date"];
            $price = $stockData["price"];
            $date = Carbon::parse($date)->format('Y-m-d');

            $product = Product::find($stockData["product"]);

            if($product->price != $price){
                if($product->stock && ($product->stock->quantity > 0)){
                    PriceAdjustment::create([
                        'product_id' => $product->id,
                        'quantity' => $product->stock->quantity,
                        'price' => $product->price
                    ]);
                }
                $product->price = $price;
                $product->save();
            }

            $stockIn = StockIn::create([
                'date' => $date,
                'price' => $price,
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);



            $stock = Stock::firstOrNew(['product_id' => $product->id]);
            $stock->quantity += $quantity;
            $stock->save();

            return response()->json(($stockIn));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockIn  $stockIn
     * @return \Illuminate\Http\Response
     */
    public function show(StockIn $stockIn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockIn  $stockIn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockIn $stockIn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockIn  $stockIn
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = StockIn::find($id);

        $product = Stock::where('product_id', $stock->product_id)->first();
        $product->quantity -= $stock->quantity;
        $product->save();

        return response()->json($stock->delete());
    }
}
