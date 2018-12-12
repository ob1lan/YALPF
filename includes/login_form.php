<form name="login_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
	<div class="row">
		<div class="form-group col-xs-4">
			<label for="email"><span class="glyphicon glyphicon-envelope"></span> Addresse email:</label>
			<input type="email" class="form-control" id="login_email" name="login_email" required>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-xs-4">
			<label for="password"><span class="glyphicon glyphicon-lock"></span> Mot de passe:</label>
			<input type="password" class="form-control" id="login_password" name="login_password" required>
		</div>
	</div>
	<button name="login_submit" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-log-in"></span> Se connecter</button>
</form>
