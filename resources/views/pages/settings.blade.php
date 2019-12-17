@extends('app')

@section('content')
	<div id="notifications">
		<div class="ui-banner" id="step1">
			<div class="ui-banner__ribbon">
				<span class="next-icon next-info next-icon--size-24 next-icon--no-nudge">1</span>
			</div>
			<div class="ui-banner__content">
				<h2 class="next-heading next-heading--small next-heading--no-margin">You need to edit your Money Formats. <a href="{!! url('faq') !!}">Click here to see how</a>.</h2>
			</div>
		</div>
		<div class="ui-banner" id="step2">
			<div class="ui-banner__ribbon">
				<span class="next-icon next-info next-icon--size-24 next-icon--no-nudge">2</span>
			</div>
			<div class="ui-banner__content">
				<h2 class="next-heading next-heading--small next-heading--no-margin">We've worked hard on making this app run out of the box and it would really help us out if you could leave us a review.</h2><button name="button" type="button" class="btn btn-primary" onclick="review()">I WANT TO HELP!</button>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		@if (!$showPriceTagNotification)
			$('#step1').addClass('line-through');
		@endif
		@if ($hasReviewed)
			$('#step2').addClass('line-through');
		@endif
		@if (!$showPriceTagNotification && $hasReviewed)
			$('#notifications').hide();
		@endif
		function review() {
			jQuery.ajax({
			   	url: "{!! url('review') !!}",
			   	dataType: 'json',
			   	success: function(data) {
					$('#step2').addClass('line-through');
					$('#step2 button').removeClass('btn-primary').addClass('btn-purchase');
					$('#step2 button').html('THANK YOU!');
			   	},
			   	type: 'POST'
			});
			var win = window.open('https://apps.shopify.com/doubly-currency-converter?reveal_new_review=true', '_blank');
  			win.focus();
		}
	</script>

	<?php $picker = "{pickerFaceColor:'#ffffff',pickerBorder:0,pickerInsetColor:'white'}" ?>
	{!! Form::open(['url'=>'settings','id' =>'settings']) !!}
		<div class="section">
			<div class="layout-content">
				<div class="layout-content__sidebar layout-content__first">
			    	<div class="section-summary">
		           		<h1>Select Currencies</h1>
		            	<p>Select the currencies that will appear in the Currency Switcher on your website.</p>
		          	</div>
			    </div>
				<div class="layout-content__main">
					<div class="next-card">
				        <div class="next-card__section">
						    <div class="ui-form__section">
								<div class="ui-form__group">
									<div class="next-input-wrapper">
								        {!! Form::label('enabled', 'Enable App', array('class'=>'next-label')) !!}
										<div class="next-select__wrapper">{!! Form::select('enabled',$boolean_array,isset($settings['enabled']) ? $settings['enabled'] : 0,array('id' => 'enabled', 'class'=>'next-select')) !!}<svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg></div>
									</div>
									{{-- 
									<div class="next-input-wrapper">
								        {!! Form::label('default_currency', 'Default Currency', array('class'=>'next-label')) !!}
										<div class="next-select__wrapper">{!! Form::select('default_currency',$currency_list,isset($settings['default_currency']) ? $settings['default_currency'] : '',array('id' => 'default_currency', 'class'=>'next-select')) !!}<svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg></div>
									</div>
									--}}
									<div class="next-input-wrapper">
								        {!! Form::label('auto_switch', 'Auto Switch', array('class'=>'next-label')) !!}
								        <div class="next-select__wrapper">{!! Form::select('auto_switch',$boolean_array,isset($settings['auto_switch']) ? $settings['auto_switch'] : 0,array('id' => 'auto_switch', 'class'=>'next-select')) !!}<svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg></div>
										<p class="next-input__help-text">The currency will automatically change based on your customer's location.</p>	
									</div>
								</div>
							</div>
						</div>
				        <div class="next-card__section datatable-section">
							@include('pages.settings.data-table',['currencies' => $currency_items, 'currency_list' => $currency_list, 'shop_currency' => $settings['default_currency']])
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="section">
			<div class="layout-content">
				<div class="layout-content__sidebar layout-content__first">
			    	<div class="section-summary">
		           		<h1>Design</h1>
		            	<p>Choose the Theme and the colors of the Currency Switcher.</p>
		          	</div>
			    </div>
				<div class="layout-content__main">
					<div class="next-card">
				        <div class="next-card__section">
						    <div class="ui-form__section">
								<div class="ui-form__group">
									<div class="next-input-wrapper">
										{!! Form::label('currency_switcher_theme', 'Currency Switcher Theme', array('class'=>'next-label')) !!}
										<div class="next-select__wrapper">{!! Form::select('currency_switcher_theme',['layered_theme' => 'Layered theme', 'flags_theme' => 'Flags theme', 'no_theme' => 'No theme' ],isset($settings['currency_switcher_theme']) ? $settings['currency_switcher_theme'] : '',array('id' => 'currency_switcher_theme', 'class'=>'next-select')) !!}<svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg></div>
										<!-- <p class="next-input__help-text">If you choose the “No theme” option, we can customize it to fit your store.</p> -->
									</div>
									<div class="next-input-wrapper"></div>
								</div>
								<div id="layered_theme_options" class="custom_options" style="<?php if(isset($settings['currency_switcher_theme']) && ($settings['currency_switcher_theme'] != 'layered_theme')) { echo 'display:none'; } ?>">
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Background Color', array('class'=>'next-label')) !!}
											{!! Form::text('layered_background_color',isset($settings['layered_background_color']) ? $settings['layered_background_color'] : '',array('id' => 'layered_background_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper">
											{!! Form::label('', 'Text Color', array('class'=>'next-label')) !!}
											{!! Form::text('layered_text_color',isset($settings['layered_text_color']) ? $settings['layered_text_color'] : '',array('id' => 'layered_text_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
									</div>
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Hover Background Color', array('class'=>'next-label')) !!}
											{!! Form::text('layered_hover_background_color',isset($settings['layered_hover_background_color']) ? $settings['layered_hover_background_color'] : '',array('id' => 'layered_hover_background_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper">
											{!! Form::label('', 'Hover Text Color', array('class'=>'next-label')) !!}
											{!! Form::text('layered_hover_text_color',isset($settings['layered_hover_text_color']) ? $settings['layered_hover_text_color'] : '',array('id' => 'layered_hover_text_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
									</div>
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Selected Background Color', array('class'=>'next-label')) !!}
											{!! Form::text('layered_selected_background_color',isset($settings['layered_selected_background_color']) ? $settings['layered_selected_background_color'] : '',array('id' => 'layered_selected_background_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper">
											{!! Form::label('', 'Selected Text Color', array('class'=>'next-label')) !!}
											{!! Form::text('layered_selected_text_color',isset($settings['layered_selected_text_color']) ? $settings['layered_selected_text_color'] : '',array('id' => 'layered_selected_text_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
									</div>
								</div>
								<div id="circle_theme_options" class="custom_options" style="<?php if(isset($settings['currency_switcher_theme']) && ($settings['currency_switcher_theme'] != 'circle_theme') || !count($settings)) { echo 'display:none'; } ?>">
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Background Color', array('class'=>'next-label')) !!}
											{!! Form::text('circle_background_color',isset($settings['circle_background_color']) ? $settings['circle_background_color'] : '',array('id' => 'circle_background_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper">
											{!! Form::label('', 'Text Color', array('class'=>'next-label')) !!}
											{!! Form::text('circle_text_color',isset($settings['circle_text_color']) ? $settings['circle_text_color'] : '',array('id' => 'circle_text_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
									</div>
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Hover Background Color', array('class'=>'next-label')) !!}
											{!! Form::text('circle_hover_background_color',isset($settings['circle_hover_background_color']) ? $settings['circle_hover_background_color'] : '',array('id' => 'circle_hover_background_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper">
											{!! Form::label('', 'Hover Text Color', array('class'=>'next-label')) !!}
											{!! Form::text('circle_hover_text_color',isset($settings['circle_hover_text_color']) ? $settings['circle_hover_text_color'] : '',array('id' => 'circle_hover_text_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
									</div>
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Selected Background Color', array('class'=>'next-label')) !!}
											{!! Form::text('circle_selected_background_color',isset($settings['circle_selected_background_color']) ? $settings['circle_selected_background_color'] : '',array('id' => 'circle_selected_background_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper">
											{!! Form::label('', 'Selected Text Color', array('class'=>'next-label')) !!}
											{!! Form::text('circle_selected_text_color',isset($settings['circle_selected_text_color']) ? $settings['circle_selected_text_color'] : '',array('id' => 'circle_selected_text_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
									</div>
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Border Color', array('class'=>'next-label')) !!}
											{!! Form::text('circle_border_color',isset($settings['circle_border_color']) ? $settings['circle_border_color'] : '',array('id' => 'circle_border_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper"></div>
									</div>
								</div>
								<div id="flags_theme_options" class="custom_options" style="<?php if(isset($settings['currency_switcher_theme']) && ($settings['currency_switcher_theme'] != 'flags_theme') || !count($settings)) { echo 'display:none'; } ?>">
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Background Color', array('class'=>'next-label')) !!}
											{!! Form::text('flag_background_color',isset($settings['flag_background_color']) ? $settings['flag_background_color'] : '',array('id' => 'flag_background_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper">
											{!! Form::label('', 'Text Color', array('class'=>'next-label')) !!}
											{!! Form::text('flag_text_color',isset($settings['flag_text_color']) ? $settings['flag_text_color'] : '',array('id' => 'flag_text_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
									</div>
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Hover Background Color', array('class'=>'next-label')) !!}
											{!! Form::text('flag_hover_color',isset($settings['flag_hover_color']) ? $settings['flag_hover_color'] : '',array('id' => 'flag_hover_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper"></div>
									</div>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="section">
			<div class="layout-content">
				<div class="layout-content__sidebar layout-content__first">
			    	<div class="section-summary">
		           		<h1>Price Configuration</h1>
		            	<p>Configure how your Prices should display.</p>
		          	</div>
			    </div>
				<div class="layout-content__main">
					<div class="next-card">
				        <div class="next-card__section">
						    <div class="ui-form__section">
								<div class="ui-form__group">
									<div class="next-input-wrapper">
								        {!! Form::label('display_currency_name', 'Display Currency Code', array('class'=>'next-label')) !!}
								        <div class="next-select__wrapper">{!! Form::select('display_currency_name',$boolean_array,isset($settings['display_currency_name']) ? $settings['display_currency_name'] : 0,array('id' => 'display_currency_name', 'class'=>'next-select')) !!}<svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg></div>
									</div>
									<div class="next-input-wrapper">
								        {!! Form::label('remove_decimals', 'Remove Decimals', array('class'=>'next-label')) !!}
								        <div class="next-select__wrapper">{!! Form::select('remove_decimals',$boolean_array,isset($settings['remove_decimals']) ? $settings['remove_decimals'] : 0,array('id' => 'remove_decimals', 'class'=>'next-select')) !!}<svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="section">
			<div class="layout-content">
				<div class="layout-content__sidebar layout-content__first">
			    	<div class="section-summary">
		           		<h1>Extra Features</h1>
		            	<p>We've added a few cool new features for you :)</p>
		          	</div>
			    </div>
				<div class="layout-content__main">
					<div class="next-card">
						<div class="next-card__section">
						    <div class="ui-form__section">
								<div class="ui-form__group">
									<div class="next-input-wrapper">
								        {!! Form::label('check_out_curr_notify', 'Checkout Currency Notification', array('class'=>'next-label')) !!}
								        <div class="next-select__wrapper">{!! Form::select('check_out_curr_notify',$boolean_array,isset($settings['check_out_curr_notify']) ? $settings['check_out_curr_notify'] : 0,array('id' => 'check_out_curr_notify', 'class' => ' next-select')) !!}<svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg></div>
										<p class="next-input__help-text">This notification will appear on the cart page. It is meant to inform your customers that Shopify only allows them to Checkout in your shop's main currency.</p>
									</div>
									<div class="next-input-wrapper"></div>
								</div>
								<div id="check_out_curr_notify_options" class="color_picker" style="<?php if(isset($settings['check_out_curr_notify']) && (!$settings['check_out_curr_notify'])) { echo 'display:none'; } ?>">
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('message', 'Message', array('class'=>'next-label')) !!}
											{!! Form::textarea('message',isset($settings['message']) ? $settings['message'] : '',array('id' => 'message','class' => 'next-input')) !!}
										</div>
										<div class="next-input-wrapper"></div>
									</div>
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Message Color', array('class'=>'next-label')) !!}
											{!! Form::text('check_out_curr_message_color',isset($settings['check_out_curr_message_color']) ? $settings['check_out_curr_message_color'] : '',array('id' => 'check_out_curr_message_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper">
											{!! Form::label('', 'Background Color', array('class'=>'next-label')) !!}
											{!! Form::text('check_out_curr_background_color',isset($settings['check_out_curr_background_color']) ? $settings['check_out_curr_background_color'] : '',array('id' => 'check_out_curr_background_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
									</div>
								</div>
							</div>
						</div>
				        <div class="next-card__section">
						    <div class="ui-form__section">
								<div class="ui-form__group">
									<div class="next-input-wrapper">
								        {!! Form::label('show_original_price_on_hover', 'Show Original Price on Hover', array('class'=>'next-label')) !!}
								        <div class="next-select__wrapper">{!! Form::select('show_original_price_on_hover',$boolean_array,isset($settings['show_original_price_on_hover']) ? $settings['show_original_price_on_hover'] : 0,array('id' => 'show_original_price_on_hover', 'class' => ' next-select')) !!}<svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg></div>
										<p class="next-input__help-text">If your customer hovers over the converted prices he will still be able to see the price in your shop's main currency.</p>
									</div>
									<div class="next-input-wrapper"></div>
								</div>
								<div id="show_orig_price_options" class="color_picker" style="<?php if(isset($settings['show_original_price_on_hover']) && ($settings['show_original_price_on_hover'] == 0)) { echo 'display:none'; } ?>">
									<div class="ui-form__group">
										<div class="next-input-wrapper">
											{!! Form::label('', 'Price Color', array('class'=>'next-label')) !!}
											{!! Form::text('show_on_hover_price_color',isset($settings['show_on_hover_price_color']) ? $settings['show_on_hover_price_color'] : '',array('id' => 'show_on_hover_price_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
										<div class="next-input-wrapper">
											{!! Form::label('', 'Background Color', array('class'=>'next-label')) !!}
											{!! Form::text('show_on_hover_background_color',isset($settings['show_on_hover_background_color']) ? $settings['show_on_hover_background_color'] : '',array('id' => 'show_on_hover_background_color','class' => 'color '.$picker.' next-input')) !!}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	    <div style="display: none;" refresh-always="true" refresh="global-icon-symbols" id="global-icon-symbols">
	        <svg xmlns="http://www.w3.org/2000/svg">
	        	<symbol id="next-chevron-down" class="icon-symbol--loaded"><svg enable-background="new 0 0 24 24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 5.176l-9.086 9.353-8.914-9.353-2.314 2.471 11.314 11.735 11.314-11.735-2.314-2.471z"/></svg></symbol>
	        </svg>
	    </div>
		<script type="text/javascript">
			// Make Shopify Responsive
			$(document).ready(function() {
				if ($( window ).width()<685) {
					$('#currency-dt-buttons').removeClass('segmented');
					$('#currency-dt-buttons').addClass('segmented-small');
				}
				@if ($showPriceTagNotification)
					// ShopifyApp.Modal.confirm({
					//   title: "Just 1 thing to do and you're good to go!",
					//   message: "You need to add currency tags to your prices in order for the app to work.",
					//   okButton: "Show Me How",
					//   cancelButton: "Maybe Later"
					// }, function(result){
					// 	if(result){
					// 		ShopifyApp.Bar.loadingOn();
					// 		window.location.href = 'faq';
					//   	}
					// });
				@endif
			});
			$( window ).resize(function() {
				if ($( window ).width()<685) {
					$('#currency-dt-buttons').removeClass('segmented').addClass('segmented-small');
				} else {
					$('#currency-dt-buttons').addClass('segmented').removeClass('segmented-small');
				}
			});

			// Show/Hide Elements
			$(document).on('change', '#currency_switcher_theme', function(e) {
			     if(this.options[e.target.selectedIndex].text == 'Layered theme'){
			         $('.custom_options').css('display','none');
			         $('#layered_theme_options').css('display','block');
			     }else if(this.options[e.target.selectedIndex].text == 'Circle theme'){
			         $('.custom_options').css('display','none');
			         $('#circle_theme_options').css('display','block');
			     }else if(this.options[e.target.selectedIndex].text == 'Flags theme'){
			         $('.custom_options').css('display','none');
			         $('#flags_theme_options').css('display','block');
			     }else if(this.options[e.target.selectedIndex].text == 'No theme'){
			         $('.custom_options').css('display','none');
			     }
			     checkChangeTheme();
			});

			$(document).on('change','#show_original_price_on_hover',function(e){

			    if(this.options[e.target.selectedIndex].text == 'Yes'){
			         $('#show_orig_price_options').css('display','block');
			    }else{
			         $('#show_orig_price_options').css('display','none');
			    }
			});

			$(document).on('change','#check_out_curr_notify',function(e){

			    if(this.options[e.target.selectedIndex].text == 'Yes'){
			        $('#check_out_curr_notify_options').css('display','block');
			    }else{
			        $('#check_out_curr_notify_options').css('display','none');
			    }
			});

			//disable form submission when hitting enter on inputs or selects
			$('form').submit(function(){
				if (!shopifySubmit) {
					return false;
				}
			});

			/* Validate Settings Form */
			function submitForm(){		        
			    //alert($('#check_out_curr_notify').val());
			    if($('#check_out_curr_notify').val()==1 && $('#message').val()=='') {
					ShopifyApp.flashError("You forgot to fill in the Message field at the bottom.");
					shopifySubmit = false;
			        return false;
			    }
			    //if default currency doesn't exist and layered theme selected, notifty user
			    if($('#currency_switcher_theme').val()=='layered_theme' && table.rows().data().length==5) {
			    	var defaultCurrencyExists = false;
			    	$('.select_currency').each(function(){
			    		if ($(this).val()=='<?php echo substr($default_currency,-4,-1); ?>') {
			    			defaultCurrencyExists = true;
			    		}
			    	});
			    	if (!defaultCurrencyExists) {
						ShopifyApp.flashError("One of the currencies needs to be your shop's main currency.");
						shopifySubmit = false;
			        	return false;
			        }
			    }

				ShopifyApp.Bar.loadingOn();
				console.log('init_before_save');
		        beforeSave();

		        $('#settings').submit();
			}
		</script>
	{!! Form::close() !!}

@stop