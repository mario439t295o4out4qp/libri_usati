$(function () {

	//Catalogo
	$('#dashboard .riduci').click(function () {
		$(this).nextAll('.descrCat').slideToggle();
		if ($(this).hasClass("fa-angle-up")) $(this).removeClass("fa-angle-up").addClass("fa-angle-down");
		else $(this).removeClass("fa-angle-down").addClass("fa-angle-up");
	});
	
	//Utente
	$('#utente .riduci').click(function () {
		$(this).nextAll('.formMod').slideToggle();
		if ($(this).hasClass("fa-angle-up")) $(this).removeClass("fa-angle-up").addClass("fa-angle-down");
		else $(this).removeClass("fa-angle-down").addClass("fa-angle-up");
	});
	
	//Prenotazioni
	$('#scuola').change(function(){
		var s=$(this).val();
		$('#indirizzo option').slice(1).each(function(index, element){
			if($(element).attr('data-scuola')==s) $(element).fadeIn();
			else $(element).fadeOut();
		});
		$('#indirizzo').removeClass("hidden");
	});
	$('#indirizzo').change(function(){
		var s=$(this).val();
		$('#classe option').slice(1).each(function(index, element){
			if($(element).attr('data-indirizzo')==s) $(element).fadeIn();
			else $(element).fadeOut();
		});
		$('#classe').removeClass("hidden");
	});
	$('#classe').change(function(){
		var cl=$(this).val();
		var ind=$('#indirizzo').val();
		var sc=$('#scuola').val();
		$('#sceltaLibri tbody').find("tr").remove();
		var data = "sc=" + sc + "&cl="+cl+"&ind=" + ind;
		$.ajax({
				method: "POST",
				url: "fx/carica.php",
				data: data,
			})
			.done(function (data) {
				var libri = $.parseJSON(data);
				libri.forEach(function(row) {  // 0.ID, 1.ISBN, 2.titolo, 3.casa_editrice, 4.autore, 5.classe, 6.nn, 7.pp 
					nnn = row[6] - row[7] ;  // nn-np
					if (nnn>0) car= "<i class='fa fa-cart-plus cart'></i>";
					else car = "<i class='fa fa-times nocart'></i>";
					$('#sceltaLibri').find('tbody').append($("<tr style='display:table-row' 'data-classe'='" + row[5] +"'>")
						.append($("<td class='ID'>").html(row[0]))
						.append($("<td>").html(row[1]))
						.append($("<td>").html(row[2]))
						.append($("<td>").html(row[3]))
						.append($("<td>").html(row[4]))
						.append($("<td class='nn'>").html(row[6]))
						.append($("<td class='pp'>").html(row[7]))
						.append($("<td>").html("<span class='fa fa-eye vedi'></span>" + car)));
				});
			})
			.fail(function () {
				printAlert("danger", "<b>Attenzione!</b><br> C'è stato un errore nel caricamento dei dati!");
			});
		$('#sceltaLibri').fadeIn();
	});

	$('#sceltaLibri').on("click", ".cart", function(){
		var s=$(this).parents('tr').clone();
		$(s).find('i').removeClass('cart fa-cart-plus').addClass('fa-times-circle').addClass('cancellaLibro');
		$(s).find('span').removeClass('fa-eye vedi');
		var tro = 0;
		var cod=$(s).find("td")[0];
		$('td:eq(0)', $('#nuoviLibri tr')).each(function () {
			if ( $(this).html() == cod.innerHTML ) tro = tro +1;
		});
		
		if (tro>0)
			printAlert("danger","Libro già nella lista.<b> Non aggiunto</b>");
		else {
			var nn=$(s).find("td")[5];
			if ( nn.innerHTML=="0") {
				printAlert("warning","Libro non in magazzino.<b> Prenotazione opzionata</b>");
				$(s).addClass('nomagazzino');
			}
			$(nn).addClass("hidden");
			nn=$(s).find("td")[6];
			$(nn).addClass("hidden");
			$(this).css('color','#279cd8');
			$('.prenotati').fadeIn();
			$("#nuoviLibri").find('tbody').prepend(s);
			$('.cancellaLibro').click(function () {
				$(this).closest('tr').remove();
			});
		}
	});	
	$('.cancellaLibro').click(function () {
		$(this).closest('tr').remove();
	});
	$('.regLibri').click(function () {
		var ID = $('#ID').html();
		var valori = "";
		$('#nuoviLibri tbody tr').each(function (index, element) {
			valori = valori + $(element).find('.ID').html() + "; ";
		});
		if (valori == "") 
			printAlert("danger", "<b>Attenzione!</b><br>Lista vuota");
		else {
			var data = "ID=" + ID + "&tabella=prenotazione&valori=" + valori;
			data = data.slice(0, data.length - 2);
			$.ajax({
				method: "POST",
				url: "fx/inserisci.php",
				data: data,
			})
			.done(function (msg) {
				printAlertLong("info", "Prenotazione completata.<br>Adesso puoi vedere e stampare la ricevuta.");
				sleep(4000).then(() => {
					window.location.href = 'dashboard.php';
				});
			})
			.fail(function () {
				printAlert("danger", "<b>Attenzione!</b><br> C'è stato un errore nel salvataggio dei dati, riprova!");
			});
		}
	});
	
	$('.carrello').click(function(){
		$(".box").animate({ scrollTop: $('.box').prop("scrollHeight")}, 1000);
	});

	$('.vedi').click(function(){
		var isbn=$(this).parents('tr').find('td:eq(1)').html();
		txt = "<img src='../../imgs/libri/" + isbn + ".jpg' width='300px'><br>" + "ISBN: " + isbn;
		$('body').append("<div class=\"alert alert-info\">" + txt + "</div>");
		$('.alert').fadeOut(6000, function () {
			$('.alert').remove();
		});
	});

	//Orologio
	function orologio() {
		today = new Date()
		var ora = today.getHours();
		var min = today.getMinutes();
		if (ora < 10 && min < 10) $(".orologio").html("0" + ora + ":0" + min);
		else if (ora < 10) $(".orologio").html("0" + ora + ":" + min);
		else if (min < 10) $(".orologio").html(ora + ":0" + min);
		else $(".orologio").html(ora + ":" + min);
		setTimeout(function () {
			orologio()
		}, 1000);
	}
	orologio();

	//Alert
	function printAlert(type, text) {
		$('body').append("<div class=\"alert alert-" + type + "\">" + text + "</div>");
		$('.alert').fadeOut(4000, function () {
			$('.alert').remove();
		});
	}
	
	function sleep (time) {
		return new Promise((resolve) => setTimeout(resolve, time));
	}

	function printAlertLong(type, text) {
		$('body').append("<div class=\"alert alert-" + type + "\">" + text + "</div>");
		$('.alert').fadeOut(8000, function () {
			$('.alert').remove();
		});
	}

});