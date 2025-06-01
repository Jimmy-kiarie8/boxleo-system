<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\OrderHistory;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        // $sale = Sale::find($request->sale_id);

        $comment = new Comment;
        $comment->user_id = Auth::id();
        $comment->sale_id = $request->sale_id;
        $comment->comment = $request->comment;
        $comment->save();
        $history = new Comment();
        return $history->comment($request->all(), $request->sale_id);
        $comment = new Comment();
        return $comment->comment($request->all(), $request->sale_id);
        // $comment = new Comment;
        // $comment->comment = $request->comment;
        // $comment->user_id = Auth::id();
        // $comment->order_id = $id;
        // $comment->save();
        // $data = $request->all();
        // $data['user_id'] = Auth::id();
        // Comment::create($data);


        $history  = new OrderHistory();
        $history->action = 'Commented';
        $reference_no = IdGenerator::generate(['table' => 'order_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
        $history->comment = $request->comment;
        $history->update_comment = 'A comment added';
        $history->reference_no = $reference_no;
        $history->user_name = Auth::user()->name;

        $history->user_id = Auth::id();
        $history->sale_id = $request->sale_id;
        $history->save();
        // if($history->save()) {
        //     // $sale->history_comment = 'A comment added by ' . '<b style="color: red;">' . Auth::user()->name . '</b>';
        //     // $sale->save();
        // }
        return $history;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        // dd($id);
        $comments = Comment::with(['user' => function ($user) {
            $user->setEagerLoads([]);
        }])->where('sale_id', $id)->get();
        $comments->transform(function ($item) {
                 $item->user_name = $item->user->name;
                 return $item;
        });
        return $comments;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comment::find($id)->delete();
    }
}
