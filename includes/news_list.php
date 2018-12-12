			<div class="row">
				<div class="col-sm-12">
					<h3>Liste des news</h3>
					<div class="table-responsive">
						<table class="table table-condensed  table-hover table-striped">
						<tr>
							<th>ID</th>
							<th>Contenu</th>
							<th>Type</th>
							<th>Date de création</th>
							<th>Date d'expiration</th>
						</tr>
						<?php
							try {
								// EDIT THE QUERY AND MIND THE PROPERTIES ORDER
								$stmt = $conn->prepare("SELECT * FROM news"); 
								$stmt->execute();
								while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
									echo "<tr>";
									echo "<td>" . $row['id'] . "</td>";
									echo "<td>" . $row['content'] . "</td>";
									echo "<td>" . $row['level'] . "</td>";
									echo "<td>" . $row['creation_date'] . "</td>";
									echo "<td>" . $row['expiration_date'] . "</td>";
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
						<hr />
					</div>
				</div>
			</div>