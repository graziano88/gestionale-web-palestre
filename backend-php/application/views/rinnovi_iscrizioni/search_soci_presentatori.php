<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default" id="sgp-tabella">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-users fa-fw"></i> Elenco soci presentatori</h3> <!-- Utenti Palestra</h3> -->
			</div>
			<div class="panel-body">

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th></th>
								<th>Cognome e Nome</th>
								<th>Contatti</th>
							</tr>
						</thead>
						<tbody>

						<?php
							if( count($soci) > 0) {
								foreach( $soci as $socio ) {
						?>
							<tr>
								<td>
									<a href="javascript:void(0);" class="btn btn-sm btn-success sgp-select-socio-presentatore" id="socio-presentatore-<?=$socio->id?>" role="button" title="Seleziona questo utente"><i class="fa fa-check"></i> Seleziona utente</a>
									<div style="display: none"><strong>Selezionato</strong></div>
								</td> <!-- DISPONIBILE PER SU-ADMIN E ADMIN PALESTRA -->
								<td> <?=$socio->cognome?> <?=$socio->nome?></td>
								<td>
									<div><?=$socio->email?></div>
									<div><?=$socio->indirizzo?>, <?=$socio->cap?> <?=$socio->citta?> (<?=$socio->provincia?>)</div>
								</td>
							</tr>
						<?php
								}
							}
						?>
						</tbody>
					</table>
					<a href="javascript:void(0);" class="btn btn-sm btn-warning sgp-reset-select-socio-presentatore" role="button" title="" style="display: none">Annulla selezione</a> 
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	
	$('.sgp-select-socio-presentatore').click(function() {
		var bottone = $(this);
		var id_button = $(this).attr("id");
		var id = id_button.replace('socio-presentatore-','');
		
		var nome_cognome = bottone.parent().next().html();
		
		$('input[name=id_socio_presentatore]').val(id);
		$('#sgp-input-search-socio').val(nome_cognome);
		$('#sgp-input-search-socio').attr('disabled', true);
		
		$('.sgp-select-socio-presentatore').hide();
		//bottone.parent().append('<div><strong>Selezionato</strong></div>');
		bottone.parent().children('div').show();
		$('.sgp-reset-select-socio-presentatore').show();
		
	});
	
	$('.sgp-reset-select-socio-presentatore').click(function() {
		$('input[name=id_socio_presentatore]').val('');
		//$('#sgp-input-search-socio').val('');
		$('#sgp-input-search-socio').attr('disabled', false);
		$('.sgp-select-socio-presentatore').show();
		$('.sgp-select-socio-presentatore').parent().children('div').hide();
		$('.sgp-reset-select-socio-presentatore').hide();
		
	});
</script>