	<div class="row">
		<div class="col-sm-12">
			<form name="update_news_form" action="<?php echo htmlspecialchars('admin_news.php');?>" method="post">
				<div class="form-group">
					<label for="id">iD:</label>
					<input type="text" class="form-control" name="uid" id="uid" value="<?php echo $result["id"];?>" readonly>
				</div>
				<div class="form-group">
					<label for="update_news_content">Contenu:</label>
					<input type="text" class="form-control" name="update_news_content" id="update_news_content" value="<?php echo $result["content"];?>" required>
				</div>
				<div class="form-group">
					<label for="update_news_level">Type de news:</label>
					<select class="form-control" id="update_news_level" name="update_news_level">
						<option <?php if ($result["level"] == 'info') {echo 'selected="selected"';} ?>>Information (bleu)</option>
						<option <?php if ($result["level"] == 'warning') {echo 'selected="selected"';} ?>>Attention (orange)</option>
						<option <?php if ($result["level"] == 'danger') {echo 'selected="selected"';} ?>>Danger (rouge)</option>
					</select>
				</div>
				<div class="form-group">
					<label for="update_news_creation">Date de création:</label>
					<input type="date" class="form-control" name="update_news_creation" id="update_news_creation" value="<?php echo $result["creation_date"];?>" disabled>
				</div>
				<div class="form-group">
					<label for="update_news_expiration">Date d'expiration:</label>
					<input type="date" class="form-control" name="update_news_expiration" id="update_news_expiration" value="<?php echo $result["expiration_date"];?>">
				</div>				
				<button name="update_news_submit" type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-floppy-save"></span> Mettre à jour</button>
				<button name="delete_news_submit" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer</button>
			</form>
		</div>
	</div>