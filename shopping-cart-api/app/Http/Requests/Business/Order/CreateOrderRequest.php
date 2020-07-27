<?php namespace App\Http\Requests\Business\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
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
            'order_total' => [ 'required', 'numeric', 'min:1' ],
            'order_status' => [ 'required', 'string', Rule::in( [ 'CREATED' ] ) ],
        ];
    }
}
