<?php
	$qry = $conn->prepare("SELECT * FROM users WHERE id = ?");	
	$qry->execute(array($_SESSION['id']));
	$info = $qry->fetch();
	$qry->closeCursor();

	$myId = $info['id'];	
	$myEmail = $info['email'];
	$myRole = $info['isSiteAdmin'];
	$myStatus = $info['isEnabled'];
	$myVerification = $info['isVerified'];
	$myName = $info['name'];
	$myFirstname = $info['firstname'];
	$myGender = $info['gender'];
	$myAddress = $info['address'];
	$myPhone = $info['mainPhone'];
	$myBirthDate = $info['birth_date'];
?>

<div class="row">
	<div class="col-sm-12">
		<form name="update_profile" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<div class="form-group">
				<label for="name">iD:</label>
				<input type="text" class="form-control" name="uid" id="uid" value="<?php echo $_SESSION['id'];?>" readonly>
			</div>
			<div class="form-group">
				<label for="profile_name">Nom:</label>
				<input type="text" class="form-control" name="profile_name" id="profile_name" value="<?php echo $myName;?>" required>
			</div>
			<div class="form-group">
				<label for="profile_firstname">Prénom:</label>
				<input type="text" class="form-control" name="profile_firstname" id="profile_firstname" value="<?php echo $myFirstname;?>" required>
			</div>
			<div class="form-group">
				<label for="profile_gender">Genre:</label>
				<select class="form-control" id="profile_gender" name="profile_gender">
					<option <?php if ($myGender == 'Homme') {echo 'selected="selected"';} ?>>Homme</option>
					<option <?php if ($myGender == 'Femme') {echo 'selected="selected"';} ?>>Femme</option>
				</select>
			</div>
			<div class="form-group">
				<label for="profile_birth_date">Date de naissance (optionnel):</label>
				<input type="date" class="form-control" name="profile_birth_date" id="profile_birth_date" value="<?php echo $myBirthDate;?>">
			</div>
			<div class="form-group">
				<label for="profile_email">Adresse email:</label>
				<input type="email" class="form-control" name="profile_email" id="profile_email" value="<?php echo $myEmail;?>" required>
			</div>
			<div class="form-group">
				<label for="profile_address">Adresse postale:</label>
				<textarea class="form-control" name="profile_address" rows="4" id="profile_address"><?php echo $myAddress;?></textarea>
			</div>
			<div class="form-group">
				<label for="profile_phone">Numéro de téléphone principal (optionnel):</label>
				<input type="text" class="form-control" name="profile_phone" id="profile_phone" value="<?php echo $myPhone;?>">
			</div>
			<div class="checkbox">
				<label><input type="checkbox" name="isVerified" id="isVerified"  value="isVerified" <?php if ($myVerification == "1"){echo "checked";}?> disabled>email vérifié</label>
			</div>
			<div class="checkbox">
				<label><input type="checkbox" name="isSiteAdmin" id="isSiteAdmin"  value="isSiteAdmin" <?php if ($myRole == "1"){echo "checked";} ?> disabled>Administrateur</label>
			</div>
			<div class="checkbox">
				<label><input type="checkbox" name="isEnabled" id="isEnabled"  value="isEnabled" <?php if ($myStatus == "1"){echo "checked";}?> disabled>Compte activé</label>
			</div>
			<button name="update_profile_submit" type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-floppy-save"></span> Mettre à jour</button>
		</form>
	</div>
</div>