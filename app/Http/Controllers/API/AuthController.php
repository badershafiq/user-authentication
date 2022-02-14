<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
	
	
	
	public function register(Request $request)
	{
		$validator = Validator::make($request->all() ,[
			'name' => 'required|max:55',
			'email' => 'email|required|unique:users',
			'password' => 'required'
		]);
		
		if($validator->fails()) {
			return response()->json($validator->errors(), 422);
		} else {
			$validatedData = $validator->validated();
			$validatedData['password'] = bcrypt($request->password);
			
			$user = User::create($validatedData);
			
			$accessToken = $user->createToken('authToken')->accessToken;
			
			return response([ 'user' => $user, 'access_token' => $accessToken]);
		}

	}
	
	public function login(Request $request)
	{
		$validator = Validator::make($request->all() ,[
			'email' => 'email|required',
			'password' => 'required'
		]);
		
		if ($validator->fails())
		{
			return response(['errors'=>$validator->errors()->all()], 422);
		}
		$user = User::where('email', $request->email)->first();
		if ($user) {
			if (Hash::check($request->password, $user->password)) {
				$token = $user->createToken('Laravel Password Grant Client')->accessToken;
				$response = ['token' => $token];
				return response($response, 200);
			} else {
				$response = ["message" => "Password mismatch"];
				return response($response, 422);
			}
		} else {
			$response = ["message" =>'User does not exist'];
			return response($response, 422);
		}
	}
}
