<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;

use App\Models\Warehouse\Bin;
use Illuminate\Http\Request;
use PDF;
use DNS2D;

class StickerController extends Controller
{
    public function sticker($id)
    {
        $bin = Bin::find($id);
        $qrcode = DNS2D::getBarcodeHTML($bin->code, 'QRCODE');
        $data = ['qrcode' => $qrcode, 'data' => $bin];
        // return view('pdf.stickers.index', compact('qrcode'));
        $pdf = PDF::loadView('pdf.stickers.index', $data);
        return $pdf->stream('sticker.pdf');
    }
}
