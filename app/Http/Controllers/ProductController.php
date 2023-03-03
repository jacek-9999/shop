<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends ModifyLoggedController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->query->all();
        $skip = $query['skip'] ?? 0;
        $take = $query['take'] ?? 10;
        return Product::all()->skip($skip)->take($take);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|max:255',
            'ean'     => 'required|max:13',
            'sku'     => 'required|unique:products,sku|max:12',
            'shop_id' => 'required|max:255',
        ]);
        $product = new Product($data);
        $product->save();
        $this->modifyLog->log(
            userId: Auth::user()->id,
            shopId: $product->shop()->id,
            productSku: $product->sku,
            eventType: 'create_product'
        );
        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $sku)
    {
        $product = Product::where('sku', $sku)->first();
        if (empty($product)) {
            return response(['status' => 'not found'], 404);
        }
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $ean)
    {
        $product = Product::where('ean', $ean)->first();
        $this->modifyLog->log(
            userId: Auth::user()->id,
            shopId: $product->shop()->id,
            productSku: $product->ean,
            eventType: 'update_product'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $sku)
    {
        $product = Product::where('sku', $sku)->first();
        $this->modifyLog->log(
            userId: Auth::user()->id,
            shopId: $product->shop()->id,
            productSku: $product->sku,
            eventType: 'delete_product');
        $status = $product->delete();
        return ['deleted' => $status];
    }
}
