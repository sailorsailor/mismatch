<?php
	require_once('appvars.php');
	require_once('connectvars.php');

	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	if (isset($_POST['submit'])) {
		// Grab the profile data from the POST
		$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
		$password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
		$password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

		if(!empty($username) && !empty($password1) && !empty($password2) &&
			($password1 == $password2)) {
			// Make sure someone hasn't already registered this username
			$query = "SELECT * FROM mismatch_user WHERE username = '$username'";

			$data = mysqli_query($dbc, $query);
			if (mysqli_num_rows($data) == 0) {
				// The username is unique, so insert the data into the database
				$query = "INSERT INTO mismatch_user (username, password, join_date) VALUES " .
						"('$username', SHA('$password1'), NOW())";

				mysqli_query($dbc, $query);

				// Confirm success with the user
				echo '<p>Your new account has been successfully created. You are now ready to ' .
					'log in and <a href="editprofile.php">edit your profile</a>.</p>';

				mysqli_close($dbc);
				exit();
			} else {
				// An account already exits for this username, so display an error message
				echo '<p class=error>An account already exists for this username. Please use a ' .
					'different username.</p>';

				// Clear the $username variable
				$username == "";
			}
		} else {
			echo '<p class="erroe">You must enter all of the sign_up data, including the desired ' .
				'password twice.';
		}
	}

	mysqli_close($dbc);
?>

<p>Please enter your username and desired password to sign up to Mismatch.</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>Registration Info</legend>
		<table>
			<tr>
			<td><label for="username">Username:</label></td>
			<td><input type="text" id="username" name="username"
					value="<?php if (!empty($username)) {echo $username; } ?>"></td>
			</tr>
			
			<tr>
			<td><label for="password1">Password:</label></td>
			<td><input type="password" id="password1" name="password1"></td>
			</tr>
			
			<tr>
			<td><label for="password2">Password(retype):</label></td>
			<td><input type="password" id="password2" name="password2"></td>
			</tr>
		</table>
	</fieldset>
	<input type="submit" value="Sign Up" name="submit">
</form>
