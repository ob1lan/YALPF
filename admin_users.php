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
				<p>Outils de gestion des utilisateurs</p> 
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php require 'scripts/process_forms.php'; ?>
				</div>
			</div>
			<?php include('includes/user_list.php'); ?>
			<hr />
			<script>
				function showHint(int) {
					if (int.length == 0) { 
						document.getElementById("txtHint").innerHTML = "";
						return;
					} else {
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("txtHint").innerHTML = this.responseText;
							}
						};
						xmlhttp.open("GET", "scripts/process_forms.php?idshow=" + int, true);
						xmlhttp.send();
					}
				}
			</script>
			<?php 
				$stmt = $conn->prepare("SELECT * FROM users"); 
				$stmt->execute();
			?>
	
			<div class="row">
				<form>
					<div class="form-group col-xs-4">
					  <label for="sel1">Modifier un utilisateur :</label>
					  <select class="form-control" id="sel1" onchange="showHint(this.value)">
					  <option value="" disabled selected>Choisir un iD à modifier</option>
						<?php
							while($row=$stmt->fetch(PDO::FETCH_ASSOC)) { 
								$id=$row["id"];							
								echo "<OPTION>$id</option>";
							}
						?>
					  </select>
					</div>
				</form>
			</div>
			
			<br />
			<div id="txtHint"></div>			
		</div>
		<?php include('includes/nav.php'); ?>
		<?php include('includes/footer.php'); ?>
	</body>
</html>
