<?php
require_once "../fpdf/fpdf.php";
require_once "../../lib/funzioni.php";

include "../../lib/check.php";

class PDF extends FPDF {
	// Load data

	function LoadData( $ID, $type, $IdUt ) {
		
		if($type=="prenotazione"){
			$query = "SELECT p.ID, c.ISBN, c.titolo, c.prezzo, p.ID_catalogo
				FROM $type AS o
				INNER JOIN prenotati AS p ON o.ID = p.ID_$type
				INNER JOIN catalogo AS c ON p.ID_catalogo = c.ID
				WHERE p.ID_$type=$ID";
		}
		else if ($type=="saldo"){
			$query = "SELECT o.idAnno, m.ID, c.ISBN, c.titolo, m.prezzo, m.ID_catalogo, m.descrizione, m.id_vendita idv
				FROM ritiro AS o
				INNER JOIN magazzino AS m ON o.ID = m.ID_ritiro
				LEFT JOIN catalogo AS c ON m.ID_catalogo = c.ID
				WHERE o.ID_utente=$IdUt ORDER BY o.idAnno,m.id";
		}
		else {
			$query = "SELECT m.ID, c.ISBN, c.titolo, m.prezzo, m.ID_catalogo, m.descrizione
				FROM $type AS o
				INNER JOIN magazzino AS m ON o.ID = m.ID_$type
				LEFT JOIN catalogo AS c ON m.ID_catalogo = c.ID
				WHERE m.ID_$type=$ID";
		}
		connect_DB( $mysqli );
		$mysqli->real_query( $query );
		$res = $mysqli->use_result();
		$i = 0;
//		$gg = getGuadagno();
		$campi = array();
		while ( $row = $res->fetch_assoc() ) {
			$gV = getVendita($row[ 'ID_catalogo' ]);
			$gR = getRicavo($row[ 'ID_catalogo' ]);
			$isbn = $row[ 'ISBN' ];
			$tit = $row[ 'titolo' ];
			if ( $type!="prenotazione")
				if ($row[ 'ID_catalogo' ] == 0) {
					$n = strpos($row[ 'descrizione' ],"::");
					if ( $n !== false) {
						$isbn =substr($row[ 'descrizione' ],$n+2);
						$tit = "(fc) " . substr($row[ 'descrizione' ],0,$n);
					}
				}
			$campi[ $i ][ 0 ] = $row[ 'ID' ];
			$campi[ $i ][ 1 ] = "-" . substr( $isbn, 9, 12 );
			if ($type == "saldo")
				$campi[ $i ][ 2 ] = strtolower(substr( $tit, 0, 20 ) . "...");
			else 
				$campi[ $i ][ 2 ] = strtolower(substr( $tit, 0, 12 ) . "...");
			$campi[ $i ][ 3 ] = sprintf( "%01.2f", $row[ 'prezzo' ] );
			if($type=="ritiro") $campi[ $i ][ 4 ] = sprintf( "%01.2f", $campi[ $i ][ 3 ] * $gV);
			else if($type=="saldo") { 
				if ($row['idv']) $campi[ $i ][ 4 ] = sprintf( "%01.2f", $campi[ $i ][ 3 ] * $gV); else $campi[ $i ][ 4 ]="0";
				$campi[ $i ][ 5 ] = $row[ 'idAnno' ];
			}
			else $campi[ $i ][ 4 ] = sprintf( "%01.2f", $campi[ $i ][ 3 ] * $gR );
			$i++;
		}
		mysqli_close($mysqli);
		return $campi; // $data;
	}

	function SetCol( $col ) {
		// Set position at a given column
		$this->col = $col;
		$x = 10 + $col * 148.5;
		$this->SetLeftMargin( $x );
		$this->SetX( $x );
		$this->SetY( 10 );
	}

	function AcceptPageBreak() {
		// Method accepting or not automatic page break
		if ( $this->col < 2 ) {
			// Go to next column
			$this->SetCol( $this->col + 1 );
			// Set ordinate to top
			$this->SetY( $this->y );
			// Keep on page
			return false;
		} else {
			// Go back to first column
			$this->SetCol( 0 );
			// Page break
			return true;
		}
	}

