<?php

namespace App\Http\Controllers\API;

use App\User; 
use Socialite;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
		public $successStatus = 200;
		/**
		 * API Login
		 */
		public function login()
		{ 
			if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
				$user = Auth::user(); 
				$success['token'] =  $user->createToken('MyApp')-> accessToken; 
				return response()->json(['success' => $success], $this-> successStatus); 
			} 
			else{ 
				return response()->json(['error'=>'Unauthorised'], 401); 
			} 
		}

		/**
		 * API Register
		 */
		public function register(Request $request) 
    { 
			$validator = Validator::make($request->all(), [ 
				'name' => ['required', 'string', 'max:255'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'password' => ['required', 'string', 'min:8', 'confirmed'],
			]);
			if ($validator->fails()) { 
				return response()->json(['error'=>$validator->errors()], 401);            
			}
			$user = User::create([
				'name' => $request['name'],
				'email' => $request['email'],
				'password' => Hash::make($request['password'])
			]); 
			$success['token'] =  $user->createToken('MyApp')->accessToken; 
			$success['name'] =  $user->name;
			return response()->json(['success'=>$success], 200); 
		}

		/**
		 * API Logout
		 */
		public function logout (Request $request) 
		{
			$token = $request->user()->token();
			$token->revoke();
			$response = 'You have been succesfully logged out!';
			return response()->json(['success'=>$response], 200);
	
		}
		
		/**
		 * API UserDetails
		 * For testing purposes
		 */
		public function details() 
    { 
			$user = Auth::user(); 
			return response()->json(['success' => $user], 200); 
		}
		
		/* -------------------- Google Login Functions -------------------- */
		/**
		 * Google Auth API Login
		 */
		public function authlogin($userId)
		{ 
			$user = Auth::loginUsingId($userId, true); 
			$success['token'] =  $user->createToken('MyApp')-> accessToken; 
			return response()->json(['success' => $success], $this-> successStatus); 
		}
		/**
		* Redirect the user to the Google authentication page.
		*
		* @return \Illuminate\Http\Response
		*/
		public function redirectToGoogle()
		{
				return Socialite::driver('google')
        // ->scopes(['openid', 'profile', 'email'])
        ->redirect();
		}

		/**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
		public function handleGoogleCallback()
		{
				try {
						$user = Socialite::driver('google')->user();
				} catch (\Exception $e) {
						return redirect('/login');
				}
				// only allow people with @company.com to login
				// if(explode("@", $user->email)[1] !== 'company.com'){
				// 		return redirect()->to('/');
				// }
				// check if they're an existing user
				$existingUser = User::where('email', $user->email)->first();
				if($existingUser){
					// log them in
					return $this->authlogin($existingUser);
				} else {
					// register new user
					$newUser = User::create([
						'name' => $user->name,
						'email' => $user->email,
						'password' => Hash::make(rand(1,10000))
					]); 
					$success['token'] =  $newUser->createToken('MyApp')->accessToken; 
					$success['name'] =  $newUser->name;
					return response()->json(['success'=>$success], 200);
				}
		}
}
