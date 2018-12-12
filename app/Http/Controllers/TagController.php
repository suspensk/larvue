<?php

namespace App\Http\Controllers;

use App\Tag;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TagController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->q)){
            $tags= Tag::where('name', 'like', '%' . $request->q .'%')->orderBy('created_at', 'desc')->get();
        } else {
            $tags= Tag::orderBy('created_at', 'desc')->get();
        }

        return response()->json($tags->toArray());
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
        $user= $request->user();
       // $input['password'] = bcrypt($input['password']);
        $input['user_id'] = $user['id'];
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
