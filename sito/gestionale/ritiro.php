<?php include "fx/session.php"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>
<style>
</style>
<body>

<!-- NAVBAR -->
<?php include "elements/navbar.php"; ?>

<div class="shadow">
	<div id="jumRub" class="jumbotron">
		<h3>Seleziona utente</h3>
		<i class="fa fa-times close chiudiJum" aria-hidden="true"></i>
		<div class="lettere">
<?php 

	if (isset($_GET[ 'let' ])) $let = $_GET[ 'let' ];
	else $let="A";
	
	for ($i=0; $i<26 ; $i++)
		echo "<a class='filtraLettera'>" . chr($i+65) . "</a>&nbsp;&nbsp;";
	echo "<a class='filtraLettera'><i class='fa fa-list' aria-hidden='true'></i></a>";
?>
		</div>

		<div class="input-group cerca">
			<input type="text" class="form-control" id="cercaBar" name="cerca" placeholder="Cerca nella rubrica" aria-describedby="basic-addon1">
			<span class="input-group-btn">
			<button class="btn btn-default" id="cerca" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
			</span>
		</div>

		<div class="scroll">
		<table class="table tableRubrica" id="elencoUtenti">
			<thead>
				<td style="width:50px;">ID</td>
				<td>Cognome</td>
				<td>Nome</td>
				<td>Telefono</td>
				<td style="width:300px;">Mail</td>
				<td>scuola</td>
				<td style="width:50px;">Sel.</td>
			</thead>
			<tbody>
			<?php
			  connect_DB($mysqli);
			  $campi="ID, cognome, nome, telefono, mail, scuola";
			  $query="SELECT $campi FROM utenti ORDER BY cognome, nome, ID DESC";
			  $mysqli->real_query($query);
			  $res = $mysqli->use_result();

			  $campi=explode(", ", $campi);
			  echo"\n";
			  while ($row = $res->fetch_assoc()) {
				$lett = strtoupper(substr($row['cognome'],0,1));
				echo "\t\t<tr cognome='$lett'>\n";
				for($i=0; $i<count($campi); $i++) echo "\t\t\t<td style='text-align:left'>".$row[$campi[$i]] . "</td>\n";
				echo "\t\t\t<td><i class=\"fa fa-plus-circle seleziona-utente\" aria-hidden=\"true\"></i></td>";
				echo "\t\t</tr>\n";
			  }
			?>
			</tbody>
		</table>
		</div>
	</div>
</div>

<div class="container-fluid box">
	<div class="container corpo" id="ritiro">
		<h1>Ritiro</h1>
		<button class="btn btn-default regLibri" id="btnSalva" type="button">Chiudi e registra nuovi libri</button>

		<form id="formUtente" method="post" class="utente">
			<div class="top-utente">
				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1">ID</span>
				  <input type="text" class="form-control ID" name="ID" aria-describedby="basic-addon1" disabled>
				</div>
				<i id="pulisci" class="fa fa-times-circle ico" aria-hidden="true"></i>
				<!--<i id="aggiungi" class="fa fa-plus-circle ico" aria-hidden="true"></i>-->
				<i id="apriRub" class="fa fa-user ico" aria-hidden="true"></i>
				<i id="riduci" class="fa fa-angle-up ico" aria-hidden="true"></i>
				</div>
				<div class="form-group datiUtente">
				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1">Cognome</span>
				  <input type="text" class="form-control" name="cognome" aria-describedby="basic-addon1" readonly>
				</div>
				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1">Nome</span>
				  <input type="text" class="form-control" name="nome" aria-describedby="basic-addon1" readonly>
				</div>
				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1">Telefono</span>
				  <input type="text" class="form-control" name="telefono" aria-describedby="basic-addon1" readonly>
				</div>
				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1">Mail</span>
				  <input type="text" class="form-control" name="mail" aria-describedby="basic-addon1" readonly>
				</div>
				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1">Scuola</span>
				  <input type="text" class="form-control" name="scuola" aria-describedby="basic-addon1" readonly>
				</div>
				<input type="none" name="tabella" value="utenti" hidden>
       			<input type="none" name="privilegi" value="1" hidden>
        		<input type="none" name="campi" value="nome, cognome, telefono, mail, scuola, privilegi" hidden>
			</div>
		</form>

		<div class="input-group ISBN">
		  <input type="text" class="form-control" name="ISBN" placeholder="Inserisci ISBN" aria-describedby="basic-addon1">
		  <span class="input-group-btn">
			<button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
		  </span>
		  
		  <span id="prnRicevute" style="margin-left: 20px;visibility:hidden">
		  <a id="lnkRicevute" target="_blank" href="#"><i class="fa fa-print" style="color:red!important;" aria-hidden="true"></i></a>
		  </span>

		</div>
		
		<input type="hidden" id="sede" name="SEDE" value="<?php echo $_SESSION['sede'] ?>">

		<table class="table" id="nuoviLibri">
		  <thead>
			<td width="15%">ISBN</td>
			<td width="40%">Titolo</td>
			<td width="10%">Prezzo</td>
			<td width="20%">Note</td>
			<td width="10%">Deposito</td>
			<td width="5%"></td>
		  </thead>
		  <tbody></tbody>
		</table>
	</div>
</div>


<!-- FOOTER -->
<?php include "elements/footer.php"; ?> 

</body>

</html>