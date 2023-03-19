<?php

namespace App\Http\Controllers\User;

use Auth, Hash, Lang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        return view('user.password.edit');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'kata_sandi_lama' => 'required',
            'kata_sandi_baru' => 'required|confirmed|min:8'
        ]);

        $user = Auth::user();

        if (Hash::check($request->kata_sandi_lama, $user->password) !== true)
            return $this->validationError(Lang::get('Your current password is incorrect.'));

        $user->password = bcrypt($request->kata_sandi_baru);
        $user->save();

        return redirect()->back()->with('status', Lang::get('Your password has been successfully updated.'));
    }
}
