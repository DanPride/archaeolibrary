<?php  
	require_once("Connection.php");
	require_once("functions.php");
	ob_start();
	session_start() ; 
	if(logged_in()){
		redirect_to("index.php");
	}
	function full_copy( $source, $target ) 
	{
		if ( is_dir( $source ) ) {
			@mkdir( $target );
			$d = dir( $source );
			while ( FALSE !== ( $entry = $d->read() ) ) {
				if ( $entry == '.' || $entry == '..' ) {
					continue;
				}
				$Entry = $source . '/' . $entry; 
				if ( is_dir( $Entry ) ) {
					full_copy( $Entry, $target . '/' . $entry );
					continue;
				}
				copy( $Entry, $target . '/' . $entry );
			}

			$d->close();
		}else {
			copy( $source, $target );
		}
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
						$_SESSION['Perm'] = $found_user['Perms'];
						$_SESSION['group'] = 'None';
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
<?php include("header.php"); ?>

<table id="structure">
	<tr>
			<td id="navigation">
		 <H2>Create<br>New Site</H2>
		<p><a href="Logout.php">Log Out</a></p>
			</td>
				<td id="page">
					<h2>Log On</h2>
					<?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>" ;} ?>
					<?php if (!empty($errors)) { display_errors($errors); } ?>
					<form action="Login.php" method="post">
					<table id="login" border="0">
						<tr><td>Name: </td>
							<td>
							<?php userPop($UserNameDefault);?><br /><br>				
							</td></tr>
						<tr><td>Password:</td>
							<td><input type="Password" name="Password" maxlength="30" value="BethSaida39"></td></tr>
							<tr><td Align=right colspan="2"> <br><input type="submit" name="submit" value="Login" /></td></tr>
					<tr><td></td><td Align=right><br>
						<ul class="loginbuttons" style="width:150px;text-align:center;align:right">
						</td></tr>
						</table>
					</form>

					</ul>
				</td>
			</tr>
		</table>
		
<?php require("footer.php") ?>

<?php ob_end_flush(); ?>
