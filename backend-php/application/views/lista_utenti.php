  <div id="page-wrapper">

		<div class="container-fluid">

			<!-- Page Heading -->
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">
						Utenti <small><?=$sub_titolo_pagina?></small>
					</h1>
				</div>
			</div>
			<!-- /.row -->
<?php if( $privilegi <= 1 ) { ?>
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
									<div class="huge"><?=$numero_utenti; ?></div> <!-- si ottiene con getAllPalestre()); -->
									<div><?=$testo_counter?></div> <!--- VARIANTE PER USER NON SU-ADMIN <div>Numero utenti palestra</div> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.row -->

			<div class="row text-center">
				<div class="col-lg-12" style="margin-bottom: 20px;">
					<a href="<?=base_url()?>listautenti/getFormInsert" class="btn btn-success btn-sm sgp-insert"><i class="fa fa-pencil"></i> Crea nuovo utente</a>
				</div>				
			</div>
			<!-- /.row -->
			<div class="row text-center">
				<form id="sgp-search-form" action="" method="post">
					<input type="text" name="search_words" class="sgp-search-bar"><button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
				</form>
			</div>
			<div class="row">


				<div class="col-lg-12">
					<div class="panel panel-default" id="sgp-tabella">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-users fa-fw"></i> Elenco Utenti</h3> <!-- Utenti Palestra</h3> -->
						</div>
						<div class="panel-body">
							
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th></th>
											<th>Username</th>
											<th>Palestra</th>
											<th>Cognome e Nome</th>
											<th>Ruolo</th>
											<th>Email</th>
										</tr>
									</thead>
									<tbody>
									
									<?php
							 			if( count($utenti) > 0) {
											foreach($utenti as $utente) {
									?>
										<tr>
											<td>
												<a href="<?=base_url()?>listautenti/showUtente/<?=$utente->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a> 
												<!--<a href="<?=base_url()?>listautenti/getFormEdit/<?=$utente->id?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a>-->
												<?php
												if( $utente->ruolo > 0 && $utente->id != $id_utente && !$utente->delete_lock ) {
												?>
												<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$utente->id?>"><i class="fa fa-trash"></i></button>
												<?php
												}
												?>
											</td> <!-- DISPONIBILE PER SU-ADMIN E ADMIN PALESTRA -->
											<td><?=$utente->username?></td>
											<td><?=$utente->nome_palestra?></td>
											<td><?=$utente->cognome?> <?=$utente->nome?></td>
											<td><?=$utente->ruolo_str?></td>
											<td><a href="mailto:<?=$utente->email?>"><?=$utente->email?></a></td>
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
								<a href="<?=base_url();?>listautenti/p/<?=($i+1);?>/<?=$filter?>" class="btn btn-sm btn-default disabled" role="button" title=""><?=($i+1);?></a>
								<?php
										} else {
								?>
								<a href="<?=base_url();?>listautenti/p/<?=($i+1);?>/<?=$filter?>" class="btn btn-sm btn-default" role="button" title=""><?=($i+1);?></a>
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
<?php } ?>
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- /#page-wrapper -->
	<script>
		var url_confirm_delete = '<?=base_url()?>listautenti/AskConfirmDelete';
		//var url_delete = '<?=base_url()?>utenteController/deleteUtente';
		
		var url_search = '<?=base_url()?>listautenti/searchUtente';
		
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