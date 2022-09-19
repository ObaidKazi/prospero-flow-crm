<?php

namespace App\Http\Controllers\Email;

use Illuminate\Http\Request;
use App\Http\Controllers\MainController;
use App\Models\Email;
use Illuminate\Support\Facades\Auth;

class EmailUpdateController extends MainController
{
    public function update(Request $request, int $id)
    {
        $email = Email::find($id);
        $data['froms'] = [
            Auth::user()->company->email,
            Auth::user()->email,
        ];
        $data['email'] =  $email;
        return view('email.email', $data);
    }
}
