$().ready(function() {
	$("#log_email").keyup(function() {
		if ($(this).val().length > 2)
			$("#passReset").show();
		else
			$("#passReset").hide();
			
		var link = 'login.php?action=passReset&email='+$("#log_email").val();

		$("#resetLink").attr("href", link);
	});
});
