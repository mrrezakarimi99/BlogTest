<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request): bool
    {
        if ($request->method == 'PUT') {
            return Gate::allows('update', Post::class);
        }
        return Gate::allows('create', Post::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title'     => 'required|string',
            'content'   => 'required|string|max:250',
            'thumbnail' => 'required|mimes:jpeg,jpg,png,gif|max:4096',
        ];
    }
}
