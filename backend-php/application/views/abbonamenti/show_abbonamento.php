<?php if(false) { ?>
<link href="../../../public/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<?php } ?>
<div id="page-wrapper">

	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Visualizzazione Abbonamenti Socio <small><?=$abbonamento->nome_socio?> <?=$abbonamento->cognome_socio?></small>
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
		<?php
		if( $abbonamento->scaduto ) {
		?>
		<div class="row">
			<div class="col-lg-7">
				<div class="alert alert-danger alert-dismissable">
					Attenzione, questo abbonamento non è più valido!
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				</div>
			</div>
		</div>
		<?php
		}
		?>
		<?php
		if( !$abbonamento->attivo && !$abbonamento->scaduto ) {
		?>
		<div class="row">
			<div class="col-lg-7">
				<div class="alert alert-danger alert-dismissable">
					Attenzione, questo abbonamento non è attivo!
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				</div>
			</div>
		</div>
		<?php
		}
		?>
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listasoci/showSocio/<?=$abbonamento->id_socio?>" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a> 
				<?php
				if( !$workout_lock ) {
				?>
				<a href="<?=base_url()?>pdf/workOut/<?=$abbonamento->id?>" class="btn btn-sm btn-success sgp-back-btn" role="button" title="" target="_blank"><i class="fa fa-print"></i> Stampa WorkOut</a>
				<?php
				}
				?>
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
									<div class="col-lg-6"><strong>Socio:</strong> </div>
									<div class="col-lg-6"><?=$abbonamento->nome_socio?> <?=$abbonamento->cognome_socio?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Tipo abbonamento:</strong> </div>
									<div class="col-lg-6"><?=$abbonamento->tipo_abbonamento?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Data di inizio:</strong> </div>
									<div class="col-lg-6"><?=$abbonamento->data_inizio_str?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Data di fine:</strong> </div>
									<div class="col-lg-6"><?=$abbonamento->data_fine_str?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Durata:</strong> </div>
									<div class="col-lg-6"><?=$abbonamento->durata?> giorni</div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Valore Abbonamento:</strong> </div>
									<div class="col-lg-6">&euro; <?=$abbonamento->valore_abbonamento?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Residuo da pagare:</strong> </div>
									<div class="col-lg-6">&euro; <?=$residuo_da_pagare?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Attivo:</strong> </div>
									<div class="col-lg-6"><?=( $abbonamento->attivo == 0 ? '<strong>No</strong>' : 'Sì' )?></div>
								</div>
								<?php
								if( $numero_bonus_socio > 0 ) {
								?>
								<div class="row">
									<div class="col-lg-6">
										<strong><?=( $numero_bonus_socio == 1 ? 'Un bonus disponibile' : $numero_bonus_socio.' bonus disponibili' )?>:</strong> 
									</div>
									<div class="col-lg-6">
										<button type="button" class="btn btn-success btn-sm sgp-bonus" data-toggle="modal" data-target="#modal" title="Bonus">Applica Bonus</button>
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
						<h3 class="panel-title"><i class="fa fa-credit-card fa-fw"></i> Rate dei pagamenti da saldare</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th></th>
										<th>Scadenza</th>
										<th>Numero Rata</th>
										<th>Importo Rata</th>
										<th>Importo Pagato</th>
										<th>Importo Residuo</th>
										<th>Tipo</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if( count($rate_da_saldare) > 0 ) {
										$i=0;
										foreach( $rate_da_saldare as $rata ) {
											//if( $rata->residuo > 0 ) {
									?>
									<tr>
										<td>
											<a href="<?=base_url()?>listarate/showRata/<?=$rata->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a>
									<?php
											//if( !$rata->lock ) {
									?> 
											<!--<a href="<?=base_url()?>listarate/getFormEdit/<?=$rata->id?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a>
											<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$rata->id?>" title="Elimina"><i class="fa fa-trash"></i></button>-->
									<?php
											//}
									?>
									<?php
											if( $i == 0 ) {
									?>
											<a href="<?=base_url()?>listapagamenti/getFormInsert/<?=$rata->id?>" class="btn btn-sm btn-success sgp-show-btn" role="button" title="Paga Rata"><i class="fa fa-check"></i> Paga</a>
									<?php
											}
											$i++;
									?>
										</td>
										<td><?=( $rata->scaduta ? '<div><strong>Scaduta</strong></div>' : '' )?><div><?=$rata->data_scadenza_str?></div></td>
										<td><?=$rata->numero_sequenziale_romano?></td>
										<td>&euro; <?=$rata->valore_rata?></td>
										<td>&euro; <?=$rata->pagato?></td>
										<td>&euro; <?=$rata->residuo?></td>
										<td><?=( $rata->tipo == 0 ? 'Acconto' : 'Saldo' )?></td>
									</tr>
									<?php
											//}
										}
									} else {
									?>
									<tr>
										<td colspan="7" class="text-center">Nessuna rata da saldare presente</td>
									</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="row text-center">
							<div class="col-lg-12" style="margin-bottom: 20px;">
								<?php
								if( count($rate) <= 0 ) {
								?>
								<!--<div <?=( count($rate) > 0 ? 'class="sgp-disabled-btn" title="Le rate sono già impostate"' : '' )?>>-->
								<a href="<?=base_url()?>listarate/getFormInsert/<?=$abbonamento->id?>" class="btn btn-success btn-sm sgp-insert"><i class="fa fa-pencil"></i> Inserisci rate</a>
								<!--</div>-->
								<?php
								}
								?>
								
								<?php
								if( !$lock_delete_rate && count($rate) > 0 ) {
								?>
								<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$abbonamento->id?>" title="Elimina"><i class="fa fa-trash"></i> Elimina tutte le rate</button>
								<?php
								}
								?>
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
						<h3 class="panel-title"><i class="fa fa-credit-card fa-fw"></i> Rate dei pagamenti Saldate</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th></th>
										<th>Numero Rata</th>
										<th>Tipo</th>
										<th>Importo Rata</th>
										<th>Importo Pagato</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if( count($rate_saldate) > 0 ) {
										foreach( $rate_saldate as $rata ) {
											//if( $rata->residuo <= 0 ) {
									?>
									<tr>
										<td>
											<a href="<?=base_url()?>listarate/showRata/<?=$rata->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a>
										</td>
										<td><?=$rata->numero_sequenziale_romano?></td>
										<td><?=( $rata->tipo == 0 ? 'Acconto' : 'Saldo' )?></td>
										<td>&euro; <?=$rata->valore_rata?></td>
										<td>&euro; <?=$rata->pagato?></td>
									</tr>
									<?php
											//}
										}
									} else {
									?>
									<tr>
										<td colspan="5" class="text-center">Nessuna rata saldata presente</td>
									</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listasoci/showSocio/<?=$abbonamento->id_socio?>" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a> <!--<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$socio->id?>"><i class="fa fa-trash"></i> Elimina</button>-->
			</div>
		</div>
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<script>
	var url_confirm_delete = '<?=base_url()?>listarate/AskConfirmDelete';
	
	var url_scelta_bonus = '<?=base_url()?>listaabbonamenti/AskBonus';
	
	var id_abbonamento = '<?=$abbonamento->id?>';
	var id_socio = '<?=$abbonamento->id_socio?>';
		
	$(document).ready(function(){
		listenerModal();
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

			} else if(classi.indexOf("sgp-bonus") >= 0) {
				
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_scelta_bonus+'/'+id_socio+'/'+id_abbonamento, 
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
	
	/*
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
	*/
	
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			location.reload();
		});
	}
	
</script>
	