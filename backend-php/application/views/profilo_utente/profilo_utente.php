<div id="page-wrapper">

	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Visualizzazione profilo <small><?=$nome?> <?=$cognome?></small>
				</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Profilo</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-3"><strong>Username:</strong> </div>
									<div class="col-lg-9"><?=$username?></div>
								</div>
								<div class="row">
									<div class="col-lg-3"><strong>Ruolo:</strong> </div>
									<div class="col-lg-9"><?=$ruolo_str?></div>
								</div>
								<?php
								if( $ruolo != 0 ) {
								?>	
								<div class="row">
									<div class="col-lg-3"><strong>Palestra:</strong> </div>
									<div class="col-lg-9"><?=$nome_palestra?></div>
								</div>

								<div class="row">
									<div class="col-lg-3"><strong>Coordinatore:</strong> </div>
									<div class="col-lg-9"><?=$coordinatore_str?></div>
								</div>
								<?php
									if( $coordinatore == 0 ) {	
								?>
								<div class="row">
									<div class="col-lg-3"><strong>Nome e cognome coordinatore:</strong> </div>
									<div class="col-lg-9"><?=$nome_coordinatore?> <?=$cognome_coordinatore?></div>
								</div>	
								<?php
									}
								?>
								<?php	
								}
								?>
								<div class="row sgp-row">
									<div class="col-lg-3"><strong>Password:</strong> </div>
									<div class="col-lg-9"><button type="button" class="btn btn-warning btn-md sgp-edit-password" data-toggle="modal" data-target="#modal"  id="edit-password-<?=$id?>"><i class="fa fa-pencil"></i> Modifica password</button></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Dati anagrafici</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-3"><strong>Nome:</strong> </div>
									<div class="col-lg-9"><?=$nome?></div>
								</div>
								<div class="row">
									<div class="col-lg-3"><strong>Cognome:</strong> </div>
									<div class="col-lg-9"><?=$cognome?></div>
								</div>
								<div class="row">
									<div class="col-lg-3"><strong>Sesso:</strong> </div>
									<div class="col-lg-9"><?=$sesso_str?></div>
								</div>
								<div class="row">
									<div class="col-lg-3"><strong>Data di nascita:</strong> </div>
									<div class="col-lg-9"><?=$data_nascita?></div>
								</div>
								<div class="row">
									<div class="col-lg-3"><strong>Indirizzo:</strong> </div>
									<div class="col-lg-9"><?=$indirizzo?>, <?=$cap?>, <?=$citta?> (<?=$provincia?>)</div>
								</div>
								<div class="row">
									<div class="col-lg-3"><strong>E-mail:</strong> </div>
									<div class="col-lg-9"><?=$email?></div>
								</div>
							</div>
						</div>
						<div class="row sgp-btn-row">
							<div class="col-lg-7">
								<button type="button" class="btn btn-warning btn-md sgp-edit-dati-anagrafici" data-toggle="modal" data-target="#modal" id="edit-dati-anagrafici-<?=$id?>"><i class="fa fa-pencil"></i> Modifica dati anagrafici</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<script>
	/*
		var url_confirm_delete = '<?=base_url()?>listapalestre/confirmDelete';
		var url_delete = '<?=base_url()?>palestraController/deletePalestra';
		
		// ELIMINABILI
		var url_edit = '<?=base_url()?>listapalestre/getFormEdit';
		var url_edit_palestra_function = '<?=base_url()?>palestraController/modificaPalestra';
		//var url_show = 'listapalestre/showPalestra';
		var url_add = '<?=base_url()?>listapalestre/getFormInsert';
		var url_add_palestra_function = '<?=base_url()?>palestraController/creaPalestra';
		var test = '<?=base_url()?>palestraController/test';
		var url_new_contatto = '<?=base_url()?>listapalestre/getNuovoCampoNumeroRecapito';
		var url_new_riferimento = '<?=base_url()?>listapalestre/getNuovaPersoneRiferimento';
		var url_search = '<?=base_url()?>listapalestre/searchPalestre/<?=$filter?>';
	
	*/
	
	var url_form_edit_password = '<?=base_url()?>profiloutente/getUpdatePasswordForm';
	var url_edit_password_function = '<?=base_url()?>profiloutente/updatePassword';
	var url_form_edit_dati_anagrafici = '<?=base_url()?>profiloutente/getUpdateDatiAnagraficiForm';
	var url_edit_password_dati_anagrafici = '<?=base_url()?>profiloutente/updateDatiAnagrafici';
		
	$(document).ready(function(){
		listenerModal();
	});

	function listenerModal() {
		$('button[data-toggle="modal"]').click(function() {

			var id_button = $(this).attr("id");
			var classi = $(this).attr("class");
			//alert(classi);
			if (classi.indexOf("sgp-edit-password") >= 0) {
				/* EDIT PASSWORD */

				var id = id_button.replace('edit-password-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_form_edit_password+'/'+id, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);

						listenerLoad();
						
					}

				});	

			} else if(classi.indexOf("sgp-edit-dati-anagrafici") >= 0) {
				//alert('MODIFICA');
				var id = id_button.replace('edit-dati-anagrafici-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_form_edit_dati_anagrafici+'/'+id, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);

						listenerLoad();
						

					}

				});	

			}/* else if(classi.indexOf("sgp-insert") >= 0) {
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

	function listenerLoad() {

		/* DATETIME PICKER */
		$('#data_nascita').datetimepicker({
			viewMode:'years',
			format:"DD/MM/YYYY"
		});

		// LISTENER SUBMIT BUTTON
		// edit-password
		$('#sgp-submit-form-edit-password').click(function() {
			var form = $('#sgp-edit-password-form');
			var dataForm = form.serializeArray();

			$.ajax({
				url: url_edit_password_function,
				data: dataForm,
				type: 'POST',
				dataType: "html",
				success: function(data){
					testo_modal = data;
					$('#modal').html(data);
					listenerLoad();
					reloadPagina();
				}
			});

		});

		// edit-dati-anagrafici
		$('#sgp-submit-form-edit-dati-anagrafici').click(function() {
			var form = $('#sgp-edit-dati-anagrafici-form');
			var dataForm = form.serializeArray();

			$.ajax({
				url: url_edit_password_dati_anagrafici,
				data: dataForm,
				type: 'POST',
				dataType: "html",
				success: function(data){
					testo_modal = data;
					$('#modal').html(data);
					listenerLoad();
					reloadPagina();
				}
			});

		});


		/* LISTENER REMOVE BUTTON */
		// NESSUNO
		/*
		$('.sgp-remove-logo').click(function(){
			$(this).parent().remove();
			$('input[name="old_logo"]').attr('value','');
		});

		$('.sgp-remove-contact').click(function(){
			$(this).parent().parent().remove();
		});

		$('.sgp-remove-riferimento').click(function(){
			$(this).parent().parent().remove();
		});*/
	}

	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			location.reload();
		});
	}
	</script>