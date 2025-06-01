<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Dropbox\Client;
use Dropbox\WriteMode;

class ImageController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $image_file = $request->all();
        dd($image_file);
        $image = new Image();
        $image->product_id = $request->id;


        if ($request->hasFile('image')) {
            $img = $request->image;
            if (File::exists($image->image)) {
                $image_path = $image->image;
                File::delete($image_path);
            }


            $imagename = Storage::disk(env('STORAGE_DISK'))->put('slider', $img);
            $imgArr = explode('/', $imagename);
            $image_name = $imgArr[1];
            $image->image = env('STORAGE_PATH') . '/products/' . $image_name;

            // $imagename = Storage::disk('public')->put('products', $img);
            // $imgArr = explode('/', $imagename);
            // $image_name = $imgArr[1];
            // $image->image = '/storage/products/' . $image_name;


            $image->save();
            return $image;
        }
        return 'error';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function images(Request $request, $id)
    {
        // dd(Storage::disk(env('STORAGE_DISK'))->delete('/pos/products/1MniNbpPc6XXwZJkbxQ8Sriuz2X66EjR42E3FK8x.jpeg'));
        // return ;

        // dd($request->all());
        // dd($image);

        if ($request->hasFile('file')) {
            $image = Image::where('product_id', $id)->where('display', 1)->first();
            if (!$image) {
                $image_file = $request->all();
                $image = new Image();
            } else {
                $image_display = Image::where('product_id', $id)->first();
                // foreach ($image_display as  $image_update) {
                //     $image_update->display = false;
                //     $image_update->save();
                // }
                // $file_arr = explode('.com', $image->image);
                // $image_path = $file_arr[1];
                // dd($file_arr);
                if ($image_display) {
                    if (Storage::disk(env('STORAGE_DISK'))->exists($image_display->image)) {
                        $image_path = $image->image;
                        Storage::disk(env('STORAGE_DISK'))->delete($image_path);
                    }
                }
            }
            $image->product_id = $id;

            $img = $request->file;
            $imagename = Storage::disk(env('STORAGE_DISK'))->put('/products', $img, 'public');
            // $imagename = Storage::disk(env('STORAGE_DISK'))->putFile('pos/products', $img, 'public');
            // $imgArr = explode('/', $imagename);
            // $image_name = $imgArr[2];
            $uploaded_img = env('STORAGE_PATH') . $imagename;
            $image->image = $uploaded_img;
            $image->display = true;
            $image->save();
            return $image;
        }
        return 'error';
    }

    public function product_image(Request $request, $id)
    {
        // return $request->all();
        if ($request->hasFile('image')) {
            $image = Image::where('product_id', $id)->where('display', 0)->first();
            if (!$image) {
                $image_file = $request->all();
                $image = new Image();
            } else {
                $image_display = Image::where('product_id', $id)->where('display', 0)->get();
                foreach ($image_display as  $image_update) {
                    $image_update->display = false;
                    $image_update->save();
                }
                $file_arr = explode('.com', $image->image);
                $image_path = $file_arr[1];
                // dd($file_arr);
                if (Storage::disk(env('STORAGE_DISK'))->exists($image_path)) {
                    $image_path = $image->image;
                    Storage::disk(env('STORAGE_DISK'))->delete($image_path);
                }
            }
            $image->product_id = $id;

            $img = $request->image;
            $imagename = Storage::disk(env('STORAGE_DISK'))->putFile('pos/productImages', $img, 'public');
            $imgArr = explode('/', $imagename);
            $image_name = $imgArr[2];
            $uploaded_img = env('STORAGE_PATH') . 'pos/productImages/' . $image_name;
            $image->image = $uploaded_img;
            $image->display = false;
            $image->save();
            return $image;
        }
        return 'error';
    }


    public function dropboxFileUpload()
    {
        $Client = new Client(env('DROPBOX_TOKEN'), env('DROPBOX_SECRET'));


        $file = fopen(public_path('img/admin.png'), 'rb');
        $size = filesize(public_path('img/admin.png'));
        $dropboxFileName = '/myphoto4.png';


        $Client->uploadFile($dropboxFileName, WriteMode::add(), $file, $size);
        $links['share'] = $Client->createShareableLink($dropboxFileName);
        $links['view'] = $Client->createTemporaryDirectLink($dropboxFileName);


        print_r($links);
    }
}
