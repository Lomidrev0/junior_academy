<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Course;
use App\Providers\RouteServiceProvider;
use App\SystemVariable;
use App\Traits\RegisterPermitTrait;
use App\User;
use Carbon\Carbon;
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
    use RegisterPermitTrait;

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
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'school' => ['required', 'string', 'max:255'],
            'class' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!$this->permitCheck()) {
            $validator->after(function ($validator) {
                $validator->errors()->add('permit_check', 'Sorry, you are not allowed to register at this time. Please try again later.');
            });
        }

        return $validator;
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
