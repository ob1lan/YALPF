<?php
if (file_exists('scripts/configuration.php')) {
		include_once('scripts/configuration.php');
	}

if (file_exists('configuration.php')) {
	require('configuration.php');
} 	

// Reset variable to avoid problems
$req = $resultat = $result = $error = $secureimage = $code = $verification_code = $activation_code = $info = $email = $to = $subject = $header = "";

// LOGIN form handling
if(isset($_POST['login_submit'])) {
	$email = $_POST['login_email'];
	// Hachage et sallage du mot de passe
	$pass_hache = sha1('ptz' . $_POST['login_password']);

	// Vérification des identifiants
	$req = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :pass");
	$req->execute(array('email' => $email, 'pass' => $pass_hache));

	$resultat = $req->fetch();

	$req->closeCursor();

	if (!$resultat)
	{
		echo '<div class="alert alert-danger"><strong>Erreur!</strong> L\'adresse email ou le mot de passe est incorrect !</div>';
	}
	else
	{
		$_SESSION['id'] = $resultat['id'];	
		$_SESSION['email'] = $email;
		$_SESSION['isSiteAdmin'] = $resultat['isSiteAdmin'];
		$_SESSION['isEnabled'] = $resultat['isEnabled'];
		$_SESSION['isVerified'] = $resultat['isVerified'];
		$_SESSION['name'] = $resultat['name'];
		$_SESSION['firstname'] = $resultat['firstname'];
		$currdate = date('Y-m-d');
		$update = $conn->prepare("UPDATE users SET last_login_date = :lastseen WHERE id = :id");
		$update->execute(array('lastseen' => $currdate, 'id' => $_SESSION['id']));
		$update->closeCursor();	
		echo '<div class="alert alert-success"><strong>Succès!</strong> Bienvenue sur le site, ' . $_SESSION['firstname'] .  ' !</div>';
	}
}

