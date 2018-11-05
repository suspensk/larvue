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
        return response()->json(Note::all()->toArray());
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'text' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
       // $input['password'] = bcrypt($input['password']);

        $note = Note::create($input);
     //   $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $note->name;
        $success['text'] = $note->text;;

        return response()->json(['success' => $success]);
    }



//    public function getDetails()
//    {
//        return response()->json(['success' => Auth::user()]);
//    }
}
