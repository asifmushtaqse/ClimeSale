<?php

namespace App\Http\Controllers;

use App\PriceAdjustment;
use App\Product;
use App\Stock;
use App\StockOut;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stocks = StockOut::with('product','customer');
        if($request->has('fromDate')){
            $fromDate = Carbon::parse($request->get('fromDate'))->format('Y-m-d');
            $toDate = Carbon::parse($request->get('toDate'))->format('Y-m-d');
            $stocks = $stocks->whereBetween('date', [$fromDate, $toDate]);
        }
        return response()->json($stocks->get());
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

            $totalBuyPrice = $product->price * $quantity;
            $priceAdjustment = PriceAdjustment::where('product_id', $product->id)->first();
            if($priceAdjustment){
                if($priceAdjustment->quantity >= $quantity){
                    $priceAdjustment->quantity -= $quantity;
                    $totalBuyPrice += ($quantity*$priceAdjustment->price);
                    $priceAdjustment->save();
                    if($priceAdjustment->quantity == 0){
                        $priceAdjustment->delete();
                    }
                }
                if($priceAdjustment->quantity < $quantity){
                    $totalBuyPrice += ($priceAdjustment->quantity*$priceAdjustment->price);
                    $tempQuantity = $quantity-$priceAdjustment->quantity;
                    $totalBuyPrice += ($tempQuantity*$product->price);

                    $priceAdjustment->delete();
                }

            }

            $stockOut = StockOut::create([
                'date' => $date,
                'price' => $price,
                'customer_id' => $stockData['customer'],
                'product_id' => $product->id,
                'buy_price' => $totalBuyPrice,
                'quantity' => $quantity,
                'is_total' => $stockData["is_total"]
            ]);


            $stock = Stock::firstOrNew(['product_id' => $product->id]);
            $stock->quantity -= $quantity;
            $stock->save();

            return response()->json(($stockOut));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function show(StockOut $stockOut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockOut $stockOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockOut  $stockOut
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = StockOut::find($id);

        $product = Stock::where('product_id', $stock->product_id)->first();
        $product->quantity += $stock->quantity;
        $product->save();

        return response()->json($stock->delete());
    }
}
