<?php
	session_start();			
	// If the user is NOT logged in, AND NOT enabled, redirect to the home page
	if (!isset($_SESSION['id']) == '0' || $_SESSION['isEnabled'] == '0') {
		header("location:index.php");
	}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>My Framework</title>
		<?php include('includes/head.php'); ?>
	</head>
	<body>		
		<div id="top" class="container">
			<div class="jumbotron">
				<h1>Template</h1> 
				<p>Template explanation</p> 
			</div>
			<?php include('includes/nav.php'); ?>
			<?php include('includes/footer.php'); ?>
		</div>
	</body>
</html>