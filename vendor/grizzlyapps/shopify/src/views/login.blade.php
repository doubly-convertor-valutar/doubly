<!doctype html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Login</title>
		<link href="{{ env('APP_URL') }}/css/login.css" media="all" rel="stylesheet">
		@if ($shop!='')
		<script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
		<script type="text/javascript">
			ShopifyApp.init({
		    	apiKey: '{{ env('APP_CLIENT_ID') }}',
		    	shopOrigin: 'https://{{ $shop }}',
		    	debug: true,
	    		forceRedirect: false
			});
			ShopifyApp.ready(function(){
			    ShopifyApp.Bar.initialize({
		      	  icon: "{{ env('APP_URL') }}/images/doubly-icon.png",
			      title: 'Login',
				  buttons: { 
			        primary: { label: "Contact Us", href: "mailto:", loading: false }
			      }
			    });
			});
		</script>
		@endif
		<style type="text/css">
			body { line-height: 1px; }
			.dialog-input { padding:24px 47px !important; }
			.dialog-input-container, #login { width: 400px; }
			.login-container { max-width: 400px; }
		</style>
	</head>
	<body>
		@if ($shop!='' && count($errors) > 0)
        	<script type="text/javascript">
        		@foreach ($errors->all() as $error)
					ShopifyApp.flashError("{{ $error }}");
				@endforeach
			</script>
		@endif
		<div id="container">
			<div class="login-form">
			  	<h1 class="dialog-heading">{{ env('APP_NAME') }}</h1>
			  	<h2 class="dialog-subheading">Log in to manage the app.</h2>
			  	<h2 class="dialog-subheading">Third Party Cookies need to be enabled in your browser in order to access the app.</h2>

				<form class="form-horizontal" role="form" method="POST" action="{{ url('/postLogin') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="clearfix">
				      <div class="login-container">
				        <div class="lform dialog-form" id="sign-in-form">

				          <input type="hidden" id="redirect" value="" name="redirect">

				          <div id="login">
				            <div class="dialog-input-container clearfix">
								<input type="text" class="dialog-input" id="login-input" name="shop" value="{{ old('shop') }}" placeholder="Enter your store...">
				              <label class="visuallyhidden" for="login-input">Enter your store...</label>
				              <!-- <i class="ico dialog-ico ico-email"></i> -->
				            </div>
				          </div> <!-- /#login -->
				          <input type="submit" class="dialog-btn" value="Log in" name="commit">
				        </div> <!-- /#sign-in-form -->
				      </div>
				    </div>
				</form>
			</div>
		</div>
	</body>
</html>