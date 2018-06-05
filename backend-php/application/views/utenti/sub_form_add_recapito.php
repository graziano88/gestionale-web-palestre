<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Inserimento Contatto </h4>
		</div>
		<div class="modal-body text-left">
			<form role="form" action="" method="post" id="sgp-add-contatto-form">
				<div class="form-group">
					<input name="id_recapito_telefonico" value="" hidden="true">
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
						<a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-submit-form-contatto">Aggiungi</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	
	listenerTipologiaContatto();
	$('#sgp-submit-form-contatto').click(function(e) {
		
		var form = $('#sgp-add-contatto-form');
		var dataForm = form.serializeArray();
		e.preventDefault();
		$.ajax({
			type: "POST",
			data: dataForm,
			url: url_add_new_contatto,
			dataType: "html",
			success:function(data){
				$('#modal').modal('hide');
				$("#sgp-row-contatti").append(data);
				listenerRemoveContact();
				checkCountContatti();
			}
		});
	});
	
	function listenerTipologiaContatto() {
		// LISTENER ALTRO NELLA TIPOLOGIA NUMERO
		var id_tipologie = $('select[name="id_tipologia_numero"]');
		for(var i=0;i<id_tipologie.length;i++) {
			if( $(id_tipologie[i]).val() == "" ) {
				$(id_tipologie[i]).next().show();
			}
		}
		$('select[name="id_tipologia_numero"]').change(function() {
			if( $(this).val() == "" ) {
				$(this).next().show();
			} else {
				$(this).next().hide();
				$(this).next().val('');
			}
		});		
	}
</script>