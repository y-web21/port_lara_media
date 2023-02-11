<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostArticleRequest extends FormRequest
{
    /**
     * バリデータが最初のルールの失敗で停​​止するかを指示
     *
     * @var bool
     */
    protected $stopOnFirstFailure = false;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'max:100'],
            'content' => ['required', 'max:5000'],
            'status_id' => ['required', 'int', 'between:0,1'],
        ];
    }

    /**
     * 定義済みバリデーションルールのエラーメッセージ取得
     *
     * @return array
     */
    public function messages()
    {
        return [
            'status_id.between' => __('Unknown error. Please select again.'),
        ];
    }
}
