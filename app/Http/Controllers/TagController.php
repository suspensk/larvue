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
        $user= $request->user('api');
        $user_id = 0;
        if(!empty($user)){
            $user_id = $user['id'];
        }
// !!
      /*  $query = Tag::orderBy('created_at', 'desc');
        if (isset($request->q)){
            if(!is_array($request->q)){
                $query->where('name', 'like', '%' . $request->q .'%');
            } else {
                foreach($request->q as $tag){
                    $query->orWhere('name', '=', $tag);
                }
            }
        }*/
        $whereStr = '';
         if (isset($request->q)){
              if(!is_array($request->q)){
                  $whereStr .= ' WHERE `name` like "%' . $request->q .'%"';
                 // $query->where('name', 'like', '%' . $request->q .'%');
              } else {
                  foreach($request->q as $tag){
                      $whereStr .= ' OR `name` = "' . $tag .'"';
                   //   $query->orWhere('name', '=', $tag);
                  }
                  $whereStr = ' WHERE ' . substr($whereStr,3);
              }
          }

         \DB::connection()->enableQueryLog();
        $tags = \DB::select('SELECT `tags`.*, (
                                SELECT COUNT(*)
                                FROM `notes`
                                INNER JOIN `note_tag` ON `notes`.`id` = `note_tag`.`note_id`
                                WHERE `tags`.`id` = `note_tag`.`tag_id` AND `notes`.`privacy` = 0) AS `feed_count`,
                                (
                                SELECT COUNT(*)
                                FROM `notes`
                                INNER JOIN `note_tag` ON `notes`.`id` = `note_tag`.`note_id`
                                WHERE `tags`.`id` = `note_tag`.`tag_id` AND `notes`.user_id = ' .$user_id . ') AS `notes_count`
                                FROM `tags` '.$whereStr.' 
                                ORDER BY `created_at` DESC');

       // $tags= $query->withCount('notes')->has('privacy','=',0)->get();
      //  $tags= $query->leftJoin('notes', 'users.id', '=', 'posts.user_id')->get();
        $queries = \DB::connection()->getQueryLog();
        $last_query = end($queries);
       //     var_dump($queries);
        return response()->json($tags);
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
