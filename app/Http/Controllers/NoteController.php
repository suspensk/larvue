<?php

namespace App\Http\Controllers;

use App\Tag;
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
        if (!empty($request->tags)){
            $tags = $request->tags;
            $callback = function($subQuery) use ($tags) {
                $subQuery->whereIn('tag_id', $tags);
            };

        }

        $query = Note::orderBy('created_at', 'desc');
        if(!empty($request->feed) && $request->feed == "true"){
            /*if(!empty($user)){
                $query->where(function ($q) use ($user) {
                    $q->where('privacy','=',0)
                        ->orWhere('user_id','=',$user->id);
                });
            } else {
                $query->where('privacy','=',0);
            }*/
            $query->where('privacy','=',0);
        } else {
            if(empty($user)){
                return response()->json([]);
            }
            $query->where('user_id','=',$user->id);
        }

        if(isset($callback)){
            $query->
            whereHas('tags', $callback)->
            with('tags');
        } else {
            $query->with('tags');
        }

        if (!empty($request->search)){
            $search = trim(strtolower($request->search));
            $query->where('text','LIKE',"%$search%");
        }

        $query->
        with('images')->
        with('user');

        //  $notes = $query->get();
   //     \DB::connection()->enableQueryLog();
        $data = $query->simplePaginate(20)->toArray();
        $notes = & $data['data'];

        if(isset($callback)){
            usort($notes, function(array $a, array $b) use($tags){

                $aIds = array_column($a['tags'], 'id');
                $bIds = array_column($b['tags'], 'id');
                $aIntersect = count(array_intersect($tags, $aIds));
                $bIntersect = count(array_intersect($tags, $bIds));

                if ($aIntersect == $bIntersect) {
                    return 0;
                }
                return ($aIntersect > $bIntersect) ? -1 : 1;
            });
        }

     //   $queries = \DB::connection()->getQueryLog();
     //   $last_query = end($queries);
     //   var_dump($queries);

        foreach($notes as $key=>$note){
            $text = str_limit($note['text'], 1000, '');
            if($text != $note['text']){
                $notes[$key]['text'] = $text;
                $notes[$key]['limited'] = true;
            }
        }

        return response()->json($data);
    }


    public function notesCount(Request $request)
    {
        $user = $request->user('api');
        $countFeed = Note::where('privacy','=',0)->count();
        $countNotes = 0;
        if(!empty($user)){
            $countNotes = Note::where('user_id','=',$user->id)->count();
        }

        return response()->json(['feed' => $countFeed, 'notes' => $countNotes]);
    }

    public function addImage($file, $userId, $noteId, $rotated){
        $target_dir = __DIR__ . "/../../../public/uploads/";
        $ext = $file->getClientOriginalExtension();

        $target_file_name = base64_encode('user' .  $userId . 'time' . time()) . '.' . $ext ;
        $this->uploadFiles($file, $target_dir, $target_file_name, $rotated);

        return Image::create([
            'original_name' => $file->getClientOriginalName(),
            'name' => $target_file_name,
            'note_id' => $noteId
        ]);
    }

    public function addTags ($newTags, $userId){
        $addedTags = [];
        foreach($newTags as $newTagValue){
            $newTag = [];
            $newTag['user_id'] = $userId;
            $newTag['name'] = $newTagValue;
            $newTagObj = Tag::create($newTag);
            $addedTags[] = $newTagObj;
        }

        return $addedTags;
    }

    public function joinTags ($tags, $newTags){
            foreach($newTags as $newTagObj){
                $tags[] = $newTagObj->id;
            }
        return $tags;
    }
    public function changeText($tags,$text){
        $oldText = [];
        $newText=[];
        foreach($tags as $at){
            $oldText[]= 'data-id="0" data-value="'.$at->name.'"';
            $newText[]= 'data-id="'.$at->id.'" data-value="'.$at->name.'"';;
        }
        return str_replace($oldText, $newText, $text);

}
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'text' => 'required'
        ]);

        if ($validator->fails()) {
            $errorString = implode("<br/>",$validator->messages()->all());
            return response()->json(['errorText' => $errorString], 403);
        }

        $user = $request->user('api');
        $input = $request->all();
        $input['user_id'] = $user['id'];
        $tags= json_decode($request->tags);
        if(!empty(json_decode($request->newtags))){
            $addedTags = $this->addTags(json_decode($request->newtags), $user['id']);
            $input['text'] = $this->changeText($addedTags, $input['text']);
            $tags = $this->joinTags($tags, $addedTags);
        }

        $note = Note::create($input);
        if(!empty($tags)){
            $note->tags()->attach($tags);
        }

        $note->save();

        $rotated = false;
        if(!empty($request->rotated) && $request->rotated == "true"){
            $rotated = true;
        }
        if(!empty($request->file('image'))){
            $this->addImage($request->file('image'), $user['id'], $note->id, $rotated);
        }
     //   var_dump($note->id);

      //  $success['text'] = $note->text;
        $note = Note::with('tags')->with('images')->
        with('user')->find($note->id);
        return response()->json($note);

      //  return response()->json(['success' => $success]);
    }

    public function update (Request $request, $id) {
        $user = $request->user('api');
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'text' => 'required'
        ]);

        if ($validator->fails()) {
            $errorString = implode("<br/>",$validator->messages()->all());
            return response()->json(['errorText' => $errorString], 403);
        }
        $input['user_id'] = $user['id'];
        $note = Note::with('tags')->with('images')->find($id);
        if($note->user->id != $user['id']){
            return response()->json(['message' => 'Unauthorised'], 401);
        }

      //  $tags = $this->prepareTags(json_decode($request->tags), json_decode($request->newtags), $user['id']);
        $tags= json_decode($request->tags);
        if(!empty(json_decode($request->newtags))){
            $addedTags = $this->addTags(json_decode($request->newtags), $user['id']);
            $input['text'] = $this->changeText($addedTags, $input['text']);
            $tags = $this->joinTags($tags, $addedTags);
        }

        $oldTags = $note->tags;
        $oldTagsIds = $oldTags->pluck('id')->toArray();
        $addedTags = array_diff($tags, $oldTagsIds);
        $removedTags = array_diff($oldTagsIds, $tags);
        if(!empty($addedTags)){
            $note->tags()->attach($addedTags);
        }

        if(!empty($removedTags)){
            $note->tags()->detach($removedTags);
        }
        $rotated = false;
        if(!empty($request->rotated) && $request->rotated == "true"){
            $rotated = true;
        }

        if($request->imageRemoved){
            foreach($note->images as $oldImage){
                $oldImage->delete();
                unlink(__DIR__ . '/../../../public/uploads/'.$oldImage->name);
                unlink(__DIR__ . '/../../../public/uploads/540-'.$oldImage->name);
                unlink(__DIR__ . '/../../../public/uploads/200-'.$oldImage->name);
            }
        }

        if(!empty($request->file('image'))){
            $this->addImage($request->file('image'), $user['id'], $note->id, $rotated);
        } elseif($rotated == true){
            foreach($note->images as $oldImage){
                $this->rotateImage(__DIR__ . '/../../../public/uploads/'.$oldImage->name);
                $this->rotateImage(__DIR__ . '/../../../public/uploads/540-'.$oldImage->name);
                $this->rotateImage(__DIR__ . '/../../../public/uploads/200-'.$oldImage->name);
            }
        }

        $note->update(['text' => $input['text'],'privacy' => $request->privacy]);
        $note = Note::with('tags')->with('images')->
        with('user')->find($id);
        return response()->json($note);
    }

    public function show(Note $note)
    {
        return response()->json($note);
    }

    public function destroy(Request $request, Note $note)
    {
        $user = $request->user('api');
        if($note->user->id != $user['id']){
            return response()->json(['message' => 'Unauthorised'], 401);
        }
        foreach($note->images as $oldImage){
            $oldImage->delete();
            unlink(__DIR__ . '/../../../public/uploads/'.$oldImage->name);
            unlink(__DIR__ . '/../../../public/uploads/540-'.$oldImage->name);
            unlink(__DIR__ . '/../../../public/uploads/200-'.$oldImage->name);
        }

      //  $note->images()->delete();
        $note->tags()->detach();
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

    public function uploadFiles($file, $target_dir, $target_file_name, $rotated){
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

                if($rotated == true){
                    $this->rotateImage($target_dir . $target_file_name);
                }

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

    function rotateImage($photo) {

        $image_info = getimagesize($photo);
        $type = $image_info[2];
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
             $new_image = imagerotate($image, -90, 0);
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
   //     $new_image = imagerotate($new_image, 90, 0);
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
