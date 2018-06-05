  <div id="page-wrapper">

		<div class="container-fluid">

			<!-- Page Heading -->
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">
						Soci <small><?=$sub_titolo_pagina?></small>
					</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="panel <?=$bgcolor_counter?>">
						<div class="panel-heading">
							<div class="row">
								<div class="col-xs-3">
									<i class="fa fa-users fa-5x"></i>
								</div>
								<!-- NEL CASO DI UN SU-ADMIN LOGGATO MOSTRA IL CONTATORE DI TUTTE LE PALESTRE-->
								<div class="col-xs-9 text-right">
									<div class="huge"><?=$numero_soci; ?></div> <!-- si ottiene con getAllPalestre()); -->
									<div><?=$testo_counter?></div> <!--- VARIANTE PER USER NON SU-ADMIN <div>Numero utenti palestra</div> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.row -->
			<!--
			<div class="row text-center">
				<div class="col-lg-12" style="margin-bottom: 20px;">
					<a href="<?=base_url()?>listasoci/getFormInsert" class="btn btn-success btn-sm sgp-insert"><i class="fa fa-pencil"></i> Crea nuovo socio</a>
				</div>				
			</div>
			-->
			<!-- /.row -->
			<div class="row text-center">
				<form id="sgp-search-form" action="" method="post">
					<input type="text" name="search_words" class="sgp-search-bar"><button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
				</form>
			</div>
			
			<div class="row sgp-btn-row">
				<div class="col-lg-12 text-center">
					<a href="<?=base_url()?>pdf/recapitiSoci/<?=$id_palestra?>" class="btn btn-sm btn-success sgp-back-btn" role="button" title="" target="_blank"><i class="fa fa-print"></i> Stampa Recapiti Soci</a>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default" id="sgp-tabella">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-users fa-fw"></i> Elenco Soci</h3> <!-- Utenti Palestra</h3> -->
						</div>
						<div class="panel-body">
							
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th></th>
											<th>Cognome e Nome</th>
											<th>Data d'iscrizione</th>
											<th>Email</th>
											<th>Stato abbonamenti</th>
										</tr>
									</thead>
									<tbody>
									
									<?php
							 			if( count($soci) > 0) {
											foreach($soci as $socio) {
									?>
										<tr>
											<td>
												<a href="<?=base_url()?>listasoci/showSocio/<?=$socio->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a> 
												<a href="<?=base_url()?>listasoci/getFormEdit/<?=$socio->id?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a>
												<?php 
												if( !$socio->lock ) {
												?>
												<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$socio->id?>" title="Elimina"><i class="fa fa-trash"></i></button>
												<?php
												} else {
												?>
												<span class="sgp-disabled-btn" title="Impossibile eliminare, ci sono abbonamenti"><a href="" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></span>
												<?php
												}
												?>
											</td>
											<td><?=$socio->cognome?> <?=$socio->nome?></td>
											<td><?=$socio->data_iscrizione_str?></td>
											<td><a href="mailto:<?=$socio->email?>"><?=$socio->email?></a></td>
											<td>
											<?php
											if( $socio->numero_abbonamenti_attivi > 0 ) {
												if( $socio->numero_abbonamenti_validi > 0 ) {
											?>
												<div>Validi: <?=$socio->numero_abbonamenti_validi?></div>
											<?php		
												}
												if( $socio->numero_abbonamenti_scaduti > 0 ) {
											?>
												<div>Scaduti: <?=$socio->numero_abbonamenti_scaduti?></div>
											<?php		
												}
											} else {
											?>
												<div>Nessuno</div>
											<?php
											}
											?>
											</td>
										</tr>
									<?php
											}
										} else {
									?>
										<tr><td colspan="6" class="text-center">Nessun utente nel sistema</td></tr>
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
								<a href="<?=base_url();?>listasoci/p/<?=($i+1);?>" class="btn btn-sm btn-default disabled" role="button" title=""><?=($i+1);?></a>
								<?php
										} else {
								?>
								<a href="<?=base_url();?>listasoci/p/<?=($i+1);?>" class="btn btn-sm btn-default" role="button" title=""><?=($i+1);?></a>
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
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- /#page-wrapper -->
	<script>
		var url_confirm_delete = '<?=base_url()?>listasoci/AskConfirmDelete';
		//var url_delete = '<?=base_url()?>utenteController/deleteUtente';
		
		var url_search = '<?=base_url()?>listasoci/searchSocio';
		
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
							
							// il listener del form è nella view del pupup
						}

					});	

				} 
			});
		}
		
	</script>

</div>
<!-- /#wrapper -->    