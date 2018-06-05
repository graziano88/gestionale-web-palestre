<?php if(false) { ?>
<link href="../../../public/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<?php } ?>
<div id="page-wrapper">

	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Visualizzazione Pagamento Rata</span> <small>Socio: <?=$nome_socio?> <?=$cognome_socio?></small>
				</h1>
			</div>
		</div>
		
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listarate/showRata/<?=$pagamento->id_rata?>" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a> <!--<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$pagamento->id?>"><i class="fa fa-trash"></i> Elimina</button>-->
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-credit-card fa-fw"></i> Informazioni pagamento</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-5"><strong>Socio:</strong> </div>
									<div class="col-lg-6"><?=$nome_socio?> <?=$cognome_socio?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Tipo abbonamento:</strong> </div>
									<div class="col-lg-6"><?=$tipologia_abbonamento?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Data e Ora:</strong> </div>
									<div class="col-lg-6"><?=$pagamento->data?> <?=$pagamento->ora?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Numero Ricevuta:</strong> </div>
									<div class="col-lg-6"><?=$pagamento->numero_ricevuta?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Desk:</strong> </div>
									<div class="col-lg-6"><?=$pagamento->desk->nome?> <?=$pagamento->desk->cognome?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Importo pagato:</strong> </div>
									<div class="col-lg-6">&euro; <?=$pagamento->importo_pagato?></div>
								</div>
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
				<a href="<?=base_url()?>listarate/showRata/<?=$pagamento->id_rata?>" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a>
			</div>
		</div>
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<script>
	var url_confirm_delete = '<?=base_url()?>listapagamenti/AskConfirmDelete';
	var change_stato_abbonamento = '<?=base_url()?>abbonamentocontroller/changeStatoAbbonamento';
		
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
	