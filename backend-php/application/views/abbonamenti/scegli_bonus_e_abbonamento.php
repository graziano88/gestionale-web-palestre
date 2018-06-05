<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-body text-center">
			<h4>Applicazione del bonus per il socio <strong><?=$nome_socio?> <?=$cognome_socio?></strong></h4>
		</div>
		<div class="modal-body">
			<form role="form" action="<?=base_url()?>abbonamentocontroller/applicaBonus" method="post">
				<div class="row">
					<div class="col-lg-7">
						<div class="form-group">
							<label>Seleziona abbonamento: </label>
							<select name="id_abbonamento" class="form-control">
								<?=$option_abbonamento?>
							</select>
						</div>
						<div class="form-group">
							<label>Seleziona bonus: </label>
							<select name="id_bonus" class="form-control">
								<?=$option_bonus?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-7">
						<input type="submit" class="btn btn-success btn-sm" value="Applica"> <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Annulla</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>