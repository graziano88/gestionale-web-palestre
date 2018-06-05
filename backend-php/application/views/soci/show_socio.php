<?php if(false) { ?>
<link href="../../../public/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<?php } ?>
<div id="page-wrapper">

	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Visualizzazione Socio <small><?=$socio->nome?> <?=$socio->cognome?></small>
				</h1>
			</div>
		</div>
		
		
		<?php
		if( $numero_rate_scadute > 0 ) {
		?>
		<div class="row">
			<div class="col-lg-7">
				<div class="alert alert-danger alert-dismissable">
					Attenzione, <?=( $numero_rate_scadute == 1 ? 'una rata non pagata è scaduta' : $numero_rate_scadute.' rate non pagate sono scadute' )?>!
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				</div>
			</div>
		</div>
		<?php
		}
		?>
		
		
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listasoci" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a> <a href="<?=base_url()?>listasoci/getFormEdit/<?=$socio->id?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i> Modifica</a> <a href="<?=base_url()?>pdf/schedaSocio/<?=$socio->id?>" class="btn btn-sm btn-success sgp-back-btn" role="button" title="" target="_blank"><i class="fa fa-print"></i> Stampa Scheda Socio</a><!--<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$socio->id?>"><i class="fa fa-trash"></i> Elimina</button>-->
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Anagrafica Socio</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-5"><strong>Nome:</strong> </div>
									<div class="col-lg-6"><?=$socio->nome?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Cognome:</strong> </div>
									<div class="col-lg-6"><?=$socio->cognome?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Data di iscrizione:</strong> </div>
									<div class="col-lg-6"><?=$socio->data_iscrizione_str?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Indirizzo:</strong> </div>
									<div class="col-lg-6"><?=( $socio->indirizzo != '' ? $socio->indirizzo.', '.$socio->cap.', '.$socio->citta.' ('.$socio->provincia.')' : 'n.d.')?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Sesso:</strong> </div>
									<div class="col-lg-6"><?=( $socio->sesso == 0 ? 'Maschio' : 'Femmina' )?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Data di nascita:</strong> </div>
									<div class="col-lg-6"><?=$socio->data_nascita_str?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Luogo di nascita:</strong> </div>
									<div class="col-lg-6"><?=$socio->nato_a?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Professione:</strong> </div>
									<div class="col-lg-6"><?=$socio->professione?></div>
								</div>
								
								<div class="row">
									<div class="col-lg-5"><strong>Recapiti Telefonici:</strong> </div>
									<div class="col-lg-6">
									<?php
									if( count($recapiti_telefonici) > 0 ) {
										foreach( $recapiti_telefonici as $recapito_telefonico ) {
									?>
										<div><?=$recapito_telefonico->numero?> (<?=$recapito_telefonico->tipologia_str?>)</div>
									<?php
										}
									} else {
									?>
										n.d.
									<?php
									}
									?>
									</div>
								</div>
								
								<div class="row">
									<div class="col-lg-5"><strong>E-mail:</strong> </div>
									<div class="col-lg-6"><?=$socio->email?></div>
								</div>
								
								<?php
								if( $socio->socio_presentatore != null ) {
									$socio_presentatore = $socio->socio_presentatore;
								?>
								<div class="row">
									<div class="col-lg-5"><strong>Socio presentatore:</strong> </div>
									<div class="col-lg-6"><a href="<?=base_url()?>listasoci/showSocio/<?=$socio_presentatore->id?>"><?=$socio_presentatore->nome?> <?=$socio_presentatore->cognome?></a></div>
								</div>
								<?php
								}
								?>
								
								<div class="row">
									<div class="col-lg-5"><strong>Consulente:</strong> </div>
									<div class="col-lg-6">
									<?php
										if( $privilegi <= 1 ) {
									?>
										<a href="<?=base_url()?>listautenti/showUtente/<?=$socio->consulente->id?>">
									<?php
										}
									?>
											<?=$socio->consulente->nome?> <?=$socio->consulente->cognome?>
									<?php
										if( $privilegi <= 1 ) {
									?>
										</a>
									<?php
										}
									?>
									</div>
								</div>
								
								<?php
								if( $socio->coordinatore != '' ) {
								?>
								<div class="row">
									<div class="col-lg-5"><strong>Coordinatore:</strong> </div>
									<div class="col-lg-6">
									<?php
										if( $privilegi <= 1 ) {
									?>
										<a href="<?=base_url()?>listautenti/showUtente/<?=$socio->coordinatore->id?>">
									<?php
										}
									?>
											<?=$socio->coordinatore->nome?> <?=$socio->coordinatore->cognome?>
									<?php
										if( $privilegi <= 1 ) {
									?>
										</a>
									<?php
										}
									?>
									</div>
								</div>
								<?php
								}
								?>
								
								<div class="row">
									<div class="col-lg-5"><strong>Provenienza:</strong> </div>
									<div class="col-lg-6"><?=$socio->fonte_provenienza?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Motivazone:</strong> </div>
									<div class="col-lg-6"><?=$socio->motivazione?></div>
								</div>
								<?php
								if( $numero_bonus_socio > 0 && count($abbonamenti_socio) > 0 ) {
								?>
								<div class="row">
									<div class="col-lg-5">
										<strong><?=( $numero_bonus_socio == 1 ? 'Un bonus disponibile' : $numero_bonus_socio.' bonus disponibili' )?>:</strong> 
									</div>
									<div class="col-lg-6">
										<button type="button" class="btn btn-success btn-sm sgp-bonus" data-toggle="modal" data-target="#modal" id="bonus-<?=$socio->id?>" title="Bonus">Applica Bonus</button>
									</div>
								</div>
								<?php
								}
								?>
							</div>
						</div>
						<!--
						<div class="row text-center sgp-row">
							<div class="col-lg-12">
								<button type="button" class="btn btn-warning btn-md sgp-edit-profilo" data-toggle="modal" data-target="#modal"  id="edit-profilo-<?=$id?>"><i class="fa fa-pencil"></i> Modifica Dati Profilo</button>
							</div>
						</div>
						-->
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-address-card fa-fw"></i> Abbonamenti Socio</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th></th>
										<th>Tipo Abbonamento</th>
										<th>Stato</th>
										<!--<th>Data Scadenza</th>-->
									</tr>
								</thead>
								<tbody>
									
									<?php 
									if( count($abbonamenti_socio) > 0 ) {
										foreach( $abbonamenti_socio as $abbonamento_socio ) {
									?>
									<tr>
										<td>
											<div>
												<a href="<?=base_url()?>listaabbonamenti/showAbbonamento/<?=$abbonamento_socio->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a>
									<?php
											if( !$abbonamento_socio->lock ) {
									?>
												<a href="<?=base_url()?>listaabbonamenti/getFormEdit/<?=$abbonamento_socio->id?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a>
												<button type="button" class="btn btn-danger btn-sm sgp-delete-abbonamento" data-toggle="modal" data-target="#modal" id="delete-<?=$abbonamento_socio->id?>" title="Elimina"><i class="fa fa-trash"></i></button>
									<?php
											}
									?>
											</div>
											<?php
											if( $abbonamento_socio->stato ) {
											?>
											<div class="sgp-btn-row">
												<a href="javascript:void(0);" class="btn btn-sm btn-success changeStatoAbbonamento" name="<?=( $abbonamento_socio->attivo == 1 ? 'disattiva' : 'attiva' )?>-<?=$abbonamento_socio->id?>" role="button" title="" id=""><?=( $abbonamento_socio->attivo == 1 ? 'Disattiva' : 'Attiva' )?></a>
											</div>
											<?php
											}
											?>
										</td>
										<td><?=$abbonamento_socio->tipo_abbonamento?></td>
										<td>
											<?php
											if( $abbonamento_socio->attivo == 1 ) {
												//ATTIVO
											?>
											<?php	
												if( $abbonamento_socio->stato ) {
													// VALIDO
											?>
											<div>
												<!--<strong>Attivo</strong>-->
												<strong><?=( $abbonamento_socio->saldato ? 'Saldato' : 'Da saldare' )?></strong>
											</div>
											<div>Scade il: <?=$abbonamento_socio->data_fine_str?></div>
											<?php	
												} else {
													// SCADUTO
											?>
											<div>
												<!--<strong>Attivo</strong>-->
												<strong><?=( $abbonamento_socio->saldato ? 'Saldato' : 'Mai saldato' )?></strong>
											</div>
											<div><strong>Scaduto il: <?=$abbonamento_socio->data_fine_str?></strong></div>
											<!--
											<div>
												<a href="javascript:void(0);" class="btn btn-sm btn-success" name="rinnova-<?=$abbonamento_socio->id?>" role="button" title="" id="rinnovaAbbonamento">Rinnova</a>
											</div>
											-->
											<?php	
												}
											} else {
												// NON ATTIVO
											?>
											<div>
												<strong>Non attivo</strong> 
												<!--<a href="javascript:void(0);" class="btn btn-sm btn-success" name="attiva-<?=$abbonamento_socio->id?>" role="button" title="" id="changeStatoAbbonamento">Attiva</a>-->
											</div>
											<?php	
												if( $abbonamento_socio->stato ) {
													// VALIDO
											?>
											<div>Scade il: <?=$abbonamento_socio->data_fine_str?></div>
											<?php	
												} else {
													// SCADUTO
											?>
											<div><strong>Scaduto il: <?=$abbonamento_socio->data_fine_str?></strong></div>
											<?php	
												}	
											}
											?>
										</td>
									</tr>
									<?php
										}
									} else {
									?>
									<tr><td colspan="4" class="text-center">Nessun abbonamento presente</td></tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="row text-center">
							<div class="col-lg-12" style="margin-bottom: 20px;">
								<div <?=( $lock_insert_abbonamento ? 'class="sgp-disabled-btn" title="Nessun\'altra tipologia di abbonamento disponibile per questo socio"' : '' )?>>
									<a href="<?=base_url()?>listaabbonamenti/getFormInsert/<?=$socio->id?>" class="btn btn-success btn-sm sgp-insert" <?=( $lock_insert_abbonamento ? '' : '' )?>><i class="fa fa-pencil"></i> Inserisci nuovo abbonamento</a>
								</div>
							</div>				
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-check-square fa-fw"></i> Colloqui di Verifica</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th></th>
										<th>Data/Ora</th>
										<th>Descrizione</th>
										<th>Desk</th>
									</tr>
								</thead>
								<tbody>
									
									<?php 
									if( count($colloqui_verifica) > 0 ) {
										foreach( $colloqui_verifica as $colloquio ) {
									?>
									<tr>
										<td>
											<div>
												<!--<a href="<?=base_url()?>listacontatti/showColloquio/<?=$colloquio->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a>-->
												<button type="button" class="btn btn-danger btn-sm sgp-delete-colloquio" data-toggle="modal" data-target="#modal" id="delete-<?=$colloquio->id?>" title="Elimina"><i class="fa fa-trash"></i></button>
											</div>
										</td>
										<td><?=$colloquio->data?> <?=$colloquio->ora?></td>
										<td><?=$colloquio->descrizione?></td>
										<td><?=$colloquio->cognome_desk?> <?=$colloquio->nome_desk?></td>
									</tr>
									<?php
										}
									} else {
									?>
									<tr><td colspan="4" class="text-center">Nessun colloquio di verifica presente</td></tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="row text-center">
							<div class="col-lg-12" style="margin-bottom: 20px;">
								<div>
								<a href="<?=base_url()?>listacontatti/getFormInsertColloquio/<?=$socio->id?>" class="btn btn-success btn-sm sgp-insert-colloquio"><i class="fa fa-pencil"></i> Inserisci nuovo colloquio di verifica</a>
								</div>
							</div>				
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-phone fa-fw"></i> Telefonate al socio</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th></th>
										<th>Data/Ora</th>
										<th>Motivo</th>
										<th>Esito</th>
										<th>Desk</th>
									</tr>
								</thead>
								<tbody>
									
									<?php 
									if( count($telefonate) > 0 ) {
										foreach( $telefonate as $telefonata ) {
									?>
									<tr>
										<td>
											<div>
												<!--<a href="<?=base_url()?>listacontatti/showTelefonata/<?=$telefonata->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a>-->
												<button type="button" class="btn btn-danger btn-sm sgp-delete-telefonata" data-toggle="modal" data-target="#modal" id="delete-<?=$telefonata->id?>" title="Elimina"><i class="fa fa-trash"></i></button>
											</div>
										</td>
										<td><?=$telefonata->data?> <?=$telefonata->ora?></td>
										<td><?=$telefonata->motivo?></td>
										<td><?=( $telefonata->esito == 1 ? 'Positivo' : 'Negativo' )?></td>
										<td><?=$telefonata->cognome_desk?> <?=$telefonata->nome_desk?></td>
									</tr>
									<?php
										}
									} else {
									?>
									<tr><td colspan="5" class="text-center">Nessun telefonata di verifica presente</td></tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="row text-center">
							<div class="col-lg-12" style="margin-bottom: 20px;">
								<div>
								<a href="<?=base_url()?>listacontatti/getFormInsertTelefonata/<?=$socio->id?>" class="btn btn-success btn-sm sgp-insert-telefonata"><i class="fa fa-pencil"></i> Inserisci nuova telefonata</a>
								</div>
							</div>				
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listasoci" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a> <!--<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$socio->id?>"><i class="fa fa-trash"></i> Elimina</button>-->
			</div>
		</div>
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<script>
	var url_confirm_delete_abbonamento = '<?=base_url()?>listaabbonamenti/AskConfirmDelete';
	var url_confirm_delete_colloquio = '<?=base_url()?>listacontatti/AskConfirmDeleteColloquio';
	var url_confirm_delete_telefonata = '<?=base_url()?>listacontatti/AskConfirmDeleteTelefonata';
	
	var url_scelta_bonus = '<?=base_url()?>listaabbonamenti/AskBonusEAbbonamento';
	var change_stato_abbonamento = '<?=base_url()?>abbonamentocontroller/changeStatoAbbonamento';
		
	$(document).ready(function(){
		listenerModal();
	});
	
	$('.changeStatoAbbonamento').click(function() {
		var name_action =  $(this).attr("name");
		if( name_action.indexOf("disattiva") >= 0 ) {
			var id = name_action.replace('disattiva-','');
			changeStatoAbbonamento(id, 0);
		} else {
			var id = name_action.replace('attiva-','');
			changeStatoAbbonamento(id, 1);
		}
	});

	function listenerModal() {
		$('button[data-toggle="modal"]').click(function() {

			var id_button = $(this).attr("id");
			var classi = $(this).attr("class");
			//alert(classi);
			if(classi.indexOf("sgp-delete-abbonamento") >= 0) {
				/* DELETE */

				var id = id_button.replace('delete-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_confirm_delete_abbonamento+'/'+id, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);
						
						// il listener del form è nella view del pupup
					}

				});	

			} else if(classi.indexOf("sgp-bonus") >= 0) {
				var id = id_button.replace('bonus-','');
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_scelta_bonus+'/'+id, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);
						
						// il listener del form è nella view del pupup
					}

				});	
			} else if(classi.indexOf("sgp-delete-colloquio") >= 0) {
				/* DELETE */

				var id = id_button.replace('delete-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_confirm_delete_colloquio+'/'+id, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);
						
						// il listener del form è nella view del pupup
					}

				});	

			} else if(classi.indexOf("sgp-delete-telefonata") >= 0) {
				/* DELETE */

				var id = id_button.replace('delete-','');
				//alert(id);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_confirm_delete_telefonata+'/'+id, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);
						
						// il listener del form è nella view del pupup
					}

				});	

			}
		});
	}
	
	function changeStatoAbbonamento(id_abbonamento, new_stato) {
		$.ajax({
			type: "POST",
			url: change_stato_abbonamento+'/'+id_abbonamento+'/'+new_stato, 
			dataType: "html",
			success:function(data){
				location.reload();
			}

		});
	}
	
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			location.reload();
		});
	}
</script>
	