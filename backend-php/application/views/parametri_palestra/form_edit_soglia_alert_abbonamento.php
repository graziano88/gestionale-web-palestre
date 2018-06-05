<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Modifica soglia per Alert scadenza abbonamento</h4>
		</div>
		<div class="modal-body">



			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-8">
							<form role="form" action="" method="post" id="sgp-edit-form">
								<input name="id" value="<?=$soglia_abbonamento['id']?>" hidden="true">
								<div class="form-group">
									<label>Alert scadenza Abbonamento normale prima di:</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="alert_scadenza_abbonamento" value="<?=$soglia_abbonamento['alert_scadenza_abbonamento']?>" autofocus>
										<span class="input-group-addon">
											<span class="">Giorni</span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>Alert scadenza Freepass prima di:</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="alert_scadenza_freepass" value="<?=$soglia_abbonamento['alert_scadenza_freepass']?>">
										<span class="input-group-addon">
											<span class="">Giorni</span>
										</span>
									</div>
								</div>
								<div class="row sgp-btn-row">
									<div class="text-center">
										<span><a href="javascript:void(0)" class="btn btn-sm btn-warning" role="button" title="" id="sgp-submit-form">Modifica</a></span>
										<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
									</div>
								</div>
							</form>
						</div>
						<div class="col-lg-2"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	
<script>
	var url_submit_form_function = '<?=base_url()?>parametripalestra/editSogliaAlertAbbonamento';
	
	$('#sgp-submit-form').click(function() {
		var form = $('#sgp-edit-form');
		var dataForm = form.serializeArray();
		$.ajax({
			url: url_submit_form_function,
			data: dataForm,
			type: 'POST',
			dataType: "html",
			success: function(data){
				var testo_modal = data;
				$('#modal').html(testo_modal);
				/*
				$('#modal').on('hidden.bs.modal', function () {
					window.location.replace(page_redirect);
				});
				*/
				reloadPagina();
			}
		});
	});
		
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			location.reload();
		});
	}
</script>