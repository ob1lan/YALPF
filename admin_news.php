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
		<title>My Framework</title>
		<?php include('includes/head.php'); ?>
	</head>
	<body>		
		<div id="top" class="container">
			<div class="jumbotron">
				<h1><span class="glyphicon glyphicon-wrench"></span> Administration</h1> 
				<p>Outils de gestion des news</p> 
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php require 'scripts/process_forms.php'; ?>
				</div>
			</div>
			<script>
				function showHint(int) {
					if (int.length == 0) { 
						document.getElementById("txtHint").innerHTML = "";
						return;
					} else {
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("newstxtHint").innerHTML = this.responseText;
							}
						};
						xmlhttp.open("GET", "scripts/process_forms.php?newsshow=" + int, true);
						xmlhttp.send();
					}
				}
			</script>
			<?php 
				include('includes/news_list.php'); 

				$stmt = $conn->prepare("SELECT * FROM news"); 
				$stmt->execute();
			?>
			<div class="row">
				<div class="col-sm-12">
					<h3>Ajouter une news</h3>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
						<div class="form-group">
							<label for="news_content">Contenu :</label>
							<input type="text" name="news_content" class="form-control" id="news_content">
						</div>
						<div class="form-group">
							<label for="news_level">Type de news:</label>
							<select class="form-control" id="news_level" name="news_level">
								<option>Information (bleu)</option>
								<option>Attention (orange)</option>
								<option>Danger (rouge)</option>
							</select>
						</div>
						<div class="form-group">
							<label for="news_expiration_date">Date d'expiration :</label>
							<input type="date" name="news_expiration_date" class="form-control" min="2016-01-02"><br>
						</div>
						<button name="news_add_submit" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Ajouter</button>
					</form>
				</div>	
			</div>		
			<hr />		
			<div class="row">
				<div class="col-sm-4">
					<h3>Modifier une news</h3>
					<form>						
						<div class="form-group">
						  <label for="sel1">News à modifier:</label>
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
			</div>	
			<br />
			<div id="newstxtHint"></div>			
		</div>
		<?php include('includes/nav.php'); ?>
		<?php include('includes/footer.php'); ?>
	</body>
</html>
