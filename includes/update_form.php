	<div class="row">
		<div class="col-sm-12">
			<form name="update_form" action="<?php echo htmlspecialchars('admin_users.php');?>" method="post">
				<div class="form-group">
					<label for="name">iD:</label>
					<input type="text" class="form-control" name="uid" id="uid" value="<?php echo $result["id"];?>" readonly>
				</div>
				<div class="form-group">
					<label for="update_name">Nom:</label>
					<input type="text" class="form-control" name="update_name" id="update_name" value="<?php echo $result["name"];?>" required>
				</div>
				<div class="form-group">
					<label for="update_firstname">Prénom:</label>
					<input type="text" class="form-control" name="update_firstname" id="update_firstname" value="<?php echo $result["firstname"];?>" required>
				</div>
				<div class="form-group">
					<label for="update_gender">Genre:</label>
					<select class="form-control" id="update_gender" name="update_gender">
						<option <?php if ($result["gender"] == 'Homme') {echo 'selected="selected"';} ?>>Homme</option>
						<option <?php if ($result["gender"] == 'Femme') {echo 'selected="selected"';} ?>>Femme</option>
					</select>
				</div>
				<div class="form-group">
					<label for="birth_date">Date de naissance:</label>
					<input type="date" class="form-control" name="birth_date" id="birth_date" value="<?php echo $result["birth_date"];?>">
				</div>
				<div class="form-group">
					<label for="update_email">Adresse email:</label>
					<input type="email" class="form-control" name="update_email" id="update_email" value="<?php echo $result["email"];?>" required>
				</div>
				<div class="form-group">
					<label for="registration_date">Date d'inscription:</label>
					<input type="date" class="form-control" name="registration_date" id="registration_date" value="<?php echo $result["registration_date"];?>" disabled>
				</div>
				<div class="form-group">
					<label for="last_login_date">Dernière visite:</label>
					<input type="date" class="form-control" name="last_login_date" id="last_login_date" value="<?php echo $result["last_login_date"];?>" disabled>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="isVerified" id="isVerified"  value="isVerified" <?php if ($result["isVerified"] == "1"){echo "checked";}?> disabled>email vérifié</label>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="isSiteAdmin" id="isSiteAdmin"  value="isSiteAdmin" <?php if ($result["isSiteAdmin"] == "1"){echo "checked";}?> >Administrateur</label>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="isEnabled" id="isEnabled"  value="isEnabled" <?php if ($result["isEnabled"] == "1"){echo "checked";}?> >Compte activé</label>
				</div>
				<button name="update_submit" type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-floppy-save"></span> Mettre à jour</button>
				<button name="delete_user_submit" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer</button>
			</form>
		</div>
	</div>