<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('name') && !empty($request->get('name'))){
            return response()->json(Product::where('name', 'like', '%'.strtolower($request->get('name')).'%')->get());
        }else{
            return response()->json(Product::all());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $productData = json_decode($request->getContent(), TRUE);
        $product = Product::where('name', '=', strtolower($productData["name"]))->first();
        if($product === null)
            $product = Product::create($productData);
        else
            $product = false;
        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent(), TRUE);
        $product = Product::find($id);
        $product->name = $data["name"];
        $product->price = $data["price"];
        return response()->json($product->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if($product->stock()->count() || $product->stockIn()->count() || $product->stockOut()->count()){
            return false;
        }
        return response()->json($product->delete());
    }
}
