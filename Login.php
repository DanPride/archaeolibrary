<?php  
	require_once("includes/Connection.php");
	require_once("includes/functions.php");
	ob_start();
	session_start() ; 
	if(logged_in()){
		redirect_to("index.php");
	}

	// START FORM PROCESSING
	if(isset($_POST['submit'])) 
	{ //Form has been submitted.
		$errors = array();

		//perform validation on form data
		$required_fields = array('Name', "Password");
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));

		$fields_with_lengths = array('Name' => 30, 'Password' => 30);
		$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));

		$Name = trim(mysql_prep($_POST['Name']));
		$Password = trim(mysql_prep($_POST['Password']));
		$hashed_Password = sha1($Password);
			if(empty($errors)) 
			{
				// Check to see if User and Password exist there
					$query = "SELECT * ";
					$query .= "FROM Staff ";
					$query .= " WHERE Name = '{$Name}' ";
					$query .= " AND Password = '{$hashed_Password}' ";
					$result_set = mysql_query($query);
					confirm_query($result_set);
					if(mysql_num_rows($result_set) == 1 )
					{
						//User and passowrd authenticated
						//and only 1 match
						$_SESSION['Perm'] = "View";
						$found_user = mysql_fetch_array($result_set);
						$_SESSION['Name'] = $found_user['Name'];
						$_SESSION['User'] = $found_user['User'];
						$_SESSION['PermsView'] = $found_user['PermsView'];
						$_SESSION['PermsMod'] = $found_user['PermsMod'];
						$_SESSION['PermsDelete'] = $found_user['PermsDelete'];
						$_SESSION['PermsAdd'] = $found_user['PermsAdd'];
						redirect_to("index.php");
					} else {
						// User/pass combo was not found in database
					
						$message = "User/Password combination incorrect.";
						$message .="Please make sure your caps lock key is off and try again. " ;
					}
				} else {
					if(count($errors) == 1 ) {
						$message = "There was 1 error in the form.";
					} else {
						$message = "There were " . count($errors) .  "errors in the form.";
					}
				}
	} else { // Form has not been submitted.
		$User = "";
		$Password = "";	
	}

?>
<?php include("includes/header.php"); ?>

<table id="structure">
	<tr>
			<td id="navigation">
		 <H2><?php echo digname() ?> Excavations</H2>
		<p><a href="Logout.php">Log Out</a></p>
			</td>
				<td id="page">
					<h2>Log On</h2>
					<?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>" ;} ?>
					<?php if (!empty($errors)) { display_errors($errors); } ?>
					<h4>This Demo has Automatic "Guest" and "View Only" Log on<br>Just Click the Login Button<br>
						The "Guest" user has full privileges in this Demo<br>and can alter data at will.<br>The Data set is restored regularly</h4>
					<form action="Login.php" method="post">
					<table id="login" border="0">
						<tr><td>Name: </td>
							<td>
							<?php userPop($UserNameDefault);?><br /><br>				
							</td></tr>
						<tr><td>Password:</td>
							<td><input type="Password" name="Password" maxlength="30" value="Guest"></td></tr>
							<tr><td Align=right colspan="2"> <br><input type="submit" name="submit" value="Login" /></td></tr>
					<tr><td></td><td Align=right><br>
					
						</td></tr>
						</table>
					</form>

					</ul>
				</td>
			</tr>
		</table>
		
<?php require("includes/footer.php") ?>

<?php ob_end_flush(); ?>
