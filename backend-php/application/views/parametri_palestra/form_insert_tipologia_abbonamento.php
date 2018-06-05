<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Modifica Tipologia Abbonamento</h4>
		</div>
		<div class="modal-body">



			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-8">
							<form role="form" action="" method="post" id="sgp-edit-form">
								<input name="id_palestra" value="<?=$id_palestra?>" hidden="true">
								<div class="form-group">
									<label>Nome tipologia abbonamento:</label>
									<div class='input-group'>
										<input class="form-control sgp-capitalize" type="text" name="tipo" autofocus>
									</div>
								</div>
								<div class="form-group">
									<label>Durata:</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="durata" value="30">
										<span class="input-group-addon">
											<span class="">Giorni</span>
										</span>
									</div>
								</div>
								<div class="form-group" id="sgp-costo-base">
									<label>Costo base:</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="costo_base" value="0">
										<span class="input-group-addon">
											<span class="">&euro;</span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>Freepass:</label>
									<div class="radio">
										<label>
											<input type="radio" name="freepass" value="1">Sì
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="freepass" value="0" checked>No
										</label>
									</div>															
								</div>
								<div class="form-group" id="sgp-giorni-gratuiti-socio">
									<label>Giorni Bonus per il socio presentatore (freepass):</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="giorni_gratuiti_socio" value="0">
										<span class="input-group-addon">
											<span class="">Giorni</span>
										</span>
									</div>
								</div>
								<script>
									
									checkFreepassState();
									
									$('input[name=freepass]').change(function() {
										checkFreepassState();
									});
									function checkFreepassState() {
										if( $('input[name=freepass]:checked').val() == 0 ) {
											$('input[name=giorni_gratuiti_socio]').val(0);
											$('#sgp-giorni-gratuiti-socio').hide();
											$('#sgp-costo-base').show();
										} else {
											$('#sgp-giorni-gratuiti-socio').show();
											$('input[name=costo_base]').val(0);
											$('#sgp-costo-base').hide();
										}
									}
								</script>
								<div class="row sgp-btn-row">
									<div class="text-center">
										<span><a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-submit-form">Inserisci</a></span>
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
	var url_submit_form_function = '<?=base_url()?>parametripalestra/insertTipologiaAbbonamento';
	
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