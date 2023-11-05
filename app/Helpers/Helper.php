<?php

use Illuminate\Support\Facades\DB;

 function upload_image($image, $folder)
{
    $img = Image::make($image);
    $mime = $img->mime();
    $mim = explode('/', $mime)[1];
    $extension = '.' . $mim;
    $rand = rand(10,100000);
    $name = $rand .time() . $extension;
    $upload_path = 'uploads/' . $folder;
    $image_url = $upload_path . '/' . $name;
    if (!file_exists(public_path($upload_path))) {
        mkdir(public_path($upload_path), 755, true);
    }
    $img->save(public_path($image_url));
    return $image_url;
}


     function delete_image($path)
    {
        if($path != "uploads/default.jpg"){
       File::delete(public_path($path));
        }
    }

     function upload_video($video, $file)
    {
        $videoName = time() . rand(1, 100) . '.' . $video->getClientOriginalExtension();
        $path = $video->storeAs($file, $videoName, 'uploads');
        return 'uploads/' . $path;

    }
    function upload_pdf($pdf, $file)
    {
        $pdfName = time() . rand(1, 100) . '.' . $pdf->getClientOriginalExtension();
        $path = $pdf->storeAs($file, $pdfName, 'uploads');
        return 'uploads/' . $path;
    }

     function upload_new_image($image,$folder){

        $img = Image::make($image);
        $mime = $img->mime();  //edited due to updated to 2.x

        $mim = explode('/', $mime)[1];
        $extension = '.' . $mim;
            $rand = rand(33,2343434);
                $name = $rand.time().$extension;
                $upload_path = 'uploads/'.$folder;
                $image_url = $upload_path .'/'.$name;
                if (!file_exists(public_path($upload_path))) {
        mkdir(public_path($upload_path), 775);
        }
        $img->save(public_path($image_url));
        return $image_url;
       }


      function hash_user_password($password){
        return  Hash::make($password);

     }


