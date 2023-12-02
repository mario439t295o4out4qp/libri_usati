<?php include "fx/session.php"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>

<body>
<style>
	input::-webkit-calendar-picker-indicator {
              opacity: 100;
           }
.fa-list-alt {
  color: #ccc !important;
  margin-right: 10px;
  font-size: 25px; }
.fa-list-alt:hover {
    color: #279cd8 !important;
    cursor: pointer; }
</style>
	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div class="shadow">
		<div class="jumbotron" id="jumNew">
			<h3>Registra un nuovo Utente</h3>
			<i class="fa fa-times close chiudiJum" aria-hidden="true"></i>
			<form id="formUtente" method="post" class="utente" action="fx/inserisci.php">
				<input type="none" name="tabella" value="utenti" hidden>
				<input type="none" name="campi" value="nome, cognome, telefono, mail, scuola, data" hidden>
				<input type="none" name="redirect" value="/gestionale/utenti.php" hidden>
				<input type="none" name="data" value="<?php echo date(" Y-m-d H:i:s ");?>" hidden>
				<div class="form-group datiUtente">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">ID</span>
						<input type="text" class="form-control smallImp" name="ID" aria-describedby="basic-addon1" disabled>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Cognome</span>
						<input type="text" class="form-control" name="cognome" aria-describedby="basic-addon1">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Nome</span>
						<input type="text" class="form-control" name="nome" aria-describedby="basic-addon1">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Telefono</span>
						<input type="text" class="form-control" name="telefono" aria-describedby="basic-addon1">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Mail</span>
						<input type="text" class="form-control" name="mail" aria-describedby="basic-addon1">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Scuola</span>
      					<input type="text" class="form-control" name="scuola" list="select-list-id"/>
   					</div>
   					<datalist id="select-list-id">
      					<option value="I.I.S. G.Galilei"></option>
      					<option value="I.I.S. Sraffa-Marazzi"></option>
      					<option value="I.I.S. L.Pacioli"></option>
   					</datalist>
				</div>
				<input class="btn btn-default regUtente" type="submit" value="Registra nuovo Utente">
			</form>
		</div>
		<div class="jumbotron" id="jumMod">
			<h3>Modifica anagrafica</h3>
			<i class="fa fa-times close chiudiJum" aria-hidden="true"></i>
			<form id="formUtente" method="post" class="utente" action="fx/modifica.php">
				<div class="form-group datiUtente">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">ID</span>
						<input type="text" class="form-control smallImp" name="ID" aria-describedby="basic-addon1" readonly>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Cognome</span>
						<input type="text" class="form-control" name="cognome" aria-describedby="basic-addon1">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Nome</span>
						<input type="text" class="form-control" name="nome" aria-describedby="basic-addon1">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Telefono</span>
						<input type="text" class="form-control" name="telefono" aria-describedby="basic-addon1">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Mail</span>
						<input type="text" class="form-control" name="mail" aria-describedby="basic-addon1">
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Scuola</span>
						<input type="text" class="form-control" name="scuola" aria-describedby="basic-addon1">
					</div>
				</div>
				<input type="submit" class="btn btn-default regUtente" value="Salva">
				<input type="none" name="tabella" value="utenti" hidden>
				<input type="none" name="campi" value="nome, cognome, telefono, mail, scuola" hidden>
			</form>
		</div>
	</div>

	<div class="container-fluid box">
		<div id="utenti" class="container corpo">

			<h1>Utenti</h1>
			<div class="lettere">
<?php 

	if (isset($_GET[ 'let' ])) $let = $_GET[ 'let' ];
	else $let="A";
	
	for ($i=0; $i<26 ; $i++)
		echo "<a href='/gestionale/utenti.php?let=" . chr($i+65) . "'>" . chr($i+65) . "</a>&nbsp;&nbsp;";
	echo "<a href='/gestionale/utenti.php?let=*'><i class='fa fa-list' aria-hidden='true'></i></a>";
