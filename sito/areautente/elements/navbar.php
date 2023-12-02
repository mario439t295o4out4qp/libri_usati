<nav class="navbar navbar-default">
  <div class="container-fluid">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand logo" href="dashboard.php"><h1><img src="imgs/logo.png" ></h1></a>
	</div>

	<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
	  <ul class="nav navbar-nav">
		<li><a href="prenotazione.php">Prenota!</a></li>
		<!--li><a href="fuoricatalogo.php">Fuori catalogo</a></li-->
		<li><a href="utente.php">Utente</a></li>
		  <li class="hidden-sm hidden-md hidden-lg"><a href="fx/logout.php">Logout<i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
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