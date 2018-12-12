<?php
	session_start();
	if (file_exists('scripts/configuration.php')) {
		include('scripts/configuration.php');
	} 
	else {
		$_SESSION['error_message'] = "Le fichier de configuration n'existe pas ou ne contient pas des valeurs correctes. <br /> Utilisez la page d'installation pour créer un script de configuration correct : http://your_domain.com/INSTALL/install.php";
		echo $_SESSION['error_message'];
		die;
	}
	
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>My Index</title>		
		<?php include('includes/head.php');?>	
	</head>
	<body>		
		<div class="container">
			<div class="jumbotron">
				<h1><span class="glyphicon glyphicon-home"></span> Bienvenue</h1> 
				<p>Bienvenue sur le site<?php if(isset($_SESSION['firstname'])) {echo ', ' . $_SESSION['firstname']; } ?></p> 
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php require 'scripts/process_forms.php'; ?>
				</div>
			</div>
			
			<?php			
				// If logged in AND NOT enabled, display the admin approval remark
				if(isset($_SESSION['id']) && $_SESSION['isEnabled'] == '0' ) {	
					echo '<div class="alert alert-warning">
							<strong>Attention!</strong> Votre compte doit encore être activé par un administrateur avant d\'avoir accès à toutes les fonctionalités.
						</div>';
				}
			
				// If loggedin AND enabled, display the logoff button and the news
				if(isset($_SESSION['id']) && $_SESSION['isVerified'] == '1' ) {	
					?>
					<div class="row">
						<div class="col-sm-12">
							<?php
								$req = $conn->prepare("SELECT * FROM news");
								$req->execute();
								$countnews=$req->rowCount();
							?>
								
							<H3>Nos news <span class="badge"><?php echo $countnews; ?></span> :</h3>
							
							<?php
								while($row=$req->fetch(PDO::FETCH_ASSOC)) {
											$content = $row["content"];
							?>
							
								<div class="alert alert-<?php echo $row['level']; ?>"><p><strong>NEW </strong><?php echo $row['content']; ?></p></div>
							<?php
								}
								$req->closeCursor();
							?>
						</div>
					</div>
					<hr />
					<?php
					echo '<a href="scripts/logoff.php" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-log-out"></span> Déconnexion</a>';
				}
				// If loggedin AND NOT email-verified, display the verification message
				elseif (isset($_SESSION['id']) && $_SESSION['isVerified'] == '0' ) {
					echo '<div class="alert alert-warning"><strong>Attention!</strong> Veuillez consulter vos emails et cliquer sur le lien d\'activation qui vous a été envoyé !</div>' ;
				}
				// Else (=if NOT logged in)
				else {
					echo '<p id="your_name">Veuillez vous identifier avec un compte valide</p>';
					include('includes/login_form.php'); 
					echo '<br />';
					include('includes/registration_form.php'); 
				}		
			?>			
		</div>		
		<?php include('includes/nav.php'); ?>
		<?php include('includes/footer.php'); ?>		
	</body>
</html>
