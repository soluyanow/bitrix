formOpen = false;

function showForm(formName) {
	if (!formOpen) {
		elem 				= document.getElementById(formName);
		elem.style.display 	= "block";
		formOpen 			= true;
	} else {
		elem 				= document.getElementById(formName);
		elem.style.display 	= "none";
		formOpen 			= false;
	}	
}

$(function() {
	$("#subscribeForm").submit(function() {				
		$.ajax({
				url: '/bitrix/components/art/price.subscribe/templates/subscribe.form/subscribe.php'
				, type: 'POST'
				, data: {'email': $("#emailInput").val(), 
						'username': $("#usernameInput").val(), 
						'ibid': $("#ibidInput").val(), 
						'productid': $("#productidInput").val(), 
						'productibid': $("#productibidInput").val() 
					}								
				, success: function(data) {					
					$("#formResult").html('<p> Пользователь ' + $("#usernameInput").val() + ' успешно подписан на товар </p>');					
			}
		});
				   
		return false;
	});
});


