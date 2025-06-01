<?php

namespace App\Http\Controllers;

use App\Models\ProductHistory;
use App\Models\User;
use Illuminate\Http\Request;

class ProductHistoryController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductHistory  $productHistory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $histories =  ProductHistory::with(['user' => function($query) {
            $query->setEagerLoads([]);
        }])->where('product_id', $id)->get();


        $histories->transform(function($history) {
            $random_color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            $history->color = $random_color;
            return $history;
        });
        return $histories;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductHistory  $productHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductHistory  $productHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
