<div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-users fa-fw"></i> Elenco Palestre</h3> <!-- Utenti Palestra</h3> -->
</div>
<div class="panel-body">

	<div class="table-responsive">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Username</th>
					<th>Palestra</th>
					<th>Cognome e Nome</th>
					<th>Ruolo</th>
					<th>Email</th>
				</tr>
			</thead>
			<tbody>
<?php
	if( count($utenti) > 0) {
		foreach($utenti as $utente) {
?>			
				<tr>
					<td>
						<a href="<?=base_url()?>listautenti/showUtente/<?=$utente->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a> 
						<!--<a href="<?=base_url()?>listautenti/getFormEdit/<?=$utente->id?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a>-->
						<?php
						if( $utente->ruolo > 0 ) {
						?>
						<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$utente->id?>"><i class="fa fa-trash"></i></button>
						<?php
						}
						?>
					</td> <!-- DISPONIBILE PER SU-ADMIN E ADMIN PALESTRA -->
					<td><?=$utente->username?></td>
					<td><?=$utente->nome_palestra?>
					<td><?=$utente->cognome?> <?=$utente->nome?></td>
					<td><?=$utente->ruolo_str?></td>
					<td><a href="mailto:<?=$utente->email?>"><?=$utente->email?></a></td>
				</tr>
<?php
		}
	} else {
?>
				<tr><td colspan="6" class="text-center">Nessuna Utente trovato</td></tr>
<?php
	}
?>
			</tbody>
		</table>
	</div>
</div>