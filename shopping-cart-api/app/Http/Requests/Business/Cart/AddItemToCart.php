<?php namespace App\Http\Requests\Business\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddItemToCart extends FormRequest
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
            'quantity'   => [ 'required', 'numeric', 'min:0', 'max:10' ],
            'client_key' => [ 'required', 'string' ],
        ];
    }
}
