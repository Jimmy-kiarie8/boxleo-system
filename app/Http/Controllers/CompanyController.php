<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Ou;
use App\Models\Setup;
// use App\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Cache::remember('company', now()->addHours(10), function () {
            return Company::first();
        });
        // if (Company::first()) {
        // } else {
        //     return Company::all();
        // }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Company::first()) {
             Company::first()->update($request->all());
            return 'Updated';
        } else {
            Company::create($request->all());
            return 'created';
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        return  Company::first()->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
    public function company_logo(Request $request)
    {
        // dd(Storage::disk(env('STORAGE_DISK'))->delete('/pos/products/1MniNbpPc6XXwZJkbxQ8Sriuz2X66EjR42E3FK8x.jpeg'));
        // return ;

        // return $request->all();
        // dd($image);

        if ($request->hasFile('image')) {
            $image = Company::first();
            if ($image->logo) {
                // dd($file_arr);
                if (Storage::disk(env('STORAGE_DISK'))->exists($image->logo)) {
                    Storage::disk(env('STORAGE_DISK'))->delete($image->logo);
                }
            }
            $img = $request->image;
            $imagename = Storage::disk(env('STORAGE_DISK'))->put('logo', $img, 'public');
            // dd($imagename);
            // $imgArr = explode('/', $imagename);
            // $image_name = $imgArr[3];
            // dd($imgArr);
            $uploaded_img = 'storage/' . $imagename;
            // $uploaded_img = env('STORAGE_PATH') . 'logo' . $image_name;
            $image->logo = $uploaded_img;
            $image->save();
            $setup = new Setup();
            $setup->updateSetup('company_logo');
            return $image;
        }
        return 'error';
    }
}
