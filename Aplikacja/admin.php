<?php
	include('php/top.php');
?>
<div id="big_contener">
	<div id="left_contener">
		<div class="left_menu">
			<h3>Administracja</h3>
			<ul>
<?php
				foreach ($my_siteTitle->admin_links() as $link => $title) {
					echo '<li><a ';
					if (isset($_GET['subPage']) && $link == $_GET['subPage'])
						echo 'class="act" ';
					echo 'href="'.my_getFilename::normal().'?subPage='.$link.'">'.$title.'</a></li>';
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
?>
						
<?php
				break;
			}
		}
?>
	</div>
</div>
<?php
	include('php/bottom.php');
?>
