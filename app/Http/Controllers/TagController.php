<?php

namespace App\Http\Controllers;

use App\Tag;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TagController extends Controller
{
    public function index()
    {
        $tag= Tag::orderBy('created_at', 'desc')->get();
//        foreach($notes as $key=>$note){
//            $text = str_limit($note->text, 5, '');
//            if($text != $note->text){
//                $notes[$key]['text'] = $text;
//                $notes[$key]['limited'] = true;
//            }
//        }
        return response()->json($tag->toArray());
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
           // 'text' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
       // $input['password'] = bcrypt($input['password']);

        $tag = Tag::create($input);
     //   $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $tag->name;

     //   return response()->json(['success' => Auth::user()]);
        return response()->json(['success' => $success]);
    }

    public function show(Tag $tag)
    {
        return response()->json($tag);
    }



//    public function getDetails()
//    {
//        return response()->json(['success' => Auth::user()]);
//    }
}
