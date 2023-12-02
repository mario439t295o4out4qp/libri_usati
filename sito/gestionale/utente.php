<?php
$privilegiP=3;
include "fx/session.php";
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
			
			<?php
			
			$u_ID=$_GET['ID'];
			
			connect_DB( $mysqli );
			$query = "SELECT u.ID, u.nome, u.cognome, u.telefono, u.mail, u.scuola, u.data, us.privilegi, us.nickname, us.sede, us.ID AS IDu, us.attivo FROM utenti as u INNER JOIN users as us ON u.ID=us.ID_utenti WHERE u.ID=$u_ID";

			$mysqli->real_query( $query );
			$res = $mysqli->use_result()->fetch_assoc();
			
			$u_IDu=$res['IDu'];
			$u_nome=$res['nome'];
			$u_cognome=$res['cognome'];
			$u_telefono=$res['telefono'];
			$u_mail=$res['mail'];
			$u_scuola=$res['scuola'];
			$u_nick=$res['nickname'];
			$u_privilegi=$res['privilegi'];
			$u_sede=$res['sede'];
			$u_act_f="";$u_act_t="";
			if ($res['attivo']==1) $u_act_t="selected"; else $u_act_f="selected"; 
			$priv_adm=""; $priv_ges=""; $priv_usr="";
			if ($u_privilegi==1) $priv_usr="selected";
			if ($u_privilegi==2) $priv_ges="selected";
			if ($u_privilegi==3) $priv_adm="selected";
			$sede_g = ""; $sede_p="";
			if ($u_sede==1) $sede_g="selected";
			if ($u_sede==3) $sede_p="selected";
			
			$u_data= new DateTime($res['data']);
			$u_img=strtolower("../imgs/users/".$u_nome.$u_cognome.".jpg");
			if(!file_exists($u_img)) $u_img="../imgs/users/user.jpg";
			
			if ($u_IDu == $_SESSION[ 'ID' ] || $_SESSION[ 'privilegi' ]==3) $changepw = "" ; else $changepw="disabled";
			if ($_SESSION[ 'privilegi' ]==3) {
				$privilegi = "" ;
				$campi = "nickname, privilegi, sede, attivo";
			}
			else {
				$privilegi="disabled";
				$campi = "nickname, sede, attivo";
			}
			?>
			
			<div class="profilo">
				<table><tr><td>
				<img src="<?php echo $u_img; ?>">
				</td><td>
				<?php
				echo "<p><b>Cognome:</b> " . $u_cognome . "</p>".
					 "<p><b>Nome:</b> " . $u_nome . "</p>".			 
					 "<p><b>Telefono:</b> " . $u_telefono . "</p>".
					 "<p><b>Mail:</b> " . $u_mail . "</p>".
					 "<p><b>Scuola:</b> " . $u_scuola . "</p>".
					 "<p><b>Data iscrizione:</b> " . date_format($u_data, 'd/m/Y') . "</p>";
				if ($u_privilegi != 1)
					 echo "<p><b>Sede lavoro:</b> (" . $u_sede . ") " . getSede($u_sede) . "</p>";
				?>
				</td></tr></table>
			</div>
			
			<div class="modifica">
				<h3>Modifica dati</h3>
				<i class="fa fa-angle-down riduci" aria-hidden="true"></i>
				<form class="formMod" method="post" action="fx/modifica.php">
					<div class="form-group datiUtente">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Cognome</span>
							<input type="text" class="form-control" name="cognome" aria-describedby="basic-addon1" value="<?php echo $u_cognome; ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Nome</span>
							<input type="text" class="form-control" name="nome" aria-describedby="basic-addon1" value="<?php echo $u_nome; ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Telefono</span>
							<input type="text" class="form-control" name="telefono" aria-describedby="basic-addon1" value="<?php echo $u_telefono ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Mail</span>
							<input type="text" class="form-control" name="mail" aria-describedby="basic-addon1" value="<?php echo $u_mail; ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Scuola</span>
							<input type="text" class="form-control" name="scuola" aria-describedby="basic-addon1" value="<?php echo $u_scuola; ?>">
						</div>
					</div>
					<input type="none" name="tabella" value="utenti" hidden>
					<input type="none" name="ID" value="<?php echo $u_ID; ?>" hidden>
					<input type="none" name="campi" value="nome, cognome, telefono, mail, scuola" hidden>
					<input type="submit" class="btn btn-default regUtente" value="Salva">
				</form>
			</div>
			
			<div class="modificaPw">
				<h3>Modifica Credenziali</h3>
				<i class="fa fa-angle-down riduci" aria-hidden="true"></i>
				<form class="formMod" method="post" action="fx/modifica.php">
					<div class="form-group datiUtente row">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Nickname</span>
							<input type="text" class="form-control" name="nickname" aria-describedby="basic-addon1" value="<?php echo $u_nick; ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon modPass" id="basic-addon1">Nuova Password</span>
							<input class="inputDisabled form-control" type="password" name="password" aria-describedby="basic-addon1" <?php echo $changepw; ?> >
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Profilo web</span>
							<select class="form-control" name="attivo" aria-describedby="basic-addon1">
								<option value="1" <?php echo $u_act_t; ?> >Abilitato</option>
								<option value="0" <?php echo $u_act_f; ?> >Disattivo</option>
							</select>
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Livello Privilegi</span>
							<select class="form-control" name="privilegi" aria-describedby="basic-addon1">
								<option value="1" <?php echo $priv_usr; ?> >Utente</option>
								<option value="2" <?php echo $priv_ges; ?> >Gestione</option>
								<option value="3" <?php echo $priv_adm; ?> >Admin</option>
							</select>
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">Sede di lavoro</span>
							<select class="form-control" name="sede" aria-describedby="basic-addon1">
								<option value="1" <?php echo $sede_g; ?> >Galilei</option>
								<option value="3" <?php echo $sede_p; ?> >Pacioli</option>
								<option value="7" disabled >Consult.</option>
							</select>
						</div>
					</div>
					<input type="none" name="tabella" value="users" hidden>
					<input type="none" name="ID" value="<?php echo $u_IDu; ?>" hidden>
					<input type="none" name="IDu" value="<?php echo $u_ID; ?>" hidden>
					<input type="none" name="campi" value="<?php echo $campi; ?>" hidden>
					<input type="submit" class="btn btn-default regUtente" value="Salva modifiche">
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