/**
 * 
 */
$( document ).ready(function() {
   var menuArr = [ "menu1", "menu2", "menu3", "menu4", "menu5", "menu6", "menu7", "menu8", "menu9" ];
	$.ajax({
		type:"POST",
		url:"../controllers/CommonController.php",
		data:"action=loadMenu",
		success:function(res){
			var obj = jQuery.parseJSON(res);
	       	var menu=obj.menu_ids;
	       	if(menu != "All"){
	       		var numbersArray = menu.split(',');
		       	for(var i=0; i<menuArr.length; i++){
		       		var menuName = menuArr[i];
		       		if(jQuery.inArray(menuName, numbersArray) == -1){
		       			$('#'+menuName+'').css('display','none');
		       		} 
		       }
	       	}
		}
	});
});
function loadDepartmentDetails(){
	var dcode = $('#dcode').val();
	if(dcode == "new"){
		$('#option').val("save");
		$('#dname').val('');
	}else{
		$('#option').val("update");
		$.ajax({
			type:"POST",
			url:"../controllers/CommonController.php",
			data:"dcode="+dcode+"&action=loadDepartmentDetails",
			success:function(res){
				var obj = jQuery.parseJSON(res);
	        	$('#dname').val(obj.dname);
			}
		});
	}
}

$("#departmentForm").submit(function( event ) {
	  event.preventDefault();
	  var dataString = $("#departmentForm").serialize();
	  if($('#dname').val() != ""){
		  $.ajax( {
		        type: 'POST',
		        url: "../controllers/CommonController.php",
		        data: dataString,
		        success: function(res) {
		        	var obj = jQuery.parseJSON(res);
			    	if(obj.status == true){
			    		$('#success').css('display','block');
			    		$('#successMsg').html(obj.successMsg);
			    		setTimeout(function() {
			    		    location.reload();
			    		}, 1000);
			    	}else{
			    		$('#warning').css('display','block');
			    		$('#errMsg').html(obj.errorMsg);
			    		$('#warning').delay(2000).fadeOut('slow');
			    	}
		        }
		    });  
	  }
		
});

