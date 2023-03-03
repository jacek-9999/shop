<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends ModifyLoggedController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->query->all();
        $skip = $query['skip'] ?? 0;
        $take = $query['take'] ?? 10;
        return Shop::all()->skip($skip)->take($take);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|max:255',
            'postcode'  => 'required|max:63',
            'country'   => 'required|max:127',
            'city'      => 'required|max:255',
            'street'    => 'required|max:255',
            'latitude'  => 'required|decimal:4,6',
            'longitude' => 'required|decimal:4,6',
        ]);
        $shop = new Shop($data);
        $shop->save();
        $this->modifyLog->log(
            userId: Auth::user()->id,
            shopId: $shop->id,
            eventType: 'create_shop'
        );
        return $shop;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shop = Shop::where('id', $id)->first();
        if (empty($shop)) {
            return response(['status' => 'not found'], 404);
        }
        return $shop;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shop =  Shop::where('id', $id)->first();
        $data = $request->validate([
            'name'      => 'sometimes|required|max:255',
            'postcode'  => 'sometimes|required|max:63',
            'country'   => 'sometimes|required|max:127',
            'city'      => 'sometimes|required|max:255',
            'street'    => 'sometimes|required|max:255',
            'latitude'  => 'sometimes|required|decimal:4,6',
            'longitude' => 'sometimes|required|decimal:4,6',
        ]);
        foreach ($data as $key => $value) {
            $shop->{$key} = $value;
        }
        // update
        $this->modifyLog->log(
            userId: Auth::user()->id,
            shopId: $shop->id,
            eventType: 'update_shop'
        );
        $shop->save();
        return $shop;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shop =  Shop::where('id', $id)->first();
        $this->modifyLog->log(
            userId: Auth::user()->id,
            shopId: $shop->id,
            eventType: 'delete_shop'
        );
        $status = $shop->delete();
        return ['deleted' => $status];
    }

    public function getWeather(string $id, WeatherService $weatherService)
    {
        $shop = Shop::where('id', $id)->first();
        return $weatherService->downloadWeatherData($shop->latitude, $shop->longitude);
    }
}
