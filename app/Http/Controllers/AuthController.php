<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        // form validation
        $request->validate(
            [
                'text_username' => 'required|email', 
                'text_password' => 'required|min:8|max:12',
            ],
            //error messages
            [
                'text_username.required' => 'O preenchimento do campo username é obrigatório.',
                'text_username.email' => 'Username preeenchido dever ser um email válido.',
                'text_password.required' => 'O preenchimento do campo password é obrigatório.',
                'text_password.min' => 'A password deve ter pelo menos :min carácteres',
                'text_password.max' => 'A password deve ter no máximo :max carácteres',
            ]
        );

        // get user input 
        $username = $request->input('text_username');
        $password = $request->input('text_password');   
        
        // check if user exists
        $user = User::where('username', $username)
                        ->where('deleted_at', NULL)
                        ->first();

        if(!$user){
            return redirect()
                    ->back()
                    ->withInput()
                    ->with('loginError', 'Username incorreto.');
        }

        // check if password is correct
        if(!password_verify($password, $user->password)){
            return redirect()
                    ->back()
                    ->withInput()
                    ->with('loginError', 'Password incorreta.');
        }

        // update last login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        // login user
        session([
            'user' => [
                'id' => $user->id,
                'usename' => $user->username
            ]
            ]);

        echo "Login com sucesso!";
    }

    public function logout()
    {
        // logout from the aplication
        session()->forget('user');
        return redirect()->to('/login');
    }
}
