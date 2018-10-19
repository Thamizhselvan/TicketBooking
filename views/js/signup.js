function createAccount(){
	
	var dataString = $("#registerForm").serialize();
	$.ajax( {
        type: 'POST',
        url: "../controllers/LoginController.php",
        data: dataString,
        success: function(res) {
        	var obj = jQuery.parseJSON(res);
        	if(obj.status == true){
	    		alert(obj.successMsg);
	    	}else{
	    		alert(obj.errorMsg);
	    	}
        }
    });
}
