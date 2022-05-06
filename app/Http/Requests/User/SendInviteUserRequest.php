<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CattrFormRequest;
use App\Models\User;

class SendInviteUserRequest extends CattrFormRequest
{
    /**
     * Determine if user authorized to make this request.
     *
     * @return bool
     */
    public function _authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function _rules(): array
    {
        return [
            'id' => 'required|int|exists:users,id'
        ];
    }
}