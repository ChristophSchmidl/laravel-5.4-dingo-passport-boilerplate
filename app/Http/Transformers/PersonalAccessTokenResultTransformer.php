<?php

namespace App\Http\Transformers;

use Illuminate\Support\Facades\Log;
use Laravel\Passport\PersonalAccessTokenResult;
use League\Fractal\TransformerAbstract;

class PersonalAccessTokenResultTransformer extends TransformerAbstract
{

    /**
     * @param PersonalAccessTokenResult $personalAccessTokenResult
     * @return array
     */
    public function transform(PersonalAccessTokenResult $personalAccessTokenResult)
    {
        return [
            'access_token' => $personalAccessTokenResult->accessToken
        ];
    }

}