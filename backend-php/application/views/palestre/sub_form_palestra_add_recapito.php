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
						<select class="form-control" name="id_tipologia_numero">
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