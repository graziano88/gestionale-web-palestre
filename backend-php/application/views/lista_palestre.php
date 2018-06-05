  <div id="page-wrapper">

		<div class="container-fluid">

			<!-- Page Heading -->
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">
						Palestre <small><?=$sub_titolo_pagina?></small>
					</h1>
				</div>
			</div>
			<!-- /.row -->
<?php if( $privilegi == 0 ) { ?>
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="panel <?=$bgcolor_counter_palestre?>">
						<div class="panel-heading">
							<div class="row">
								<div class="col-xs-3">
									<i class="fa fa-trophy fa-5x"></i>
								</div>
								<!-- NEL CASO DI UN SU-ADMIN LOGGATO MOSTRA IL CONTATORE DI TUTTE LE PALESTRE-->
								<div class="col-xs-9 text-right">
									<div class="huge"><?=$numero_palestre; ?></div> <!-- si ottiene con getAllPalestre()); -->
									<div><?=$testo_counter_palestre?></div> <!--- VARIANTE PER USER NON SU-ADMIN <div>Numero utenti palestra</div> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.row -->

			<div class="row text-center">
				<div class="col-lg-12" style="margin-bottom: 20px;">
					<a href="<?=base_url()?>listapalestre/getFormInsert" class="btn btn-success btn-sm sgp-insert"><i class="fa fa-pencil"></i> Crea nuova palestra</a>
					<!--<button type="button" class="btn btn-success btn-sm sgp-insert" data-toggle="modal" data-target="#modal"><i class="fa fa-pencil"></i> Crea nuova palestra</button>-->
				</div>				
			</div>
			<!-- /.row -->
			<div class="row text-center">
				<form id="sgp-search-form" action="" method="post">
					<input type="text" name="search_words" class="sgp-search-bar"><button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i></button><!--<a href="javascript:void(0)" class="btn btn-sm btn-default sgp-search-btn" role="button" title="Cerca"><i class="fa fa-search"></i></a> -->
				</form>
			</div>
			<div class="row">


				<div class="col-lg-12">
					<div class="panel panel-default" id="sgp-tabella">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-trophy fa-fw"></i> Elenco Palestre</h3> <!-- Utenti Palestra</h3> -->
						</div>
						<div class="panel-body">
							
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th></th>
											<th>Nome Palestra</th>
											<th>Indirizzo</th>
											<th>Ubicazione</th>
											<th>Sito web</th>
											<th>Email</th>
										</tr>
									</thead>
									<tbody>
									
									<?php
							 			if( count($palestre) > 0) {
											foreach($palestre as $palestra) {
									?>
										<tr>
											<td>
												<a href="<?=base_url()?>listapalestre/showPalestra/<?=$palestra->id;?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a> 
												<a href="<?=base_url()?>listapalestre/getFormEdit/<?=$palestra->id;?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title="Modifica"><i class="fa fa-pencil"></i></a> <button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$palestra->id;?>" title="Elimina"><i class="fa fa-trash"></i></button>
												<a href="<?=base_url()?>ChangePalestra/setPalestra/home/<?=$palestra->id?>" class="btn btn-sm btn-success sgp-select-btn" role="button" title="Seleziona Palestra"><i class="fa fa-check"></i></a>
											</td> <!-- DISPONIBILE PER SU-ADMIN E ADMIN PALESTRA -->
											<td><?=$palestra->nome;?></td>
											<td><?=$palestra->indirizzo;?></td>
											<td><?=$palestra->ubicazione?></td>
											<td><a href="<?=prep_url($palestra->sito_web);?>" target="_blank"><?=$palestra->sito_web;?></a></td>
											<td><a href="mailto:<?=$palestra->email;?>"><?=$palestra->email;?></a></td>
										</tr>
									<?php
											}
										} else {
									?>
										<tr><td colspan="6" class="text-center">Nessuna palestra nel sistema</td></tr>
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
								<a href="<?=base_url();?>listapalestre/p/<?=($i+1);?>/<?=$filter?>" class="btn btn-sm btn-default disabled" role="button" title=""><?=($i+1);?></a>
								<?php
										} else {
								?>
								<a href="<?=base_url();?>listapalestre/p/<?=($i+1);?>/<?=$filter?>" class="btn btn-sm btn-default" role="button" title=""><?=($i+1);?></a>
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
		var url_confirm_delete = '<?=base_url()?>listapalestre/confirmDelete';
		var url_delete = '<?=base_url()?>palestracontroller/deletePalestra';
		
		// ELIMINABILI
		var url_edit = '<?=base_url()?>listapalestre/getFormEdit';
		var url_edit_palestra_function = '<?=base_url()?>palestracontroller/modificaPalestra';
		//var url_show = 'listapalestre/showPalestra';
		var url_add = '<?=base_url()?>listapalestre/getFormInsert';
		var url_add_palestra_function = '<?=base_url()?>palestracontroller/creaPalestra';
		var test = '<?=base_url()?>palestracontroller/test';
		var url_new_contatto = '<?=base_url()?>listapalestre/getNuovoCampoNumeroRecapito';
		var url_new_riferimento = '<?=base_url()?>listapalestre/getNuovaPersoneRiferimento';
		var url_search = '<?=base_url()?>listapalestre/searchPalestre/<?=$filter?>';
		
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
							});
						}

					});	

				} /*else if(classi.indexOf("sgp-edit") >= 0) {
					//alert('MODIFICA');
					var id = id_button.replace('modifica-','');
					//alert(id_user);

					// il testo_modal sarà restituito dalla chiamata ajax
					var testo_modal = "";//``;
					$.ajax({
						type: "POST",
						url: url_edit+'/'+id, 
						dataType: "html",
						success:function(data){
							testo_modal = data;
							if( testo_modal == -1 ) {
								location.reload();
							}
							$('#modal').html(testo_modal);
							
							listenerLoadEdit();
							
							// DATETIME PICKER
							$('#attiva_dal, #attiva_al').datetimepicker({
								viewMode:'years',
								format:"DD/MM/YYYY"
							});
							
							
							// LISTENER ADD BUTTON
							$('#sgp-add-contact').click(function() {
								$.ajax({
									type: "POST",
									url: url_new_contatto,
									dataType: "html",
									success:function(data){
										$('#sgp-contatti').append(data);
										
										listenerLoadEdit();
									}
								});
							});
							
							$('#sgp-add-riferimento').click(function() {
								$.ajax({
									type: "POST",
									url: url_new_riferimento,
									dataType: "html",
									success:function(data){
										$('#sgp-persone-riferimento').append(data);
										
										listenerLoadEdit();
									}
								});
							});
							
							
							// LISTENER SUBMIT BUTTON
							$('#sgp-submit-form').click(function() {
								var form = $('#sgp-edit-form');
								
								var fd = new FormData();
								var file_data = $('input[type="file"]')[0].files;//.files; // for multiple files
								var logo = file_data[0];
								
								fd.append("immagine_logo", logo);
								
								var other_data = form.serializeArray();
								//console.log('%o', other_data);
								for(j=0; j<other_data.length; j++) {
									//console.log(other_data[j]['name']+" "+other_data[j]['value']);
									fd.append(other_data[j]['name'],other_data[j]['value']);
								}
								
								$.ajax({
									url: url_edit_palestra_function,
									data: fd,
									contentType: false,
									processData: false,
									type: 'POST',
									dataType: "html",
									success: function(data){
										testo_modal = data;
										$('#modal').html(data);
										
										reloadPagina();
									}
								});
								
							});
							
						}

					});	

				} else if(classi.indexOf("sgp-insert") >= 0) {
					//alert('INSERT');


					// il testo_modal sarà restituito dalla chiamata ajax
					var testo_modal = "";//``;
					$.ajax({
						type: "POST",
						url: url_add,
						dataType: "html",
						success:function(data){
							testo_modal = data;
							if( testo_modal == -1 ) {
								location.reload();
							}
							$('#modal').html(testo_modal);
							
							
							
							$('#attiva_dal, #attiva_al').datetimepicker({
								viewMode:'years',
								format:"DD/MM/YYYY"
							});
							
							
							//	LISTENER ADD BUTTON
							$('#sgp-add-contact').click(function() {
								$.ajax({
									type: "POST",
									url: url_new_contatto,
									dataType: "html",
									success:function(data){
										$('#sgp-contatti').append(data);
										
										$('.sgp-remove-contact').click(function(){
											$(this).parent().parent().remove();
										});
										listenerLoadInsert();
									}
								});
							});
							
							$('#sgp-add-riferimento').click(function() {
								$.ajax({
									type: "POST",
									url: url_new_riferimento,
									dataType: "html",
									success:function(data){
										$('#sgp-persone-riferimento').append(data);
										
										$('.sgp-remove-riferimento').click(function(){
											$(this).parent().parent().remove();
										});
										listenerLoadInsert();
									}
								});
							});
							
							
							listenerLoadInsert();
							
							$('#sgp-submit-form').click(function() {
								var form = $('#sgp-insert-form');
								
								var fd = new FormData();
								var file_data = $('input[type="file"]')[0].files;//.files; // for multiple files
								var logo = file_data[0];
								
								fd.append("immagine_logo", logo);
								
								var other_data = form.serializeArray();
								//console.log('%o', other_data);
								for(j=0; j<other_data.length; j++) {
									//console.log(other_data[j]['name']+" "+other_data[j]['value']);
									fd.append(other_data[j]['name'],other_data[j]['value']);
								}
								$.ajax({
									url: url_add_palestra_function,
									data: fd,
									contentType: false,
									processData: false,
									type: 'POST',
									dataType: "html",
									success: function(data){
										testo_modal = data;
										$('#modal').html(data);
										
										reloadPagina();
										
									},
									error: function() { alert("Error posting feed."); }
								});
							});
						}

					});	

				}*/
			});
		}
		
		/*
		function listenerLoadInsert() {
			var id_tipologie = $('select[name="id_tipologia_numero[]"]');
			for(var i=0;i<id_tipologie.length;i++) {
				if( $(id_tipologie[i]).val() == "" ) {
					$(id_tipologie[i]).next().show();
				}
			}
			$('select[name="id_tipologia_numero[]"]').change(function() {
				if( $(this).val() == "" ) {
					$(this).next().show();
				} else {
					$(this).next().hide();
					$(this).next().val('');
				}
			});
			
			var id_ruoli = $('select[name="id_ruolo_riferimento[]"]');
			for(var i=0;i<id_ruoli.length;i++) {
				if( $(id_ruoli[i]).val() == "" ) {
					$(id_ruoli[i]).next().show();
				}
			}
			$('select[name="id_ruolo_riferimento[]"]').change(function() {
				if( $(this).val() == "" ) {
					$(this).next().show();
				} else {
					$(this).next().hide();
					$(this).next().val('');
				}
			});
			
			if( $('select[name="id_attivita_palestra"]').val() == "" ) {
				$('select[name="id_attivita_palestra"]').next().show();
			}
			$('select[name="id_attivita_palestra"]').change(function() {
				if( $(this).val() == "" ) {
					$(this).next().show();
				} else {
					$(this).next().hide();
					$(this).next().val('');
				}
			});
			
			if( $('select[name="id_ubicazione"]').val() == "" ) {
				$('select[name="id_ubicazione"]').next().show();
			}
			$('select[name="id_ubicazione"]').change(function() {
				if( $(this).val() == "" ) {
					$(this).next().show();
				} else {
					$(this).next().hide();
					$(this).next().val('');
				}
			});
		}
		
		function listenerLoadEdit() {
			
			var id_tipologie = $('select[name="id_tipologia_numero[]"]');
			for(var i=0;i<id_tipologie.length;i++) {
				if( $(id_tipologie[i]).val() == "" ) {
					$(id_tipologie[i]).next().show();
				}
			}
			$('select[name="id_tipologia_numero[]"]').change(function() {
				if( $(this).val() == "" ) {
					$(this).next().show();
				} else {
					$(this).next().hide();
					$(this).next().val('');
				}
			});
			
			var id_ruoli = $('select[name="id_ruolo_riferimento[]"]');
			for(var i=0;i<id_ruoli.length;i++) {
				if( $(id_ruoli[i]).val() == "" ) {
					$(id_ruoli[i]).next().show();
				}
			}
			$('select[name="id_ruolo_riferimento[]"]').change(function() {
				if( $(this).val() == "" ) {
					$(this).next().show();
				} else {
					$(this).next().hide();
					$(this).next().val('');
				}
			});
			
			if( $('select[name="id_attivita_palestra"]').val() == "" ) {
				$('select[name="id_attivita_palestra"]').next().show();
			}
			$('select[name="id_attivita_palestra"]').change(function() {
				if( $(this).val() == "" ) {
					$(this).next().show();
				} else {
					$(this).next().hide();
					$(this).next().val('');
				}
			});
			
			if( $('select[name="id_ubicazione"]').val() == "" ) {
				$('select[name="id_ubicazione"]').next().show();
			}
			$('select[name="id_ubicazione"]').change(function() {
				if( $(this).val() == "" ) {
					$(this).next().show();
				} else {
					$(this).next().hide();
					$(this).next().val('');
				}
			});
			
			
			// LISTENER REMOVE BUTTON 
			$('.sgp-remove-logo').click(function(){
				$(this).parent().remove();
				$('input[name="old_logo"]').attr('value','');
			});
			
			$('.sgp-remove-contact').click(function(){
				$(this).parent().parent().remove();
			});
			
			$('.sgp-remove-riferimento').click(function(){
				$(this).parent().parent().remove();
			});
		}
		*/
		
		function reloadPagina() {
			$('#modal').on('hidden.bs.modal', function () {
				location.reload();
			});
		}
	</script>

</div>
<!-- /#wrapper -->    