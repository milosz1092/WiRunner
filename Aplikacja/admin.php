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
							<h1 style="margin-bottom:20px;">Utwórz rywalizację</h1>
							
<?php
							break;
						}
					}
					else {

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
