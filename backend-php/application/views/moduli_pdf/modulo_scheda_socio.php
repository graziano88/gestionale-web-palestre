<style>
	h1 {
		color:black;
		font-size: 25pt;
		text-align: center;
	}
	table.bordata > tr > td {
		border-left: 1px solid black;
        border-right: 1px solid black;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
		
	}
</style>

<h1>SCHEDA SOCIO</h1>
<p>
	<table>
		<tr>
			<td>
				Cognome:<br>
				<?=$socio->cognome?>
			</td>
			<td>
				Nome:<br>
				<?=$socio->nome?>
			</td>
			<td>
				Consulente:<br>
				Rossi Mario
			</td>
		</tr>
	</table>
</p>
<p>
	<table>
		<tr>
			<td>
				Nato il:<br>
				06/06/1988
			</td>
			<td>
				Luogo:<br>
				<?=$socio->nato_a?>
			</td>
			<td>
				Indirizzo:<br>
				<?=$socio->indirizzo?>, <?=$socio->cap?> <?=$socio->citta?> (<?=$socio->provincia?>)
			</td>
		</tr>
	</table>
</p>
<p>
	<table>
		<tr>
			<td>
				Sesso:<br>
				<?=( $socio->sesso == 0 ? 'Maschio' : 'Femmina' )?>
			</td>
			<td>
				Presentato da:<br>
				<?=$socio->cognome_socio_presentatore?> <?=$socio->nome_socio_presentatore?>
			</td>
			<td>
				Motivazione alla frequenza:<br>
				<?=$socio->motivazione?>
			</td>
		</tr>
	</table>
</p>


<p>
	<table>
		<tr>
			<td>
				e-mail:<br>
				<?=$socio->email?>
			</td>
<!-- IL CODICE PHP SE CI SONO PIù DI 2 CONTATTI CREERA' ALTRE TABELLE -->
<?php
//if( count($recapiti) > 0 ) {
	for($k=0; $k<count($recapiti)&&$k<2; $k++) {
		$recapito = $recapiti[$k];
?>
			<td>
				<?=$recapito->tipologia_numero?>:<br>
				<?=$recapito->numero?>
			</td>
<?php
	}
	for($k; $k<2; $k++) {
?>
			<td>
				<!--vuoto-->
			</td>
<?php		
	}
?>
		</tr>
	</table>
</p>
<?php
	if( count($recapiti) > 2 ) {
		$i=0;
		
		//for($k; $k<count($recapiti); $k++) {
		while( $k<count($recapiti) ) {
			$recapito = $recapiti[$k];
			if( $i == 0 ) {
?>
<p>
	<table>
		<tr>	
<?php
			}
?>
			<td>
				<?=$recapito->tipologia_numero?>:<br>
				<?=$recapito->numero?>
			</td>
<?php			
			if( $i == 2 ) {
				$i=0;
?>
		</tr>
	</table>
</p>
<?php
			} else {
				$i++;
			}
			$k++;
		}
		if( $i != 0 ) {
			while( $i < 3 ) {
?>
			<td>
				<!--vuoto-->
			</td>
<?php		
				$i++;
			}
?>
		</tr>
	</table>
</p>
<?php
		}
	}
?>

<h2>Abbonamenti Attivi</h2>
<?php
if( count($abbonamenti) > 0 ) {
	$n=1;
	foreach( $abbonamenti as $abbonamento ) {
?>
<p>
	<table>
		<tr>
			<td width="35px">
				<?=$n?>
			</td>
			<td>
				Tipo:<br>
				<?=$abbonamento->tipologia_abbonamento?>
			</td>
			<td>
				Scadenza:<br>
				<?=$abbonamento->data_scadenza?>
			</td>
			<td>
				Saldato:<br>
				<?=$abbonamento->saldato?>
			</td>
		</tr>
	</table>
</p>
<?php
		$n++;
	}
} else {
?>
<p>
	<table>
		<tr>
			<td>
				<strong>Nessuno</strong>
			</td>
		</tr>
	</table>
</p>
<?php
}
?>


