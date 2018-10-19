<html>
<head>
<title>Bus Ticket Book</title>
<script src="js/jquery-1.12.0.min.js"></script>
 <script> 
 $(document).ready(function(){
	var fruits = [];
	var amount = 400;
	$(".seat").click(function(){
	 var total_class = $('.green').length;
	 var book_seat = $(this).attr("data-book"); 
	 var current_book = $(this).attr("data-current"); 
	 if(current_book == 1 ){	
	 var seat_no = $(this).attr("data-number");
		$(this).removeClass('green');	
		$(this).removeAttr('data-current');		
		fruits.splice( fruits.indexOf(seat_no), 1 );
		$(".book_seats").val(fruits);	
		$(".amount").val( fruits.length * amount );
		return true;
	 }	  
	else if(book_seat == 1){	
		alert("Already booked");
		return false;
	}	
	else if(total_class < 5){ 	
		var seat_no = $(this).attr("data-number");
        fruits.push(seat_no);
		$(".book_seats").val(fruits);
		$(".amount").val( fruits.length * amount );
		$(this).attr('data-current', '1');
		$(this).addClass('green');	
		return true;
	}
	else if(total_class >= 5){ 
		alert("Maximum 5 seats only")
		return false;
	}	
	});
});
</script>
<style>
.lower_deck{width: 335px;float: left;min-height: 143px;border: 1px solid #CCC;padding: 6px 24px 0px 59px;}
.upper_deck{width: 352px;float: left;min-height: 143px;border: 1px solid #CCC;padding: 8px 0px 4px 62px;margin-top: 179px;margin-left: -418px;}
.seat{background:#CCC;float:left;margin:10px 10px 0 0;cursor:pointer;padding:4;}
.cancel_book{background:#CCC;}
.green{background:green;}
.red{background:red;}
img{ width: 41px; height: 20px; }
</style> 
</head>

<body>

	<div class="lower_deck">
		<?php
		 $booked_seat=array(10,11,7,4);
		 for($seat= 1; $seat <=15 ;$seat++) { 
			if(in_array($seat,$booked_seat)){ $booked="red"; $book_seat="data-book='1'"; }
			else { $booked=""; $book_seat="";}
			echo "<div class='seat $booked' data-number='$seat' $book_seat ><img src='images/seat_img.png'></div>";
		 } ?>
	</div>

	<div class="upper_deck">
		<?php
		 $booked_seat=array(16,20,27,30);
		 for($seat= 16; $seat <=30 ;$seat++) { 
			if(in_array($seat,$booked_seat)){ $booked="red"; $book_seat="data-book='1'"; }
			else { $booked=""; $book_seat="";}
			echo "<div class='seat $booked' data-number='$seat' $book_seat ><img src='images/seat_img.png'></div>";
		 } ?>
	</div>
	
	<form method="post">
	Seat No :<input type="text" name="seats" class="book_seats"><br>
	Total Amount :<input type="text" name="amount" class="amount"><br>
	<input type="submit" value="submit" class="submit">
	</form>
	<div class="details"></div>
	<p id="demo"></p>
</body>
</html>