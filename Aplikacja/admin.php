<?php
	include('php/top.php');
?>
<div id="big_contener">
	<div id="left_contener">
		<div class="left_menu">
			<h3>Administracja</h3>
			<ul>
<?php
				foreach ($my_siteTitle->admin_links() as $link => $pack) {
					echo '<li><a ';
					if (isset($_GET['subPage']) && $link == $_GET['subPage'])
						echo 'class="act" ';
					echo 'href="'.my_getFilename::normal().'?subPage='.$link.'">'.$pack[0].'</a></li>';

					if ($pack[1] != NULL) {
						echo '<ul class="actionLinks">';
						foreach ($pack[1] as $actionLink => $actionName) {
							echo '<li><a ';
							if (isset($_GET['action']) && $actionLink == $_GET['action'])
								echo 'class="act" ';
							echo 'href="'.my_getFilename::normal().'?subPage='.$link.'&action='.$actionLink.'">'.$actionName.'</a></li>';
						}
						echo '</ul>';
					}
				}
?>
			</ul>
		</div>
	</div>
	<div id="right_contener">
<?php
		if (isset($_GET['subPage'])) {
			switch($_GET['subPage']) {
				case 'rywalizacje':
					if (isset($_GET['action'])) {
						switch($_GET['action']) {
							case 'add':
?>
								<header class="entry-header">
									<h1 class="entry-title">Utwórz rywalizację</h1>
								</header>
<?php
								if (isset($_POST['rywAdd'])) {
									if ($my_Rivalry->add($_POST))
										echo '<div class="ok_msg">Rywalizacja została dodana!</div>';
									else
										echo '<div class="wrong_msg">Nie udało się dodać rywalizacji!</div>';
								}
?>
								<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" autocomplete="off">
									<ul class="form_field">
										<li>
											<label for="rywStart_date">Rozpoczęcie</label>
											<input type="date" id="rywStart_date" name="rywStart_date" required="required" />
											<input type="time" id="rywStart_time" name="rywStart_time" required="required" />
										</li>
										<li>
											<label for="rywStop_date">Zakończenie</label>
											<input type="date" id="rywStop_date" name="rywStop_date" required="required" />
											<input type="time" id="rywStop_time" name="rywStop_time" required="required" />
										</li>
										<li>
											<label for="rywName">Nazwa</label>
											<input type="text" id="rywName" name="rywName" required="required" />
										</li>
										<li>
											<label for="rywSport">Dyscyplina</label>
											<select required="required" name="rywSport" id="rywSport">
												<option></option>
<?php
												foreach($my_simpleDbCheck->getSports() as $row) {
													echo '<option value="'.$row['id_sportu'].'">'.$row['nazwa_sportu'].'</option>';
												}

?>
											</select>
										</li>
										<li>
											<label for="rywTroph">Nagroda</label>
											<input type="text" id="rywTroph" name="rywTroph" />
										</li>
										<li style="height:250px;">
											<label for="rywInfo">Więcej informacji</label>
											<textarea style="display:block;margin-bottom:10px;width:500px;height:200px;" name="rywInfo" id="rywInfo" rows="10" cols="80"></textarea>
										</li>
									</ul>
									<ul>
										<li style="margin-top:20px;">
											<input type="submit" name="rywAdd" value="Utwórz" />
										</li>
									</ul>
								</form>
								<script>
									$("#rywStart_date").focus();
								</script>
							
<?php
							break;
						}
					}
					else {
?>
						<header class="entry-header">
							<h1 class="entry-title">Twoje rywalizacje</h1>
						</header>

<?php
					}
				break;
			}
		}
?>
	</div>
</div>
<?php
	include('php/bottom.php');
?>
