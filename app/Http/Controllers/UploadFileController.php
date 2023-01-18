<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['maintenance_mode']);
    }

    public function upload_image(Request $request){
    	$request->validate([
            'files.*' => [
                'required',
                'image',
                'mimes:jpeg,jpg,bmp,png,svg,gif'
            ],
        ], [], [
            'files.*' => 'File'
        ]);
        $host = activeFileStorage();
        $files = $request->files;
    	$image_url = [];
        if($host == 'AmazonS3'){
            foreach ($files as $file) {
                foreach($file as $k => $f){
                    $img_name_for_db = 'images/editorImage/'. time() . "." . $f->getClientOriginalExtension();
                    $path = Storage::disk('s3')->put($img_name_for_db, file_get_contents($f), 'public');
                    $image_url[$k] = Storage::disk('s3')->url($img_name_for_db);
                }
            }
        }else{
            if (!file_exists(asset_path('uploads/editor-image'))) {
                mkdir(asset_path('uploads/editor-image'), 0777, true);
            }
            foreach ($files as $file) {
                foreach($file as $k => $f){
                    $fileName = time() . "." . $f->getClientOriginalExtension();
                    $f->move(asset_path('uploads/editor-image/'), $fileName);
                    $image_url[$k] = asset(asset_path('uploads/editor-image/') . $fileName);
    
                }
            }
        }
        return response()->json($image_url);
    }
}
