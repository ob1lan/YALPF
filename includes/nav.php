<nav class="navbar navbar-inverse navbar-fixed-top" data-spy="affix">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><?php echo "$siteName";?></a>
    </div>
	
	<?php
		$directoryURI = $_SERVER['REQUEST_URI'];
		$path = parse_url($directoryURI, PHP_URL_PATH);
	?>	
	
    <div id="navbar" class="collapse navbar-collapse">
		<ul class="nav navbar-nav">
			<li class="<?php if (strpos($path, 'index.php') !== false) {echo "active"; } else  {echo "noactive";}?>"><a href="index.php">Accueil</a></li> 
			
			<?php 		
				if (isset($_SESSION['id']) && $_SESSION['isVerified'] == '1') {
			?>
				<li class="<?php if (strpos($path, 'myprofile.php') !== false) {echo "active"; } else  {echo "noactive";}?>"><a href="myprofile.php">Mon Profil</a></li>
			<?php 
				}	
								
				if(isset($_SESSION['isSiteAdmin']) && $_SESSION['isSiteAdmin'] == '1' && $_SESSION['isVerified'] == '1' && $_SESSION['isEnabled'] == '1') { 
			?>
					<li class="dropdown <?php if (strpos($path, 'admin') !== false)  {echo "active"; } else  {echo "noactive";}?>">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">Administration
						<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="admin_users.php">Gestion des utilisateurs</a></li>
							<li><a href="admin_news.php">Gestion des news</a></li>
							<li><a href="admin_mails.php">Emails de masse</a></li> 
						</ul>
					</li>

			<?php
				}			
				
			?>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<?php
				if (isset($_SESSION['id'])) {
						echo '<li class="noactive"><a href="scripts/logoff.php"><span style="color:red;" class="glyphicon glyphicon-log-out"></span> <font color="red">Déconnexion</font></a></li>' ;
				}
			?>
		</ul>
    </div>
  </div>
</nav>