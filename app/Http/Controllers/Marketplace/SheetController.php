<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Jobs\GoogleSyncJob;
use App\Jobs\GoogleUpload;
use App\Models\Sheet;
use App\Models\ZoneSheet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Revolution\Google\Sheets\Facades\Sheets;

class SheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return request('status');
        $current = (request('status') == 'Current') ? true : false;
        return Sheet::with(['vendor'])
            ->when($current, function ($q) {
                return $q->where('is_current', true);
            })
            ->where('ou_id', Auth::user()->ou_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'vendor_id' => 'required',
            'post_spreadsheet_id' => 'required',
            'sheet_name' => 'required'
        ]);
        // return $request->all();
        // $this->update($request, $request->vendor_id);
        $sheet_exists = Sheet::where('post_spreadsheet_id', $request->post_spreadsheet_id)->where('sheet_name', $request->sheet_name)->exists();

        if ($sheet_exists) {
            abort(422, 'Sheet with the same id and name exists!');
        }

        $sheet = new Sheet;
        $sheet->vendor_id = $request->vendor_id;
        $sheet->sheet_name = $request->sheet_name;
        $sheet->post_spreadsheet_id = $request->post_spreadsheet_id;
        $sheet->ou_id = Auth::user()->ou_id;
        $sheet->sync_all = true;
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
        $request->validate([
            'vendor_id' => 'required',
            'post_spreadsheet_id' => 'required',
            'sheet_name' => 'required'
        ]);
        // return $request->all();

        $sheet = Sheet::find($id);
        if (!$sheet) {
            $sheet = new Sheet;
            $sheet->vendor_id = $id;
        }
        $sheet->sheet_name = $request->sheet_name;
        $sheet->post_spreadsheet_id = $request->post_spreadsheet_id;
        $sheet->ou_id = Auth::user()->ou_id;
        $sheet->sync_all = true;
        $sheet->save();
        return $sheet;
    }

    public function google_status(Request $request, $id)
    {
        $sheet = Sheet::find($id);
        $sheet->active = !$request->active;
        $sheet->save();
    }

    public function google_current(Request $request, $id)
    {
        $sheet = Sheet::find($id);
        $sheet->is_current = !$request->is_current;
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
    
    public function google_sync($sync)
    {
        $sheet = Sheet::where('id', $sync)->first();
        
        // Check if last_order_upload was more than 2 minutes ago
        if ($sheet->last_order_upload > Carbon::now()->subMinute()) {
            return;
        }
    
        // Update last_order_upload to the current time
        $sheet->update(['last_order_upload' => Carbon::now()]);
    
        // Dispatch the GoogleUpload job
        $this->dispatch(new GoogleUpload($sync, Auth::id()));
    }
    

    public function sheet_update(Request $request, $id)
    {
        $sheet = Sheet::find($id);
        $sheet->auto_sync = $request->auto_sync;
        $sheet->ou_id = $request->ou_id;
        $sheet->order_prefix = $request->order_prefix;
        $sheet->ou_id = $request->ou_id;
        $sheet->post_spreadsheet_id = $request->post_spreadsheet_id;
        $sheet->lastUpdatedOrderNumber = $request->lastUpdatedOrderNumber;
        $sheet->last_order_synced = $request->last_order_synced;
        $sheet->sheet_name = $request->sheet_name;
        $sheet->sync_all = $request->sync_all;
        $sheet->sync_interval = $request->sync_interval;
        $sheet->save();
        return $sheet;
    }

    public function sheet_sync($id)
    {
        // $sheet = Sheet::find($id);
        // if ($sheet) {
        //     $vendor_id = $sheet->vendor_id;
        //     $this->dispatch(new GoogleSyncJob($vendor_id));
        // }  else {
        //     abort(422, 'Sheet not found');
        // }

        $sheet = Sheet::where('active', true)->find($id);
        if ($sheet) {
            $this->dispatch(new GoogleSyncJob($id));
        } else {
            abort(422, 'Sheet not found');
        }
    }

    public function zone_sheets()
    {
        return ZoneSheet::paginate(100);
    }
    public function zone_sheets_update(Request $request, $id)
    {
        // return $request->all();
        $zone = ZoneSheet::find($id);
        $zone->sheet_name = $request->sheet_name;
        $zone->post_spreadsheet_id = $request->post_spreadsheet_id;
        $zone->zones = $request->zones;
        $zone->save();
    }

    public function zone_sheets_create(Request $request)
    {
        $request->validate([
            'post_spreadsheet_id' => 'required',
            'sheet_name' => 'required'
        ]);
        // return $request->all();
        // $this->update($request, $request->vendor_id);
        $sheet_exists = ZoneSheet::where('post_spreadsheet_id', $request->post_spreadsheet_id)->where('sheet_name', $request->sheet_name)->exists();

        if ($sheet_exists) {
            abort(422, 'Sheet with the same id and name exists!');
        }
        // return $request->all();
        $zone = new ZoneSheet;
        $zone->sheet_name = $request->sheet_name;
        $zone->post_spreadsheet_id = $request->post_spreadsheet_id;
        $zone->ou_id = Auth::user()->ou_id;
        $zone->zones = $request->zones;
        $zone->save();
    }
}
