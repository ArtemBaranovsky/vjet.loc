<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormRequestInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController implements FormRequestInterface
{
    /**
     * @var
     */
    protected $service;

    /**
     * @var array
     */
    protected array $params;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->params = $request->all();

        if (!Auth::guest()) {
            $request->merge(['user_id' => Auth::id()]);
        };

        $this->request = $request;
    }

    /**
     * Return the Request Object
     *
     * @return \Illuminate\Http\Request
     */
    public function getParams(): Request
    {
        return $this->request->replace($this->params);
    }
}
