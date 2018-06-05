	<div id="page-wrapper">

		<div class="container-fluid">

			<!-- Page Heading -->
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">
						Rinnovi e Iscrizioni <small><?=$sub_titolo_pagina?></small>
					</h1>
				</div>
			</div>
			<!-- /.row -->
<?php if( $privilegi == 0 || $privilegi == 3 ) { ?>
			
			<!-- INSERIRE MESSAGGI PER IL DESK -->
			<?php if( $return_msg != '' ) { ?>
			<div class="row text-center">
				<div class="col-lg-12 <?=( $return_msg_type == 'failed' ? 'sgp-error-msg' : 'sgp-success-msg')?>" style="margin-bottom: 20px;">
					<?=$return_msg?>
				</div>
			</div>
			<?php } ?>
			
			<?php if( $privilegi == 3 ) { ?>
			<!-- /.row -->
			<div class="row text-center">
				<div class="col-lg-12" style="margin-bottom: 20px;">
					<a href="<?=base_url()?>listarinnoviiscrizioni/getFormInsert" class="btn btn-success btn-sm sgp-insert"><i class="fa fa-pencil"></i> Inserisci Rinnovo/Iscrizione</a>
					<!--<button type="button" class="btn btn-success btn-sm sgp-insert" data-toggle="modal" data-target="#modal"><i class="fa fa-pencil"></i> Crea nuova palestra</button>-->
				</div>				
			</div>
			<?php } ?>
			<!-- /.row -->
			<div class="row text-center sgp-btn-row">
				<form id="sgp-search-form" action="" method="post">
					<input type="text" name="filter" value="<?=$filter?>" hidden="true">
					<input type="text" name="id_palestra" value="<?=$id_palestra?>" hidden="true">
					<input type="text" name="search_words" class="sgp-search-bar"><button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i></button><!--<a href="javascript:void(0)" class="btn btn-sm btn-default sgp-search-btn" role="button" title="Cerca"><i class="fa fa-search"></i></a> -->
				</form>
			</div>
			<div class="row text-center sgp-btn-row">
				<div>
					<a href="<?=base_url();?>listarinnoviiscrizioni/p/1" class="btn btn-sm btn-default <?=( $filter == '' ? 'disabled' : '' )?>" role="button" title="Tutti i rinnovi/iscrizioni della palestra">Tutti</a> 
			<?php if( $privilegi == 3 ) { ?>
					<!--<a href="<?=base_url();?>listarinnoviiscrizioni/p/1/desk" class="btn btn-sm btn-default <?=( $filter == 'desk' ? 'disabled' : '' )?>" role="button" title="Tutti i tuoi rinnovi/iscrizioni">Tutti i miei</a>-->
			<?php } ?>
					<a href="<?=base_url()?>listarinnoviiscrizioni/p/1/fpwe" class="btn btn-sm btn-<?=( $number_free_pass_will_expire <= 0 ? 'default' : 'warning' )?> <?=( $filter == 'fpwe' ? 'disabled' : '' )?>" role="button" title="<?=( $number_free_pass_will_expire <= 0 ? '' : 'Sono presenti dei free-pass in scadenza' )?>"><?=( $privilegi == 3 ? 'I Miei ' : '' )?>Free-pass in scadenza</a> <a href="<?=base_url()?>listarinnoviiscrizioni/p/1/missed" class="btn btn-sm btn-<?=( $number_missed <= 0 ? 'default' : 'warning' )?> <?=( $filter == 'missed' ? 'disabled' : '' )?>" role="button" title="<?=( $number_missed <= 0 ? '' : 'Sono presenti degli utenti missed' )?>"><?=( $privilegi == 3 ? 'I Miei ' : '' )?>Missed</a>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default" id="sgp-tabella">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-address-card fa-fw"></i> Elenco Rinnovi/Iscrizioni</h3> <!-- Utenti Palestra</h3> -->
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
													if( ( $rinnovo_iscrizione->id_socio_registrato == '' || $rinnovo_iscrizione->missed == 1 ) && !$rinnovo_iscrizione->delete_lock ) {
													?> 
													<!--<a href="<?=base_url()?>listarinnoviiscrizioni/getFormEdit/<?=$rinnovo_iscrizione->id;?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a>-->
													<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$rinnovo_iscrizione->id;?>" title="Elimina"><i class="fa fa-trash"></i></button>
													<?php
													}
													?>
												</div>
												<?php
												if( $rinnovo_iscrizione->missed == 1 && !$rinnovo_iscrizione->delete_lock ) {
												?> 
												<div class="sgp-btn-row"> 
													<a href="<?=base_url()?>listarinnoviiscrizioni/getFormInsertPrecompilato/<?=$rinnovo_iscrizione->id;?>" class="btn btn-sm btn-success sgp-edit-btn" role="button" title="">Compila Rinnovo/Iscrizione</a>
												</div>
												<?php
												}
												?>
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
										<tr><td colspan="<?=( $privilegi == 0 ? '8' : '7' )?>" class="text-center">Nessuna rinnovo iscrizione nel <?=( $privilegi == 0 ? 'sistema' : 'tuo profilo desk' )?></td></tr>
									<?php
										}
									?>	
									</tbody>
								</table>
							</div>
							<div class="text-center">
								<?php
							 		for($i=0; $i<$numero_pagine; $i++) {
										if( $numero_pagina == ($i+1) ) {
								?>
								<a href="<?=base_url();?>listarinnoviiscrizioni/p/<?=($i+1);?>/<?=$filter?>" class="btn btn-sm btn-default disabled" role="button" title=""><?=($i+1);?></a>
								<?php
										} else {
								?>
								<a href="<?=base_url();?>listarinnoviiscrizioni/p/<?=($i+1);?>/<?=$filter?>" class="btn btn-sm btn-default" role="button" title=""><?=($i+1);?></a>
								<?php			
										}
									}
							 	?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.row -->

			<!-- MODAL -->
			
			
			<div id="modal" class="modal fade" role="dialog">
			
			</div>
			<!--<div id="modal2" class="modal fade in" role="dialog" style="display: block; padding-right: 17px;">-->
			
