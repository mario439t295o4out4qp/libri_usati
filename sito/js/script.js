$(function () {
	
	//Sliders
	$('#news').owlCarousel({
		loop: true,
		margin: 20,
		navText: '',
		autoplay: true,
		autoplayTimeout: 5000,
		autoplayHoverPause: false,
		responsive: {
			0: {
				nav: false,
				dots: true,
				items: 1,
				center: false,
			},
			768: {
				nav: true,
				dots: false,
				items: 2,
				center: true,
			},
		}
	});
	$('#scuole').owlCarousel({
		loop: true,
		margin: 0,
		nav: true,
		navText: '',
		dots: false,
		items: 1,
		autoplay: true,
		autoplayTimeout: 5000,
		autoplayHoverPause: false,
	});
	$('#photogallery').owlCarousel({
		loop: true,
		navText: '',
		margin: 10,
		autoplay: true,
		autoplayTimeout: 5000,
		autoplayHoverPause: false,
		responsive: {
			0: {
				nav: false,
				dots: true,
				items: 1,
				center: false,
			},
			768: {
				nav: true,
				dots: true,
				items: 2,
				center: true,
			},
		}
	});
	$('#sostenitori').owlCarousel({
		loop: true,
		margin: 20,
		nav: true,
		navText: '',
		dots: false,
		items: 2,
		autoplay: true,
		autoplayTimeout: 5000,
		autoplayHoverPause: false,
		responsive: {
			0: {
				nav: false,
				dots: true,
				items: 1,
				autoHeight: true
			},
			768: {
				nav: false,
				dots: true,
				items: 2,
				autoHeight: true
			},
			1000: {
				nav: true,
				dots: false,
				items: 4,
			},
		}
	});
	$('#novita').owlCarousel({
		loop: true,
		margin: 20,
		nav: false,
		navText: '',
		dots: true,
		items: 1,
		autoplay: true,
		autoplayTimeout: 10000,
		autoplayHoverPause: false,
		animateOut: 'fadeOut'
	});
	
	//Mappa
	$('#map').addClass('scrolloff');
	$('.mappa').on('click', function () {
		$('#map').toggleClass('scrolloff');
	});
	$('#map').mouseleave(function () {
		$('#map').addClass('scrolloff');
	});
	

	//Barra
	/*$(document).ready(function () {
		var navOffset = $('.barra').offset().top;
		var navHeight = $('.barra').outerHeight();
		$(window).scroll(function () {
			if ($(window).scrollTop() > navOffset) {
				$('.barra').addClass('fixed');
				$('.corpo').css('margin-top', (navHeight) + 'px');
			} else {
				$('.barra').removeClass('fixed');
				$('.corpo').css('margin-top', '0');
			}
		});
	});
	$(document).ready(function () {
		var navOffset = $('.barra1').offset().top;
		var navHeight = $('.barra1').outerHeight();
		$(window).scroll(function () {
			if ($(window).scrollTop() > navOffset) {
				$('.barra1').addClass('fixed');
				$('.corpo').css('margin-top', (navHeight) + 'px');
			} else {
				$('.barra1').removeClass('fixed');
				$('.corpo').css('margin-top', '0');
			}
		});
	});
*/
	//Servizi
	$('.servizio').click(function(){
            var id=$(this).attr('id');
            $('.servizio').removeClass('active');
            $('.testi div').removeClass('active');
            $(this).addClass('active');
            $('.testi #'+id).addClass('active');
          });

	//Dati
	$('.dato span').each(function () {
		var val = $(this).html();
		$(this).html(0);
		var text = $(this);
		var n = 0;

		var myTimer = setInterval(function () {
			if (n < val - 56) {
				n = n + 56;
				$(text).html(n);
			}
			else $(text).html(val);
		}, 100);
	});
	$('#scuola').change(function(){
		var s=$(this).val();
		$('#indirizzo option').slice(1).each(function(index, element){
			if($(element).attr('data-scuola')==s) $(element).fadeIn();
			else $(element).fadeOut();
		});
		$('#indirizzo').removeClass("hidden");
		$('#indirizzo').val('');
		$('#classe').val('');
	});
	$('#indirizzo').change(function(){
		var s=$(this).val();
		$('#classe option').slice(1).each(function(index, element){
			if($(element).attr('data-indirizzo')==s) $(element).fadeIn();
			else $(element).fadeOut();
		});
		$('#classe').removeClass("hidden");
		$('#classe').val('');
	});
	$('#classe').change(function(){
		var s=$(this).val();
		var s1=$('#indirizzo').val();
		var s2=$('#scuola').val();
		$('#sceltaLibri').fadeIn();
		$('#sceltaLibri tbody tr').each(function(index, element){
			if($(element).attr('data-classe')==s && $(element).attr('data-indirizzo')==s1 && $(element).attr('data-scuola')==s2) $(element).fadeIn();
			else $(element).fadeOut();
		});
	});
		function printAlert(type, text) {
		$('body').append("<div class=\"alert alert-" + type + "\">" + text + "</div>");
		$('.alert').fadeOut(4000, function () {
			$('.alert').remove();
		});
	}
	function creaISBN_old(ISBN) {
		var ISBN1 = ISBN.substr(0, 3) + "-" + ISBN.substr(3, 2) + "-" + ISBN.substr(5, 3) + "-" + ISBN.substr(7, 4) + "-" + ISBN.substr(12, 1);
		return ISBN1;
	}
	
	function creaISBN(ISBN) {
		if (ISBN.length == 4)
			ISBN = "xxxxxxxxx" + ISBN;
		var ISBN1 = ISBN.substr(0, 3) + "-" + ISBN.substr(3, 2) + "-" + ISBN.substr(5, 7) + "-" + ISBN.substr(12, 1);
		return ISBN1;
	}
	
	function newRowR(ISBN, scuole, titolo, prezzo) {
//	function newRowR(ID, titolo, ISBN) {
		ISBN = creaISBN(ISBN);
		if(titolo==="Libro non registrato"){ printAlert("warning", "<b>Spiacenti</b><br> Libro non in adozione!"); return;}
		$(".risultati").find('table').prepend($('<tr>')
			.append($('<td>').html(ISBN))
			.append($('<td>').html(prezzo+' &euro;'))
			.append($('<td>').html(titolo))
			.append($('<td style="font-size:12px">').html(scuole))
			.append($('<td>').html("<i class=\"fa fa-times-circle cancellaLibro\" aria-hidden=\"true\"></i></td>"))
		);
		$('.cancellaLibro').click(function () {
			$(this).closest('tr').remove();
		});
	}
	function cercaLibroR(){
		var ISBN = $('.ricerca input').val();
		$('.ricerca input').val("");

		$.ajax({
			method: "POST",
			url: "../gestionale/fx/cerca.php",
			data: "ISBN=" + ISBN + "&tabella=catalogo",
			dataType: "json"
		}).done(function (data) {
			//newRowR(data[0], data[1], ISBN);
			newRowR(data[3], data[4], data[1], data[2]);
		}).fail(function () {
			printAlert("warning", "<b>Spiacenti</b><br> Libro non in adozione!");
		});
	}
	$('.ricerca input').keyup(function (e) {
		if (e.keyCode === 13) {
			cercaLibroR();
		}
	});

});