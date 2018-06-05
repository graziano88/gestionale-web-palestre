<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default" id="sgp-tabella">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-credit-card fa-fw"></i> <?=$numero_rata_romano?> Rata</h3> <!-- Utenti Palestra</h3> -->
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label>Tipo</label>
					<?=( $tipo == 0 ? 'Acconto' : 'Saldo' )?>
					<input name="tipo[]" value="<?=$tipo?>" hidden="true">
				</div>
				<div class="form-group">
					<label>Importo rata</label>
					<?=$valore_rata?>
					<input name="valore_rata[]" value="<?=$valore_rata?>" hidden="true">
				</div>
				<div class="form-group">
					<label>Data di scadenza della rata</label>
					<div class='input-group date' id='sgp-scadenza-<?=$numero_rata?>'>
						<input type='text' class="form-control data-scadenza" placeholder="gg/mm/aaaa" name="data_scadenza[]" value="<?=$scadenza_rata_str?>"/>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar">
							</span>
						</span>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<script>
	$('#sgp-scadenza-<?=$numero_rata?>').datetimepicker({
		viewMode:'years',
		format:"DD/MM/YYYY"
	}).on('dp.change', function(e) { setDateScadenza($(this).attr('id')); });
</script>