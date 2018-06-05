<?php if(false) { ?>
<link href="../../../public/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<?php } ?>
<div id="page-wrapper">

	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Visualizzazione Rinnovo/Iscrizione <small><?=$rinnovo_iscrizione->nome?> <?=$rinnovo_iscrizione->cognome?></small>
				</h1>
			</div>
		</div>
		
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listarinnoviiscrizioni" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a> <!--<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$rinnovo_iscrizione->id?>"><i class="fa fa-trash"></i> Elimina</button>-->
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Anagrafica</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-12">
								<div class="row">
									<div class="col-lg-5"><strong>Data:</strong> </div>
									<div class="col-lg-6"><?=$rinnovo_iscrizione->data_str?> <?=$rinnovo_iscrizione->ora_str?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Tipo incontro:</strong> </div>
									<div class="col-lg-6"><?=$rinnovo_iscrizione->tipo_rinnovo_iscrizione?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Nome Desk:</strong> </div>
									<div class="col-lg-6">
										<?php
										if( $privilegi == 0 ) {
										?>
										<a href="<?=base_url()?>listautenti/showUtente/<?=$rinnovo_iscrizione->id_consulente?>"><?=$rinnovo_iscrizione->nome_desk?> <?=$rinnovo_iscrizione->cognome_desk?></a>
										<?php
											if( $rinnovo_iscrizione->nome_coordinatore != '' || $rinnovo_iscrizione->cognome_coordinatore != '' ) {
										?>
										(Coordinatore: <a href="<?=base_url()?>listautenti/showUtente/<?=$rinnovo_iscrizione->id_coordinatore?>"><?=$rinnovo_iscrizione->nome_coordinatore?> <?=$rinnovo_iscrizione->cognome_coordinatore?></a>)
										<?php
											}
										} else {
										?>
										<?=$rinnovo_iscrizione->nome_desk?> <?=$rinnovo_iscrizione->cognome_desk?></a>
										<?php
											if( $rinnovo_iscrizione->nome_coordinatore != '' || $rinnovo_iscrizione->cognome_coordinatore != '' ) {
										?>
										(Coordinatore: <?=$rinnovo_iscrizione->nome_coordinatore?> <?=$rinnovo_iscrizione->cognome_coordinatore?>)
										<?php
											}
										}
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Nome:</strong> </div>
									<div class="col-lg-6"><?=$rinnovo_iscrizione->nome?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Cognome:</strong> </div>
									<div class="col-lg-6"><?=$rinnovo_iscrizione->cognome?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Cellulare:</strong> </div>
									<div class="col-lg-6"><?=( $rinnovo_iscrizione->cellulare != '' ? $rinnovo_iscrizione->cellulare : 'n.d.')?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Telefono Fisso:</strong> </div>
									<div class="col-lg-6"><?=( $rinnovo_iscrizione->telefono != '' ? $rinnovo_iscrizione->telefono : 'n.d.')?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>E-mail:</strong> </div>
									<div class="col-lg-6"><?=$rinnovo_iscrizione->email?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Come Back:</strong> </div>
									<div class="col-lg-6"><?=( $rinnovo_iscrizione->come_back == 0 ? 'No' : 'Sì' )?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Free Pass:</strong> </div>
									<div class="col-lg-6"><?=( $rinnovo_iscrizione->free_pass == 0 ? 'No' : 'Sì' )?></div>
								</div>
								<?php
								if( $rinnovo_iscrizione->free_pass == 1 ) {
								?>
								<div class="row">
									<div class="col-lg-5"><strong>Tipo Free Pass:</strong> </div>
									<div class="col-lg-6"><?=$rinnovo_iscrizione->tipo_abbonamento?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Socio Presentatore:</strong> </div>
									<div class="col-lg-6"><a href="<?=base_url()?>listasoci/showSocio/<?=$rinnovo_iscrizione->id_socio_presentatore?>"><?=$rinnovo_iscrizione->nome_socio_presentatore?> <?=$rinnovo_iscrizione->cognome_socio_presentatore?></a></div>
								</div>
								<?php
								}
								?>
								<div class="row">
									<div class="col-lg-5"><strong>Missed:</strong> </div>
									<div class="col-lg-6"><?=( $rinnovo_iscrizione->missed == 0 ? 'No' : 'Sì' )?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Motivazione frequenza:</strong> </div>
									<div class="col-lg-6"><?=$rinnovo_iscrizione->motivazione_frequenza?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Note:</strong> </div>
									<div class="col-lg-6"><?=$rinnovo_iscrizione->note?></div>
								</div>
								
								<?php
								if( $rinnovo_iscrizione->id_socio_registrato != '' ) {
								?>
								<div class="row">
									<div class="col-lg-5"><strong>Socio Registrato:</strong> </div>
									<div class="col-lg-6">
										<a href="<?=base_url()?>listasoci/showSocio/<?=$rinnovo_iscrizione->id_socio_registrato?>"><?=$rinnovo_iscrizione->nome_socio_registrato?> <?=$rinnovo_iscrizione->cognome_socio_registrato?></a>
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
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listarinnoviiscrizioni" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a> <!--<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$socio->id?>"><i class="fa fa-trash"></i> Elimina</button>-->
			</div>
		</div>
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<script>
	var url_confirm_delete_abbonamento = '<?=base_url()?>listaabbonamenti/AskConfirmDelete';
	var change_stato_abbonamento = '<?=base_url()?>abbonamentocontroller/changeStatoAbbonamento';
		
	$(document).ready(function(){
		listenerModal();
	});
	
	$('#changeStatoAbbonamento').click(function() {
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
			if (classi.indexOf("sgp-delete") >= 0) {
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
	