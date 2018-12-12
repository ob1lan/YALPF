<?php
	session_start();			
?>


<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>My template</title>
		<?php include('../includes/head.php'); ?>
	</head>
	<body>		
		<div id="top" class="container">
			<div class="jumbotron">
				<h1><span class="glyphicon glyphicon-wrench"></span> Installation</h1> 
				<p>Framework setup wizard</p> 
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php 
						require '../scripts/process_forms.php'; 
						$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] ;
						$indexURL = str_replace('INSTALL/install.php','index.php',$url);
					?>
				</div>
			</div>
			<h3>Configuration du serveur de base de donnée</h3>
			<div class="row">
				<div class="col-sm-12">
					<form name="verify_server" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
						<div class="form-group">
							<label for="servername">Serveur:</label>
							<input type="text" class="form-control" name="servername" id="servername" placeholder="localhost" required>
						</div>
						<div class="form-group">
							<label for="username">Utilisateur de la DB:</label>
							<input type="text" class="form-control" name="username" id="username" placeholder="root" required>
						</div>
						<div class="form-group">
							<label for="password">Mot de passe de la DB:</label>
							<input type="password" class="form-control" name="password" id="password" required>
						</div>
						<div class="form-group">
							<label for="dbname">Nom de la DB:</label>
							<input type="text" class="form-control" name="dbname" id="dbname" placeholder="site-db" required >
						</div>
						<div class="form-group">
							<label for="siteadmin">Adresse email de l'administrateur:</label>
							<input type="text" class="form-control" name="siteadmin" id="siteadmin" placeholder="admin@domain.com" required >
						</div>
						<div class="form-group">
							<label for="adminname">Nom de l'administrateur:</label>
							<input type="text" class="form-control" name="adminname" id="adminname" placeholder="DUPONT" required >
						</div>
						<div class="form-group">
							<label for="adminfirstname">Prénom de l'administrateur:</label>
							<input type="text" class="form-control" name="adminfirstname" id="adminfirstname" placeholder="Jean" required >
						</div>
						<div class="form-group">
							<label for="adminpassword">Mot de passe de l'administrateur:</label>
							<input type="password" class="form-control" name="adminpassword" id="adminpassword" required >
						</div>
						<div class="form-group">
							<label for="sitename">Nom du site:</label>
							<input type="text" class="form-control" name="sitename" id="sitename" placeholder="Mon site PHP" required >
						</div>
						<div class="form-group">
							<label for="indexURL">URL de la page index.php:</label>
							<input type="text" class="form-control" name="indexURL" id="indexURL" required value="<?php echo $indexURL;?>">
						</div>
						<div class="form-group">
							<label for="mailfrom">Mail from:</label>
							<input type="text" class="form-control" name="mailfrom" id="mailfrom" placeholder="no-reply@domain.com" required >
						</div>
						<div class="form-group">
							<label for="replyto">Reply to:</label>
							<input type="text" class="form-control" name="replyto" id="replyto" placeholder="support@domain.com" required >
						</div>
						<button name="configure" type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-cog"></span> Configurer</button>						
					</form>
				</div>
			</div>
			<?php include('../includes/footer.php'); ?>
		</div>
	</body>
</html>