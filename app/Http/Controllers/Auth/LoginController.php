<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use RegistersUsers;

    /**
     * Get the user login validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'email'    => 'required|email',
            'password_' => 'required',
        ];
    }

    public function login(Request $request)
    {
        $this->validate($request, $this->rules());
        $user = User::where('email', $request->input('email'))->first();

        if(Hash::check($request->input('password_'), $user->password)){
            $apiToken = base64_encode(Str::random(40));
            User::where('email', $request->input('email'))->update(['api_token' => "$apiToken"]);

            return response()->json(['status' => 'success','api_token' => $apiToken]);
        } else {
            return response()->json(['status' => 'fail'],401);
        }
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool  $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        $this->fireAttemptEvent($credentials, $remember);

        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        // If an implementation of UserInterface was returned, we'll ask the provider
        // to validate the user against the given credentials, and if they are in
        // fact valid we'll log the users into the application and return true.
        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user, $remember);

            return true;
        }

        // If the authentication attempt fails we will fire an event so that the user
        // may be notified of any suspicious attempts to access their account from
        // an unrecognized user. A developer may listen to this event as needed.
        $this->fireFailedEvent($user, $credentials);

        return false;
    }
}