<h2>Colloqui di Verifica</h2>
<p>
	<table class="bordata">
		<tr style="font-weight: bold;">
			<td width="95px">
				<table><tr><td width="5px"></td><td>Data/Ora</td></tr></table>
			</td>
			<td width="20%">
				<table><tr><td width="5px"></td><td>Desk</td></tr></table>
			</td>
			<td width="60%">
				<table><tr><td width="5px"></td><td>Descrizione</td></tr></table>
			</td>
		</tr>
<?php
if( count($colloqui_verifica) > 0 ) {
?>
<?php
	foreach( $colloqui_verifica as $colloquio) {
?>
		<tr>
			<td>
				<table><tr><td width="5px"></td><td><?=$colloquio->data?><br><?=$colloquio->ora?></td></tr></table>
			</td>
			<td>
				<table><tr><td width="5px"></td><td><?=$colloquio->cognome_desk?> <?=$colloquio->nome_desk?></td></tr></table>
			</td>
			<td>
				<table><tr><td width="5px"></td><td><?=$colloquio->descrizione?></td></tr></table>
			</td>
		</tr>
<?php
	}
	for($i=0; $i<3; $i++) {
?>
		<tr>
			<td>
				<br>
				<br>
			</td>
			<td>
				<br>
				<br>
			</td>
			<td>
				<br>
				<br>
			</td>
		</tr>
<?php	
	}
} else {
	for($i=0; $i<5; $i++) {
?>
		<tr>
			<td>
				<br>
				<br>
			</td>
			<td>
				<br>
				<br>
			</td>
			<td>
				<br>
				<br>
			</td>
		</tr>
<?php	
	}
}
?>
	</table>
</p>
<h2>Telefonate</h2>
<p>
	<table class="bordata">
		<tr style="font-weight: bold;">
			<td width="95px">
				<table><tr><td width="5px"></td><td>Data/Ora</td></tr></table>
			</td>
			<td width="160px">
				<table><tr><td width="5px"></td><td>Motivo</td></tr></table>
			</td>
			<td width="75px">
				<table><tr><td width="5px"></td><td>Esito</td></tr></table>
			</td>
			<td>
				<table><tr><td width="5px"></td><td>Desk</td></tr></table>
			</td>
		</tr>
<?php
if( count($telefonate) > 0 ) {
?>
<?php
	foreach( $telefonate as $telefonata) {
?>
		<tr>
			<td>
				<table><tr><td width="5px"></td><td><?=$telefonata->data?><br><?=$telefonata->ora?></td></tr></table>
			</td>
			<td>
				<table><tr><td width="5px"></td><td><?=$telefonata->motivo?></td></tr></table>
			</td>
			<td>
				<table><tr><td width="5px"></td><td><?=( $telefonata->esito == 1 ? 'Positivo' : 'Negativo' )?></td></tr></table>
			</td>
			<td>
				<table><tr><td width="5px"></td><td><?=$telefonata->cognome_desk?> <?=$telefonata->nome_desk?></td></tr></table>
			</td>
		</tr>
<?php
	}
	for($i=0; $i<3; $i++) {
?>
		<tr>
			<td>
				<br>
				<br>
			</td>
			<td>
				<br>
				<br>
			</td>
			<td>
				<br>
				<br>
			</td>
			<td>
				<br>
				<br>
			</td>
		</tr>
<?php	
	}
} else {
	for($i=0; $i<5; $i++) {
?>
		<tr>
			<td>
				<br>
				<br>
			</td>
			<td>
				<br>
				<br>
			</td>
			<td>
				<br>
				<br>
			</td>
			<td>
				<br>
				<br>
			</td>
		</tr>
<?php	
	}
}
?>
	</table>
</p>
<p>
	<br><br><br><br><br>
	<div style="font-size: 6pt;">Autorizzo il trattamento e la comunicazione dei miei dati personali,ai sensi del D.Lgs.del 30/06/2003 n°196</div><br>

<div style="text-align: right;">Firma .........................................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data ...........................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
</p>
