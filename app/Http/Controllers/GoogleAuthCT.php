<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthCT extends Controller
{
    public function redirect() {
        return socialite::driver('google')->redirect();

    }

    // public function callbackGoogle(){
    //     try{
    //         $google_user = socialite::driver()->user();
    //         $user = User::where('google_id',$google_user->getId())->first();
    //     }
    // }

    public function callbackGoogle(){
        try{
            $google_user = Socialite::driver("google")->user();
            $user = User::where("email",$google_user->getEmail())->first();

            if(!$user){
                $new_user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),

                ]);

                Auth::login($new_user);
                return redirect()->intended('register');
            } else {
                Auth::login($user);
                return redirect()->intended('register');
            } 
            
        } catch(\Throwable $throwable){
            dd('Oh disini kurang tepat!'. $throwable->getMessage());
        }
        
    }
}
