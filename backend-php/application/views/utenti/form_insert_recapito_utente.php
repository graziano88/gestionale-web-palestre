<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Inserimento Nuovo Recapito </h4>
		</div>
		<div class="modal-body text-left">
			<form role="form" action="" method="post" id="sgp-insert-recapito-utente-form">
				<div class="form-group">
					<div class="form-group">
					<input name="id_utente" value="<?=$id_utente?>" hidden="true">
					<input name="username" value="<?=$username?>" hidden="true">
					<input name="id_palestra" value="<?=$id_palestra?>" hidden="true">
					<div class="form-group">
						<label>Tipo Contatto</label>
						<select class="form-control" name="id_tipologia_numero" autofocus>
							<?=$option_tipologie_contatto?>
						</select>
						<input class="form-control sgp-hide sgp-capitalize" name="new_tipologia_numero">
					</div>
					<div class="form-group">
						<label>Numero</label>
						<input class="form-control" name="numero">
					</div>
				</div>
				<div class="row">
					<div class="text-center">
						<a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-submit-form-insert-recapito-utente">Inserisci</a>
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
	
	var url_insert_recapito_function = '<?=base_url()?>utenteController/inserimentoRecapitoUtente';
		
	
	$('select[name="id_tipologia_numero"]').change(function() {
		console.log( $('select[name="id_tipologia_numero"]').val() );
		if( $('select[name="id_tipologia_numero"]').val() == '' ) {
			$('input[name=new_tipologia_numero]').show();
		} else {
			$('input[name=new_tipologia_numero]').hide();
		}
	});
	
	$('#sgp-submit-form-insert-recapito-utente').click(function(e) {
		var form = $('#sgp-insert-recapito-utente-form');
		var dataForm = form.serializeArray();
		e.preventDefault();
		$.ajax({
			type: "POST",
			data: dataForm,
			url: url_insert_recapito_function,
			dataType: "html",
			success:function(data){
				//$('#modal').modal('hide');
				$('#modal').html(data);
			}
		});
	});
	
</script>