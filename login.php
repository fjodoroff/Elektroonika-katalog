<?php 
	require_once('functions.php');
	$self = basename(__FILE__);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
</head>
<body>
	<?php
		$userID = logIn($_POST['name'], $_POST['password']);
		if($userID == NULL) {
			echo "<h1>Please login to use service</h1>";
		} else {
			echo "<h1>Welcome, {$userID}</h1>";
		} 
	?>
	<form action="/<?php echo $self ?>" method="POST">
		<input type="text" name="name" placeholder="Enter your name">
		<input type="password" name="password" placeholder="Enter your password">
		<button type="submit">Login</button>
	</form>
</body>
</html>