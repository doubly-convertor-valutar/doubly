<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<!-- SCRIPTS -->
	<script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
	<script src="{{ env('APP_URL') }}/js/jquery.min.js"></script>		
	{!! HTML::script('js/color-picker/jscolor.js') !!}
	<script type="text/javascript">
		var shopifySubmit = false;
		ShopifyApp.init({
	    	apiKey: '{{ env('APP_CLIENT_ID') }}',
	    	shopOrigin: 'https://{{ $shop }}',
	    	debug: true
	    	// forceRedirect: false
		});
		ShopifyApp.ready(function(){
		    ShopifyApp.Bar.initialize({
		      icon: "{{ env('APP_URL') }}/images/doubly-icon.png",
		      title: '{{ $title }}',
		      @if ($plan!=0)
		      buttons: { 
		      	@if (!in_array(Request::segment(1),array('faq')))
		        primary: {
		          label: 'Save', 
		          callback: function(){ 
		          	shopifySubmit = true;
		            submitForm();
		          }
		        },
		        @endif
		        secondary: [
			      { label: "Settings", href: "{{ env('APP_URL') }}/settings" },
                  { label: "More",
			        type: "dropdown",
			        links: [
		      				 { label: "Currency By Country", href: "{{ env('APP_URL') }}/currency-by-country" },
			                 { label: "FAQ", href: "{{ env('APP_URL') }}/faq" }
			               ]
			      }
			    ]
		      }
		      @endif
		    });
		});
	</script>
	<!-- Make Shopify Responsive -->
	<script type="text/javascript">
		@if (Request::segment(1)!='faq')
			$(document).ready(function() {
				$('.layout-content').removeClass('layout-content--single-column');
				$('.next-grid').removeClass('next-grid--single-column');
				if ($( window ).width()<897) {
					$('.next-grid').addClass('next-grid--single-column');
				}
				if ($( window ).width()<881) {
					$('.layout-content').addClass('layout-content--single-column');
				}
			});
			$( window ).resize(function() {
				$('.layout-content').removeClass('layout-content--single-column');
				$('.next-grid').removeClass('next-grid--single-column');
				if ($( window ).width()<897) {
					$('.next-grid').addClass('next-grid--single-column');
				}
				if ($( window ).width()<881) {
					$('.layout-content').addClass('layout-content--single-column');
				}
			});
		@else
			$(document).ready(function() {
				$('.page--home-classic').removeClass('page--condense-spacing');
				if ($( window ).width()<510) {
					$('.page--home-classic').addClass('page--condense-spacing');
				}
			});
			$( window ).resize(function() {
				$('.page--home-classic').removeClass('page--condense-spacing');
				if ($( window ).width()<510) {
					$('.page--home-classic').addClass('page--condense-spacing');
				}
			});
		@endif
	</script>
	@yield('scripts')

	<!-- CSS -->
	<link href="{{ env('APP_URL') }}/css/shopify2.css" media="all" rel="stylesheet">
	<link href="{{ env('APP_URL') }}/css/app3.css" media="all" rel="stylesheet">
	@yield('styles')
</head>
<body class="{{ Request::segment(1) }} next-ui">
	<div class="wrapper" id="wrapper">
        <section>
			@include('flash::message')
			@yield('content')
		</section>
	</div>
</body>
</html>