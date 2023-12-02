<?php include "fx/session.php"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>

<body>

	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div class="container-fluid box">
		<div id="dashboard" class="container">

			<h1>Utility</h1>

			<?php
			$anno = getAnno();

			?>

			<ul class="list-group">
				<li class="list-group-item">
					<span id="nlibri" class="badge new"></span>
					
					Assegna prenotazioni (stato='assegnabile')
					<a href="fx/assegna.php?ut=1"></a>
				</li>
				<li class="list-group-item">
					<span id="nlibri" class="badge new"></span>
					
					Associa con magazzino
					<a href="fx/assegna.php?ut=2"></a>
				</li>
			</ul>

		</div>
	</div>

	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>
	
</body>
</html>