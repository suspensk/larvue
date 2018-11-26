<?php

namespace App\Http\Controllers;

use App\Note;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes= Note::orderBy('created_at', 'desc')->with('tags')->get();
        foreach($notes as $key=>$note){
            $text = str_limit($note->text, 5, '');
            if($text != $note->text){
                $notes[$key]['text'] = $text;
                $notes[$key]['limited'] = true;
            }
        }
        return response()->json($notes->toArray());
    }

    public function uploadFiles(){
        $target_dir = __DIR__ . "/../../../public/uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
     //   if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
    //    }
// Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
// Check file size
        define('KB', 1024);
        define('MB', 1048576);
        define('GB', 1073741824);
        define('TB', 1099511627776);
        if ($_FILES["image"]["size"] > 15*MB) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
// Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    public function store(Request $request)
    {
        if(!empty($_FILES)){
            $this->uploadFiles();
        }
        var_dump($_FILES);
        exit();

        $validator = Validator::make($request->all(), [
         //   'name' => 'required',
            'text' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
       // $input['password'] = bcrypt($input['password']);

        $note = Note::create($input);
        $note->tags()->attach($request->tags);
        $note->save();
     //   $success['token'] = $user->createToken('MyApp')->accessToken;
      //  $success['name'] = $note->name;
        $success['text'] = $note->text;;

        return response()->json(['success' => $success]);
    }

    public function show(Note $note)
    {
        return response()->json($note);
    }



//    public function getDetails()
//    {
//        return response()->json(['success' => Auth::user()]);
//    }
}
