			<div class="row">
				<div class="col-sm-12">
					<h3>Liste des utilisateurs enregistrés</h3>
					<div class="table-responsive">
						<table class="table table-condensed  table-hover table-striped">
							<tr>
								<th>ID</th>
								<th>Nom</th>
								<th>Prénom</th>
								<th>Genre</th>
								<th>email</th>
								<th>Téléphone</th>
								<th>Naissance</th>
								<th>Inscription</th>
								<th>Dernière visite</th>
								<th>Vérifié</th>
								<th>Admin</th>
								<th>Activé</th>
							</tr>
							<?php
								try {
									// EDIT THE QUERY AND MIND THE PROPERTIES ORDER
									$stmt = $conn->prepare("SELECT * FROM users"); 
									$stmt->execute();
									while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
										echo "<tr>";
										echo "<td>" . $row['id'] . "</td>";
										echo "<td>" . $row['name'] . "</td>";
										echo "<td>" . $row['firstname'] . "</td>";
										echo "<td>" . $row['gender'] . "</td>";
										echo "<td>" . $row['email'] . "</td>";
										echo "<td>" . $row['mainPhone'] . "</td>";
										echo "<td>" . $row['birth_date'] . "</td>";
										echo "<td>" . $row['registration_date'] . "</td>";
										echo "<td>" . $row['last_login_date'] . "</td>";
										echo "<td>" . $row['isSiteAdmin'] . "</td>";
										echo "<td>" . $row['isVerified'] . "</td>";
										echo "<td>" . $row['isEnabled'] . "</td>";
										echo "</tr>";
									}							
									$stmt->closeCursor();
								}
								catch(PDOException $e) {
									echo "Error: " . $e->getMessage();
								}
								
								$stmt->closeCursor();
							?>
						</table>			
					</div>
				</div>
			</div>