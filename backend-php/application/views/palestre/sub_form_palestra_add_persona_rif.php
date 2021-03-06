<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Inserimento Persona di Riferimento </h4>
		</div>
		<div class="modal-body text-left">
			<form role="form" action="" method="post" id="sgp-add-riferimento-form">
				<div class="form-group">
					<!--<label>Persona di Riferimento</label>-->
					<input name="id_riferimento" value="" hidden="true">
					<div class="form-group">
						<label>Nome</label>
						<input class="form-control sgp-capitalize" name="nome_riferimento">
					</div>
					<div class="form-group">
						<label>Cognome</label>
						<input class="form-control sgp-capitalize" name="cognome_riferimento">
					</div>
					<div class="form-group">
						<label>Ruolo</label>
						<select class="form-control" name="id_ruolo_riferimento">
							<?=$option_ruoli_persone_riferimento?>
						</select>
						<input class="form-control sgp-hide sgp-capitalize" name="new_ruolo_riferimento">
					</div>
					<div class="form-group">
						<label>Telefono</label>
						<input class="form-control" name="telefono_riferimento">
					</div>
					<div class="form-group">
						<label>Cellulare</label>
						<input class="form-control" name="cellulare_riferimento">
					</div>
					<div class="form-group">
						<label>Email</label>
						<input class="form-control" name="email_riferimento">
					</div>
				</div>
				<div class="row">
					<div class="text-center">
						<a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-submit-form-persona-riferimento">Aggiungi</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>