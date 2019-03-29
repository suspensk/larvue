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
        $query = Tag::orderBy('created_at', 'desc');
     /*   if (isset($request->q)){
            if(!is_array($request->q)){
                $query->where('name', 'like', '%' . $request->q .'%');
            } else {
                foreach($request->q as $tag){
                    $query->orWhere('name', '=', $tag);
                }
            }
        }*/

      //   \DB::connection()->enableQueryLog();

        $tags= $query->withCount('notes')->get();
//        $queries = \DB::connection()->getQueryLog();
//        $last_query = end($queries);
//            var_dump($queries);
        return response()->json($tags->toArray());
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
           // 'text' => 'required'
        ]);

        if ($validator->fails()) {
            $errorString = implode("<br/>",$validator->messages()->all());
          //  return response()->json(['error' => $validator->errors()], 401);
            return response()->json(['message' => $errorString], 403);
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
