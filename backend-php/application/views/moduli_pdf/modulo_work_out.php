
<style>
	h1 {
		color:black;
		font-size: 25pt;
		text-align: center;
		text-transform: uppercase;
	}
	.pagamenti tr td {
		border-left: 1px solid black;
        border-right: 1px solid black;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
		text-align: center
	}
</style>

<h1><?=$nome_palestra?></h1>
<h2 style="text-align: center;">DOMANDA D'ISCRIZIONE</h2>
<p>
	<strong>Cognome e nome</strong>&nbsp;&nbsp;<?=$socio->cognome?> <?=$socio->nome?><br>
	<strong>Indirizzo</strong>&nbsp;&nbsp;<?=$socio->indirizzo?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CAP</strong>&nbsp;&nbsp;<?=$socio->cap?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Citt&agrave;</strong>&nbsp;&nbsp;<?=$socio->citta?> (<?=$socio->provincia?>)<br>
	<strong>Professione</strong>&nbsp;&nbsp;<?=$socio->professione?><br>
	<strong>Nato a</strong>&nbsp;&nbsp;<?=$socio->nato_a?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Il</strong>&nbsp;&nbsp;<?=$socio->data_nascita_str?><br>
	
</p>
<p>
SPETT.LE CENTRO SPORTIVO HO PRESO VISIONE E APPROVO LE CONDIZIONI GENERALI E IL REGOLAMENTO A TERGO RIPORTATI.
DICHIARO DI OSSERVARE PIENAMENTE LO STATUTO ED I REGOLAMENTI.
SONO INOLTRE STATO DETTAGLIATAMENTE INFORMATO SUL CORSO.
MI OBBLIGO A CORRISPONDERE LA SOMMA<br>
</p>
<p>
<strong>COMPLESSIVA DI</strong>&nbsp;&nbsp;&nbsp;&nbsp;<?=$abbonamento->valore_abbonamento?> &euro;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>ENTRO E NON OLTRE IL</strong>&nbsp;&nbsp;&nbsp;&nbsp;<?=$abbonamento->scadenza_ultima_rata?><br>
</p>
<p>
<strong>PER L'ABBONAMENTO</strong>&nbsp;&nbsp;&nbsp;&nbsp;<?=$abbonamento->tipo?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CONSULENTE</strong>&nbsp;&nbsp;&nbsp;&nbsp;<?=$desk->cognome?> <?=$desk->nome?><br>
</p>
<p>
<strong>INIZIO </strong>&nbsp;&nbsp;&nbsp;&nbsp;<?=$abbonamento->data_inizio_str?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>TERMINE</strong>&nbsp;&nbsp;&nbsp;&nbsp;<?=$abbonamento->data_fine_str?>
</p>
<h2>Pagamenti</h2>
<p>
	<table>
		<tr>
			<td width="60px">
				<strong>N. Rata</strong>
			</td>
			<td width="100px">
				<strong>Scadenza rata</strong>
			</td>
			<td>
				<strong>Importo Rata</strong>
			</td>
		</tr>
<?php
for($i=0; $i<count($rate); $i++) {
	$rata = $rate[$i];
?>
		<tr>
			<td>
				<?=$rata->numero_sequenziale_romano?>
			</td>
			<td>
				<?=$rata->data_scadenza_str?>
			</td>
			<td>
				<?=$rata->valore_rata?> &euro;
			</td>
		</tr>
<?php
}
?>
	</table>
</p>
<p>
	<table class="pagamenti">
		<tr style="font-weight: bold">
			<td>
				<strong>Data</strong>
			</td>
			<td>
				<strong>N. Ricevuta</strong>
			</td>
			<td width="50px">
				<strong>Rata riferimento</strong>
			</td>
			<td>
				<strong>Importo Pagato</strong>
			</td>
		</tr>
<?php
if( count($pagamenti) > 0 ) {
	foreach( $pagamenti as $pagamento ) {
?>
		<tr>
			<td>
				<?=$pagamento->data?> <?=$pagamento->ora?><br>
			</td>
			<td>
				<?=$pagamento->numero_ricevuta?>
			</td>
			<td>
				<?=$pagamento->rata_riferimento?>
			</td>
			<td>
				<?=$pagamento->importo_pagato?>
			</td>
		</tr>
<?php
	}
}
?>
		<tr>
			<td>
				<br><br>
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				<br><br>
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				<br><br>
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				<br><br>
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				<br><br>
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				<br><br>
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
		</tr>
	</table>
</p>
<p>
	<br><br><br><br><br>
<div style="text-align: left;"><strong>L&igrave;</strong>&nbsp;&nbsp;<?=$citta_palestra?>, 6/11/2017 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Cognome e Nome</strong>&nbsp;&nbsp;<?=$socio->cognome?> <?=$socio->nome?></div><br>
<div style="text-align: left;"><strong>Firma</strong> .........................................................................</div>
</p>
