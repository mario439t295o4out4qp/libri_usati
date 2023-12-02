<nav class="navbar navbar-default">
  <div class="container-fluid">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand logo" href="dashboard.php"><h1><img src="imgs/logo.png" title="Staisullibro"></h1></a>
	</div>

	<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
	  <ul class="nav navbar-nav">
		<li><a href="ritiro.php" title="Ritiro"><i class="fa fa-book" aria-hidden="true"></i></a></li>
		<li><a href="vendita.php" title="Vendita"><i class="fa fa-euro" aria-hidden="true"></i></a></li>
		<li><a href="magazzino.php">Magazzino</a></li>
		<li><a href="prenotati.php">Prenotati</a></li>
		<li><a href="catalogo.php">Catalogo</a></li>
		<li><a href="utenti.php">Utenti</a></li>
		<li><a href="ricevute.php">Ricevute</a></li>
<?php if ($privilegi==3) {?>
		<li><a href="utility.php" title="Utility"><i class="fa fa-wrench" aria-hidden="true"></i></a></li>
<?php }; ?>
	  </ul>
	  <div class="utente hidden-xs">
		<div class="img"><img src="<?php echo $img; ?>"></div>
		<div class="dx">
		  <div class="orologio"></div>
		  <p><?php echo $nome." ".$cognome; ?><a href="fx/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i></a></p>
		</div>
	  </div>
	</div>
  </div>
</nav>