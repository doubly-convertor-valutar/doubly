<?php

namespace Grizzlyapps\Shopify\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'shop' => 'required|shopify_url'
        ];
    }

	 /**
     * Validate the input.
     *
     * @param  \Illuminate\Validation\Factory  $factory
     * @return \Illuminate\Validation\Validator
     */
    public function validator($factory)
    {
        return $factory->make(
            $this->sanitizeInput(), $this->container->call([$this, 'rules']), $this->messages()
        );
    }

    /**
     * Sanitize the input.
     *
     * @return array
     */
    protected function sanitizeInput()
    {
        if (method_exists($this, 'sanitize'))
        {
            return $this->container->call([$this, 'sanitize']);
        }

        return $this->all();
    }

    public function sanitize()
	{
	    $input = array_map('trim', $this->all());
		$this->replace($input);

	    return $this->all();
	}
}
