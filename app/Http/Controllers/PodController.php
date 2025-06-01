<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pod;
use Illuminate\Support\Facades\Storage;

class PodController extends Controller
{
    
    public function pod_upload(Request $request, $id)
    {
        $file = $request->file('file');

        if ($file) {
            $file_name = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $fileName = $file_name . '-'  . uniqid() . '.' . $ext;

            // Store the file in DigitalOcean Spaces
            Storage::disk('spaces')->put('boxleo/pods/' . $fileName, file_get_contents($file), 'public');

            // Generate the URL for the stored file
            $pdfUrl = 'https://' . env('DO_SPACES_BUCKET') . '.' . env('DO_SPACES_DB_ENDPOINT') . '/boxleo/pods/' . $fileName;


            $pod = new Pod;
            $pod->sale_id = $id;
            $pod->path = $pdfUrl; // Store the file path in the database
            $pod->notes = $file_name; // Store the file path in the database
            $pod->type = $ext; // Store the file path in the database
            $pod->save();
        }
        return;
    }

    public function pod($id)
    {
        return Pod::where('sale_id', $id)->get();
    }
}