?>
			</div>
			<div class="strumenti">
				<i id="refresh" class="fa fa-refresh ico" aria-hidden="true"></i>
				<i id="apriNew" class="fa fa-plus-circle ico" aria-hidden="true"></i>
				<!-- Filtra -->
				<div id="filtra" class="input-group">
					<input type="text" class="form-control" placeholder="Cerca tra gli Utenti">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cogn. <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li id="1">ID</li>
							<li id="2" class="active">Cogn.</li>
							<li id="3">Nome</li>
							<li id="4">Tel.</li>
							<li id="5">Mail</li>
							<li id="6">Scuola</li>
							<li id="7">Liv.</li>
						</ul>
					</div>
				</div>
				<!-- Ordinamento -->
				<div id="ordina" class="input-group">
					<span class="input-group-btn">
						<button class="btn btn-default" id="go" type="button"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i></button>
					</span>
				
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cogn. <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li id="1" data-type="n">ID</li>
							<li id="2" data-type="t" class="active">Cogn.</li>
							<li id="3" data-type="t">Nome</li>
							<li id="4" data-type="t">Tel.</li>
							<li id="5" data-type="t">Mail</li>
							<li id="6" data-type="t">Scuola</li>
							<li id="7" data-type="n">Liv.</li>
						</ul>
					</div>
				</div>
			</div>

			<table class="table" id="tabUtenti">
				<thead id="head">
					<td style="width:30px">ID</td>
					<td>Cognome</td>
					<td>Nome</td>
					<td>Tel.</td>
					<td >Mail</td>
					<td>Scuola</td>
					<td>Liv.</td>
					<td>Attivo</td>
					<td style="width:50px"></td>
					<td style="width:50px"></td>
					<?php if($privilegi>=2) echo "<td style=\"width:50px\"></td>";?>
					<?php if($privilegi==3) echo "<td style=\"width:50px\"></td>";?>
				</thead>
				<tbody>
				<?php
				connect_DB( $mysqli );
				$campi = "ID, cognome, nome, telefono, mail, scuola, privilegi";
				//$query = "SELECT u.ID, u.nome, u.cognome, u.telefono, u.mail, u.scuola, us.privilegi, us.ID_utenti FROM utenti AS u LEFT JOIN users AS us ON u.ID=us.ID_utenti ORDER BY u.ID ASC";
				$query = "SELECT u.ID, u.nome, u.cognome, u.telefono, u.mail, u.scuola, us.privilegi, us.ID_utenti, a.idu, st.ID_utenti ids, us.attivo FROM utenti AS u ";
				$query .="LEFT JOIN users AS us ON u.ID=us.ID_utenti ";
				$query .="left join standbyusers st on u.ID= st.ID_utenti ";
				$query .= "left join (select distinct ID_utente idu from (select ID_utente from ritiro union select ID_utente from vendita union select ID_utente from prenotazione) b ) a on u.ID = a.idu";
				if ($let != "*")
					$query .= " where left(u.cognome,1)='$let' ORDER BY u.cognome";
				else 
					$query .= " ORDER BY u.ID";
			//
			// ID_utenti segnala la presenza del record in users
			// idu segnala la presenza di almeno un movimento nelle tabelle RITIRO, VENDITA, PRENOTAZIONIE
			// ids segnala la presenza del record in standbyusers		
			
				$mysqli->real_query( $query );
				$res = $mysqli->use_result();

				$campi = explode( ", ", $campi );
				echo "\n";
				while ( $row = $res->fetch_assoc() ) {
					echo "\t\t<tr>\n";
					for ( $i = 0; $i < count( $campi ); $i++ ) echo "\t\t\t<td>" . $row[ $campi[ $i ] ] . "</td>\n";
					if ($row['attivo']==1) echo "<td><i class='fa fa-thumbs-o-up' aria-hidden='true'></i></td>";
					else echo "<td><i class='fa fa-user-times' aria-hidden='true'></i></td>";
					if($privilegi==3 && isset($row[ 'ids' ])) echo "\t\t\t<td><a href='fx/attivazioneG.php?ID=" . $row[ 'ID' ] . "'><i class='fa fa-user-plus' aria-hidden='true'></i></a></td>";
					else if($privilegi==3) echo "\t\t\t<td></td>\n";
					echo "\t\t\t<td><i class=\"fa fa-pencil apriMod\" title=\"anagrafica\"></i></td>"; 
					if ($row[ 'ID' ]!=1 && $row[ 'ID' ] != $_SESSION[ 'ID' ] && !isset($row['idu']) ) // utente non ha fatto movimenti
					    echo "\t\t\t<td><a target=\"blank\" href=\"fx/elimina.php?tabella=utenti&redirect=../utenti.php&ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-times-circle cancellaRiga\" aria-hidden=\"true\"></i></a></td>";
					else
						echo "\t\t\t<td></td>";
					echo "\t\t\t<td style='text-align: left;'><a href=\"ricevute.php?ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-list-alt\" title=\"ricevute\"></i></a>";
					if ($privilegi>=2 && $row[ 'ID' ]!=1 && isset($row[ 'ID_utenti' ])) 
						echo "<a href=\"utente.php?ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-eye\" title=\"scheda\"></i></a>";
					echo "</td>";
					echo "\t\t</tr>\n";
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</body>

</html>