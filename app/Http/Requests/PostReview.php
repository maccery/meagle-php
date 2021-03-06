<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostReview extends FormRequest
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
            'description' => 'required|min:80',
            'title' => 'required|min:30|max:255',
            'negative' => 'sometimes',
            'positive' => 'sometimes',
        ];
    }
}
