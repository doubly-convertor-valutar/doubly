<!doctype html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Declined Charge</title>
		<script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
		<script src="//code.jquery.com/jquery.js"></script>
		<link href="{{ env('APP_URL') }}/css/login.css" media="all" rel="stylesheet">
		<style type="text/css">
			.dialog-subheading { font-size:15px; max-width: 500px; margin:0 auto; }
		</style>
		<script type="text/javascript">
			ShopifyApp.init({
		    	apiKey: '{{ env('APP_CLIENT_ID') }}',
		    	shopOrigin: 'https://{{ $shop }}',
		    	debug: true
	    		// forceRedrect: false
			});
			ShopifyApp.ready(function(){
			    ShopifyApp.Bar.initialize({
		      	  icon: "{{ env('APP_URL') }}/images/doubly-icon.png",
			      title: 'Declined Charge'
			    });
			});
		</script>
	</head>
	<body>	
		<div id="container">
			<div class="login-form">
			  	<h1 class="dialog-heading">You have declined the charge.</h1>
			  	<h2 class="dialog-subheading">You can only use the application and benefit from the Free Trial, if you accept the charge. To retry please <a href="{{ $returnUrl }}">click here</a>.<br/><br/> If you have any issues or questions please contact us at <a href="mailto:"></a>.</h2>
			</div>
		</div>
	</body>
</html>