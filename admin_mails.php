<?php
	session_start();
	
	if (!isset($_SESSION['id']) || $_SESSION['isSiteAdmin'] == '0' || $_SESSION['isEnabled'] == '0' || $_SESSION['isVerified'] == '0') {
		header("location:index.php");
	}
		
	include('scripts/configuration.php');
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>My template</title>
		<?php include('includes/head.php'); ?>
	</head>
	<body>		
		<div id="top" class="container">
			<div class="jumbotron">
				<h1><span class="glyphicon glyphicon-wrench"></span> Administration</h1> 
				<p>Outils d'envoi d'email aux utilisateurs</p> 
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="alert alert-info">
						<strong>Info!</strong> Seuls les utilisateurs activés et vérifiés recevront l'email
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php require 'scripts/process_forms.php'; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<form name="mass_email" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
						<div class="form-group">
							<label for="subject">Sujet:</label>
							<input type="text" class="form-control" name="subject" id="subject" required>
						</div>
						<div class="form-group">
							<label for="message_content">Message:</label>
							<textarea name="message_content" class="form-control" rows="10" id="message_content" required></textarea>
						</div>
						<button name="mail_submit" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> Envoyer</button>
					</form>
				</div>
			</div>
				
		</div>
		<?php include('includes/nav.php'); ?>
		<?php include('includes/footer.php'); ?>
	</body>
</html>
