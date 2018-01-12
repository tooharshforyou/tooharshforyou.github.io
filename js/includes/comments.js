$(document).ready(function() {
	$('#comData').dataTable({
		"pageLength": 10,
		"pagingType": "simple_numbers",
		"order": [[2,'desc']]
	});
});