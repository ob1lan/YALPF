<?php
	session_start();
	
	if (!isset($_SESSION['id']) || $_SESSION['isVerified'] == '0') {
		header("location:index.php");
	}
		
	include('scripts/configuration.php');
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>My Template</title>
		<?php include('includes/head.php'); ?>
	</head>
	<body>
		<div id="top" class="container">
			<div class="jumbotron">
				<h1><span class="glyphicon glyphicon-user"></span> Mon profil</h1> 
				<p>Edition du profil utilisateur</p> 
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php require 'scripts/process_forms.php'; ?>
				</div>
			</div>			
			<?php include('includes/profile_form.php'); ?>
		</div>
		<?php include('includes/nav.php'); ?>
		<?php include('includes/footer.php'); ?>
	</body>
</html>