<?php } ?>
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- /#page-wrapper -->
	<script>
		var url_confirm_delete = '<?=base_url()?>listarinnoviiscrizioni/AskConfirmDelete';		
		var url_search = '<?=base_url()?>listarinnoviiscrizioni/searchRinnoviIscrizioni';
		var url_iscrivi_confirm_ask = '<?=base_url()?>listarinnoviiscrizioni/AskConfirmIscrizione';
		
		$(document).ready(function(){
			
			$('#sgp-search-form').submit(function(e) {
				e.preventDefault();
				var form_data = $(this).serialize();
				
				$.ajax({
					type: "POST",
					data: form_data,
					url: url_search, 
					dataType: "html",
					success:function(data){
						if( data == -1 ) {
							location.reload();
						}
						$('#sgp-tabella').html(data);
						listenerModal();
					}
				});
			});
			
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
							
							/*
							$('#conferma-delete-palestra').click(function(){
								$.ajax({
									type: "POST",
									url: url_delete+'/'+id, 
									dataType: "html",
									success:function(data){
										testo_modal = data;
										$('#modal').html(testo_modal);
										
										reloadPagina();
									}

								});
							});*/
						}

					});	

				} else if (classi.indexOf("sgp-iscrivi") >= 0) {
					var id = id_button.replace('iscrivi-','');
					var testo_modal = '';
					$.ajax({
						type: "POST",
						url: url_iscrivi_confirm_ask+'/'+id, 
						dataType: "html",
						success:function(data){
							testo_modal = data;
							if( testo_modal == -1 ) {
								location.reload();
							}
							$('#modal').html(testo_modal);
							
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

</div>
<!-- /#wrapper -->    