	function Top( $col, $ID, $nome, $data ) {
		//Creazione Header
		$this->SetFont( 'Arial', 'B', 15 );
		$this->Cell( 12, 10, $ID, 1, 0, 'C', 'green' );
		$this->SetFont( 'Arial', '', 12 );
		$this->Cell( 75, 5, $nome, 1, 0, 'C' );
		$this->Ln();
		$this->Cell( 12, 5, '', 0, 0, 'C' );
		$this->Cell( 75, 5, $data, 1, 0, 'C' );
		$this->Image( '../imgs/logo.png', $col * 148.5 + 100, 9, 'auto', 12.5 );
		$this->Ln();
		$this->Ln();

	}

	// Better table
	function ImprovedTable( $header, $data, $type) {
		// Column widths
		if ($type == "saldo")
			$w = array( 15, 15, 40, 15, 15, 15 );
		else $w = array( 11, 13, 25, 15, 15 );
		// Header
		$this->SetFont( 'Arial', 'B', 10 );
		for ( $i = 0; $i < count( $header ); $i++ )
			$this->Cell( $w[ $i ], 6, $header[ $i ], 1, 0, 'C' );
		$this->Ln();
		// Data
		//print_r($data);
		$this->SetFont( 'Arial', '', 10 );
		for ( $i = 0; $i < count( $data ); $i++ ) {
			for ( $j = 0; $j < count( $data[ $i ] ); $j++ )
				$this->Cell( $w[ $j ], 5, $data[ $i ][ $j ], 1, 0, 'C' );
			$this->Ln();
		}
		while ( $i < 32 ) {
			for ( $j = 0; $j < count( $data[ 0 ] ); $j++ )
				$this->Cell( $w[ $j ], 5, '', 1, 0, 'C' );
			$this->Ln();
			$i++;
		}
		$totale = 0;
		$totale1 = 0;
		for ( $i = 0; $i < count( $data ); $i++ ) {
			$totale  += $data[ $i ][ 3 ];
			$totale1 += $data[ $i ][ 4 ];
		}
//		$gg = getGuadagno();
//		if($type=="ritiro") $totale1 = $totale * $gg;
//		else $totale1 = $totale * 0.50;

		$oggi = date("d-m-y H:i")."      Tot.";

		if($type=="saldo") $totale="";
		else $totale = sprintf( "%01.2f", $totale );
		$totale1 = sprintf( "%01.2f", $totale1 );

		$this->SetFont( 'Arial', 'B', 10 );
		if ($type=="saldo")
			$this->Cell( 70, 5, $oggi, 1, 0, 'C' );
		else 
			$this->Cell( 49, 5, $oggi, 1, 0, 'C' );
		$this->Cell( 15, 5, $totale, 1, 0, 'C' );
		$this->Cell( 15, 5, $totale1, 1, 0, 'C' );
		$this->Ln();
		// Closing line
		$rf = array_sum( $w );
		if ($type=="saldo") $rf -= $w[5];
		$this->Cell( $rf, 1, '', 'T' );
	}

	function rightText( $col, $type ) {
		$this->SetLeftMargin( $col * 148.5 + 95 );
		$this->SetY( 25 );
		$this->Image( '../imgs/' . $type . '.jpg', $col * 148.5 + 95, 25, 40, 'auto' );
		$this->MultiCell( 40, 171, '', 1, 0 );
	}
}

$larghezza = "297";
$altezza = "210";
$formato = array( $larghezza, $altezza );
$pdf = new PDF( 'Landscape', 'mm', $formato );

$type = $_GET[ 'type' ];
$ID = $_GET[ 'ID' ];
$IdUt = $_GET[ 'IdU' ];

connect_DB( $mysqli );
$anno=getAnno();

$tipo = strtoupper(substr($type,0,1));

if ($ID === "") {  // chiamata dalla pagina di ritiro o vendita
	$mysqli->real_query( "SELECT max(numero) as nn FROM ricevute WHERE year(quando)=" . $anno . " AND utente=" . $IdUt . " AND tipo='" . $tipo . "'");
	$res = $mysqli->use_result()->fetch_assoc();
	$ID = $res['nn'];
}

