<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var array|\string[][]
     */
    private array $rules = [
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/','min:5', 'max:20'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:6'],
    ];

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            $this->rules
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $customMessages = [
            'required' => 'Please fill attribute :attribute'
        ];
        $this->validate($request, $this->rules, $customMessages);

        try {
            $request->merge(['password' => Hash::make($request->password)]);
            $userData = $request->only(['first_name', 'last_name', 'email', 'phone', 'password']);
            $user = User::create($userData);
            $user->save();

            return response()->json([
                'status'  => true,
                'message' => 'Registration success!'
            ], 200);
        } catch (QueryException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
