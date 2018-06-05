<div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-users fa-fw"></i> Elenco Rinnovi/Iscrizioni</h3> <!-- Utenti Palestra</h3> -->
</div>
<div class="panel-body">

	<div class="table-responsive">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Cognome e Nome</th>
					<th>Data d'iscrizione</th>
					<th>Email</th>
					<th>Stato abbonamenti</th>
				</tr>
			</thead>
			<tbody>

			<?php
				if( count($soci) > 0) {
					foreach($soci as $socio) {
			?>
				<tr>
					<td>
						<a href="<?=base_url()?>listasoci/showSocio/<?=$socio->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a> 
						<a href="<?=base_url()?>listasoci/getFormEdit/<?=$socio->id?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a>
						<?php 
						if( !$socio->lock ) {
						?>
						<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$socio->id?>" title="Elimina"><i class="fa fa-trash"></i></button>
						<?php
						} else {
						?>
						<span class="sgp-disabled-btn" title="Impossibile eliminare, ci sono abbonamenti"><a href="" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></span>
						<?php
						}
						?>
					</td>
					<td><?=$socio->cognome?> <?=$socio->nome?></td>
					<td><?=$socio->data_iscrizione_str?></td>
					<td><a href="mailto:<?=$socio->email?>"><?=$socio->email?></a></td>
					<td>
					<?php
					if( $socio->numero_abbonamenti_attivi > 0 ) {
						if( $socio->numero_abbonamenti_validi > 0 ) {
					?>
						<div>Validi: <?=$socio->numero_abbonamenti_validi?></div>
					<?php		
						}
						if( $socio->numero_abbonamenti_scaduti > 0 ) {
					?>
						<div>Scaduti: <?=$socio->numero_abbonamenti_scaduti?></div>
					<?php		
						}
					} else {
					?>
						<div>Nessuno</div>
					<?php
					}
					?>
					</td>
				</tr>
			<?php
					}
				} else {
			?>
				<tr><td colspan="6" class="text-center">Nessun utente nel sistema</td></tr>
			<?php
				}
			?>	
			</tbody>
		</table>
	</div>
</div>