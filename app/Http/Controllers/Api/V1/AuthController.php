<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    use Helpers;


    /**
     * This method forces Laravel to put its validation errors
     * into the errors array of a Dingo Response
     *
     * @param Request $request
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function throwValidationException(Request $request, $validator) {
        throw new ValidationHttpException($validator->getMessageBag()->toArray());
    }

    /**
     * @param Request $request
     * @return array
     */
    public function issuePersonalAccessToken(Request $request)
    {

        $this->validate($request, [
            "email" => "required|email",
            "password" => "required"
        ]);

        if ( Auth::guard("web")->once($request->all()) ){

            $user = User::where("email", $request->email)->firstOrFail();

            $token = $user->createToken('Personal Access Token')->accessToken;

            return [
                "access_token" => $token
            ];
        };

        $this->response->errorUnauthorized("Custom Error Message");
    }


    /**
     * @param Request $request
     * @return \Illuminate\Auth\GenericUser|\Illuminate\Database\Eloquent\Model
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return $user;
    }

    public function deletePersonalAccessToken(Request $request)
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();
    }

}