// REGISTRATION form handling
if(isset($_POST['registration_submit'])) {
	
	// Informations reçues du formulaire
	$pass_hache = sha1('ptz' . $_POST['register_password']);
	$email = test_input($_POST['register_email']);
	$name = test_input($_POST['register_name']);
	$firstname = test_input($_POST['register_firstname']);
	$gender = $_POST['register_gender'];
	$phone = $_POST['register_phone'];
	$verification_code=substr(md5(mt_rand()),0,15);
	$activation_code=substr(md5(mt_rand()),0,15);
	$password1 = $_POST['register_password'];
	$password2 = $_POST['register_password2'];
	
	$checkmail = $conn->prepare("SELECT¨* FROM users WHERE email = $email");
	$checkmail->execute();
	$info = $checkmail->fetch();
	$checkmail->closeCursor();	
	
	$error = "none";
	
	if (isset($info['id'])) {
		$error = "wrongEmail";
	}
	
	if ($password1 != $password2) {
		$error = "wrongPassword";
	}
	
	switch ($error) {
		case "wrongEmail" : 
			echo '<div class="alert alert-danger"><strong>Erreur!</strong> L\'adresse email est déjà utilisée par un compte enregistré !</div>';
			break;
		case "wrongPassword" : 
			echo '<div class="alert alert-danger"><strong>Erreur!</strong> Les mots de passe ne correspondent pas !</div>';
			break;
		case "none" :
			try {
				$req = $conn->prepare("INSERT INTO users (email, password, name, firstname, gender, mainPhone, registration_date, verificationcode, activationcode) VALUES(:email, :pass, UCASE(:name), :firstname, :gender, :phone, CURDATE(), :verification, :activation)");
				$req->execute(array('email' => $email, 'pass' => $pass_hache, 'name' => $name, 'firstname' => $firstname, 'gender' => $gender , 'phone' => $phone,'verification' => $verification_code, 'activation' => $activation_code));
			}
			catch (PDOException $e){
			echo $req . "<br>" . $e->getMessage();
			
			}
		
			//Email to user with the verification link	
			$to = "$email";
			$subject = "[" . "$siteName" . "]" . 'Vérification de votre adresse email';
			$headers = "From: " . $mail_from . "\r\n";
			$headers .= "Reply-To: ". $mail_replyto . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$message = '<p>Bonjour, ' . $firstname . ' !</p>';
			$message .= '<p>Merci pour votre inscription sur notre site !</p>';
			$message .= '<p>Récapitulatif de vos informations :</p>';
			$message .= "<p><ul><li><strong>Votre nom : </strong>$name</li><li><strong>Votre prénom : </strong>$firstname</li><li><strong>Votre email : </strong>$email</li></ul></p>";
			$message .= "<p>Veuillez confirmer votre adresse email en utilisant le lien de vérification suivant :<br ><br /><strong><a href=\"" . $indexURL . "?verification=" . $verification_code . '">Vérifier votre compte</a></strong></p>';
			$message .= '<p>Cordialement,</p>';
			$message .= "<p>$siteName</p>";
			mail($to,$subject,$message,$headers);
			
			//Email to admin with the activation link	
			$to2 = "antoine@a-delrue.be";
			$subject2 = "[" . "$siteName" . "]" . 'Nouvel utilisateur enregistré, activation nécessaire';
			$headers2 = "From: " . $mail_from . "\r\n";
			$headers2 .= "Reply-To: ". $mail_replyto . "\r\n";
			$headers2 .= "MIME-Version: 1.0\r\n";
			$headers2 .= "Content-Type: text/html; charset=UTF-8\r\n";
			$message2 = '<p>Bonjour,</p>';
			$message2 .= '<p>Un nouvel utilisateur s\'est inscrit sur le site.</p>';
			$message2 .= '<p>Récapitulatif des informations du nouvel utilisateur :</p>';
			$message2 .= "<p><ul><li><strong>Nom : </strong>$name</li><li><strong>Prénom : </strong>$firstname</li><li><strong>Email : </strong>$email</li></ul></p>";
			$message2 .= "<p>Veuillez vérifier les données de l'utilisateur, et activer son compte utilisant le lien suivant :<br ><br /><strong><a href=\"" .$indexURL . "?activation=" . $activation_code . '">Activer le compte ' . $email . '</a></strong></p>';
			$message2 .= '<p>Cordialement,</p>';
			$message2 .= "<p>$siteName</p>";
			mail($to2,$subject2,$message2,$headers2);
			
			$req->closeCursor();
			echo '<div class="alert alert-success"><strong>Succès!</strong> Nous vous remercions d\'avoir créé votre compte. <br /> Nous venons de vous envoyer un e-mail afin de confirmer votre adresse. Pour activer votre compte, veuillez cliquer sur le lien contenu dans cet e-mail.</div>';
	}	
}

// VERIFICATION form handling
if(isset($_GET['verification'])) {
	
	$code = $_GET['verification'];
	try{
		$req = $conn->prepare("SELECT * from users where verificationCode = ?");
		$req->execute(array($_GET['verification']));
		$resultat = $req->fetch();
	}
	catch (PDOException $e){
		echo $req . "<br>" . $e->getMessage();
	}
		
	$req->closeCursor();

	if (isset($resultat['verificationCode']) && $resultat['isVerified'] == "0") {
			try{
				$value = '1';
				$uid = $resultat['id'];
				$req2 = $conn->prepare("UPDATE users SET isVerified=:value WHERE id = :uid");
				$req2->execute(array('uid' => $uid, 'value' => $value));
				$_SESSION['isVerified'] = '1';
				echo '<div class="alert alert-success"><strong>Succès!</strong> Votre compte est maintenant vérifié!</div>' ;
				
			}
			catch (PDOException $e){
				echo $req2 . "<br>" . $e->getMessage();
			}
			
			$req2->closeCursor();
	}
	elseif (isset($resultat['verificationCode']) && $resultat['isVerified'] == "1") {
		echo '<div class="alert alert-warning"><strong>Attention!</strong> Votre compte a déjà été vérifié</div>' ;
	}	
	else {
		echo '<div class="alert alert-danger"><strong>Erreur!</strong> Vérification du code impossible, veuillez contacter un administrateur</div><br >' ;
	}
}

