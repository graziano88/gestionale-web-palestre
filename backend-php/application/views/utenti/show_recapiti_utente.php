<?php if(false) { ?>
<link href="../../../public/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<?php } ?>
<div id="page-wrapper">

	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Visualizzazione recapiti telefonici <small>profilo: <?=$username?></small>
				</h1>
			</div>
		</div>
		<div class="row sgp-btn-row"><!-- ACTION BAR -->
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listautenti/showUtente/<?=$id_utente?>" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a>
			</div>
		</div>
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<button type="button" class="btn btn-success btn-sm sgp-insert-recapito" data-toggle="modal" data-target="#modal" id="insert-recapito"><i class="fa fa-pencil"></i> Inserisci nuovo recapito telefonico</button>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-phone fa-fw"></i> Recapiti Telefonici</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th></th>
										<th>Tipo Contatto</th>
										<th>Numero</th>
									</tr>
								</thead>
								<tbody>
		<?php 
		if( count($contatti) > 0 ) {
			foreach( $contatti as $contatto ) {
		?>
									<tr>
										<td><button type="button" class="btn btn-warning btn-sm sgp-edit-recapito" data-toggle="modal" data-target="#modal" id="edit-recapito-<?=$contatto->id?>"><i class="fa fa-pencil"></i></button> <button type="button" class="btn btn-danger btn-sm sgp-delete-recapito" data-toggle="modal" data-target="#modal" id="delete-recapito-<?=$contatto->id?>"><i class="fa fa-trash"></i></button></td>
										<td><?=$contatto->tipologia_str?></td>
										<td><?=$contatto->numero?></td>
									</tr>
		<?php
			}
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
				<button type="button" class="btn btn-success btn-sm sgp-insert-recapito" data-toggle="modal" data-target="#modal" id="insert-recapito"><i class="fa fa-pencil"></i> Inserisci nuovo recapito telefonico</button>
			</div>
		</div>
		
		<div class="row sgp-btn-row"><!-- ACTION BAR -->
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listautenti/showUtente/<?=$id_utente?>" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a>
			</div>
		</div>
		
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<script>
	
	var url_confirm_delete_recapito = '<?=base_url()?>listautenti/askConfirmDeleteRecapito';
	//var url_delete_recapito = '<?=base_url()?>utentiController/deleteRecapito';
	var url_edit_form_recapito = '<?=base_url()?>listautenti/getFormEditRecapitoUtente';
	
	var url_insert_form_recapito = '<?=base_url()?>listautenti/getFormInsertRecapitoUtente';
	
	var id_utente = '<?=$id_utente?>';
	
	var url_redirect = '<?=base_url()?>listautenti/editContattiUtente/'+id_utente;
	
	
	$(document).ready(function(){
		listenerModal();
	});

	function listenerModal() {
		$('button[data-toggle="modal"]').click(function() {

			var id_button = $(this).attr("id");
			var classi = $(this).attr("class");
			
			if (classi.indexOf("sgp-delete-recapito") >= 0) {
				/* DELETE */

				var id = id_button.replace('delete-recapito-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_confirm_delete_recapito+'/'+id, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);
						
						// il listener del form è nella view del pupup
						reloadPagina();
					}

				});	
			} else if (classi.indexOf("sgp-edit-recapito") >= 0) {
				/* EDIT */

				var id = id_button.replace('edit-recapito-','');

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_edit_form_recapito+'/'+id, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);

						reloadPagina();
						
					}

				});	
			} else if (classi.indexOf("sgp-insert-recapito") >= 0) {
				
				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_insert_form_recapito+'/'+id_utente, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);

						reloadPagina();
						
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