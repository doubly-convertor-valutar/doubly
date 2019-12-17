<?php namespace App\Http\Requests;

use App\Http\Requests\Request;


class SettingFormRequest extends Request {

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

		$theme_validations       = $this->get_theme_validations();
		$on_hover_validations    = $this->get_show_price_on_hover_validation();
		$check_out_curr_notify	 = $this->check_out_curr_notify_validation();

		$common_fields = [

				'enabled'			                => 'required|boolean',
				'auto_switch'                       => 'required|boolean',
				'currency_switcher_theme'           => 'required',
				'show_original_price_on_hover'      => 'required|boolean',
				'display_currency_name'      		=> 'required|boolean',
				'display_currency_name'      		=> 'required|boolean',
				'remove_decimals'      				=> 'required|boolean'
			];

		return $this->merge_validations($common_fields,$theme_validations,$on_hover_validations,$check_out_curr_notify);
	}

	private function get_theme_validations(){

		if($this->currency_switcher_theme == 'layered_theme'){

			return [

				'layered_background_color'          => 'required',
				'layered_text_color'                => 'required',
				'layered_hover_background_color'    => 'required',
				'layered_hover_text_color'          => 'required',
				'layered_selected_background_color' => 'required',
				'layered_selected_text_color'       => 'required'
			];

		}elseif($this->currency_switcher_theme == 'circle_theme'){

			return [

				'circle_border_color'              => 'required',
				'circle_background_color'          => 'required',
				'circle_text_color'                => 'required',
				'circle_hover_background_color'    => 'required',
				'circle_hover_text_color'          => 'required',
				'circle_selected_background_color' => 'required',
				'circle_selected_text_color'       => 'required'
			];

		}elseif($this->currency_switcher_theme == 'flags_theme'){

			return [

				'flag_background_color'            => 'required',
				'flag_text_color'                  => 'required',
				'flag_hover_color'                 => 'required'
			];

		}
	}

	private function get_show_price_on_hover_validation(){

		if($this->show_original_price_on_hover){

			return [

				'show_on_hover_price_color'         => 'required',
				'show_on_hover_background_color'    => 'required'

			];
		}
	}

	private function check_out_curr_notify_validation(){

		if($this->check_out_curr_notify){

			return [

				'message'                           => 'required',
				'check_out_curr_message_color'      => 'required',
				'check_out_curr_background_color'   => 'required',

			];
		}
	}

	private function merge_validations($common_fields,$theme_validations,$on_hover_validations,$check_out_curr_notify){

		$final_array = [];

		if(count($common_fields)) 	        $final_array = 	$common_fields;
		if(count($theme_validations))       $final_array =  array_merge($final_array,$theme_validations);
		if(count($on_hover_validations))    $final_array =  array_merge($final_array,$on_hover_validations);
		if(count($check_out_curr_notify))   $final_array =  array_merge($final_array,$check_out_curr_notify);

		return $final_array;
	}

}
