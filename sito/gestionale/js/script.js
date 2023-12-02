$(function () {
	function gR (idCat) {
		return ( idCat == 0 ? 0.40 : 0.50 );
	}
	
	function printAlert(type, text) {
		$('body').append("<div class=\"alert alert-" + type + "\">" + text + "</div>");
		$('.alert').fadeOut(4000, function () {
			$('.alert').remove();
		});
	}
	function creaISBN(ISBN) {
		if (ISBN.length == 4)
			ISBN = "xxxxxxxxx" + ISBN;
		var ISBN1 = ISBN.substr(0, 3) + "-" + ISBN.substr(3, 2) + "-" + ISBN.substr(5, 7) + "-" + ISBN.substr(12, 1);
		return ISBN1;
	}
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
	
/* Eventi */
	$('#riduci').click(function () {
		//Usato in vendita e ritiro
		$('.datiUtente').slideToggle();
		if ($(this).hasClass("fa-angle-up")){ $(this).removeClass("fa-angle-up").addClass("fa-angle-down");}
		else{ $(this).removeClass("fa-angle-down").addClass("fa-angle-up");}
	});
	$('.cancellaRiga').click(function () {
		$(this).parents("tr").slice(0, 1).remove();
	});
	$('#tabRicevute i').click(function(){
		$(this).css('color','#337ab7');
	});
	
	
/* Filtri e Ordinamento*/
	$('#filtra li, #ordina li').click(function () {
		var s = $(this).html() + " <span class=\"caret\"></span>";
		$('#filtra li, #ordina li').removeClass('active');
		$(this).addClass('active');
		$(this).parent('ul').prev().html(s);
	});
	$('#filtra input').keyup(function (e) {
		if (e.keyCode === 13) {
			var s = $(this).val().toLowerCase();
			var s1 = "";
			var col = "";
			$(this).val("");
			if($("#listCatalogo").length){
				col = $(this).next().find('li.active').text();
				$('#listCatalogo li').each(function (index, element) {
					s1=$(element).find('small').filter('.' + col).text().toLowerCase();
					if (s1.search(s) === -1){ $(element).slideUp();}
					console.log(s+" "+s1);
				});
			} else{
				col = $(this).next().find('li.active').attr('id');
				$('tbody tr').each(function (index, element) {
					s1=$(element).children().filter(":nth-child(" + col + ")").text().toLowerCase();
					if (s1.search(s) === -1){ $(element).slideUp();}
				});
			}
			
		}
	});
	function ordina(order, col, type, elems){
		if (type==="n"){
			if (order === 1) {
				elems.sort(function (a, b) {
					var compA = parseInt(a.getAttribute('data-id'));
					var compB = parseInt(b.getAttribute('data-id'));
					return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
				}).appendTo(elems.parent());
			} else {
				elems.sort(function (a, b) {
					var compA = parseInt(a.getAttribute('data-id'));
					var compB = parseInt(b.getAttribute('data-id'));
					return (compA > compB) ? -1 : (compA < compB) ? 1 : 0;
				}).appendTo(elems.parent());
			}
		} else{
			if (order === 1) {
				elems.sort(function (a, b) {
					var compA = a.getAttribute('data-id').toUpperCase();
					var compB = b.getAttribute('data-id').toUpperCase();
					return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
				}).appendTo(elems.parent());
			} else {
				elems.sort(function (a, b) {
					var compA = a.getAttribute('data-id').toUpperCase();
					var compB = b.getAttribute('data-id').toUpperCase();
					return (compA > compB) ? -1 : (compA < compB) ? 1 : 0;
				}).appendTo(elems.parent());
			}
		}
		
	}
	$('#go').click(function () {
		var order = 0; //0=asc 1=desc
		if ($(this).children().hasClass('fa-sort-amount-asc')) {
			order = 0;
			$(this).children().removeClass('fa-sort-amount-asc').addClass('fa-sort-amount-desc');
		} else {
			order = 1;
			$(this).children().removeClass('fa-sort-amount-desc').addClass('fa-sort-amount-asc');
		}
		var col = $(this).parent().next().find('li.active').attr('id');
		var type = $(this).parent().next().find('li.active').attr('data-type');
		var elems = $('tbody tr');
		var val="";
		elems.each(function (index, element) {
			val=$(element).children().filter(":nth-child(" + col + ")").text();
			if( val === ""){ val=0;}
			$(element).attr('data-id', val);
		});
		ordina(order, col, type, elems);
	});
	$('#refresh').click(function () {
		$('tbody tr').show();
		$('ul li').show();
	});
	
	/* RITIRO e VENDITA */
	$('#cerca').click(function () {
		var s = $('.cerca input').val();
		$('.cerca input').val("");

		s = s.replace(/ /g, "");
		$('.tableRubrica tbody tr').show();
		$('.tableRubrica tbody tr').each(function () {
			if ($(this).attr('id').search(s) === -1){ $(this).slideUp();}
		});
	});
	$('#cercaBar').keyup(function (e) {
		if (e.keyCode === 13) {
			var s = $('.cerca input').val();
			$('.cerca input').val("");

			s = s.replace(/ /g, "");
			$('.tableRubrica tbody tr').show();
			$('.tableRubrica tbody tr').each(function () {
				if ($(this).attr('id').search(s) === -1){ $(this).slideUp();}
			});
		}
	});
	$('.seleziona-utente').click(function () {
		var campi = [null];
//		$("#prnRicevute").css("display", "none");

		$(this).parent("td").prevAll().each(function (index) {
			campi[5 - index] = $(this).text();
		});
		$('form.utente input[type="text"]').each(function (index) {
			$(this).val(campi[index]);
		});
		chiudiJum();
	});
	$('.seleziona-utenteV').click(function () {
		var campi = [null];
		$("#prnRicevute").css("display", "none");
		$("#getPrenotati").css("visibility", "hidden");

		$(this).parent("td").prevAll().each(function (index) {
			campi[6 - index] = $(this).text();
		});
		$('form.utente input[type="text"]').each(function (index) {
			$(this).val(campi[index]);
		});
		if (campi[6] > 0) {
			$("#getPrenotati").css("visibility", "visible");
//			$("#lnkPrenotati").attr("href", "/gestionale/fx/carica.php?IdU="+campi[0]);
		}
		$('#nuoviLibri tbody tr').remove();
		chiudiJum();
	});
	$('.caricaPre').click(function () {
		var ID = $('.ID').val();
		$.ajax({
			method: "POST",
			url: "fx/carica.php",
			data: "ID=" + ID,
			dataType: "json"
		}).done(function (data) {
			$('#libriVend').css('display', 'table');
			for(i = 0; i < data.length; i++)
				newRowV(data[i][0], data[i][1], data[i][2], data[i][3], data[i][4], -data[i][5]);
		}).fail(function () {
			printAlert("warning", "<b>Spiacente</b><br>Prenotazioni non caricabili!");
		});
		
		$("#getPrenotati").css("visibility", "hidden");
	});

	$('#pulisci').click(function () {
		$('form.utente input').each(function () {
			$(this).val("");
		});
		$('#nuoviLibri tbody tr').remove();
	});
		
	/* RITIRO */
	function newRowR(ISBN, ID, titolo, prezzo) {
		ISBN1 = creaISBN(ISBN);
		if (ISBN.length == 4) ISBN = "xxxxxxxxx" + ISBN;
		var input="";
		var prz="";
		var dove=""
		if (titolo === "Libro non registrato"){ 
			tit ="<input type=\"text\" name=\"titolo\" value=\"" + titolo + "\" class='form-control titolo'>";
			input = "<input type=\"text\" name=\"descrizione\" value=\"" + ISBN + "\" class='form-control descrizione' readonly>";
			prz = "<input type=\"text\" name=\"prezzo\" value=\"0\" size=\"2\" class='form-control prezzo'>";
		}
		else{ 
			tit ="<input type=\"text\" name=\"titolo\" value=\"" + titolo + "\" class='form-control titolo' readonly>";
			input = "<input type=\"text\" name=\"descrizione\" class='form-control descrizione'>";
			prz = "<input type=\"text\" name=\"prezzo\" value=\"" + prezzo + "\" size=\"2\" class='form-control prezzo'>";
		}
		var sede = $('#sede').val();
		if (sede==1) {s1="selected"; s2="";}
		if (sede==2) {s2="selected"; s1="";}
		dove= "<select name=\"dove\" class='form-control dove'><option "+s1+" value=\"1\">Galilei</option><option "+s2+" value=\"3\">Pacioli</option></select>";
		$("#nuoviLibri").find('tbody').prepend($('<tr>')
			.append($('<td>').addClass('ID').css('display', 'none').html(ID))
			.append($('<td>').addClass('ISBN').html(ISBN1))
			.append($('<td>').html(tit))
			.append($('<td>').html(prz))
			.append($('<td>').html(input))
			.append($('<td>').html(dove))
			.append($('<td>').html("<i class=\"fa fa-times-circle cancellaLibro\" aria-hidden=\"true\"></i></td>"))
		);
	}

	function cercaLibroR(){
		var ISBN = $('.ISBN input').val();
		$('.ISBN input').val("");
		$('#nuoviLibri').css('display', 'table');
		$.ajax({
			method: "POST",
			url: "fx/cerca.php",
			data: "ISBN=" + ISBN + "&tabella=catalogo",
			dataType: "json"
		}).done(function (datan) {
			datan.forEach(data => {
				newRowR(data[3], data[0], data[1], data[2]);			
			});
			if (datan.length > 1)
				printAlert("warning", "<b>Attenzione </b><br> ISBN a 4 cifre duplicato <br><b>Eliminare quello non corretto");
		}).fail(function () {
			printAlert("warning", "<b>Spiacente</b><br> Libro non in adozione!");
		});
	}

	$('#ritiro .ISBN button').click(function () {
		cercaLibroR();
	});
	$('#ritiro .ISBN input').keyup(function (e) {
		if (e.keyCode === 13) {
			cercaLibroR();
		}
	});
	$('.regLibri').click(function () {
		var ID = $('.ID').val();
		if (ID === "") {
			printAlert("warning", "<b>Attenzione!</b><br> Inserire un utente prima di eseguire l'operazione di salvataggio!");
			return;
		}
		if ($('#nuoviLibri tbody tr').length == 0) {
			printAlert("warning", "<b>Attenzione!</b><br> Deve essere specificato almeno un libro!");
			return;
		}
		$('#nuoviLibri tbody tr').each(function (index, element) {
//			var orig=$(element).find('input:hidden').val();
//			if ( $(element).find('.titolo').val() != orig )
			if ( $(element).find('.ID').html() == "0" )
				$(element).find('.descrizione').attr("value", $(element).find('.titolo').val() + "::" + $(element).find('.descrizione').val());
		});

		var valori = "";
		$('#nuoviLibri tbody tr').each(function (index, element) {
			valori = valori + $(element).find('.ID').html() + ", '" + $(element).find('.prezzo').val() + "', '" + $(element).find('.descrizione').val().replace(/'/g,"\\'") + "', " + $(element).find('.dove').val() + "; ";
		});
		var data = "ID=" + ID + "&tabella=ritiro&valori=" + valori;
		data = data.slice(0, data.length - 2);
		$("#btnSalva").css("visibility", "hidden");
		$.ajax({
			method: "POST",
			url: "fx/inserisci.php",
			data: data,
		}).done(function (data) {
			$('#nuoviLibri tbody tr').remove();
			$('form.utente input').each(function () {
				$(this).val("");
			});
			$("#btnSalva").css("visibility", "visible");
			$("#prnRicevute").css("display", "inline");
			$("#lnkRicevute").attr("href", "/gestionale/fx/ricevute.php?type=ritiro&IdU="+ID+"&ID=");
			printAlert("success", "Salvataggio avvenuto correttamente");
		}).fail(function () {
			printAlert("danger", "<b>Attenzione!</b><br> C'è stato un errore nel salvataggio dei dati, riprova!");
		});
	});

	$("#nuoviLibri").on('click','.cancellaLibro',function() {
		$(this).closest('tr').remove();
	});

	$('.regPrezzo').click(function () {
		var ID = $('#IdL').val();
		var prezzo = $('#prezzo').val();	
		var data = "ID=" + ID + "&prezzo=" + prezzo;
		$.ajax({
			method: "POST",
			url: "fx/modificaPrezzo.php",
			data: data,
		}).done(function (data) {
			$("#m"+ID).parent().prev('td').prev('td').html(prezzo);
			chiudiJum();
			printAlert("success", "Prezzo aggiornato correttamente");
		}).fail(function () {
			printAlert("danger", "<b>Attenzione!</b><br> C'è stato un errore nel salvataggio dei dati, riprova!");
		});
	});	
	
	/* VENDITA */
	function newRowV(ISBN, titolo, prezzo, idCat, codLib, idPren) {
		if(ISBN===-1){
			printAlert("warning", "<b>Spiacente</b><br> "+titolo);
			return;}
		ISBN = creaISBN(ISBN);
		if (idPren>0) flag="&nbsp;<i class='fa fa-exclamation-triangle' title='prenot.#"+idPren+"'></i>" +" ("+idPren+")"; 
		else if (idPren<0) flag="("+(-idPren)+")"; else flag="";
		$("#libriVend").find('tbody').prepend($('<tr>')
			.append($('<td>').addClass('ID').html(codLib))
			.append($('<td>').addClass('ISBN').html(ISBN))
			.append($('<td>').html(titolo))
			.append($('<td id="m'+codLib+'">').html( Math.round((prezzo*gR(idCat)) * 100) / 100))
			.append($('<td>').html(flag))
			.append($('<td>').html("<i class=\"fa fa-times-circle cancellaLibro\" aria-hidden=\"true\"></i>"))
		);
	}
	$("#libriVend").on('click','.cancellaLibro',function() {
		$(this).closest('tr').remove();
	});

	function cercaLibroV(){
		var codLib = $('.codLib input').val();
		$('.codLib input').val("");
		$('#libriVend').css('display', 'table');
		tro=0;
		$('td:eq(0)', $('#libriVend tr')).each(function () {
			if ( $(this).html() == codLib ) tro = tro +1;
		});
		if (tro>0)
			printAlert("danger","Libro già nella lista.<b> Non aggiunto</b>");
		else {
			$.ajax({
				method: "POST",
				url: "fx/cerca.php",
				data: "codLib=" + codLib + "&tabella=magazzino",
				dataType: "json"
			}).done(function (data) {
				newRowV(data[0], data[1], data[2], data[3], codLib, data[4]);
			}).fail(function () {
				printAlert("warning", "<b>Spiacente</b><br> Libro non presente in magazzino");
			});
		}
	}
	$('#vendita .codLib button').click(function () {
		cercaLibroV();
	});
	$('#vendita .codLib input').keyup(function (e) {
		if (e.keyCode === 13) {
			cercaLibroV();
		}
	});
	$('.venLibri').click(function () {
		var ID = $('.ID').val();
		if (ID === "") {
			printAlert("warning", "<b>Attenzione!</b><br> Inserire un utente prima di eseguire l'operazione di salvataggio!");
			return;
		}
		if ($('#libriVend tbody tr').length == 0) {
			printAlert("warning", "<b>Attenzione!</b><br> Deve essere specificato almeno un libro!");
			return;
		}

		var valori = "";
		$('#libriVend tbody tr').each(function (index, element) {
			valori = valori + $(element).find('.ID').html() + "; ";
		});
		var data = "ID=" + ID + "&tabella=vendita&valori=" + valori;
		data = data.slice(0, data.length - 2);
		$("#btnSalva").css("visibility", "hidden");
		$.ajax({
				method: "POST",
				url: "fx/inserisci.php",
				data: data,
		})
		.done(function (data) {
			$('#libriVend tbody tr').remove();
			$('form.utente input').each(function () {
				$(this).val("");
			});
			$("#btnSalva").css("visibility", "visible");
			$("#prnRicevute").css("display", "inline");
			$("#lnkRicevute").attr("href", "/gestionale/fx/ricevute.php?type=vendita&IdU="+ID+"&ID=");
			$("#totRicevuta").html(" "+data+" &euro;");
			printAlert("success", "Salvataggio avvenuto correttamente");
		})
		.fail(function () {
			printAlert("danger", "<b>Attenzione!</b><br> C'è stato un errore nel salvataggio dei dati, riprova!");
		});
	});
	
	/* UTENTE */
	$('#utente .riduci').click(function () {
		$(this).nextAll('.formMod').slideToggle();
		if ($(this).hasClass("fa-angle-up")){ $(this).removeClass("fa-angle-up").addClass("fa-angle-down");}
		else{ $(this).removeClass("fa-angle-down").addClass("fa-angle-up");}
	});
	$(".modPass").click(function(event){
		event.preventDefault();
		$(".inputDisabled").prop('disabled', function (_, val) { return ! val; });
	});
	
	/* RICEVUTE */
	$('#ricevute .riduci').click(function(){
		$(this).parents('tr').next('tr').slideToggle();
		if ($(this).hasClass("fa-angle-up")){ $(this).removeClass("fa-angle-up").addClass("fa-angle-down");}
		else{ $(this).removeClass("fa-angle-down").addClass("fa-angle-up");}
	});
	
	/* CATALOGO */
	$('#catalogo .riduci').click(function () {
		$(this).parent().next('.descrCat').slideToggle();
		if ($(this).hasClass("fa-angle-up")){ $(this).removeClass("fa-angle-up").addClass("fa-angle-down");}
		else{ $(this).removeClass("fa-angle-down").addClass("fa-angle-up");}
	});
	
	/* Jumbotron */
	function apriNew() {
		$('.shadow').fadeIn();
		$('#jumNew').show();
	}
	$('#apriNew').click(function () {
		apriNew();
	});

	function apriRub() {
		$('.shadow').fadeIn();
		$('#jumRub').show();
	}
	$('#apriRub').click(function () {
		apriRub();
	});

	function apriMod() {
		$('.shadow').fadeIn();
		$('#jumMod').show();
	}
	$('.apriMod').click(function () {
		apriMod();
		var dati = [];
		$(this).parents('tr').children('td').each(function (index, element) {
			dati[index] = $(element).html();
		});

		$('#jumMod').find('input[type="text"]').each(function (index, element) {
			$(element).val(dati[index]);
		});
	});

	function apriPre() {
		$('.shadow').fadeIn();
		$('#jumPre').show();
	}
	$('.apriPre').click(function () {
		apriPre();
		var dati = [];
		$(this).parents('tr').children('td').each(function (index, element) {
			dati[index] = $(element).html();
		});
		$('#jumPre').find('input[type="text"]').each(function (index, element) {
			$(element).val(dati[index]);
		});
	});
	
	function chiudiJum() {
		$('.jumbotron').slideUp();
		$('.shadow').hide();
	}
	$('.chiudiJum').click(function () {
		chiudiJum()
	});

// RIASSUNTO
	$('#sede').change(function(){
		var s=$(this).val();
		$('#giorno option').slice(1).each(function(index, element){
			if($(element).attr('data-dove')==s) $(element).fadeIn();
			else $(element).fadeOut();
		});
		$('#giorno').removeClass("hidden");
	});

	$('#giorno').change(function(){
		var s=$(this).val();
		var s1=$('#sede').val();
		$('#elencoVendite').fadeIn();
		$('#elencoVendite tbody tr').each(function(index, element){
			if($(element).attr('data-dd')==s && $(element).attr('data-dove')==s1 ) $(element).fadeIn();
			else $(element).fadeOut();
		});
	});

	$('#sede2').change(function(){
		var s=$(this).val();
		$('#elencoVendite').fadeIn();
		$('#elencoVendite tbody tr').each(function(index, element){
			if($(element).attr('data-dove')==s ) $(element).fadeIn();
			else $(element).fadeOut();
		});
	});
	
	$('.filtraLettera').click(function () {
		var lett=$(this).text();
		if (lett=="")
			$('#elencoUtenti tbody tr').each(function(index, element){
			$(element).removeClass("hidden");
			});
		else
			$('#elencoUtenti tbody tr').each(function(index, element){
				if($(element).attr('cognome')==lett ) $(element).removeClass("hidden");
				else $(element).addClass("hidden");
		});
	});
});