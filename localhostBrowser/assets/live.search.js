$(document).ready(function () {

	var delay = (function () {
		var timer = 0;
		return function (callback, ms) {
			clearTimeout(timer);
			timer = setTimeout(callback, ms);
		};
	})();

	var searchInput = $("#search");

	searchInput.keyup(
			function () {
				delay(function () {	
					var keyword = searchInput.val();
					var URL = encodeURI("index.php?search=" + keyword);
					$.ajax({
						url: URL,
						cache: false,
						type: "GET",
						success: function (response) {							
							$("#result").html(response);
						}
					});
					
				}, 200);
			}
	);
});