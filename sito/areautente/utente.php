<?php 
include "./fx/session.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>

<body>

	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div class="container-fluid box">
		<div id="utente" class="container corpo">

			<h1>Utente</h1>
			
			<div class="profilo">
				<img src="<?php echo $img; ?>">

				<?php
				echo "<p><b>Nickname:</b> " . $nickname . "</p>".
					 "<p><b>Nominativo:</b> " . $nome . " " . $cognome . "</p>".
					 "<p><b>Telefono:</b> " . $telefono . "</p>".
					 "<p><b>Mail:</b> " . $mail . "</p>".
					 "<p><b>Scuola:</b> " . $scuola . "</p>";
				?>
			</div>
			
			<div class="modifica">
				<h3>Modifica dati</h3>
				<i class="fa fa-angle-down riduci" aria-hidden="true"></i>
				<form class="formMod" method="post" action="fx/modifica.php">
					<div class="form-group datiUtente">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Nome</span>
							<input type="text" class="form-control" name="nome" aria-describedby="basic-addon1" value="<?php echo $nome; ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Cognome</span>
							<input type="text" class="form-control" name="cognome" aria-describedby="basic-addon1" value="<?php echo $cognome; ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Telefono</span>
							<input type="text" class="form-control" name="telefono" aria-describedby="basic-addon1" value="<?php echo $telefono ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Mail</span>
							<input type="text" class="form-control" name="mail" aria-describedby="basic-addon1" value="<?php echo $mail; ?>" readonly>
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">scuola</span>
							<input type="text" class="form-control" name="scuola" aria-describedby="basic-addon1" value="<?php echo $scuola; ?>">
						</div>
					</div>
					<input type="none" name="tabella" value="utenti" hidden>
					<input type="none" name="ID" value="<?php echo $ID; ?>" hidden>
					<input type="none" name="campi" value="nome, cognome, telefono, mail, scuola" hidden>
					<input type="submit" class="btn btn-default regUtente" value="Salva">
				</form>
			</div>
			
			<div class="modificaPw">
				<h3>Modifica Password</h3>
				<i class="fa fa-angle-down riduci" aria-hidden="true"></i>
				<form class="formMod" method="post" action="fx/modifica.php">
					<div class="form-group datiUtente">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Vecchia Password</span>
							<input type="password" class="form-control" name="vpassword" aria-describedby="basic-addon1">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Nuova Password</span>
							<input type="password" class="form-control" name="password" aria-describedby="basic-addon1">
						</div>
					</div>
					<input type="none" name="tabella" value="users" hidden>
					<input type="none" name="ID" value="<?php echo $IDu; ?>" hidden>
					<input type="none" name="campi" value="password" hidden>
					<input type="submit" class="btn btn-default regUtente" value="Cambia Password">
				</form>
			</div>
			
			<script>
				function printAlert(type, text) {
					$('body').append("<div class=\"alert alert-" + type + "\">" + text + "</div>");
					$('.alert').fadeOut(4000, function () {
						$('.alert').remove();
					});
				}
			</script>
			
			<?php if(isset($_GET['error'])) echo "<script type='text/javascript'>printAlert('danger', '<b>Attenzione!</b><br>La vecchia Password non corrisponde');</script>"; ?>


		</div>
	</div>

	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>