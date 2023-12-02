<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				  <span class="sr-only">Toggle navigation</span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				</button>
				<a class="navbar-brand logo" href="index.php"><h1><img src="imgs/logo_scritta.png" title="Staisullibro"></h1></a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="chisiamo.php">Chi Siamo</a></li>
					<li><a href="servizi.php">Servizi</a></li>
					<li><a href="catalogo.php">Adozioni</a></li>
<?php if (disponibili()) echo "<li><a href='magazzino.php'>Magazzino</a></li>" ?>					
					<li><a href="registrati.php">Registrati!</a></li>
					<li><a href="/areautente/">Area Utente</a></li>
					<!--li class="hidden-sm ico-social"><a href="https://www.facebook.com/libriusati.galilei/" target="blank"><img src="imgs/facebook.png"></a></li>
					<li class="hidden-sm ico-social"><a href="https://www.instagram.com/libriusati.galilei/?hl=it" target="blank"><img src="imgs/instagram.png"></a></li-->
					<li class="hidden-sm ico-social"><a href="mailto:<?php echo getMail()?>"><img src="imgs/mail.png"></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>