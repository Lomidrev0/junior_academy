<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Course;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'student';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'school' =>['required', 'string', 'max:255'],
            'class' =>['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        if ($data['school'] == null || $data['class'] == null) {
            $student_info = null;
        }
        else {
            $student_info = collect([
                'school' => $data['school'],
                'class' => $data['class'],
                'active' => false,
                'notes' => '',
            ]);
        }
        $user = User::create([
            'name' => $data['name'],
            'student_info' =>$student_info,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        try{
            $user->courses()->attach($data['courses']);
        }catch (\Throwable $exception){
            report($exception);
        }
        return $user;
    }
}