// ACTIVATION form handling
if(isset($_GET['activation'])) {
	if(isset($_GET['activation'])) {
	
	$code = $_GET['activation'];
	try{
		$req = $conn->prepare("SELECT * from users where activationCode = ?");
		$req->execute(array($_GET['activation']));
		$resultat = $req->fetch();
	}
	catch (PDOException $e){
		echo $req . "<br>" . $e->getMessage();
	}
		
	$req->closeCursor();

	if (isset($resultat['activationCode']) && $resultat['isEnabled'] == "0") {
			try{
				$value = '1';
				$uid = $resultat['id'];
				$req2 = $conn->prepare("UPDATE users SET isEnabled=:value WHERE id = :uid");
				$req2->execute(array('uid' => $uid, 'value' => $value));
				
				//Email to user with the verification link	
				$to = $resultat['email'];
				$subject = "[" . "$siteName" . "]" . ' Votre compte a été activé';
				$headers = "From: " . $mail_from . "\r\n";
				$headers .= "Reply-To: ". $mail_replyto . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				$message = '<p>Bonjour, ' . $resultat['firstname'] . ' !</p>';
				$message .= '<p>Votre compte a été activé par un administrateur, vous pouvez maintenant vous connecter et utiliser toutes les fonctionnalités.</p>';
				$message .= "<p><a href=\"" . $indexURL . "\">Se connecter</a></p>";
				$message .= '<p>Cordialement,</p>';
				$message .= "<p>$siteName</p>";
				mail($to,$subject,$message,$headers);
				
				echo '<div class="alert alert-success"><strong>Succès!</strong> Le compte ' . $resultat['email'] . ' est maintenant activé !</div>' ;
				
			}
			catch (PDOException $e){
				echo $req2 . "<br>" . $e->getMessage();
			}
			
			$req2->closeCursor();
	}
	elseif (isset($resultat['activationCode']) && $resultat['isEnabled'] == "1") {
		echo '<div class="alert alert-warning"><strong>Attention!</strong> Le compte ' . $resultat['email'] . ' a déjà été activé !</div>' ;
	}	
	else {
		echo '<div class="alert alert-danger"><strong>Erreur!</strong> Activation du compte impossible !</div><br >';
	}
}
}

// UPDATE user show form
if(isset($_GET['idshow'])) {
	$id = $_GET["idshow"];
	$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id"); 
	$stmt->execute(array('id' => $_GET["idshow"]));
	$result = $stmt->fetch();
	include '../includes/update_form.php';	

	$stmt->closeCursor();
}

// UPDATE user form handling 
if(isset($_POST['update_submit'])) {
	$uid = $_POST['uid'];
	$email = $_POST['update_email'];
	$name = strtoupper($_POST['update_name']);
	$firstname = $_POST['update_firstname'];
	$gender = $_POST['update_gender'];
	$birth = $_POST['birth_date'];

	if(isset($_POST['isSiteAdmin']) && $_POST['isSiteAdmin'] == 'isSiteAdmin') {
		$isSiteAdmin = '1';
	}
	else {$isSiteAdmin = '0';}

	if(isset($_POST['isEnabled']) && $_POST['isEnabled'] == 'isEnabled') {
		$isEnabled = '1';
	}
	else {$isEnabled = '0';}

	try{
		$req = $conn->prepare("UPDATE users SET email=:email, name=:name, firstname=:firstname, gender=:gender, birth_date=:birth, isSiteAdmin=:isSiteAdmin, isEnabled=:isEnabled WHERE id=:id");
		$req->execute(array('id' => $uid, 'email' => $email, 'name' => $name, 'firstname' => $firstname, 'gender' => $gender, 'birth' => $birth, 'isEnabled' => $isEnabled, 'isSiteAdmin' => $isSiteAdmin));
		echo '<div class="alert alert-success"><strong>Succès!</strong> Compte bien mis à jour !</div>' ;
	}
	catch (PDOException $e){
		echo $req . "<br>" . $e->getMessage();
	}
		
	$req->closeCursor();
}

// DELETE user form handling 
if(isset($_POST['delete_user_submit'])) {
	$uid = $_POST['uid'];
	
	try{
		$req = $conn->prepare("DELETE FROM users WHERE id=:id");
		$req->execute(array('id' => $uid));
		echo '<div class="alert alert-success"><strong>Succès!</strong> Utilisateur bien supprimé !</div>' ;
	}
	catch (PDOException $e){
		echo $req . "<br>" . $e->getMessage();
	}
		
	$req->closeCursor();
}

