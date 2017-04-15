<?php ob_start();
require_once(dirname(__FILE__) . "/Gezerconn.php");
require_once(dirname(__FILE__) . "/functions.inc.php");
require_once(dirname(__FILE__) . "/functions.php");
 session_start() ; 
confirm_Logged_in("NewPass");
	// START FORM PROCESSING
	if(isset($_POST['submit'])) { //Form has been submitted.
		$errors = array();
		$required_fields = array('OldPassword', "NewPassword", "NewPassword2");
		$errors = array_merge($errors, check_required_fields($required_fields));

		$fields_with_lengths = array('OldPassword' => 30, 'NewPassword' => 30, 'NewPassword2' => 30);
		$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));
		$Name = $_SESSION['Name'];
		
		$OldPassword = sha1(trim(mysql_prep($_POST['OldPassword'])));
		$NewPassword = trim(mysql_prep($_POST['NewPassword']));
		$NewPassword2 = trim(mysql_prep($_POST['NewPassword2']));
		if($NewPassword != $NewPassword2){$errors = array_merge($errors, array("New Password"));}
			$query = "SELECT Password ";
			$query .= "FROM Staff ";
			$query .= " WHERE Name = '{$Name}' ";
			$result_set = mysql_query($query);
			confirm_query($result_set);
			if(mysql_num_rows($result_set) == 1 )
			{
				$found_user = mysql_fetch_array($result_set);
				$ServerPassword = $found_user['Password'];
				if($OldPassword != $ServerPassword){$errors = array_merge($errors, array("Password Match"));}
			} else {
				// User/pass combo was not found in database
			
				$message = "User/Password combination incorrect.";
				$message .="Please make sure your caps lock key is off and try again. " ;
			}
			if(empty($errors))
				{
					$hashed_Password = sha1($NewPassword);
					$query = "UPDATE Staff SET 
					Password = '{$hashed_Password}' 
					WHERE Name = '{$Name}'";
						
					$result = mysql_query($query, $conn);
									if($result) 
									{
										$message = "The Password was successfully Changed" ;
									} else {
										$message = "The Password could not be Changed. ";
										$message .="<br />" . mysql_error();
									}
			} else {
					if(count($errors) == 1 ) {
						$message = "There was 1 error in the form.";
					} else {
						$message = "There were " . count($errors) .  "errors in the form.";
					}
			}
				
	} else { // Form has not been submitted.
		$Password = "";	
		$NewPassword = "";	
		$NewPassword2 = "";	
	}
?>
<?php include("includes/header.php"); ?>
<table id="structure">
	<tr>
	<td id="navigation">
 	<a href="GezerLogin.php">Return to LogIn</a><br /><br />
			</td>
			<td id="page">
				<h2>Change <?Php echo firstName($_SESSION['Name']) . " " . lastName($_SESSION['Name']) ?> Password</h2>
				<?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>" ;} ?>
				<?php if (!empty($errors)) { display_errors($errors); } ?>
				<form action="GezerNewPass.php" method="post">
		<table>
		<tr><td> </td>	<td>
			<tr><td>Old Password:</td>
			<td><input type="Password" name="OldPassword" maxlength="30" value="" /></td></tr>
			<tr><td>New Password:</td>
			<td><input type="Password" name="NewPassword" maxlength="30" value="" /></td></tr>
		<tr><td>New Password:</td>
		<td><input type="Password" name="NewPassword2" maxlength="30" value="" /></td></tr>
		<tr><td align=right colspan="2"> <br><input type="submit" name="submit" value="Update Password" /></td></tr>
				</table>
				</form>
					<br>
					<a href="GezerLogin.php">Cancel</a>
				</td>
			</tr>
		</table>
<?php require("includes/footer.php") ?>
<?php ob_end_flush(); ?>	


