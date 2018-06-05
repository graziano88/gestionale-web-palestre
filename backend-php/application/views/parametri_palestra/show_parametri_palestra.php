<?php if(false) { ?>
<link href="../../../public/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<?php } ?>
<div id="page-wrapper">

	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Parametri Palestra <small><?=$nome_palestra?></small>
				</h1>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Soglie scadenze missed dei Desk</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<small>Queste soglie sono per gli alert che avvisano il desk quando sta per perdere la proprietà di un missed in rinnovi/iscrizioni </small>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-6"><strong>I Alert dopo:</strong> </div>
									<div class="col-lg-6"><?=$parametri_palestra->primo_alert_missed?> giorni</div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>II Alert dopo:</strong> </div>
									<div class="col-lg-6"><?=$parametri_palestra->secondo_alert_missed?> giorni</div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Scadenza dopo:</strong> </div>
									<div class="col-lg-6"><?=$parametri_palestra->scadenza_missed?> giorni</div>
								</div>
							</div>
						</div>
						<div class="row text-center sgp-row">
							<div class="col-lg-12">
								<button type="button" class="btn btn-warning btn-sm sgp-edit-soglie-desk" data-toggle="modal" data-target="#modal"  id="edit-<?=$parametri_palestra->id?>"><i class="fa fa-pencil"></i> Modifica Soglie</button>
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
						<h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Soglie Abbonamento</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<small>Questa soglia è per l'alert che avvisa quando l'abbonamento normale sta per scadere</small>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-6"><strong>Alert Scadenza Abbonamento Normale prima di:</strong> </div>
									<div class="col-lg-6"><?=$parametri_palestra->alert_scadenza_abbonamento?> giorni</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<small>Questa soglia è per l'alert che avvisa quando l'abbonamento freepass sta per scadere</small>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-6"><strong>Alert Scadenza Abbonamento Freepass prima di:</strong> </div>
									<div class="col-lg-6"><?=$parametri_palestra->alert_scadenza_freepass?> giorni</div>
								</div>
							</div>
						</div>
						<div class="row text-center sgp-row">
							<div class="col-lg-12">
								<button type="button" class="btn btn-warning btn-sm sgp-edit-soglia-abbonamento" data-toggle="modal" data-target="#modal"  id="edit-<?=$parametri_palestra->id?>"><i class="fa fa-pencil"></i> Modifica Soglia</button>
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
						<h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Soglia Nuovi Soci</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<small>Questa soglia serve per filtrare i nuovi soci che si sono iscritti entro questa soglia</small>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-6"><strong>Nuovi iscritti fino a:</strong> </div>
									<div class="col-lg-6"><?=$parametri_palestra->soglia_nuovi_soci?> giorni</div>
								</div>
							</div>
						</div>
						<div class="row text-center sgp-row">
							<div class="col-lg-12">
								<button type="button" class="btn btn-warning btn-sm sgp-edit-soglia-nuovi-soci" data-toggle="modal" data-target="#modal"  id="edit-<?=$parametri_palestra->id?>"><i class="fa fa-pencil"></i> Modifica Soglia</button>
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
						<h3 class="panel-title"><i class="fa fa-address-card fa-fw"></i> Tipologie abbonamento</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<strong>Tipologie Abbonamento della palestra</strong>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th></th>
										<th>Nome Abbonamento</th>
										<th>Durata</th>
										<th>Costo Base</th>
										<th>Tipo</th>
										<th>Giorni gratuiti socio</th>
									</tr>
								</thead>
								<tbody>
									
									<?php 
									if( count($tipologie_abbonamenti_palestra) > 0 ) {
										foreach( $tipologie_abbonamenti_palestra as $tipologia_abbonamento ) {
									?>
									<tr>
										<td>
											<!--
											<a href="<?=base_url()?>listaabbonamenti/getFormEdit/<?=$tipologia_abbonamento->id?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a>
											-->
											<button type="button" class="btn btn-warning btn-sm sgp-edit-tipologia-abbonamento" data-toggle="modal" data-target="#modal" id="edit-<?=$tipologia_abbonamento->id?>" title="Modifica"><i class="fa fa-pencil"></i></button>
									<?php
											if( !$tipologia_abbonamento->lock ) {
									?>
											<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$tipologia_abbonamento->id?>" title="Elimina"><i class="fa fa-trash"></i></button>
									<?php
											}
									?>
										</td>
										<td><?=$tipologia_abbonamento->tipo?></td>
										<td><?=$tipologia_abbonamento->durata?> giorni</td>
										<td><?=( $tipologia_abbonamento->freepass == 0 ? $tipologia_abbonamento->costo_base.' €' : '-' )?></td>
										<td><?=( $tipologia_abbonamento->freepass == 1 ? 'Freepass' : 'Normale' )?></td>
										<td><?=( $tipologia_abbonamento->freepass == 1 ? $tipologia_abbonamento->giorni_gratuiti_socio.' giorni' : '-' )?></td>
									</tr>
									<?php
										}
									} else {
									?>
									<tr><td colspan="6" class="text-center">Nessuna tipologia abbonamento della palestra presente</td></tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<strong>Tipologie Abbonamento del sistema (Default)</strong>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th>Nome Abbonamento</th>
										<th>Durata</th>
										<th>Costo Base</th>
										<th>Tipo</th>
										<th>Giorni gratuiti socio</th>
									</tr>
								</thead>
								<tbody>
									
									<?php 
									if( count($tipologie_abbonamenti_sistema) > 0 ) {
										foreach( $tipologie_abbonamenti_sistema as $tipologia_abbonamento ) {
									?>
									<tr>
										<td><?=$tipologia_abbonamento->tipo?></td>
										<td><?=$tipologia_abbonamento->durata?> giorni</td>
										<td><?=( $tipologia_abbonamento->freepass == 0 ? $tipologia_abbonamento->costo_base.' €' : '-' )?></td>
										<td><?=( $tipologia_abbonamento->freepass == 1 ? 'Freepass' : 'Normale' )?></td>
										<td><?=( $tipologia_abbonamento->freepass == 1 ? $tipologia_abbonamento->giorni_gratuiti_socio.' giorni' : '-' )?></td>
									</tr>
									<?php
										}
									} else {
									?>
									<tr><td colspan="5" class="text-center">Nessuna tipologia abbonamento di sistema presente</td></tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						
						<div class="row text-center sgp-row">
							<div class="col-lg-12">
								<button type="button" class="btn btn-success btn-sm sgp-insert-tipologia-abbonamento" data-toggle="modal" data-target="#modal"><i class="fa fa-pencil"></i> Inserisci nuova tipologia di abbonamento (palestra)</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<script>
	var url_edit_soglie_desk = '<?=base_url()?>parametripalestra/getFormEditSoglieMissedDesk';
	var url_edit_soglia_abbonamento = '<?=base_url()?>parametripalestra/getFormEditSogliaAlertAbbonamento';
	var url_edit_soglia_nuovi_soci = '<?=base_url()?>parametripalestra/getFormEditSogliaNuoviSoci';
	var url_edit_tipologia_abbonamento = '<?=base_url()?>parametripalestra/getFormEditTipologiaAbbonamento';
	var url_insert_tipologia_abbonamento = '<?=base_url()?>parametripalestra/getFormInsertTipologiaAbbonamento';
	var url_confirm_delete = '<?=base_url()?>parametripalestra/askConfirmDeleteTipologiaAbbonamento';
		
	$(document).ready(function(){
		listenerModal();
	});

	function listenerModal() {
		$('button[data-toggle="modal"]').click(function() {

			var id_button = $(this).attr("id");
			var classi = $(this).attr("class");
			//alert(classi);
			if(classi.indexOf("sgp-edit-soglie-desk") >= 0) {
				// EDIT

				var id = id_button.replace('edit-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_edit_soglie_desk+'/'+id, 
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

			} else if(classi.indexOf("sgp-edit-soglia-abbonamento") >= 0) {
				// EDIT
				var id = id_button.replace('edit-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_edit_soglia_abbonamento+'/'+id, 
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

			} else if(classi.indexOf("sgp-edit-soglia-nuovi-soci") >= 0) {
				// EDIT

				var id = id_button.replace('edit-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_edit_soglia_nuovi_soci+'/'+id, 
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

			} else if(classi.indexOf("sgp-edit-tipologia-abbonamento") >= 0) {
				// EDIT

				var id = id_button.replace('edit-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_edit_tipologia_abbonamento+'/'+id, 
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

			} else if(classi.indexOf("sgp-insert-tipologia-abbonamento") >= 0) {
				// INSERT

				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_insert_tipologia_abbonamento, 
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

			} else if(classi.indexOf("sgp-delete") >= 0) {
				// DELETE
				
				var id = id_button.replace('delete-','');
				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_confirm_delete+'/'+id, 
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
		
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			location.reload();
		});
	}
</script>