// ADD NEWS form handling 
if(isset($_POST['news_add_submit'])) {
	$news_content = $_POST['news_content'];
	$news_expiration = $_POST['news_expiration_date'];
	switch ($_POST['news_level']) {
		case "Danger (rouge)": 
			$level = "danger";
			break;
		case "Attention (orange)": 
			$level = "warning";
			break;
		case "Information (bleu)": 
			$level = "info";
			break;
	}
	
	try{
		$req = $conn->prepare("INSERT INTO news (content,level,expiration_date,creation_date) VALUES (:content, :level, :expiration_date, CURDATE())");
		$req->execute(array('content' => $news_content, 'level' => $level, 'expiration_date' => $news_expiration));
		echo '<div class="alert alert-success"><strong>Succès!</strong> News créée !</div>' ;
	}
	catch (PDOException $e){
		echo $req . "<br>" . $e->getMessage();
	}
		
	$req->closeCursor();	
}

// UPDATE news show form
if(isset($_GET['newsshow'])) {
	$id = $_GET["newsshow"];
	$stmt = $conn->prepare("SELECT * FROM news WHERE id = :id"); 
	$stmt->execute(array('id' => $_GET["newsshow"]));
	$result = $stmt->fetch();
	include '../includes/update_news_form.php';	

	$stmt->closeCursor();
}

// UPDATE news form handling 
if(isset($_POST['update_news_submit'])) {
	$uid = $_POST['uid'];
	$content = $_POST['update_news_content'];
	$expiration_date = $_POST['update_news_expiration'];
	switch ($_POST['update_news_level']) {
		case "Danger (rouge)": 
			$level = "danger";
			break;
		case "Attention (orange)": 
			$level = "warning";
			break;
		case "Information (bleu)": 
			$level = "info";
			break;
	}
	
	try{
		$req = $conn->prepare("UPDATE news SET content=:content, level=:level, expiration_date=:expiration_date WHERE id=:id");
		$req->execute(array('id' => $uid, 'content' => $content, 'level' => $level, 'expiration_date' => $expiration_date));
		echo '<div class="alert alert-success"><strong>Succès!</strong> News bien mise à jour !</div>' ;
	}
	catch (PDOException $e){
		echo $req . "<br>" . $e->getMessage();
	}
		
	$req->closeCursor();	
}

// DELETE news form handling 
if(isset($_POST['delete_news_submit'])) {
	$uid = $_POST['uid'];
	
	try{
		$req = $conn->prepare("DELETE FROM news WHERE id=:id");
		$req->execute(array('id' => $uid));
		echo '<div class="alert alert-success"><strong>Succès!</strong> News bien supprimée !</div>' ;
	}
	catch (PDOException $e){
		echo $req . "<br>" . $e->getMessage();
	}
		
	$req->closeCursor();
}

// UPDATE profile form handling 
if(isset($_POST['update_profile_submit'])) {
	$uid = $_POST['uid'];
	$email = $_POST['profile_email'];
	$name = strtoupper($_POST['profile_name']);
	$firstname = $_POST['profile_firstname'];
	$gender = $_POST['profile_gender'];
	$address = $_POST['profile_address'];
	$phone = $_POST['profile_phone'];
	$birth_date = $_POST['profile_birth_date'];

	try{
		$req = $conn->prepare("UPDATE users SET email=:email, name=:name, firstname=:firstname, gender=:gender, birth_date=:birth, mainPhone=:phone, address=:address WHERE id=:id");
		$req->execute(array('id' => $uid, 'email' => $email, 'name' => $name, 'firstname' => $firstname, 'gender' => $gender, 'birth' => $birth_date, 'phone' => $phone, 'address' => $address));
		echo '<div class="alert alert-success"><strong>Succès!</strong> Compte bien mis à jour !</div>' ;
	}
	catch (PDOException $e){
		echo $req . "<br>" . $e->getMessage();
	}
		
	$req->closeCursor();
}

