<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyCreateRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllCompanies()
    {
        $companies = Company::all()->where('user_id', '=', Auth::id());

        return response()->json($companies, 200);
    }

    /**
     * @param CompanyCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CompanyCreateRequest $request)
    {
        $companyData = [
            ...$request->params,
            'user_id' => Auth::id()
        ];

        $company = Company::create($companyData);

        return response()->json($company, 201);
    }
}
