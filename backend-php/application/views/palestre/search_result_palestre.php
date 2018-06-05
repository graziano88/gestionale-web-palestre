<div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-users fa-fw"></i> Elenco Palestre</h3> <!-- Utenti Palestra</h3> -->
</div>
<div class="panel-body">

	<div class="table-responsive">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Nome</th>
					<th>Indirizzo</th>
					<th>Ubicazione</th>
					<th>Sito web</th>
					<th>Email</th>
				</tr>
			</thead>
			<tbody>
<?php
	if( count($palestre) > 0) {
		foreach($palestre as $palestra) {
?>			
				<tr>
					<td>
						<!--
						<button type="button" class="btn btn-info btn-sm sgp-show" data-toggle="modal" data-target="#modal" id="mostra-<?=$palestra->id;?>"><i class="fa fa-expand"></i></button> <button type="button" class="btn btn-warning btn-sm sgp-edit" data-toggle="modal" data-target="#modal" id="modifica-<?=$palestra->id;?>"><i class="fa fa-pencil"></i></button> <button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$palestra->id;?>"><i class="fa fa-trash"></i></button>
						-->
						<a href="<?=base_url()?>listapalestre/showPalestra/<?=$palestra->id;?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a> 
						<a href="<?=base_url()?>listapalestre/getFormEdit/<?=$palestra->id;?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a> <button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$palestra->id;?>"><i class="fa fa-trash"></i></button>
					</td> <!-- DISPONIBILE PER SU-ADMIN E ADMIN PALESTRA -->
					<td><?=$palestra->nome;?></td>
					<td><?=$palestra->indirizzo;?></td>
					<td><?=$palestra->ubicazione?></td>
					<td><a href="<?=prep_url($palestra->sito_web);?>" target="_blank"><?=$palestra->sito_web;?></a></td>
					<td><a href="mailto:<?=$palestra->email;?>"><?=$palestra->email;?></a></td>
				</tr>
<?php
		}
	} else {
?>
				<tr><td colspan="6" class="text-center">Nessuna palestra trovata</td></tr>
<?php
	}
?>
			</tbody>
		</table>
	</div>
</div>