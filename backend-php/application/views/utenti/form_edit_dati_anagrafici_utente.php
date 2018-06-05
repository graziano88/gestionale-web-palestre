<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Modifica Dati Anagrafici </h4>
		</div>
		<div class="modal-body text-left">
			<div class="sgp-form-error"><?=$error_msg?></div>
			<form role="form" action="" method="post" id="sgp-edit-dati-anagrafici-form">
				<div class="form-group">
					<!--<label>Persona di Riferimento</label>-->
					<input name="id_utente" value="<?=$id?>" hidden="true">
					<div class="form-group">
						<label>Nome</label>
						<input class="form-control" name="nome" type="text" value="<?=$nome?>">
					</div>
					<div class="form-group">
						<label>Cognome</label>
						<input class="form-control" name="cognome" type="text" value="<?=$cognome?>">
					</div>
					<div class="form-group">
						<label>Data di nascita</label>
						<div class='input-group date' id='data_nascita'>
							<input type='text' class="form-control" placeholder="gg/mm/aaaa" name="data_nascita" value="<?=$data_nascita;?>"/>
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar">
								</span>
							</span>
						</div>
					</div>
					<div class="form-group">
						<label>Sesso</label>
						<div class="radio">
							<label>
								<input type="radio" name="sesso" value="0" <?=($sesso == 0 ? 'checked' : '');?>>Maschio
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="sesso" value="1" <?=($sesso == 1 ? 'checked' : '');?>>Femmina
							</label>
						</div>															
					</div>
					<div class="form-group">
						<label>Indirizzo</label>
						<input class="form-control sgp-capitalize" name="indirizzo" value="<?=$indirizzo;?>">
					</div>
					<div class="form-group">
						<label>Città</label>
						<input class="form-control sgp-capitalize" name="citta" value="<?=$citta;?>">
					</div>
					<div class="form-group">
						<label>CAP</label>
						<input class="form-control" name="cap" maxlength="5" value="<?=$cap;?>">
					</div>
					<div class="form-group">
						<label>Provincia</label>
						<input class="form-control sgp-uppercase" name="provincia" maxlength="2" value="<?=$provincia;?>">
					</div>
					<div class="form-group">
						<label>E-mail</label>
						<input class="form-control" name="email" type="text" value="<?=$email?>">
					</div>
				</div>
				<div class="row">
					<div class="text-center">
						<a href="javascript:void(0)" class="btn btn-sm btn-warning" role="button" title="" id="sgp-submit-form-edit-dati-anagrafici">Modifica</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
					</div>
				</div>
			</form>
		</div>
		</div>
	</div>
</div>
<script>
	var url_edit_password_dati_anagrafici = '<?=base_url()?>utenteController/updateDatiAnagrafici';
	/* DATETIME PICKER */
	$('#data_nascita').datetimepicker({
		viewMode:'years',
		format:"DD/MM/YYYY"
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
				
				$('#modal').on('hidden.bs.modal', function () {
					location.reload();
				});
			}
		});

	});
</script>
