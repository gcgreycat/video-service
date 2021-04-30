<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PaginableRequest
 * @package App\Http\Requests
 *
 * @property ?string $perPage
 * @property string $page
 */
class PaginableRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => 'gt:0',
            'per_page' => 'between:0,21'
        ];
    }
}
