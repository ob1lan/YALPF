<button class="btn btn-info" data-toggle="collapse" data-target="#collapse_registration"><span class="glyphicon glyphicon-pencil"></span> Pas encore de compte ?</button>
<br />
<br />
<div id="collapse_registration" class="collapse">
	<p>Veuillez remplir le formulaire d'inscription ci-dessous :</p>
	<form name="registration_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		<div class="row">		
			<div class="form-group col-xs-4">
				<label for="name">Nom:</label>
				<input type="text" class="form-control" id="register_name" name="register_name" placeholder="Ex : DUPONT" required>
			</div>
		</div>	
		<div class="row">
			<div class="form-group col-xs-4">
				<label for="name">Prénom:</label>
				<input type="text" class="form-control" id="register_firstname" name="register_firstname" placeholder="Ex : Jean" required>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-xs-4">
				<label for="sel1">Genre:</label>
				<select class="form-control" id="sel1" name="register_gender">
					<option>Homme</option>
					<option>Femme</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-xs-4">
				<label for="email">Addresse email:</label>
				<input type="email" class="form-control" id="register_email" name="register_email" placeholder="Ex : jean.dupont@domaine.be" required>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-xs-4">
				<label for="tel">Numéro de téléphone (optionnel):</label>
				<input type="tel" class="form-control" id="register_phone" name="register_phone" placeholder="Ex : 0474015563">
			</div>
		</div>
		<div class="row">
			<div class="form-group col-xs-4">
				<label for="password">Mot de passe:</label>
				<input type="password" class="form-control" id="register_password" name="register_password" required>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-xs-4">
				<label for="register_password2">Mot de passe (vérification):</label>
				<input type="password" class="form-control" id="register_password2" name="register_password2" required>
			</div>
		</div>
		<br />
		<button name="registration_submit" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> S'inscrire</button>
	</form>
</div>