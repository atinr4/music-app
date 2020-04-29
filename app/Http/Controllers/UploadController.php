<?php

namespace App\Http\Controllers;
use Log;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\UserGameSystem;

class UploadController extends Controller
{
   
    public function doUpload (Request $request)
    {
 
        if($request->has('files')) {
            $user_id = $request->input('user_id');
            $files = $request->file('files');
            
            $name = $files->getClientOriginalName();
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            $name = rand().'.'.$extension;
            $storage_folder = $this->public_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $user_id. DIRECTORY_SEPARATOR;

            $files = $files->move($storage_folder, $name);

            $getUser = UserGameSystem::find($user_id);
            $getUser->profile_image = 'app' . DIRECTORY_SEPARATOR . $user_id. DIRECTORY_SEPARATOR . $name;
            $getUser->save();
            $getUser->profile_image = url( $getUser->profile_image );
            
            return response()->json(['message' => 'Profile Picture updated Successfully','user_data'=>$getUser], 200);

        }
    }

    public function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }

}