$("#courseForm1").click(function(event){
	var dataString = $("#courseForm").serialize();
	if(!$("#courseForm")[0].checkValidity()){
		$("#courseForm").submit();
    }else{
    	$.ajax({
	        type: 'POST',
	        url: "../controllers/CommonController.php",
	        data: dataString,
	        success: function(res) {
	        	var obj = jQuery.parseJSON(res);
		    	if(obj.status == true){
		    		//alert(obj.successMsg);
		    		$('#success').css('display','block');
		    		$('#successMsg').html(obj.successMsg);
		    		$('#success').delay(2000).fadeOut('slow');
		    		$("#courseForm").trigger('reset');
		    	}else{
		    		//alert(obj.errorMsg);
		    		$('#danger').css('display','block');
		    		$('#errorMsg').html(obj.errorMsg);
		    		$('#danger').delay(3000).fadeOut('slow');
		    	}
	        }
	    });
    }
})
function loadCourseByDepartment(){
	if($("#dcode").val()!=0){
		$.ajax({
			type:"POST",
			url:"../controllers/GeneralSettings.php",
			data:"dcode="+$("#dcode").val()+"&action=getCourseByDepartment",
			success:function(res){
				var obj = jQuery.parseJSON(res);
				/*$('#ccode').empty();
				$('<option>').val('0').text('Choose..').appendTo('#ccode');*/
				$('#ccode').children('option:not(:first)').remove();
				$.each( obj, function( key, value ) {
					  var opt = $('<option />'); 
						opt.val(key);
						opt.text(value);
						$('#ccode').append(opt);
				});
			}
		});
	}
}
function loadCaste(){
	$.ajax({
		type:"POST",
		url:"../controllers/CommonController.php",
		data:"category="+$("#category").val()+"&action=loadCasteByCategory",
		success:function(res){
			var obj = jQuery.parseJSON(res);
			$('#casteId').empty();
			$('<option>').val('new').text('New..').appendTo('#casteId');
			$.each( obj, function( key, value ) {
				  var opt = $('<option />'); 
					opt.val(key);
					opt.text(value);
					$('#casteId').append(opt);
			});
		}
	});
}
function loadCourseDetails(){
	if($('#ccode').val()!='new'){
		$.ajax({
			type:"POST",
			url:"../controllers/CommonController.php",
			data:"dcode="+$('#dcode').val()+"&ccode="+$('#ccode').val()+"&action=loadCourseDetails",
			success:function(res){
				var obj = jQuery.parseJSON(res);
	        	$('#cname').val(obj.cname);
	        	$('#duration').val(obj.duration);
	        	$('#sem').val(obj.sem);
	        	$('#startYr').val(obj.start_year);
	        	$('#option').val("update");
			}
		});
	}else{
		$('#cname').focus();
		$('#cname').val('');
    	$('#duration').val('');
    	$('#sem').val('');
    	$('#startYr').val('');
    	$('#option').val("save");
	}
	
}
function loadCategory(){
	$.ajax({
		type:"POST",
		url:"../controllers/CommonController.php",
		data:"categoryId="+$('#categoryId').val()+"&action=loadCategory",
		success:function(res){
			var obj = jQuery.parseJSON(res);
        	$('#categoryName').val(obj.category_name);
        	$('#option').val("update");
		}
	});
}
function loadCasteDetails(){
	$.ajax({
		type:"POST",
		url:"../controllers/CommonController.php",
		data:"categoryId="+$('#category').val()+"&casteId="+$('#casteId').val()+"&action=loadCasteDetails",
		success:function(res){
			var obj = jQuery.parseJSON(res);
        	$('#casteName').val(obj.caste_name);
        	$('#option2').val("update");
		}
	});
}
function categoryForm(){
	if($('#categoryName').val() ==""){
		$('#danger').css('display','block');
		$('#errorMsg').html("Please enter Category Name");
		$('#danger').delay(3000).fadeOut('slow');
		$('#categoryName').focus();
	}else{
		var dataString = $("#categoryForm").serialize();
		$.ajax( {
	        type: 'POST',
	        url: "../controllers/CommonController.php",
	        data: dataString,
	        success: function(res) {
	        	var obj = jQuery.parseJSON(res);
	        	if(obj.status == true){
		    		//alert(obj.successMsg);
		    		$('#success').css('display','block');
		    		$('#successMsg').html(obj.successMsg);
		    		$('#success').delay(2000).fadeOut('slow');
		    		$("#categoryForm").trigger('reset');
		    	}else{
		    		//alert(obj.errorMsg);
		    		$('#warning').css('display','block');
		    		$('#errMsg').html(obj.errorMsg);
		    		$('#warning').delay(2000).fadeOut('slow');
		    	}
	        }
	    });
	}	
}
function casteForm(){
	if($('#casteName').val() ==""){
		$('#danger1').css('display','block');
		$('#errorMsg1').html("Please enter Caste Name");
		$('#danger1').delay(3000).fadeOut('slow');
		$('#casteName').focus();
	}else{
		var dataString = $("#casteForm").serialize();
		$.ajax( {
	        type: 'POST',
	        url: "../controllers/CommonController.php",
	        data: dataString,
	        success: function(res) {
	        	var obj = jQuery.parseJSON(res);
	        	if(obj.status == true){
		    		$('#success1').css('display','block');
		    		$('#successMsg1').html(obj.successMsg);
		    		$('#success1').delay(2000).fadeOut('slow');
		    		$("#casteForm").trigger('reset');
		    	}else{
		    		$('#warning1').css('display','block');
		    		$('#errMsg1').html(obj.errorMsg);
		    		$('#warning1').delay(2000).fadeOut('slow');
		    	}
	        }
	    });
	}
}
function smsForm(){
	var dataString = $("#smsForm").serialize();
	if($('#group').val() == 0){
		$('#danger').css('display','block');
		$('#errorMsg').html("Please select group");
		$('#danger').delay(3000).fadeOut('slow');
		return false;
	}
	else if($('#message').val() == ""){
		$('#danger').css('display','block');
		$('#errorMsg').html("Please enter message to send");
		$('#danger').delay(3000).fadeOut('slow');
		return false;
	}
	else{
		$.ajax({
			type:"POST",
			url:"../controllers/CommonController.php",
			data: dataString,
			success:function(res){
				var obj = jQuery.parseJSON(res);
		    	if(obj.status == true){
		    		$('#success').css('display','block');
		    		$('#successMsg').html(obj.successMsg);
		    		setTimeout(function() {
		    		    location.reload();
		    		}, 1000);
		    	}else{
		    		$('#warning').css('display','block');
		    		$('#errMsg').html(obj.errorMsg);
		    		$('#warning').delay(2000).fadeOut('slow');
		    	}
			}
		});
	}
}
function getFormattedDate(dateStr) {

	  var date = new Date(dateStr);
	  var month = (1 + date.getMonth()).toString();
	  month = month.length > 1 ? month : '0' + month;

	  var day = date.getDate().toString();
	  day = day.length > 1 ? day : '0' + day;
	  
	  return day + '/' + month + '/' + date.getFullYear();
	}
function alphatsAndSpace(str){
	
	if( str.match("^[a-zA-Z\.]+$") ) {
	     return true
	}else{
		return false;
	}
}
function alphaNumeric(str){
	if( str.match(/^[a-zA-Z0-9]+/) ) {
	     return true
	}
}
function isNumeric(str){
	if( str.match("^\\d+$")) {
	     return true
	}
}
function isValidDate(txtDate){
	
  if(txtDate == '')
    return false;
	
  //Declare Regex 
  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;// Accept both (/,-) delimiter  
  var dtArray = txtDate.match(rxDatePattern); // is format OK?
  
  if (dtArray == null)
     return false;
	 
  //Checks for mm/dd/yyyy format.
  dtDay = dtArray[1];
  dtMonth = dtArray[3];
  dtYear = dtArray[5];
  if (dtMonth < 1 || dtMonth > 12)
      return false;
  else if (dtDay < 1 || dtDay> 31)
      return false;
  else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return false;
  else if (dtMonth == 2){
     var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
     if (dtDay> 29 || (dtDay ==29 && !isleap))
          return false;
  }
  return true;
}