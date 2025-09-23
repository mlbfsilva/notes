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
        
        // get all the users from database
        //$users = User::all()->toArray();

        // as an object instance of the model's class
        $userModel = new User();
        $users = $userModel->all()->toArray();

        echo '<prev>';
        print_r($users);

    }

    public function logout()
    {
        echo 'logout';
    }
}