$query = "UPDATE $type SET stato='stampato' WHERE ID=$ID";
$mysqli->query( $query );

if ( $type == "ritiro" && $_SESSION[ 'privilegi' ] == 1) {
	$query = "update magazzino set postupd=0 where id_ritiro=$ID"; // aggiorna i flag di aggiornamento post-ritiro del prezzo 
	$mysqli->query( $query );
}
if($type=="saldo"){
	$query = "SELECT o.IdAnno as ida, u.nome, u.cognome, c.ISBN, c.materia, m.prezzo, m.descrizione, m.ID_catalogo, o.data
	FROM ritiro AS o
	INNER JOIN magazzino AS m ON o.ID = m.ID_ritiro
	INNER JOIN utenti AS u ON o.ID_utente = u.ID
	LEFT JOIN catalogo AS c ON m.ID_catalogo = c.ID
	WHERE u.ID=$IdUt";
}
else if($type=="prenotazione"){
	$query = "SELECT o.IdAnno as ida, u.nome, u.cognome, c.ISBN, c.materia, c.prezzo, o.data
			FROM $type AS o
			INNER JOIN prenotati AS p ON o.ID = p.ID_$type
			INNER JOIN utenti AS u ON o.ID_utente = u.ID
			LEFT JOIN catalogo AS c ON p.ID_catalogo = c.ID
			WHERE o.ID=$ID";
}
else{
	$query = "SELECT o.IdAnno as ida, u.nome, u.cognome, c.ISBN, c.materia, m.prezzo, m.descrizione, m.ID_catalogo, o.data
			FROM $type AS o
			INNER JOIN magazzino AS m ON o.ID = m.ID_$type
			INNER JOIN utenti AS u ON o.ID_utente = u.ID
			LEFT JOIN catalogo AS c ON m.ID_catalogo = c.ID
			WHERE o.ID=$ID";
}
$mysqli->real_query( $query );
$res = $mysqli->use_result()->fetch_assoc();
$nome = $res[ 'nome' ] . " " . $res[ 'cognome' ];
$giorno = $res[ 'data' ];
if($type=="saldo") $progressivo = "#"; else $progressivo = $res[ 'ida' ];

if ( $type == "ritiro" )$pdf->SetFillColor( 136, 191, 103 );
else if ( $type == "vendita" )$pdf->SetFillColor( 0, 158, 224 );
else if ( $type == "prenotazione" )$pdf->SetFillColor( 223, 37, 137 );
else if ( $type == "saldo" )$pdf->SetFillColor( 255, 255, 0 );
if ($type=="ritiro" ) $header = array( 'Cod.', 'ISBN', 'Titolo', 'Orig.', 'Quota' );
else if ($type=="saldo") $header = array( 'Cod.', 'ISBN', 'Titolo', 'Orig.', 'Quota', '#Ric.' );
else $header = array( 'Cod.', 'ISBN', 'Titolo', 'Orig.', 'Prezzo' );
$data = $pdf->LoadData( $ID, $type, $IdUt );
$pdf->SetLineWidth( 0.01 );
$pdf->SetAutoPageBreak( false, 10 );

$pdf->AddPage();

$pdf->Line( 148.5, 0, 148.5, 210 );

$pdf->SetCol( 0 );
$pdf->SetFont( 'Arial', '', 12 );
$pdf->Top( 0, $progressivo, $nome, $giorno );
$pdf->SetFont( 'Arial', '', 10 );
$pdf->ImprovedTable( $header, $data, $type );
if($type != "saldo") $pdf->rightText( 0, $type );


$pdf->SetCol( 1 );
$pdf->SetFont( 'Arial', '', 12 );
$pdf->Top( 1, $progressivo, $nome, $giorno );
$pdf->SetFont( 'Arial', '', 10 );
$pdf->ImprovedTable( $header, $data, $type);
if($type != "saldo") $pdf->rightText( 1, $type );

$pdf->Output();
?>