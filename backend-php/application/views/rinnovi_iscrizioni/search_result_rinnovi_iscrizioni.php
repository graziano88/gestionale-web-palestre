<div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-users fa-fw"></i> Elenco Rinnovi/Iscrizioni</h3> <!-- Utenti Palestra</h3> -->
</div>
<div class="panel-body">

	<div class="table-responsive">
		<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th></th>
											<th>Data e Ora</th>
											<th>Cognome e Nome</th>
											<th>Contatti</th>
											<th>Free Pass</th>
											<th>Missed</th>
											<th>Registrato</th>
											<?=( $privilegi == 0 ? '<th>Desk</th>' : '' )?>
										</tr>
									</thead>
									<tbody>
									
									<?php
							 			if( count($rinnovi_iscrizioni) > 0) {
											foreach($rinnovi_iscrizioni as $rinnovo_iscrizione) {
									?>
										<tr>
											<td>
												<div class="sgp-btn-row">
													<a href="<?=base_url()?>listarinnoviiscrizioni/showRinnovoIscrizione/<?=$rinnovo_iscrizione->id;?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a>
													<?php
													if( $rinnovo_iscrizione->id_socio_registrato == '' ) {
													?> 
													<!--<a href="<?=base_url()?>listarinnoviiscrizioni/getFormEdit/<?=$rinnovo_iscrizione->id;?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a>--> <button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$rinnovo_iscrizione->id;?>" title="Elimina"><i class="fa fa-trash"></i></button>
													<?php
													}
													?>
												</div>
											</td>
											<td><?=$rinnovo_iscrizione->data_str?> <?=$rinnovo_iscrizione->ora_str?></td>
											<td><?=$rinnovo_iscrizione->cognome?> <?=$rinnovo_iscrizione->nome?></td>
											<td>
												<div><?=$rinnovo_iscrizione->cellulare?></div>
												<div><?=$rinnovo_iscrizione->telefono?></div>
												<div><?=$rinnovo_iscrizione->email?></div>
											</td>
											<td>
												<div><?=( $rinnovo_iscrizione->free_pass == 0 ? 'No' : 'Sì' )?></div>
												<div><?=( $rinnovo_iscrizione->free_pass == 1 ? ( $rinnovo_iscrizione->scaduto == 1 ? 'Scaduto' : 'Valido' ) : '' )?></div>
											</td>
											<td><?=( $rinnovo_iscrizione->missed == 0 ? 'No' : 'Sì' )?></td>
											<td><?=( $rinnovo_iscrizione->id_socio_registrato == '' ? 'No' : 'Sì' )?></td>
											<?php
											if( $privilegi == 0 ) {
											?>
											<td><?=$rinnovo_iscrizione->nome_desk?> <?=$rinnovo_iscrizione->cognome_desk?></td>
											<?php
											}
											?>
										</tr>
									<?php
											}
										} else {
									?>
										<tr><td colspan="<?=( $privilegi == 0 ? '10' : '9' )?>" class="text-center">Nessuna rinnovo iscrizione nel <?=( $privilegi == 0 ? 'sistema' : 'tuo profilo desk' )?></td></tr>
									<?php
										}
									?>	
									</tbody>
								</table>
	</div>
</div>