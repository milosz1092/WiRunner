function delMsg(msqId, usrId, fromCall) {
		$.ajax({
			type: "post",
			url: "php/async/asn_Poster.php",
			context: document.body,
			data: { type: "msgDel", magId: msqId, usrId: usrId},
			success: function(data) {
				if (data != '0') {
					if (fromCall == "header") {
						$('#row'+data).hide(200, function(){ 
							$('#row'+data).remove();
						});
					}
					else {
						document.location.href="konto.php?subPage=poczta&msg=justDelMsg";
					}
				}
			}
		});
}
