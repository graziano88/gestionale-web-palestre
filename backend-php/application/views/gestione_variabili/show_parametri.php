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
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-6"><strong>I Alert dopo:</strong> </div>
									<div class="col-lg-6"><?=$soglia->giorni_primo_alert?> giorni</div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>II Alert dopo:</strong> </div>
									<div class="col-lg-6"><?=$soglia->giorni_secondo_alert?> giorni</div>
								</div>
								<div class="row">
									<div class="col-lg-6"><strong>Scadenza dopo:</strong> </div>
									<div class="col-lg-6"><?=$soglia->scadenza?> giorni</div>
								</div>
							</div>
						</div>
						<div class="row text-center sgp-row">
							<div class="col-lg-12">
								<button type="button" class="btn btn-warning btn-sm sgp-edit-soglie-desk" data-toggle="modal" data-target="#modal"  id="edit-<?=$soglia->id?>"><i class="fa fa-pencil"></i> Modifica Soglie</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php
		if( $privilegi == 0 ) {
		?>
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Parametri Sistema</h3>
					</div>
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-6"><strong>Soglia Alert Scadenza Palestre prima di:</strong> </div>
									<div class="col-lg-6"><?=$parametri_palestra->alert_scadenza_palestre?> giorni</div>
								</div>
							</div>
						</div>
						<div class="row text-center sgp-row">
							<div class="col-lg-12">
								<button type="button" class="btn btn-warning btn-sm sgp-edit-parametri-sistema" data-toggle="modal" data-target="#modal"  id="edit-<?=$parametri_palestra->id?>"><i class="fa fa-pencil"></i> Modifica Soglie</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		}
		?>
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<script>
	var url_edit_soglie_desk = '<?=base_url()?>gestionevariabili/getFormEditSogliaMissedDesk';
	var url_edit_parametri_sistema = '<?=base_url()?>gestionevariabili/getFormEditParametriSistema';
		
	$(document).ready(function(){
		listenerModal();
	});

	function listenerModal() {
		$('button[data-toggle="modal"]').click(function() {

			var id_button = $(this).attr("id");
			var classi = $(this).attr("class");
			//alert(classi);
			if (classi.indexOf("sgp-edit-soglie-desk") >= 0) {
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

			} else if (classi.indexOf("sgp-edit-parametri-sistema") >= 0) {
				// EDIT

				var id = id_button.replace('edit-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_edit_parametri_sistema+'/'+id, 
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
	