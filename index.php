<?php
//If the form is submitted
if(isset($_POST['submit'])) {

	//Check to make sure sure that a valid phone address is submitted
	
	if(trim($_POST['phone']) == '')  {
		$hasError = true;
	} else if (!eregi("^[(]{0,1}[0-9]{3}[)]{0,1}[-\s.]{0,1}[0-9]{3}[-\s.]{0,1}[0-9]{4}$", trim($_POST['phone']))) {
		$hasError = true;
	} else {
		$phone = trim($_POST['phone']);
	}

	//If there is no error, send the phone
	if(!isset($hasError)) {
		//echo '<div class="hidden">';
		require_once('func.php');
		$number= "1".$_POST["phone"];
		if(userExists($number)){
			setState($number,"confirming");
		}
		else{
			addUserToCSV($number);
		}
		$ch = curl_init('http://api.tropo.com/1.0/sessions?action=create&token=0ec5eb6bcc51c243972349201a13cd6f76f98e36acc1c5a250bff23af9d161b7b6b94c66e03b5440e44a979f&to='.$number.'&msg=Respond+Yes+to+confirm');

		curl_exec($ch);

		curl_close($ch);
		//echo '</div>';
		echo '<div id="resultMsg">Successfully signed up!<br /></div>';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>IDAS Signup</title>
	
<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	// validate signup form on keyup and submit
	var validator = $("#contactform").validate({
		rules: {
			phone: {
				required: true,
				minlength: 10
			}
		},
		messages: {
			phone: {
				required: "Please enter a valid phone address",
				minlength: "Please enter a valid phone address"
			}
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			label.addClass("checked");
		}
	});
});
</script>
</head>

<body>
<div class="wrapper"> 
	<div id="contactWrapper" role="form">
	
		<h1 role="heading">Sign up for survey</h1>

		<?php if(isset($hasError)) { //If errors are found ?>
			<p class="error">Please check if you've filled all the fields with valid information and try again. Thank you.</p>
		<?php } ?>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="contactform">

			<div class="stage clear">
				<label for="phone"><strong>Phone: <em>*</em></strong></label>
				<input type="text" name="phone" id="phone" value="" class="required phone" role="input" aria-required="true" />
			</div>
			
			<input type="submit" value="Sign Up" name="submit" id="submitButton" title="Click here to sign up for the survey!" />
		</form>
		
	</div>
</div>
</body>
</html>