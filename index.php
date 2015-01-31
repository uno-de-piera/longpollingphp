<!DOCTYPE html>
<html>
<head>
	<title>Long Polling con PHP y jQuery</title>
	<meta charset="utf-8">
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		$("button").on("click", function()
		{
			longPolling();
		});
	});

	function longPolling(timestamp)
	{
		var polling = {};
		if(typeof timestamp != "undefined")
		{
			polling.timestamp = timestamp;
		}

		$.ajax({
			type: "POST",
			url: "server.php",
			data: polling,
			success: function(res)
			{
				longPolling(res.timestamp);
			}
		})
	}
	</script>
</head>
<body>
	<button>Lanzar longpolling</button>
</body>
</html>