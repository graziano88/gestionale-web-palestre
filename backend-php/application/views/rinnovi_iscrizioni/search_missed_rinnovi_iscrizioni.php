<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default" id="sgp-tabella">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-users fa-fw"></i> Elenco Rinnovi/Iscrizioni Missed e Free Pass</h3> <!-- Utenti Palestra</h3> -->
			</div>
			<div class="panel-body">

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th></th>
								<th>Cognome e Nome</th>
								<th>Contatti</th>
								<th>Data</th>
								<th>Tipo</th>
								<th>Tuo/scaduto</th>
							</tr>
						</thead>
						<tbody>

						<?php
							if( count($rinnovi_iscrizioni) > 0) {
								for( $i=0; $i<count($rinnovi_iscrizioni); $i++ ) {
									$rinnovo_iscrizione = $rinnovi_iscrizioni[$i];
						?>
							<tr>
								<td>
									<a href="javascript:void(0);" class="btn btn-sm btn-success sgp-select-missed-user" id="precompila-<?=$rinnovo_iscrizione->id?>" role="button" title="Seleziona questo utente"><i class="fa fa-check"></i> Seleziona utente</a>
								</td> <!-- DISPONIBILE PER SU-ADMIN E ADMIN PALESTRA -->
								<td><?=$rinnovo_iscrizione->cognome?> <?=$rinnovo_iscrizione->nome?></td>
								<td>
									<div><?=( $rinnovo_iscrizione->cellulare != '' ? $rinnovo_iscrizione->cellulare.' (cellulare)' : '' )?></div>
									<div><?=( $rinnovo_iscrizione->telefono != '' ? $rinnovo_iscrizione->telefono.' (telefono)' : '' )?></div>
									<div><?=( $rinnovo_iscrizione->email != '' ? $rinnovo_iscrizione->email : '' )?></div>
								</td>
								<td><?=$rinnovo_iscrizione->data_str?> <?=$rinnovo_iscrizione->ora_str?></td>
								<td><?=( $rinnovo_iscrizione->free_pass == 1 ? 'Free Pass' : 'Missed' )?></td>
								<td>
						<?php
									if( $rinnovo_iscrizione->scaduto == 0 ) {
										if( $rinnovo_iscrizione->proprieta == 1 ) {
						?>
									<strong>Tuo</strong>
						<?php
										} else {
						?>
									Non Tuo
						<?php					
										}
									} else {
						?>
									<strong>Contratto scaduto</strong>
						<?php				
									}
						?>
								</td>
							</tr>
						<?php
								}
							} else {
						?>
							<tr><td colspan="4" class="text-center">Nessuna rinnovo iscrizione missed</td></tr>
						<?php
							}
						?>	
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var url_get_rinnovo = "<?=base_url()?>listarinnoviiscrizioni/getRinnovoIscrizioneAjax";
	
	$('.sgp-select-missed-user').click(function() {
		var id_button = $(this).attr("id");
		var id = id_button.replace('precompila-','');
		var bottone = $(this);
		$.ajax({
			url: url_get_rinnovo+'/'+id,
			dataType: 'json',
			success:function(data) {
				$('input[name=nome]').val(data.nome).attr('type', 'hidden');
				$('input[name=cognome]').val(data.cognome).attr('type', 'hidden');
				$('#nome-selezionato').html(data.nome);
				$('#cognome-selezionato').html(data.cognome);
				
				$('input[name=id_rinnovo_iscrizione_passata]').val(data.id);
				
				$('input[name=cellulare]').val(data.cellulare);
				$('input[name=telefono]').val(data.telefono);
				$('input[name=email]').val(data.email);
				$('input[name=id_socio_registrato]').val(data.id_socio_registrato);
				$('input[name=id_concatenazione]').val(data.id_concatenazione);
				$('input[name=come_back]').val(1);
				$('input[name=scaduto]').val(data.scaduto);
				$('input[name="id_consulente"]').val(data.id_consulente);
				$('select[name="id_motivazione"]').val(data.id_motivazione);
				$('textarea[name="note"]').val(data.note);
				
				$('.sgp-select-registrato-user, .sgp-select-missed-user').hide();
				bottone.parent().html('<strong>Selezionato</strong>');
				
				$('input[name=missed]').filter('[value=0]').prop('checked', true);
				checkAllCantatti();
			}, error:function(){alert('ERRORE!!!');}
		});
	});
</script>