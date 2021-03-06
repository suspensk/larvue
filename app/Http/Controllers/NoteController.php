<?php

namespace App\Http\Controllers;

use App\Note;
use App\Image;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('api');
        if (isset($request->q)){
            $q = json_decode($request->q);
            if (!empty($q->tags)){
                $callback = function($subQuery) use ($q) {
                    $subQuery->whereIn('tag_id', $q->tags);
                };
            }
        }

        $query = Note::orderBy('created_at', 'desc');
        if(!empty($user)){
            $query->where(function ($q) use ($user) {
                $q->where('privacy','=',0)
                    ->orWhere('user_id','=',$user->id);
            });
        } else {
            $query->where('privacy','=',0);
        }

        if(isset($callback)){
            $query->
            whereHas('tags', $callback)->
            with(['tags' => $callback]);
        } else {
            $query->with('tags');
        }

        $query->
        with('images')->
        with('user');
        $notes = $query->get();
//        \DB::connection()->enableQueryLog();
//        $notes= $query->get();
//        $queries = \DB::connection()->getQueryLog();
//        $last_query = end($queries);
    //    var_dump($queries);

        foreach($notes as $key=>$note){
            $text = str_limit($note->text, 1000, '');
            if($text != $note->text){
                $notes[$key]['text'] = $text;
                $notes[$key]['limited'] = true;
            }
        }
        return response()->json($notes->toArray());
    }

    public function store(Request $request)
    {
        $user = $request->user('api');
        $input = $request->all();
        $file_flag = 0;
        if(!empty($request->file('image'))){
            $file = $request->file('image');
            $target_dir = __DIR__ . "/../../../public/uploads/";
            $ext = $file->getClientOriginalExtension();

            $target_file_name = base64_encode('user' .  $user['id'] . 'time' . time()) . '.' . $ext ;
            $this->uploadFiles($file, $target_dir, $target_file_name);
            $file_flag = 1;
        }


        $validator = Validator::make($request->all(), [
            'text' => 'required'
        ]);

        if ($validator->fails()) {
            $errorString = implode("<br/>",$validator->messages()->all());
            return response()->json(['message' => $errorString], 403);
        }
        $input['user_id'] = $user['id'];
        $note = Note::create($input);
        $tags = json_decode($request->tags);

        foreach($tags as $tag){
            $note->tags()->attach($tag);
        }

        $note->save();

     //   var_dump($note->id);
        if($file_flag){
            Image::create([
                'original_name' => $file->getClientOriginalName(),
                'name' => $target_file_name,
                'note_id' => $note->id
            ]);
        }

        $success['text'] = $note->text;;

        return response()->json(['success' => $success]);
    }

    public function show(Note $note)
    {
        return response()->json($note);
    }

    public function destroy(Note $note)
    {
        $note->images()->delete();
        $note->delete();
        return response()->json(['success'=>0]);
    }

//    public function destroy($id) {
//        $article = Article::findOrFail($id);
//        $article->delete();
//
//        return view('dashboard')->with([
//            'flash_message' => 'Deleted',
//            'flash_message_important' => false
//        ]);
//    }

    public function uploadFiles($file, $target_dir, $target_file_name){
      //  $file = $request->file('image');
        $realPath = $file->getRealPath();
     //   $target_dir = __DIR__ . "/../../../public/uploads/";
     //   $ext = $file->getClientOriginalExtension();
      //  $user= $request->user();

      //  $target_file_name = 'user' .  $user['id'] . 'time' . time() . '.' . $ext ;
        $target_file = $target_dir . $target_file_name;
        $uploadOk = 1;
        $errorMsg = "";
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
        //   if(isset($_POST["submit"])) {
        $check = getimagesize($realPath);
        if($check !== false) {
        //    echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $errorMsg .= "File is not an image. <br/>";
            $uploadOk = 0;
        }
        //    }
// Check if file already exists
        if (file_exists($target_file)) {
            $errorMsg .=  "Sorry, file already exists. <br/>";
            $uploadOk = 0;
        }
// Check file size
        define('KB', 1024);
        define('MB', 1048576);
        define('GB', 1073741824);
        define('TB', 1099511627776);
        if ($file->getClientSize() > 15*MB) {
            $errorMsg .= "Sorry, your file is too large. <br/>";
            $uploadOk = 0;
        }
// Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            $errorMsg .=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed. <br/>";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $errorMsg .= "Sorry, your file was not uploaded.";
            throw new \App\Exceptions\CustomException($errorMsg);
// if everything is ok, try to upload file
        } else {
        //    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            if ($file->move($target_dir, $target_file_name )) {
                if (copy($target_dir . $target_file_name, $target_dir . '200-' . $target_file_name)) {
                    $this->resize_image($target_dir . '200-' . $target_file_name, 200);
                }
                if (copy($target_dir . $target_file_name, $target_dir . '540-' . $target_file_name)) {
                    $this->resize_image($target_dir . '540-' . $target_file_name, 540);
                }


              //  echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
            } else {
               // echo "Sorry, there was an error uploading your file.";
                throw new \App\Exceptions\CustomException('Sorry, there was an error uploading your file.');
            }
        }
        return $target_file;
    }

    function resize_image($photo, $new_width) {
        // Get the image info from the photo
        $image_info = getimagesize($photo);
        $width = $image_info[0];
        $height = $new_height = $image_info[1];
        $type = $image_info[2];

// Load the image
        switch ($type)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($photo);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($photo);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($photo);
                break;
            default:
                die('Error loading '.$photo.' - File type '.$type.' not supported');
        }

// Create a new, resized image
    //    $new_width = 180;
        $new_height = $height / ($width / $new_width);
        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// Save the new image over the top of the original photo
        switch ($type)
        {
            case IMAGETYPE_JPEG:
                imagejpeg($new_image, $photo, 100);
                break;
            case IMAGETYPE_GIF:
                imagegif($new_image, $photo);
                break;
            case IMAGETYPE_PNG:
                imagepng($new_image, $photo);
                break;
            default:
                die('Error saving image: '.$photo);
        }
    }

//    public function getDetails()
//    {
//        return response()->json(['success' => Auth::user()]);
//    }
}
