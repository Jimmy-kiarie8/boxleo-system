<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Pod extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id', 'image', 'signature', 'notes', 'type', 'path'];

    public function store_image($data, $item)
    {
        $base64 = $data['image'];
        $folderPath = tenant('id') . '/pod/' . $data['user_name'] . '/' . $item . '/';
        $base64Image = explode(";base64,", $base64);
        $explodeImage = explode("image/", $base64Image[0]);
        $imageType = $explodeImage[1];
        $image_base64 = base64_decode($base64Image[1]);
        $file = $folderPath . Str::random(10) . '.' . $imageType;
        Storage::disk('tenant')->put($file, $image_base64);
        return $file;
    }


    public function store_signature(Request $request)
    {
        $user = $request->user();
        $base64 = $request->signature;
        if ($request->signature) {
            $folderPath = tenant('id') . '/pod/' . $user->name . '/' . 'signature' . '/';
            $base64Image = explode(";base64,", $base64);
            $explodeImage = explode("image/", $base64Image[0]);
            $imageType = $explodeImage[1];
            $image_base64 = base64_decode($base64Image[1]);
            $file = $folderPath . Str::random(10) . '.' . $imageType;
            Storage::disk('tenant')->put($file, $image_base64);
            return $file;
        }
        return null;
    }

    /**
     * Get the sale that owns the Pod
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
