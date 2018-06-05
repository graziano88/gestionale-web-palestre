<?php if(false) { ?>
<link href="../../../public/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<?php } ?>
<div id="page-wrapper">

	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Visualizzazione <?=$rata->numero_rata_romano?> Rata <small>Socio: <?=$nome_socio?> <?=$cognome_socio?></small>
				</h1>
			</div>
		</div>
		<?php
		if( $rata->scaduta ) {
		?>
		<div class="row">
			<div class="col-lg-7">
				<div class="alert alert-danger alert-dismissable">
					Attenzione, questa rata è scaduta!
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				</div>
			</div>
		</div>
		<?php
		}
		?>
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listaabbonamenti/showAbbonamento/<?=$rata->id_abbonamento?>" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-credit-card fa-fw"></i> Informazioni Rata</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-6"><strong>Per:</strong> </div>
									<div class="col-lg-6"><?=( $rata->per == 0 ? 'Nuova iscrizione' : 'Rinnovo' )?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Tipo:</strong> </div>
									<div class="col-lg-6"><?=( $rata->tipo == 0 ? 'Acconto' : 'Saldo' )?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Da pagare:</strong> </div>
									<div class="col-lg-6">&euro; <?=$rata->valore_rata?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Pagato finora:</strong> </div>
									<div class="col-lg-6">&euro; <?=$rata->pagato?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Residuo da pagare:</strong> </div>
									<div class="col-lg-6">&euro; <?=$rata->residuo?></div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Data scadenza della rata:</strong> </div>
									<div class="col-lg-6"><?=( $rata->scaduta ? 'Scaduta il: ' : '' )?><?=$rata->data_scadenza_str?></div>
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
						<h3 class="panel-title"><i class="fa fa-credit-card fa-fw"></i> Pagamenti relativi all'abbonamento</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th></th>
										<!--<th>Numero Ricevuta</th>-->
										<th>Data e Ora</th>
										<th>Importo Pagato</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if( count($pagamenti) > 0 ) {
										foreach( $pagamenti as $pagamento ) {
									?>
									<tr>
										<td>
											<a href="<?=base_url()?>listapagamenti/showPagamento/<?=$pagamento->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a>
									<?php
											if( !$pagamento->lock ) {
									?> 
											<a href="<?=base_url()?>listapagamenti/getFormEdit/<?=$pagamento->id?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a>
											<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$pagamento->id?>" title="Elimina"><i class="fa fa-trash"></i></button>
									<?php
											}
									?>
										</td>
										<!--<td><?=$pagamento->numero_ricevuta?></td>-->
										<td><?=$pagamento->data?> <?=$pagamento->ora?></td>
										<td>&euro; <?=$pagamento->importo_pagato?></td>
									</tr>
									<?php
										}
									} else {
									?>
									<tr>
										<td colspan="3" class="text-center">Nessun pagamento presente</td>
									</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="row text-center">
							<div class="col-lg-12" style="margin-bottom: 20px;">
								<div <?=( $rata->residuo <= 0 ? 'class="sgp-disabled-btn" title="L\'abbonamento è stato saldato"' : '' )?>>
									<a href="<?=base_url()?>listapagamenti/getFormInsert/<?=$rata->id?>" class="btn btn-success btn-sm sgp-insert"><i class="fa fa-pencil"></i> Inserisci nuovo pagamento</a>
								</div>
							</div>				
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listaabbonamenti/showAbbonamento/<?=$rata->id_abbonamento?>" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a>
			</div>
		</div>
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<script>
	var url_confirm_delete = '<?=base_url()?>listapagamenti/AskConfirmDelete';
	//var change_stato_abbonamento = '<?=base_url()?>abbonamentocontroller/changeStatoAbbonamento';
		
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

			}
		});
	}
	
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
	
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			location.reload();
		});
	}
	
</script>