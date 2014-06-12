<?php
	include('php/top.php');
?>
		<article>
			<section>
				<div class="left_part"  style="width:500px;margin-right:70px;">
					<header class="entry-header hr_bor">
						<h1 class="entry-title">Kontakt</h1>
					</header>
<?php

					if ($_SESSION['WiRunner_log_id'] > 0) {
						if (isset($_POST['send_msg'])) {
							$my_Poster->sendMsg($_POST);
						}
						echo( $my_activities->validateDate("2014-04-04 23:23:23"));
?>
						<form id="writeMsg" name="writeMsg" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
							<ul class="form_field">
								<input type="text" name="ToUid_msg" value="1" hidden />
								<input type="text" name="FromUid_msg" value="<?php echo $_SESSION['WiRunner_log_id']; ?>" hidden />
								<li>
									<label style="width:60px;" for="to_msg">Adresat</label>
									<input style="width:300px;" type="text" id="to_msg" name="to_msg" required="required" value="Administrator" disabled />
								</li>
								<li>
									<label style="width:60px;" for="title_msg">Tytuł</label>
									<input style="width:300px;" type="text" id="title_msg" name="title_msg" required="required" />
								</li>
								<li style="height:250px;">
									<textarea required="required" style="display:block;margin-bottom:10px;width:500px;height:200px;" name="content_msg" id="content_msg" rows="10" cols="80"></textarea>
								</li>
							</ul>
							<ul>
								<li><input style="margin-left:10px;" type="submit" name="send_msg" id="send_msg" value="Wyślij wiadomość"/></li>
							</ul>			
						</form>
						<script>
							$("#title_msg").focus();
						</script>
<?php
					}
					else {
?>
						<p>Napisz do jednego z administratorów:</p>
						<ul>
							<li style="margin-bottom:10px;"><a href="mailto:lstaniszczak@wi.zut.edu.pl">Łukasz Staniszczak</a><br />lstaniszczak@wi.zut.edu.pl</li>
							<li style="margin-bottom:10px;"><a href="mailto:jsuszczewicz@wi.zut.edu.pl">Jarek Suszczewicz</a><br />jsuszczewicz@wi.zut.edu.pl</li>
							<li style="margin-bottom:10px;"><a href="mailto:tpietrzak@wi.zut.edu.pl">Tomasz Pietrzak</a><br />tpietrzak@wi.zut.edu.pl</li>
							<li style="margin-bottom:10px;"><a href="mailto:miszewczyk@wi.zut.edu.pl">Miłosz Szewczyk</a><br />miszewczyk@wi.zut.edu.pl</li>
						</ul>	
<?php
					}
?>
				</div>
				<div class="right_part">
				
				</div>
			</section>
		</article>
<?php
	include('php/bottom.php');
?>
