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

    public function store(Request $request)
    {

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
