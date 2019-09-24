<?php
//profile.php

include('database_connection.php');

if(!isset($_SESSION['type']))
{
	header("location:login.php");
}

$query = "
		SELECT * FROM user
		WHERE u_id = '".$_SESSION["u_id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$fname = '';
		$u_name = '';
		$u_id = '';
		foreach($result as $row)
		{
			$fname = $row['fname'];
			$u_name = $row['u_name'];
		}

include('header.php');

?>
	<div class="profile-panel panel-default">
		<div class="profile-panel-heading">
			<h4 class="modal-title">Edit Profile</h4>
		</div>
		<div class="profile-panel-body">
			<form method="post" id="edit_profile_form">
				<span id="message"></span>
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="fname" id="fname" class="form-control" value="<?php echo $fname; ?>" required />
				</div>
				<div class="form-group">
					<label>Username</label>
					<input type="text" name="u_name" id="u_name" class="form-control" required value="<?php echo $u_name; ?>" />
				</div>
				<hr />
				<label>Leave Password blank if you do not want to change</label>
				<div class="form-group">
					<label>New Password</label>
					<input type="password" name="user_new_password" id="user_new_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" class="form-control" />
				</div>
				<div id="pwdmessage">
					<h3>Password must contain the following:</h3>
					<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
					<p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
					<p id="number" class="invalid">A <b>number</b></p>
					<p id="length" class="invalid">Minimum <b>8 characters</b></p>
				</div>
				<div class="form-group">
					<label>Re-enter Password</label>
					<input type="password" name="user_re_enter_password" id="user_re_enter_password" class="form-control" />
					<span id="error_password"></span> <!-- error password label -->
				</div>
				<div class="form-group">
					<input type="submit" name="edit_prfile" id="edit_prfile" value="EDIT" class="btn btn-submit" />
				</div>
			</form>
		</div>
	</div>

<script>
$(document).ready(function(){
	$('#edit_profile_form').on('submit', function(event){
		event.preventDefault();
		if($('#user_new_password').val() != '') // if user change passwod
		{
			if($('#user_new_password').val() != $('#user_re_enter_password').val())
			{
				$('#error_password').html('<label class="text-danger">Password Not Match</label>');
				return false;
			}
			else
			{
				$('#error_password').html('');
			}
		}
		// pass edited values to edit_profile.php
		$('#edit_prfile').attr('disabled', 'disabled');
		var form_data = $(this).serialize();
		$('#user_re_enter_password').attr('required',false);
		$.ajax({
			url:"edit_profile.php",
			method:"POST",
			data:form_data,
			success:function(data)
			{
				$('#edit_prfile').attr('disabled', false);
				$('#user_new_password').val('');
				$('#user_re_enter_password').val('');
				$('#message').html(data);
			}
		})
	});
});


//Password Validation
var userInput = document.getElementById("user_new_password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
userInput.onfocus = function() {
  document.getElementById("pwdmessage").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
userInput.onblur = function() {
  document.getElementById("pwdmessage").style.display = "none";
}

// When the user starts to type something inside the password field
userInput.onkeyup = function() {

  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(userInput.value.match(lowerCaseLetters)) {
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
}

  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(userInput.value.match(upperCaseLetters)) {
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(userInput.value.match(numbers)) {
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }

  // Validate length
  if(userInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>