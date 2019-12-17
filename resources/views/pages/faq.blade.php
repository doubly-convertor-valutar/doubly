@extends('app')

@section('content')
	<div class="page page--home-classic page--single-column">
		<div class="page__content">
			<div class="ui-feed">
		      	<div class="ui-feed__section">
		      		<div class="ui-feed__item ui-feed__item--heading">
			            <div class="ui-feed__marker">
			              	<div class="frame frame--32 frame--fill frame--circle frame--alternate-border frame--success">
					            <svg class="next-icon next-icon--16"><use xlink:href="#next-website"></use></svg>
			              	</div>
			            </div>
			            <h2 class="next-heading next-heading--no-margin next-heading--small next-heading--subdued next-heading--semi-bold"><?php if ($showPriceTagNotification): ?>JUST 1 THING TO DO AND YOU'RE GOOD TO GO!<?php else: ?>MONEY FORMATS ARE SETUP CORRECTLY<?php endif; ?></h2>
		          	</div>
			      	<div class="ui-feed__item ui-feed__item--card">
				      	<div class="overlaid next-card ">
				            <div class="feed-card next-card next-card--onboarding-tasks overlaid">
								<div class="next-grid next-grid--no-padding">
									<div class="next-grid__cell">
									    <div class="next-card__section">
									        <h3 class="next-heading">
									        	<svg class="next-icon next-icon--size-20 next-icon--header"><use xlink:href="#next-settings"/></svg>
									        	Edit your money formats
									      	</h3>
									      	<div class="next-grid next-grid--vertically-centered next-grid--inner-grid next-grid--compact next-grid--no-outside-padding st">
									    		<div class="next-grid__cell next-grid__cell--no-flex">
									        		<a class="btn" href="javascript:void(0);" id="moneyFormatsFaqBtn" onclick="openFaq(this)">
									          			Show me
													</a>    
												</div>
											</div>
									    </div>
									</div>
								  	<div class="next-grid__cell next-grid__cell--no-flex next-grid__cell--vertically-centered">
								    	<div class="overlaid overlaid--inline">
								      		<!-- <img class="next-card__image" src="//cdn.shopify.com/s/assets/admin/home/onboarding-tasks/onboarding-website-104d76e112cb73b83a8032c9674fe09a38a43175f70b81dfd6edb4f52fee86c3.png" /> -->
								      		{!! HTML::image('images/settings-icon2.png', '', array('height'=>'60px', 'style'=>'margin-right:10px;')) !!}
								    	</div>
								  	</div>
								</div>
								<div class="next-grid next-grid--no-padding open-faq">
									<div class="next-grid__cell">
									    <div class="next-card__section">
											<div class="next-grid next-grid--vertically-centered next-grid--inner-grid next-grid--compact next-grid--no-outside-padding">
									    		<div class="next-grid__cell next-grid__cell--no-flex">
													If you want we can do all this for you, just hit us up with an email at <a href="mailto:"></a><br/><br/>
													<div class="step">
												  		<b>Step 1</b><br/> From your Shopify admin, click <b>Settings</b>:<br/>
												  		{!! HTML::image('images/settings.png') !!}
												  	</div>
												  	<div class="step">
												  		<b>Step 2</b><br/> Scroll down to the <b>Standards and formats</b> section, where you'll see your <b>Currency</b> settings:<br/>
												  		{!! HTML::image('images/currency-setting.png') !!}
												  	</div>
												  	<div class="step">
												  		<b>Step 3</b><br/> Click <b>Change formatting</b>.
												  	</div>
												  	<div class="step">
												  		<b>Step 4</b><br/> Find the <b>HTML with currency</b> and the <b>HTML without currency</b> formats:<br/>
												  		{!! HTML::image('images/currency-formatting'.$hasComma.'-dialog.png') !!}
												  	</div>
												  	<div class="step">
												  		<b>Step 5</b><br/> In both text fields, copy and paste <pre style="background #f8f8f8; border:1px solid #ddd; display: inline-block; padding: 0px 3px;">&lt;span class=money&gt;</pre> in front of the formatting text.
												  	</div>
												  	<div class="step">
												  		<b>Step 6</b><br/> In both text fields, copy and paste <pre style="background #f8f8f8; border:1px solid #ddd; display: inline-block; padding: 0px 3px;">&lt;/span&gt;</pre> after the formatting text:<br/>
												  		{!! HTML::image('images/currency-formatting'.$hasComma.'-dialog2.png') !!}
												  	</div>
												  	<div class="step">
												  		<b>Step 7</b><br/> Click <b>Save</b>.
												  	</div>
												  	<div class="step">
												  		<b>Step 8</b><br/> Configure the app however you want from the <a href="{!! url('settings') !!}" onclick="ShopifyApp.Bar.loadingOn();">Settings</a> page.
												  	</div>
									        	</div>
									        </div>
									    </div>
									</div>
								</div>
							</div>
						</div>    
			      	</div>  
		      	</div>  
			@if (!$adapted || 1!=2)
		      	<div class="ui-feed__section">
		      		<div class="ui-feed__item ui-feed__item--heading">
			            <div class="ui-feed__marker">
			              	<div class="frame frame--32 frame--fill frame--circle frame--alternate-border frame--success">
					            <svg role="img" class="next-icon next-icon--16"><use xlink:href="#next-website"></use></svg>
			              	</div>
			            </div>
			            <h2 class="next-heading next-heading--no-margin next-heading--small next-heading--subdued next-heading--semi-bold">HOW TO</h2>
		          	</div>
			      	<div class="ui-feed__item ui-feed__item--card">
				      	<div class="overlaid next-card ">
				            <div class="feed-card next-card next-card--onboarding-tasks overlaid">
								<div class="next-grid next-grid--no-padding">
									<div class="next-grid__cell">
									    <div class="next-card__section">
									        <h3 class="next-heading">
									        	<svg class="next-icon next-icon--size-20 next-icon--header"><use xlink:href="#next-themes"/></svg>
									        	How to move the Currency Switcher
									      	</h3>
									      	<div class="next-grid next-grid--vertically-centered next-grid--inner-grid next-grid--compact next-grid--no-outside-padding st">
									    		<div class="next-grid__cell next-grid__cell--no-flex">
									        		<a class="btn" href="javascript:void(0);" onclick="openFaq(this)">
									          			Show me
													</a>    
												</div>
											</div>
									    </div>
									</div>
								  	<div class="next-grid__cell next-grid__cell--no-flex next-grid__cell--vertically-centered">
								    	<div class="overlaid overlaid--inline">
								      		{!! HTML::image('images/theme-card.png', '', array('width'=>'80px')) !!}
								    	</div>
								  	</div>
								</div>
								<div class="next-grid next-grid--no-padding open-faq">
									<div class="next-grid__cell">
									    <div class="next-card__section">
											<div class="next-grid next-grid--vertically-centered next-grid--inner-grid next-grid--compact next-grid--no-outside-padding">
									    		<div class="next-grid__cell next-grid__cell--no-flex">
									    			If you want we can do all this for you, just hit us up with an email at <a href="mailto:"></a><br/><br/>
													<div class="step">
												  		<b>Step 1</b><br/> From your Shopify admin, click <b>Online Store</b>, then click <b>Themes</b> (or press <pre style="background #f8f8f8; border:1px solid #ddd; display: inline-block; padding: 0px 3px;">G</pre> <pre style="background #f8f8f8; border:1px solid #ddd; display: inline-block; padding: 0px 3px;">W</pre> <pre style="background #f8f8f8; border:1px solid #ddd; display: inline-block; padding: 0px 3px;">T</pre>):<br/>
												  		{!! HTML::image('images/click-Online-Store-then-Themes.gif') !!}
												  	</div>
												  	<div class="step">
												  		<b>Step 2</b><br/> Find the theme you want to edit, click the <b>…</b> button, then click <b>Edit HTML/CSS</b>.<br/>
												  		{!! HTML::image('images/click-Edit-HTML-CSS-button.png') !!}
												  	</div>
												  	<div class="step">
												  		<b>Step 3</b><br/> Click <b>Layout</b> and then the <b>theme.liquid</b> file.
												  	</div>
												  	<div class="step">
												  		<b>Step 4</b><br/> Add <pre style="background #f8f8f8; border:1px solid #ddd; display: inline-block; padding: 0px 3px;">&lt;div class="doubly-wrapper"&gt;&lt;/div&gt;</pre> inside your &lt;body&gt; tag.<br/>
												  		{!! HTML::image('images/edit-theme-liquid.png') !!}
												  	</div>
												  	<div class="step">
												  		<b>Step 5</b><br/> Click <b>Save</b>.
												  	</div>
									        	</div>
									        </div>
									    </div>
									</div>
								</div>
							</div>    
						</div>    
					</div>   
			      	<div class="ui-feed__item ui-feed__item--card">
				      	<div class="overlaid next-card "> 
				            <div class="feed-card next-card next-card--onboarding-tasks overlaid">
								<div class="next-grid next-grid--no-padding">
									<div class="next-grid__cell">
									    <div class="next-card__section">
									        <h3 class="next-heading">
									        	<svg class="next-icon next-icon--size-20 next-icon--header"><use xlink:href="#next-themes"/></svg>
									        	How to add Checkout Currency Notification
									      	</h3>
									      	<div class="next-grid next-grid--vertically-centered next-grid--inner-grid next-grid--compact next-grid--no-outside-padding st">
									    		<div class="next-grid__cell next-grid__cell--no-flex">
									        		<a class="btn" href="javascript:void(0);" onclick="openFaq(this)">
									          			Show me
													</a>    
												</div>
											</div>
									    </div>
									</div>
								  	<div class="next-grid__cell next-grid__cell--no-flex next-grid__cell--vertically-centered">
								    	<div class="overlaid overlaid--inline">
								      		{!! HTML::image('images/theme-card.png', '', array('width'=>'80px')) !!}
								    	</div>
								  	</div>
								</div>
								<div class="next-grid next-grid--no-padding open-faq">
									<div class="next-grid__cell">
									    <div class="next-card__section">
											<div class="next-grid next-grid--vertically-centered next-grid--inner-grid next-grid--compact next-grid--no-outside-padding">
									    		<div class="next-grid__cell next-grid__cell--no-flex">
									    			If you want we can do all this for you, just hit us up with an email at <a href="mailto:"></a><br/><br/>
													This notification will appear on the cart page. It is meant to inform your customers that Shopify only allows them to Checkout in your shop's main currency.<br/><br/>
													<div class="step">
												  		<b>Step 1</b><br/> From your Shopify admin, click <b>Online Store</b>, then click <b>Themes</b> (or press <pre style="background #f8f8f8; border:1px solid #ddd; display: inline-block; padding: 0px 3px;">G</pre> <pre style="background #f8f8f8; border:1px solid #ddd; display: inline-block; padding: 0px 3px;">W</pre> <pre style="background #f8f8f8; border:1px solid #ddd; display: inline-block; padding: 0px 3px;">T</pre>):<br/>
												  		{!! HTML::image('images/click-Online-Store-then-Themes.gif') !!}
												  	</div>
												  	<div class="step">
												  		<b>Step 2</b><br/> Find the theme you want to edit, click the <b>…</b> button, then click <b>Edit HTML/CSS</b>.<br/>
												  		{!! HTML::image('images/click-Edit-HTML-CSS-button.png') !!}
												  	</div>
												  	<div class="step">
												  		<b>Step 3</b><br/> Click <b>Templates</b> and then the <b>cart.liquid</b> file (the file name might be different, depending on the theme you use).
												  	</div>
												  	<div class="step">
												  		<b>Step 4</b><br/> Add <pre style="background #f8f8f8; border:1px solid #ddd; display: inline-block; padding: 0px 3px;">&lt;div class="doubly-message"&gt;&lt;/div&gt;</pre> where you want the message to appear.<br/>
												  		{!! HTML::image('images/cart.png') !!}
												  	</div>
												  	<div class="step">
												  		<b>Step 5</b><br/> Click <b>Save</b>.
												  	</div>
												  	<div class="step">
												  		<b>Step 6</b><br/> Enable the <b>Checkout Currency Notification</b> from the <a href="{!! url('settings') !!}" onclick="ShopifyApp.Bar.loadingOn();">Settings</a> page at the bottom.
												  	</div>
									        	</div>
									        </div>
									    </div>
									</div>
								</div>
							</div>        
						</div>        
					</div>        
		        </div>  
		    @endif
	      	</div>  
  		</div>
  	</div>
	<script type="text/javascript">
		function openFaq(element) {
			$('.open-faq').hide();
			if ($(element).closest('.feed-card').find('.btn').text()!='Hide') {
				$('.btn').text('Show me');
				$(element).closest('.feed-card').find('.btn').text('Hide');
				$(element).closest('.feed-card').find('.open-faq').show();
			} else {
				$('.btn').text('Show me');
			}
			$('.btn').blur();
		}
		$(document).ready(function(){
			$('#moneyFormatsFaqBtn').click();
		});
	</script>
    <div style="display: none;" refresh-always="true" refresh="global-icon-symbols" id="global-icon-symbols">
        <svg xmlns="http://www.w3.org/2000/svg">
        	<symbol id="next-themes" class="icon-symbol--loaded"><svg enable-background="new 0 0 24 24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M23.5 7.7C22.4 3 17.5.1 12 .1 5.4.1 0 5.4 0 12c0 6.6 5.4 12 12 12 9.2 0 8.5-4.7 5.5-6.5-1.7-1.1-2.6-3.6-.9-5.4 3.2-3.4 8.3 1.9 6.9-4.4zM4.3 17.1c-.3 0-.4-.3-.2-.5 1.9-2.3.4-4.5 3.3-5.4.2-.1.4 0 .5.1l1.9 1.9c.1.1.2.3.1.4-.4 3.2-4 3.5-5.6 3.5zM16.5 6.5l-4.9 4.9c-.2.2-.5.2-.7 0l-1.3-1.3c-.2-.2-.2-.5 0-.7l4.9-4.9c1.3-1.3 2.7-1.9 3.3-1.3.5.6 0 2-1.3 3.3z"/></svg></symbol>
            <symbol id="next-website" class="icon-symbol--loaded"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M9.4 2c-.2 0-.3.3-.2.4l1.4 1.4-3.8 3.9c-.2.2-.2.6 0 .8l.8.8c.2.2.6.2.8 0l3.8-3.8 1.4 1.4c.2.2.4 0 .4-.2v-4.2c0-.3-.2-.5-.5-.5h-4.1zm.6 0h-6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2v-6 2h-2v3c0 .6-.5 1-1 1h-6c-.6 0-1-.5-1-1v-6c0-.6.5-1 1-1h3v-2h2z"></path></svg></symbol>
            <symbol id="next-settings" class="icon-symbol--loaded"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" enable-background="new 0 0 24 24"><path d="M23.6 10.1l-2.7-.5c-.2 0-.3-.2-.4-.3-.2-.5-.3-1-.6-1.4-.1-.2-.1-.4 0-.5l1.5-2.3c.1-.2.1-.5-.1-.6l-1.9-1.9c-.2-.2-.4-.2-.6-.1L16.6 4c-.2.1-.3.1-.5 0-.4-.2-.9-.4-1.4-.6-.2-.1-.3-.2-.3-.4L13.9.3c0-.2-.3-.4-.5-.4h-2.7c-.2 0-.4.2-.5.4l-.6 2.8c0 .2-.2.3-.3.4-.5.1-1 .3-1.4.5-.2.1-.4.1-.5 0L5.1 2.5c-.2-.1-.5-.1-.6.1L2.6 4.5c-.2.1-.2.4-.1.6L4 7.4c.1.2.1.3 0 .5-.2.4-.4.9-.6 1.4 0 .1-.2.3-.3.3l-2.7.5c-.2 0-.4.3-.4.5v2.7c0 .2.2.4.4.5l2.7.5c.2 0 .3.2.4.3.2.5.3 1 .6 1.4.1.2.1.4 0 .5l-1.5 2.3c-.1.2-.1.5.1.6l1.9 1.9c.2.2.4.2.6.1L7.4 20c.2-.1.3-.1.5 0 .4.2.9.4 1.4.6.2.1.3.2.3.4l.5 2.7c0 .2.3.4.5.4h2.7c.2 0 .4-.2.5-.4l.5-2.7c0-.2.2-.3.3-.4.5-.2 1-.3 1.4-.6.2-.1.4-.1.5 0l2.3 1.5c.2.1.5.1.6-.1l1.9-1.9c.2-.2.2-.4.1-.6L20 16.6c-.1-.2-.1-.3 0-.5.2-.4.4-.9.6-1.4.1-.2.2-.3.4-.3l2.7-.5c.2 0 .4-.3.4-.5v-2.7c-.1-.3-.3-.5-.5-.6zM16 12c0 2.2-1.8 4-4 4s-4-1.8-4-4 1.8-4 4-4 4 1.8 4 4z"></path></svg></symbol>
        </svg>
    </div>
@stop