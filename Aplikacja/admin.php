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
							case 'edit':
								$rivInfo = $my_Rivalry->show($_GET['rId']);
								$startTable = explode(' ', $rivInfo['data_startu']);;
								$endTable = explode(' ', $rivInfo['data_konca']);;
?>
								<header class="entry-header">
									<h1 class="entry-title">Edytuj rywalizację</h1>
								</header>
<?php
								if (isset($_POST['rywEdit'])) {
									if ($my_Rivalry->edit($_POST))
										header('Location: '.$_SERVER['REQUEST_URI'].'&msg=justOkRywEdit');
									else
										header('Location: '.$_SERVER['REQUEST_URI'].'&msg=justWrongRywEdit');
								}

								if (isset($_GET['msg'])) {
									if ($_GET['msg'] == 'justOkRywEdit')
										echo '<div class="ok_msg">Rywalizacja została edytowana!</div>';
									else if ($_GET['msg'] == 'justWrongRywEdit')
										echo '<div class="wrong_msg">Nie udało się edytować rywalizacji!</div>';
								}
?>
								<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" autocomplete="off">
									
									<ul class="form_field">
										<input type="text" name="rywEdit_id" value="<?php echo $_GET['rId']; ?>" hidden />
										<li>
											<label for="rywStart_date">Rozpoczęcie</label>
											<input type="date" value="<?php echo $startTable[0]; ?>" id="rywStart_date" name="rywStart_date" required="required" />
											<input type="time" value="<?php echo $startTable[1]; ?>" id="rywStart_time" name="rywStart_time" required="required" />
										</li>
										<li>
											<label for="rywStop_date">Zakończenie</label>
											<input type="date" value="<?php echo $endTable[0]; ?>" id="rywStop_date" name="rywStop_date" required="required" />
											<input type="time" value="<?php echo $endTable[1]; ?>" id="rywStop_time" name="rywStop_time" required="required" />
										</li>
										<li>
											<label for="rywName">Nazwa</label>
											<input type="text" value="<?php echo $rivInfo['nazwa_rywalizacji']; ?>" id="rywName" name="rywName" required="required" />
										</li>
										<li>
											<label for="rywSport">Dyscyplina</label>
											<select required="required" name="rywSport" id="rywSport">
												<option></option>
<?php
												foreach($my_simpleDbCheck->getSports() as $row) {
													echo '<option ';
													if ($row['id_sportu'] == $rivInfo['nr_sportu'])
														echo 'selected="selected" ';
													echo 'value="'.$row['id_sportu'].'">'.$row['nazwa_sportu'].'</option>';
												}

?>
											</select>
										</li>
										<li style="height:250px;">
											<label for="rywInfo">Więcej informacji</label>
											<textarea style="display:block;margin-bottom:10px;width:500px;height:200px;" name="rywInfo" id="rywInfo" rows="10" cols="80"><?php echo $rivInfo['opis_rywalizacji']; ?></textarea>
										</li>
									</ul>
									<ul>
										<li style="margin-top:20px;">
											<input type="submit" name="rywEdit" value="Edytuj" />
<?php
											echo '<input type="button" value="Usuń" onclick="delRiv('.$_GET['rId'].', '.$_SESSION['WiRunner_policy'].', \'edit\')" />';
?>
										</li>
									</ul>
								</form>
								<script>
									$("#rywStart_date").focus();
								</script>
<?php
							break;
							case 'showRyw':
								$rivInfo = $my_Rivalry->show($_GET['rId']);
								$startTable = explode(' ', $rivInfo['data_startu']);;
								$endTable = explode(' ', $rivInfo['data_konca']);;
?>
								<header class="entry-header">
									<h1 class="entry-title">Wyświetlanie rywalizacji</h1>
								</header>

								<form action="" method="post" autocomplete="off">
									
									<ul class="form_field">
										<li>
											<label for="rywStart_date">Rozpoczęcie</label>
											<input type="date" value="<?php echo $startTable[0]; ?>" id="rywStart_date" name="rywStart_date" required="required" disabled/>
											<input type="time" value="<?php echo $startTable[1]; ?>" id="rywStart_time" name="rywStart_time" required="required" disabled/>
										</li>
										<li>
											<label for="rywStop_date">Zakończenie</label>
											<input type="date" value="<?php echo $endTable[0]; ?>" id="rywStop_date" name="rywStop_date" required="required" disabled/>
											<input type="time" value="<?php echo $endTable[1]; ?>" id="rywStop_time" name="rywStop_time" required="required" disabled/>
										</li>
										<li>
											<label for="rywName">Nazwa</label>
											<input type="text" value="<?php echo $rivInfo['nazwa_rywalizacji']; ?>" id="rywName" name="rywName" required="required" disabled/>
										</li>
										<li>
											<label for="rywSport">Dyscyplina</label>
											<select required="required" name="rywSport" id="rywSport" disabled>
												<option></option>
<?php
												foreach($my_simpleDbCheck->getSports() as $row) {
													echo '<option ';
													if ($row['id_sportu'] == $rivInfo['nr_sportu'])
														echo 'selected="selected" ';
													echo 'value="'.$row['id_sportu'].'">'.$row['nazwa_sportu'].'</option>';
												}

?>
											</select>
										</li>
										<li style="height:250px;">
											<label for="rywInfo">Więcej informacji</label>
											<textarea style="display:block;margin-bottom:10px;width:500px;height:200px;" name="rywInfo" id="rywInfo" rows="10" cols="80" disabled><?php echo $rivInfo['opis_rywalizacji']; ?></textarea>
										</li>
									</ul>
									<ul>
										<li style="margin-top:20px;">
<?php
											echo '<input type="button" value="Usuń" onclick="delRiv('.$_GET['rId'].', '.$_SESSION['WiRunner_policy'].', \'show\')" />';
											echo '<input type="button" value="Edytowanie" onclick="document.location.href=\'admin.php?subPage=rywalizacje&action=edit&rId='.$_GET['rId'].'\'" />';
?>
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
							<h1 class="entry-title">Dodane rywalizacje</h1>
						</header>
						<div>
<?php
						if (isset($_GET['msg'])) {
							if ($_GET['msg'] == 'justDelRyw')
								echo '<div class="ok_msg">Rywalizacja została usunięta!</div>';
						}
						
						foreach($my_Rivalry->showAll() as $row)  {
							echo '<div id="row'.$row['id_rywalizacji'].'" class="rywRow_header">';
								echo '<div class="rywTitle_header"><a href="admin.php?subPage=rywalizacje&action=showRyw&rId='.$row['id_rywalizacji'].'">'.$row['nazwa_rywalizacji'].'</a></div>';
								echo '<div style="margin-top:10px;" class="rywAction_header">';
									echo '<input type="button" value="Usuń" onclick="delRiv('.$row['id_rywalizacji'].', '.$_SESSION['WiRunner_policy'].', \'list\')" />';
									echo '<input type="button" value="Edytuj" onclick="document.location.href=\'admin.php?subPage=rywalizacje&action=edit&rId='.$row['id_rywalizacji'].'\'" />';
								echo '</div>';
							echo '</div>';
						}
?>
						</div>
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
