<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Modifica Password </h4>
		</div>
		<div class="modal-body text-left">
			<div class="sgp-form-error"><?=$error_msg?></div>
			<form role="form" action="" method="post" id="sgp-edit-password-form">
				<div class="form-group">
					<!--<label>Persona di Riferimento</label>-->
					<input name="id_utente" value="<?=$id_utente?>" hidden="true">
					<div class="form-group">
						<label>Vecchia password</label>
						<input class="form-control" name="old_pass" type="password">
					</div>
					<div class="form-group">
						<label>Nuova password</label>
						<input class="form-control" name="new_pass" type="password">
					</div>
					<div class="form-group">
						<label>Ripeti nuova password</label>
						<input class="form-control" name="new_pass_2" type="password">
					</div>
				</div>
				<div class="row">
					<div class="text-center">
						<a href="javascript:void(0)" class="btn btn-sm btn-warning" role="button" title="" id="sgp-submit-form-edit-password">Modifica</a>
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