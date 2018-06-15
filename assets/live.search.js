$(document).ready(function () {

	const delay = (function () {
		var timer = 0;
		return function (callback, ms) {
			clearTimeout(timer);
			timer = setTimeout(callback, ms);
		};
	})();

	const searchInput = $("#search");

	searchInput.keyup(
		function () {
			delay(function () {
				const keyword = searchInput.val();
				const URL = encodeURI("index.php?search=" + keyword);
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