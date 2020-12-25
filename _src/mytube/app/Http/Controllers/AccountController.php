<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function show(){
    	return view('account', [
    		'user' => request()->user()
    	]);
    }

    public function update(Request $request){

    	$this->validate($request, [
    		'email' => 'required|unique:users,email,' . $request->user()->id,
    		'name' => 'required'
    	]);

    	$payload = [
    		'name' => $request->name,
    		'email' => $request->email
    	];


    	if($request->has('password')){
    		$payload['password'] = bcrypt($request->password);
    	}


    	$request->user()->update($payload);

    	return redirect()->back();

    }
}
