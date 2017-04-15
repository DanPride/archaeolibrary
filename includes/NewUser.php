<?php ob_start();
 session_start() ; 
require_once(dirname(__FILE__) . "/Gezerconn.php");
require_once(dirname(__FILE__) . "/functions.php");
global $conn;
?>
<?php 
	// START FORM PROCESSING
	if(isset($_POST['submit'])) 
	{ //Form has been submitted.
		$errors = array();
		//perform validation on form data
		$required_fields = array('Name', "Password");
		$errors = array_merge($errors, check_required_fields($required_fields));

		$fields_with_lengths = array('Name' => 30, 'Password' => 30);
		$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

		$Name = trim(mysql_prep($_POST['Name']));
		$Password = trim(mysql_prep($_POST['Password']));
		$hashed_Password = sha1($Password);
			if(empty($errors)) 
			{
					$query = "INSERT INTO Staff (Name, Password) VALUES ('{$Name}', '{$hashed_Password}' )";
					$result = mysql_query($query, $conn);
					echo $query;
									if($result) 
									{
										$message = "The Name was successfully created" ;
									} else {
										$message = "The Name could not be created. ";
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
		<h2>Create New Name</h2>
		<?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>" ;} ?>
		<?php if (!empty($errors)) { display_errors($errors); } ?>
		<form action="GezerNewUser.php" method="post">
			<table>
				<tr><td>Name:</td>
					<td><input type="text" name="Name" maxlength="30" value="<?php echo htmlentities($Name); ?>" ></td></tr>
				<tr><td>Password:</td>
					<td><input type="Password" name="Password" maxlength="30" value="<?php echo htmlentities($Password); ?>" /></td></tr>
				<tr><td colspan="2"> <input type="submit" name="submit" value="Create New Name" /></td></tr>
			</table>
		</form>
	<br>
	<a href="GezerLogin.php">Cancel</a>
	</td>
	</tr>
</table>

<?php require("includes/footer.php") ?>
<?php ob_end_flush(); ?>	


