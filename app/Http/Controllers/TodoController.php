<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Todo;


class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $todos = Todo::orderBy('id', 'DESC')->get();

        return response()->json(array('data'=> $todos), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isName = Todo::where('name', $request->name)->first();
        if($isName) {
            return response()->json(array('msg'=> 'Duplicate entry'), 400);
        }
        Todo::create([
            'name' => $request->name
        ]);

        return response()->json(array('msg'=> 'Successfully added'), 200);
    }

 




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // return $request;
        
        $category = Todo::findOrFail($request->id);
        $category->delete();

        return response()->json(array('msg'=> 'Successfully deleted'), 200);

    }
}
