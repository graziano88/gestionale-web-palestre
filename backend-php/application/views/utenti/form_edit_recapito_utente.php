<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Modifica Recapito Telefonico </h4>
		</div>
		<div class="modal-body text-left">
			<form role="form" action="" method="post" id="sgp-edit-recapito-utente-form">
				<div class="form-group">
					<div class="form-group">
					<input name="id" value="<?=$contatto->id?>" hidden="true">
					<input name="id_utente" value="<?=$id_utente?>" hidden="true">
					<input name="id_palestra" value="<?=$id_palestra?>" hidden="true">
					<input name="username" value="<?=$username?>" hidden="true">
					<div class="form-group">
						<label>Tipo Contatto</label>
						<select class="form-control" name="id_tipologia_numero" autofocus>
							<?=$option_tipologie_contatto?>
						</select>
						<input class="form-control sgp-hide sgp-capitalize" name="new_tipologia_numero">
					</div>
					<div class="form-group">
						<label>Numero</label>
						<input class="form-control" name="numero" value="<?=$contatto->numero?>">
					</div>
				</div>
				<div class="row">
					<div class="text-center">
						<a href="javascript:void(0)" class="btn btn-sm btn-warning" role="button" title="" id="sgp-submit-form-edit-recapito-utente">Modifica</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
					</div>
				</div>
			</form>
		</div>
		</div>
												<!--
		<div class="modal-footer text-center">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
		-->
	</div>
</div>
<script>
	
	var url_edit_recapito_function = '<?=base_url()?>utenteController/modificaRecapitoUtente';
		
	
	$('select[name="id_tipologia_numero"]').change(function() {
		
		if( $('select[name="id_tipologia_numero"]').val() == '' ) {
			$('input[name=new_tipologia_numero]').show();
		} else {
			$('input[name=new_tipologia_numero]').hide();
		}
	});
	
	$('#sgp-submit-form-edit-recapito-utente').click(function(e) {
		var form = $('#sgp-edit-recapito-utente-form');
		var dataForm = form.serializeArray();
		e.preventDefault();
		$.ajax({
			type: "POST",
			data: dataForm,
			url: url_edit_recapito_function,
			dataType: "html",
			success:function(data){
				//$('#modal').modal('hide');
				$('#modal').html(data);
			}
		});
	});
	
</script>