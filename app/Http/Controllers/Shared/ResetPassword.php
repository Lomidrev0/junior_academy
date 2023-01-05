<?php


namespace App\Http\Controllers\Shared;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPassword
{
    public function passReset(Request $request) {
        if (Hash::check($request->old_password,Auth::user()->password)) {
            $user = User::where('id',Auth::user()->id)->first();
            $update = array();
            $validatedData = $request->validate(
                [
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);
            if (Hash::check($request->password, $user->password)) {
                return redirect()->back()->with('message', 1);

            } else {
                $user->fill([
                    'password' => Hash::make($request->password)
                ])->save();
                return redirect()->back()->with('message', 0);
            }
        }
        else {
            return redirect()->back()->with('message', 2);
        }
    }
}