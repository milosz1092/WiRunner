<?php
	include('php/top.php');

$daneUserow = $my_simpleDbCheck->getUsersInfo();
$fp = fopen("php/users.txt",'w');
foreach($daneUserow as $ele){
	fwrite($fp, $ele['id_usera'] . '|' . $ele['imie'] . '|' . $ele['nazwisko'] . '|' . $ele['miejscowosc'] ."\n");
}

fclose($fp);

?>
		<article>
			<section>
					<script>
						$(function()
							{
								$("input[name='fraza']").keyup(function()
								{
									var fraza = $("input[name='fraza']").val();
									fraza = fraza.replace(/\s/g, '%20' );
									$("#podpowiedzi").load('php/search-suggestions.php?fraza='+fraza+'&ads');
								});
				
				
								$("body").on("click", "span", function()
								{
									$("input[name='fraza']").val($(this).text());
								});
							});
					</script>

					<form name="wyszukiwarka">
						Szukaj: <input  autocomplete="off" name="fraza" type="text" maxlength="30">
						<div style="margin-top: 10px; padding: 5px; border:1px solid; width: 300px; min-height: 20px;" id="podpowiedzi">Znajdź swoich znajomych!</div>
					</form>
			</section>
		</article>
<?php
	include('php/bottom.php');
?>
