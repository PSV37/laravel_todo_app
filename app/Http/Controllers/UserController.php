<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Mail;
use App\Mail\UserPasswordEmail;
use Hash;
use Gate;
use DateTime;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Gate::allows('isAdmin')){
            abort(404,"Sorry, You can do this actions");
        }
        
        $users = User::all();
        return view('user.index',compact('users'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'created_at' => new \DateTime('NOW')

        ]);
        // User::create($request->all());

        Mail::to($request->email)->send(new UserPasswordEmail($request));
        
        return back()->with('success_msg', 'User created successfully and send credentials on user email');  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $category = User::findOrFail($request->user_id);

        $category->update($request->all());
       
        return back()->with('success_msg', 'User Updated successfully ');
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
        
        $category = User::findOrFail($request->user_id);
        $category->delete();

        return back()->with('success_msg', 'User Deleted successfully ');

    }
}
