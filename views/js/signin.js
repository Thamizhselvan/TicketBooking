function dologin(){
	var dataString =  $("#loginForm").serialize();
	if($('#login_uname').val()!='' && $('#login_password').val()!=''){
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
}

function userProfile()(){
	var dataString =  $("#userprofileForm").serialize();
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