// SEND mass email form handling 
if(isset($_POST['mail_submit'])) {
	$subject = $_POST['subject'];
	$message_content = $_POST['message_content'];
	
	$qry = $conn->prepare("SELECT email FROM users where isEnabled = '1'");	
	$qry->execute();
	$emails = $qry->fetchAll(PDO::FETCH_COLUMN);
	$list_emails = '';
	foreach ($emails as $x => $x_value) {
		$list_emails .= $x_value . ',';
	}
	
	$to = "$list_emails";
	$subject = "[" . "$siteName" . "]" . "$subject";
	$headers = "From: " . $mail_from . "\r\n";
	$headers .= "Reply-To: ". $mail_replyto . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type:text/plain;charset=utf-8\r\n";
	$message = "$message_content";
	$message .= "\r\n";
	$message .= "\r\n";
	$message .= 'Fraternellement,';
	$message .= "\r\n";
	$message .= $_SESSION['firstname'];
	mail($to,$subject,$message,$headers);
	
	$qry->closeCursor();
	echo '<div class="alert alert-success"><strong>Succès!</strong> Email envoyé aux utilisateurs !</div>' ;
}

// Function to validate data sent by the forms
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// CONFIGURE server form handling
if(isset($_POST['configure'])) {
	$servername = $_POST['servername'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$dbname = $_POST['dbname'];
	$siteadmin = $_POST['siteadmin'];
	$adminname = $_POST['adminname'];
	$adminfirstname = $_POST['adminfirstname'];
	$adminpassword = sha1('ptz' . $_POST['adminpassword']);
	$sitename = $_POST['sitename'];
	$mailfrom = $_POST['mailfrom'];
	$replyto = $_POST['replyto'];
	$indexURL = $_POST['indexURL'];
	
	// Create connection
	try {
		$conn = new PDO("mysql:host=$servername;charset=utf8", $username, $password,
		array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$result = 'SUCCESS';
		echo '<div class="alert alert-success"><strong>OK!</strong> Connexion au serveur établie</div>';	
	}
	catch (Exception $e) {
		$result = 'Erreur : ' . $e->getMessage();
		echo '<div class="alert alert-danger"><strong>ERREUR!</strong> Impossible de se connecter au serveur avec les paramètres spécifiés</div>';
	}
	
	try {
		$configure = $conn->prepare("
			SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
			CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
			USE `$dbname`;

			DROP TABLE IF EXISTS `configuration`;
			CREATE TABLE `configuration` (
			  `sitename` varchar(255) NOT NULL,
			  `siteadmin` varchar(255) NOT NULL,
			  `mail_from` varchar(255) NOT NULL,
			  `reply_to` varchar(255) NOT NULL,
			  `indexurl` varchar(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;

			DROP TABLE IF EXISTS `news`;
			CREATE TABLE `news` (
			  `id` int(11) NOT NULL,
			  `content` varchar(255) NOT NULL,
			  `level` varchar(255) NOT NULL,
			  `creation_date` date NOT NULL,
			  `expiration_date` date NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;

			DROP TABLE IF EXISTS `users`;
			CREATE TABLE `users` (
			  `id` int(11) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `firstname` varchar(255) NOT NULL,
			  `email` varchar(255) NOT NULL,
			  `password` varchar(255) NOT NULL,
			  `gender` varchar(255) NULL,
			  `birth_date` date NULL,
			  `address` text NULL,
			  `mainPhone` varchar(255) NULL,
			  `isSiteAdmin` tinyint(1) NULL,
			  `registration_date` date NOT NULL,
			  `last_login_date` date NULL,
			  `isEnabled` tinyint(1) NULL,
			  `isVerified` int(11) NULL,
			  `verificationCode` varchar(255) NULL,
			  `activationCode` varchar(255) NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;

			ALTER TABLE `news`
			  ADD PRIMARY KEY (`id`);

			ALTER TABLE `users`
			  ADD PRIMARY KEY (`id`);

			ALTER TABLE `news`
			  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

			ALTER TABLE `users`
			  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
		");	
		$configure->execute();
		echo '<div class="alert alert-success"><strong>OK!</strong> DB créée</div>';
		
	}
	catch (Exception $e) {
		$result = 'Erreur : ' . $e->getMessage();
		echo '<div class="alert alert-danger"><strong>ERREUR!</strong> Impossible de se connecter au serveur avec les paramètres spécifiés</div>';
	}
	
	sleep(2);
	
	// Create connection
	try {
		$adminconn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password,
		array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$result = 'SUCCESS';	
	}
	catch (Exception $e) {
		$result = 'Erreur : ' . $e->getMessage();
		echo '<div class="alert alert-danger"><strong>ERREUR!</strong> Impossible de se connecter à la DB créée</div>';
	}
	
	 
	
	try {
		$createadmin = $adminconn->prepare("INSERT INTO users (email, password, name, firstname, registration_date, isSiteAdmin, isEnabled, isVerified) VALUES (:email, :password, UCASE(:name), :firstname, CURDATE(), 1, 1, 1)");
		$createadmin->execute(array('email' => $siteadmin, 'password' => $adminpassword, 'name' => $adminname, 'firstname' => $adminfirstname));
		echo '<div class="alert alert-success"><strong>OK!</strong> Compte administrateur créé</div>';
		
	}
	catch (Exception $e) {		
		$result = 'Erreur : ' . $e->getMessage();
		echo '<div class="alert alert-danger"><strong>ERREUR!</strong> Impossible de créer le compte admin</div>';
		die('Erreur : ' . $e->getMessage());
	}
	
	try {
		$dir = '../tmp';
		if ( !file_exists($dir) ) {
		mkdir ($dir, 0755);
		}
		$current = file_get_contents("../INSTALL/config_inc.php");
		fopen('../tmp/configuration.php', "w") or die("unable to create configuration file");		
		$content = '<?php' . "\n";
		$content .= "\n";
		$content .= '// Server configuration' . "\n";
		$content .= '$db_server =' . "'" . $servername . "';" . "\n";
		$content .= '$db_name =' . "'" . $dbname . "';" . "\n";
		$content .= '$db_user =' . "'" . $username . "';" . "\n";
		$content .= '$db_password =' . "'" . $password . "';" . "\n";
		$content .= "\n";
		$content .= '// email configuration' . "\n";
		$content .= '$site_admin =' . "'" . $siteadmin . "';" . "\n";
		$content .= '$mail_from =' . "'" . $mailfrom . "';" . "\n";
		$content .= '$reply_to =' . "'" . $replyto . "';" . "\n";
		$content .= "\n";
		$content .= '// Site configuration' . "\n";
		$content .= '$indexURL =' . "'" . $indexURL . "';" . "\n";
		$content .= '$siteName =' . "'" . $sitename . "';" . "\n";
		$content .= "\n";
		$content .= $current;
		file_put_contents ('../tmp/configuration.php', $content);
		fclose('../tmp/configuration.php');
		copy("../tmp/configuration.php","../scripts/configuration.php");
	}
	catch (Exception $e) {		
		$result = 'Erreur : ' . $e->getMessage();
		echo '<div class="alert alert-danger"><strong>ERREUR!</strong> Impossible de créer le fichier de configuration</div>';
		echo $current;
		echo $content;
		die('Erreur : ' . $e->getMessage());
	}
	
	try {
		function rrmdir($dir) { 
			if (is_dir($dir)) { 
				$objects = scandir($dir); 
				foreach ($objects as $object) { 
					if ($object != "." && $object != "..") { 
						if (is_dir($dir."/".$object))
						rrmdir($dir."/".$object);
						else
						unlink($dir."/".$object); 
					} 
				}
				rmdir($dir); 
			} 
		}
		rrmdir('../tmp');
		rrmdir('../INSTALL');
		echo '<div class="alert alert-success"><strong>OK!</strong> Dossier INSTALL & tmp supprimés</div>';
		echo '<div class="alert alert-info"><strong>OK!</strong> <a href="' . $indexURL . '">Cliquez ici pour aller à la page de login </a></div>';
	}
	catch (Exception $e) {		
		$result = 'Erreur : ' . $e->getMessage();
		echo '<div class="alert alert-danger"><strong>ERREUR!</strong> Impossible de supprimer le dossier INSTALL</div>';
		echo $current;
		echo $content;
		die('Erreur : ' . $e->getMessage());
	}
}
?>
