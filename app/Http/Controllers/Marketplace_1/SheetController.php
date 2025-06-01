<?php

namespace App\Http\Controllers\Marketplace_;

use App\Http\Controllers\Controller;
use App\Jobs\GoogleUpload;
use App\Models\Sheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class SheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sheet::with(['vendor'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $sheet_exist = Sheet::where('post_spreadsheet_id', $request->post_spreadsheet_id)->where('sheet_name', $request->sheet_name)->exists();

        if($sheet_exist) {
            abort(422, 'Sheet exists');
        }

        $sheet = new Sheet;
        $sheet->sheet_name = $request->sheet_name;
        $sheet->vendor_id = $request->vendor_id;
        $sheet->post_spreadsheet_id = $request->post_spreadsheet_id;
        $sheet->ou_id = Auth::user()->ou_id;
        $sheet->save();
        return $sheet;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sheet  $sheet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Sheet::where('vendor_id', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sheet  $sheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();

        $sheet = Sheet::find($id);
        /*  if (!$sheet) {
            $sheet = new Sheet;
            $sheet->vendor_id = $id;
        } */
        $sheet->sheet_name = $request->sheet_name;
        $sheet->post_spreadsheet_id = $request->post_spreadsheet_id;
        $sheet->ou_id = Auth::user()->ou_id;
        $sheet->save();
        return $sheet;
    }

    public function google_status(Request $request, $id)
    {
        $sheet = Sheet::find($id);
        $sheet->active = !$request->active;
        $sheet->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sheet  $sheet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function google_sync($id)
    {
        // Artisan::call('upload:google');
        $this->dispatch(new GoogleUpload($id));
    }

    public function sheet_update(Request $request, $id)
    {
        $sheet = Sheet::find($id);
        $sheet->auto_sync = $request->auto_sync;
        $sheet->order_prefix = $request->order_prefix;
        $sheet->ou_id = $request->ou_id;
        $sheet->post_spreadsheet_id = $request->post_spreadsheet_id;
        $sheet->sheet_name = $request->sheet_name;
        $sheet->sync_all = $request->sync_all;
        $sheet->sync_interval = $request->sync_interval;
        $sheet->save();
        return $sheet;
    }
}
