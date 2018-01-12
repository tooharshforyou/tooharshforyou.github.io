$(document).ready(function() {
    $('#postDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	
	$('#commentData').dataTable({
		"pageLength": 10,
		"pagingType": "simple_numbers",
		"order": [[2,'desc']]
	});
});