
<style>
	h1 {
		color:black;
		font-size: 25pt;
		text-align: center;
		text-transform: uppercase;
	}
	.recapiti tr td {
		border-left: 1px solid black;
        border-right: 1px solid black;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
		text-align: left
	}
</style>

<h1><?=$nome_palestra?></h1>
<h2 style="text-align: center;">Elenco Recapiti dei Soci</h2>

<p>
	<table cellspacing="0" cellpadding="0" border="1">
		<tr>
			<td>
				&nbsp;&nbsp;<strong>Cognome e Nome</strong>
			</td>
			<td>
				&nbsp;&nbsp;<strong>Recapiti</strong>
			</td>
		</tr>
<?php
for($i=0; $i<count($soci); $i++) {
	$socio = $soci[$i];
?>
		<tr>
			<td>
				&nbsp;&nbsp;<?=$socio->cognome?> <?=$socio->nome?>
			</td>
			<td>
					
					<?php
					if( $socio->email != '' ) {
					?>
						&nbsp;&nbsp;E-mail:&nbsp;&nbsp;&nbsp;<?=$socio->email?><br>
					<?php
					}
					$recapiti = $socio->recapiti;
					for($j=0; $j<count($recapiti); $j++) {
						$recapito = $recapiti[$j];
					?>
					&nbsp;&nbsp;<?=$recapito->tipo?>:&nbsp;&nbsp;&nbsp;<?=$recapito->numero?><br>
					<?php
					}
					?>
					<?=( count($recapiti) <= 0 && $socio->email == '' ? '<br><br>' : '' )?>
			</td>
		</tr>
<?php
}
?>
	</table>
</p>