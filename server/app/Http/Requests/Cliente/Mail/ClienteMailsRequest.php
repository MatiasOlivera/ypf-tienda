<?php

namespace App\Http\Requests\Cliente\Mail;

use App\Http\Requests\PaginacionRequest;

class ClienteMailsRequest extends PaginacionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
