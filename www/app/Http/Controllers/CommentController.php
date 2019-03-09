<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Event;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Comment::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $event)
    {
        $request->request->add(['event' => $event]);

        $data = $request->validate([
            'author' => 'required|string|min:2|max:30',
            'text' => 'required|string|max:255',
            'event' => 'required|integer|exists:event,id',
        ]);

        $comment = new Comment;
        $comment->author = $request->author;
        $comment->text = $request->text;
        $comment->event()->associate($request->event);
        $comment->save();

        return ['status' => 'success'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Comment::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        $comment->event()->associate($request->event);
        $comment->author = $request->author;
        $comment->text = $request->text;
        $comment->save();

        return ['status' => 'success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        
        return ['status' => 'success'];
    }
}
