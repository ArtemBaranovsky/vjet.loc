<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\SendsPasswordResetEmails;
use App\Traits\ValidatesRequests;
use Illuminate\Http\Request;

class RequestPasswordController extends Controller
{
    use SendsPasswordResetEmails, ValidatesRequests;

    public function __construct()
    {
        $this->broker = 'users';
    }

    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

    public function sendResetLink(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

//dd($response);
        return json_encode(['status' => trans($response)]);
    }


}
