$(document).ready(function() {
	/** ******************************
	 * Load More Confessions
	 ****************************** **/
	totalFessup = $(".confessbox .confHide").size();
    showTotal = 10;
    $('.confessbox .confHide:lt('+showTotal+')').show();
    $('#loadMore').click(function (e) {
        e.preventDefault();
        showTotal = (showTotal + 5 <= totalFessup) ? showTotal + 5 : totalFessup;
        $('.confessbox .confHide:lt('+showTotal+')').slideDown(1000, function() {
            $(this).show();
        });
		if (showTotal == totalFessup) {
			$('#loadMore').hide();
		}
    });
});