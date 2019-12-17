<!doctype html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>{{ $title }}</title>
	</head>
	<body>
		{{ $content }}
	</body>
</html>
<!-- <script type="text/javascript">
	ShopifyApp.ready(function(){
		// var shopifyQL = "SHOW total_sessions BY utm_campaign_name FROM visits SINCE -2w";
		// var shopifyQL = "SHOW total_sessions FROM visits WHERE utm_campaign_name==\"dailydeal\" SINCE -2w";
		var shopifyQL = "SHOW count() FROM orders WHERE product_id==7513594 SINCE -2w";
		var renderData = function(response) {
		  // dump(response.result.columns[1]);
			dump(response.result.data);
		};
		var handleError = function(response) {
		  // handle missing API errors here (missing scopes, back shopifyql, etc...)
		};
		ShopifyApp.Analytics.fetch({
		  query: shopifyQL,
		  success: renderData,
		  error: handleError
		});
		});
		function dump(obj) {
	    var out = '';
	    for (var i in obj) {
	        out += i + ": " + obj[i] + "\n";
	    }

	    alert(out);

	    // or, if you wanted to avoid alerts...

	    var pre = document.createElement('pre');
	    pre.innerHTML = out;
	    document.body.appendChild(pre)
	}
</script> -->