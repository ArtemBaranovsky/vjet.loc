<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyCreateRequest extends Controller
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'phone' => 'nullable|min:5|max:20',
            'description' => 'required|max:5000',
        ];
    }

    public function __construct(Request $request)
    {
        $this->validate($request, $this->rules());

        parent:: __construct ($request);
   }
}
