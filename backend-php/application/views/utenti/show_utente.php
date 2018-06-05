<?php if(false) { ?>
<link href="../../../public/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<?php } ?>
<div id="page-wrapper">

	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Visualizzazione profilo <?=$username?> <small><?=$nome?> <?=$cognome?></small>
				</h1>
			</div>
		</div>
		<div class="row sgp-btn-row"><!-- ACTION BAR -->
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listautenti" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a> 
				<?php
				if( !$delete_lock ) {
				?>
				<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$id?>"><i class="fa fa-trash"></i> Elimina</button>
				<?php
				}
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Profilo</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-5"><strong>Username:</strong> </div>
									<div class="col-lg-6"><?=$username?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Ruolo:</strong> </div>
									<div class="col-lg-6"><?=$ruolo_str?></div>
								</div>
								<?php
								if( $ruolo != 0 ) {
								?>	
								<div class="row">
									<div class="col-lg-5"><strong>Palestra:</strong> </div>
									<div class="col-lg-6"><?=$nome_palestra?></div>
								</div>

								<div class="row">
									<div class="col-lg-5"><strong>Coordinatore:</strong> </div>
									<div class="col-lg-6"><?=$coordinatore_str?></div>
								</div>
								<?php
									if( $coordinatore == 0 ) {	
								?>
								<div class="row">
									<div class="col-lg-5"><strong>Nome e cognome coordinatore:</strong> </div>
									<div class="col-lg-6"><?=$nome_coordinatore?> <?=$cognome_coordinatore?></div>
								</div>	
								<?php
									}
								?>
								<?php	
								}
								?>
							</div>
						</div>
						<div class="row text-center sgp-row">
							<div class="col-lg-12">
								<button type="button" class="btn btn-warning btn-md sgp-edit-profilo" data-toggle="modal" data-target="#modal"  id="edit-profilo-<?=$id?>"><i class="fa fa-pencil"></i> Modifica Dati Profilo</button></div>
					  </div>	
						<div class="row sgp-row">
							<!--<div class="col-lg-7">
								<div class="row sgp-row">
									<div class="col-lg-4"><strong>Password:</strong> </div>-->
									<!--<div class="col-lg-7">--><div class="col-lg-12 text-center"><button type="button" class="btn btn-warning btn-md sgp-edit-password" data-toggle="modal" data-target="#modal"  id="edit-password-<?=$id?>"><i class="fa fa-pencil"></i> Modifica password</button></div>
								<!--</div>
							</div>-->
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-home fa-fw"></i> Dati anagrafici</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-7">
								<div class="row">
									<div class="col-lg-5"><strong>Nome:</strong> </div>
									<div class="col-lg-6"><?=$nome?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Cognome:</strong> </div>
									<div class="col-lg-6"><?=$cognome?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Sesso:</strong> </div>
									<div class="col-lg-6"><?=$sesso_str?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Data di nascita:</strong> </div>
									<div class="col-lg-6"><?=$data_nascita?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>Indirizzo:</strong> </div>
									<div class="col-lg-6"><?=( $indirizzo != '' ? $indirizzo.', '.$cap.', '.$citta.' ('.$provincia.')' : 'n.d.')?></div>
								</div>
								<div class="row">
									<div class="col-lg-5"><strong>E-mail:</strong> </div>
									<div class="col-lg-6"><?=$email?></div>
								</div>
							</div>
						</div>
						<div class="row sgp-btn-row text-center">
							<div class="col-lg-12">
								<button type="button" class="btn btn-warning btn-md sgp-edit-dati-anagrafici" data-toggle="modal" data-target="#modal"  id="edit-dati-anagrafici-<?=$id?>"><i class="fa fa-pencil"></i> Modifica dati anagrafici</botton>
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
						<h3 class="panel-title"><i class="fa fa-phone fa-fw"></i> Recapiti Telefonici</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
		<?php 
		if( count($contatti) > 0 ) {
		?>
						<div class="row">
							<div class="col-lg-7">
		<?php
			foreach( $contatti as $contatto ) {
		?>
								<div class="row">
									<div class="col-lg-5"><strong><?=$contatto->tipologia_str?>:</strong> </div>
									<div class="col-lg-7"><?=$contatto->numero?></div>
								</div>
		<?php
			}
		?>
							</div>
						</div>
		<?php
		}
		?>
						<div class="row sgp-btn-row text-center">
							<div class="col-lg-12">
								<a href="<?=base_url()?>listautenti/editContattiUtente/<?=$id?>" class="btn btn-warning btn-md sgp-back-btn" role="button" title=""><i class="fa fa-pencil"></i> Modifica Recapiti Telefonici</a>
  </div>
</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php 
		if( $coordinatore == 1 ) {
			if( isset($coordinati) ) {
				if( count($coordinati) > 0 ) {
		?>
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default" id="sgp-tabella">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-users fa-fw"></i> Utenti coordinati</h3> <!-- Utenti Palestra</h3> -->
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12 table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th></th>
											<th>Nome</th>
											<th>Cognome</th>
											<th>Username</th>
										</tr>
									</thead>
									<tbody>
		<?php
				foreach( $coordinati as $coordinato ) {
		?>
										<tr>
											<td class="col-lg-2"><a href="<?=base_url()?>listautenti/showUtente/<?=$coordinato->id?>" class="btn btn-sm btn-info sgp-show-btn" role="button" title="Mostra dettagli"><i class="fa fa-expand"></i></a></td>
											<td class="col-lg-2"><?=$coordinato->nome?></td>
											<td class="col-lg-2"><?=$coordinato->cognome?></td>
											<td class="col-lg-2"><?=$coordinato->username?></td>
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
		</div>
		<?php
				}
			}
		}
		?>
		<div class="row sgp-btn-row"><!-- ACTION BAR -->
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listautenti" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a>
				<?php
				if( !$delete_lock ) {
				?>
				<button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$id?>"><i class="fa fa-trash"></i> Elimina</button> 
				<?php
				}
				?>
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
	
	var url_form_edit_password = '<?=base_url()?>listautenti/getUpdatePasswordForm';
	//var url_edit_password_function = '<?=base_url()?>listautenti/updatePassword';
	var url_form_edit_dati_anagrafici = '<?=base_url()?>listautenti/getUpdateDatiAnagraficiForm';
	//var url_edit_password_dati_anagrafici = '<?=base_url()?>profiloutente/updateDatiAnagrafici';
	var url_form_edit_profilo = '<?=base_url()?>listautenti/getUpdateProfiloForm';
	var url_confirm_delete = '<?=base_url()?>listautenti/AskConfirmDelete';
	var url_delete = '<?=base_url()?>utenteController/deleteUtente';
		
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

			} else if (classi.indexOf("sgp-edit-password") >= 0) {
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
						
						//listener nella view nel modal
						
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

						//listener nella view nel modal
						

					}

				});	

			} else if(classi.indexOf("sgp-edit-profilo") >= 0) {
				//alert('MODIFICA');
				var id = id_button.replace('edit-profilo-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_form_edit_profilo+'/'+id, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);

						listenerLoad();
						reloadPagina();

					}

				});
			}
		});
	}

	function listenerLoad() {

		


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
	