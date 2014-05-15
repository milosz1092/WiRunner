function delRiv(rivId, usrPol, fromCall) {
		$.ajax({
			type: "post",
			url: "php/async/asn_Rivalry.php",
			context: document.body,
			data: { type: "rivDel", rivId: rivId, usrPol: usrPol},
			success: function(data) {
				if (data != '0') {
					if (fromCall == 'list') {
						$('#row'+data).hide(200, function(){ 
							$('#row'+data).remove();
						});
					}
					else {
						document.location.href="admin.php?subPage=rywalizacje&msg=justDelRyw";
					}
				}
			}
		});
}
