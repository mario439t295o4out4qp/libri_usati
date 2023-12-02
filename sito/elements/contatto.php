<script type="text/javascript">
      var onloadCallback = function() {
        grecaptcha.render('g-recaptcha', {
          'sitekey' : '6LfL98cmAAAAAMR9HrQz38eczg7IB_tNjdprlMYY'
        });
      };
</script>
	<script src="https://www.google.com/recaptcha/api.js?hl=it"></script>
	<div class="container-fluid contattaci">
		<a name="contattaci"></a>
		<div class="container">
			<h2>Contattaci</h2>
			<form method="post" action="gestionale/fx/inviamail.php">
				<input type="text" class="form-control" name="nome" placeholder="Nome">
				<input type="text" class="form-control" name="cognome" placeholder="Cognome">
				<input type="text" class="txt2" name="nick" placeholder="Nick" value>
				<input type="email" class="form-control" name="email" placeholder="E-mail">
				<input type="tel" class="form-control" name="telefono" placeholder="Telefono">
				<textarea class="form-control" name="messaggio" placeholder="Messaggio"></textarea>
				<div class="form-group">
					<input class="magic-checkbox" type="checkbox" name="PrivacyBox" id="check" value="y" required>
					<label for="check">Presa visione della <a href="./">Privacy Policy</a></label>
				</div>
				<div id="g-recaptcha"></div>
				<input class="form-control invio" type="submit" name="invia" value="INVIA">
			</form>
			<script src="https://www.google.com/recaptcha/api.js?hl=it&onload=onloadCallback&render=explicit" async defer>
    		</script>
		</div>
	</div>

