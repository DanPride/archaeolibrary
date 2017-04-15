<?php ob_start();
 session_start() ; 
require_once(dirname(__FILE__) . "/Gezerconn.php");
require_once(dirname(__FILE__) . "/functions.php");
global $conn;
	$query = "SELECT Name FROM Staff";
	$result = mysql_query($query);
	confirm_query($result);		
?>
<?php 
	// START FORM PROCESSING
	if(isset($_POST['submit'])) 
	{ //Form has been submitted.
		$errors = array();
		//perform validation on form data
		$Name = trim(mysql_prep($_POST['Name']));
		$Password = trim(mysql_prep($_POST['Password']));
		$hashed_Password = sha1($Password);
			if(empty($errors)) 
			{
					$query = "UPDATE Staff SET 
					Password = '{$hashed_Password}' 
					WHERE Name = '{$Name}'";
					$result = mysql_query($query, $conn);
					echo $query;
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
		$Name = "";
		$Password = "";	
	}
?>
<?php include("includes/header.php"); ?>
<table id="structure">
	<tr>
	<td id="navigation">
 	<a href="GezerLogin.php">Return to LogIn</a><br /><br />
			</td>
			<td id="page">
				<h2>Change User Password</h2>
				<?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>" ;} ?>
				<?php if (!empty($errors)) { display_errors($errors); } ?>
		<form action="GezerPassAssigns.php" method="post">
		<table>
		<tr><td>Name:</td>	<td>
			<select name="Name" size="1">
		<?php echo "<option value =Select User>Select User</option>";
		while ($row = mysql_fetch_array($result)) {
		
			echo "<option value ='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
		}
	?>
				</select>
		<tr><td>Password:</td>
		<td><input type="Password" name="Password" maxlength="30" value="<?php echo htmlentities($Password); ?>" /></td></tr>
		<tr><td colspan="2"> <input type="submit" name="submit" value="Update Password" /></td></tr>
				</table>
				</form>
					<br>
					<a href="GezerLogin.php">Cancel</a>
				</td>
			</tr>
		</table>
<?php require("includes/footer.php") ?>
<?php ob_end_flush(); ?>	


