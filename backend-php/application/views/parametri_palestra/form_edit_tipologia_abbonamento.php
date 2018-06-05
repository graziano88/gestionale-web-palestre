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
								<input name="id" value="<?=$tipologia_abbonamento->id?>" hidden="true">
								<div class="form-group">
									<label>Nome tipologia abbonamento:</label>
									<div class='input-group'>
										<input class="form-control" type="text" name="tipo" value="<?=$tipologia_abbonamento->tipo?>" autofocus>
									</div>
								</div>
								<div class="form-group">
									<label>Durata:</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="durata" value="<?=$tipologia_abbonamento->durata?>">
										<span class="input-group-addon">
											<span class="">Giorni</span>
										</span>
									</div>
								</div>
								<?php if( $tipologia_abbonamento->freepass == 0 ) { ?>
								<div class="form-group">
									<label>Costo base:</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="costo_base" value="<?=$tipologia_abbonamento->costo_base?>">
										<span class="input-group-addon">
											<span class="">&euro;</span>
										</span>
									</div>
								</div>
								<?php } else { ?>
								<div class="form-group" id="sgp-giorni-gratuiti-socio">
									<label>Giorni Bonus per il socio presentatore (freepass):</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="giorni_gratuiti_socio" value="<?=$tipologia_abbonamento->giorni_gratuiti_socio?>">
										<span class="input-group-addon">
											<span class="">Giorni</span>
										</span>
									</div>
								</div>
								<?php } ?>
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
	var url_submit_form_function = '<?=base_url()?>parametripalestra/editTipologiaAbbonamento';
	
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