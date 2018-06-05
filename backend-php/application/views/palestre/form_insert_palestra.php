<div id="page-wrapper">

	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Inserimento nuova palestra</h1>
			</div>
		</div>
		<div class="row sgp-btn-row">
			<div class="text-center col-lg-7">
				<a href="<?=base_url()?>listapalestre" class="btn btn-sm btn-info" role="button" title="">Indietro</a>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
			<form role="form" action="" method="post" id="sgp-insert-form">
			<div class="form-group">
				<label>Nome</label>
				<input class="form-control sgp-capitalize" name="nome">
			</div>
			<div class="form-group">
				<label>Ragione sociale</label>
				<input class="form-control sgp-capitalize" name="ragione_sociale">
			</div>
			<div class="form-group">
				<label>P.iva</label>
				<input class="form-control sgp-capitalize" name="partita_iva">
			</div>
			<div class="form-group">
				<label>Attiva dal</label>
				<div class='input-group date' id='attiva_dal'>
					<input type='text' class="form-control" placeholder="gg/mm/aaaa" name="attiva_dal" value="<?=$data_oggi?>" />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar">
						</span>
					</span>
				</div>
			</div>
			<div class="form-group">
				<label>Attiva al</label>
				<div class='input-group date' id='attiva_al'>
					<input type='text' class="form-control" placeholder="gg/mm/aaaa" name="attiva_al" value="<?=$data_scadenza_default?>" />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar">
						</span>
					</span>
				</div>
			</div>
			<div class="form-group">
				<label>Logo</label>
				<input type="file" name="immagine_logo">
			</div>
			<div class="form-group">
				<label>Indirizzo</label>
				<input class="form-control sgp-capitalize" name="indirizzo">
			</div>
			<div class="form-group">
				<label>Città</label>
				<input class="form-control sgp-capitalize" name="citta">
			</div>
			<div class="form-group">
				<label>CAP</label>
				<input class="form-control" name="cap" maxlength="5">
			</div>
			<div class="form-group">
				<label>Provincia</label>
				<input class="form-control sgp-uppercase" name="provincia" maxlength="2">
			</div>
			
			
			<!-- CONTATTI -->
			<div class="row">
				<div class="col-lg-12"><strong>Contatti:</strong> </div>
			</div>
			<div class="row">
				<div class="col-lg-12 table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th></th>
								<th>Tipo Contatto</th>
								<th>Numero</th>
							</tr>
						</thead>
						<tbody id="sgp-row-contatti">
						</tbody>
					</table>
					<div class="row text-center">
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal" id="sgp-add-contatto"><i class="fa fa-plus"></i> Aggiungi contatto</button>
					</div>
				</div>		
			</div>
			
			
			<div class="form-group">
				<label>Sito web</label>
				<input class="form-control" name="sito_web">
			</div>
			<div class="form-group">
				<label>Email</label>
				<input class="form-control" name="email">
			</div>
			
			
			<!-- PERSONE RIFERIMENTO -->
			<div class="row">
				<div class="col-lg-12"><strong>Persone di riferimento:</strong> </div>
			</div>
			<div class="row">
				<div class="col-lg-12 table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th></th>
								<th>Nome</th>
								<th>Cognome</th>
								<th>Ruolo</th>
								<th>Telefono</th>
								<th>Cellulare</th>
								<th>E-mail</th>
							</tr>
						</thead>
						<tbody id="row-persone-riferimento">
						</tbody>
					</table>
					<div class="row text-center">
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal" id="sgp-add-persona-riferimento"><i class="fa fa-plus"></i> Aggiungi una persona di riferimento</button>
					</div>
				</div>		
			</div>
			
			<div class="form-group">
				<label>Tipo attività palestra</label>
				<select class="form-control" name="id_attivita_palestra">
					<?=$option_attivita_palestra;?>
				</select>
				<input class="form-control sgp-hide sgp-capitalize" name="new_attivita_palestra">
			</div>
			<div class="form-group">
				<label>Mq totali</label>
				<input class="form-control" name="mq">
			</div>
			
			<div class="form-group">
				<label>Mq sala attrezzi</label>
				<input class="form-control" name="mq_sala_attrezzi">
			</div>
			<div class="form-group">
				<label>Mq sala corsi</label>
				<input class="form-control" name="mq_sala_corsi">
			</div>
			<div class="form-group">
				<label>Mq cadio fitness</label>
				<input class="form-control" name="mq_cadio_fitness">
			</div>
			<div class="form-group">
				<label>Mq spinning</label>
				<input class="form-control" name="mq_spinning">
			</div>
			<div class="form-group">
				<label>Mq rowing</label>
				<input class="form-control" name="mq_rowing">
			</div>
			<div class="form-group">
				<label>Mq arti marziali</label>
				<input class="form-control" name="mq_arti_marziali">
			</div>
			<div class="form-group">
				<label>Mq piscina</label>
				<input class="form-control" name="mq_piscina">
			</div>
			<div class="form-group">
				<label>Mq thermarium</label>
				<input class="form-control" name="mq_thermarium">
			</div>			
			
			<div class="form-group">
				<label>Ubicazione</label>
				<select class="form-control" name="id_ubicazione">
					<?=$option_ubicazioni;?>
				</select>
				<input class="form-control sgp-hide sgp-capitalize" name="new_ubicazione">
			</div>
			<div class="form-group">
				<label>Parcheggi</label>
				<select class="form-control" name="parcheggi">
					<option value="0">0 (assenti)</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5 (ampi)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Valutazione struttura</label>
				<select class="form-control" name="rating_struttura">
					<option value="1">1 (fattiscente)</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5 (nuova)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Valutazione attrezzature</label>
				<select class="form-control" name="rating_attrezzature">
					<option value="1">1 (scadenti)</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5 (ottimo stato)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Valutazione spogliatoi</label>
				<select class="form-control" name="rating_spogliatoi">
					<option value="1">1 (scadenti)</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5 (ottimo stato)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Valutazione pulizia</label>
				<select class="form-control" name="rating_pulizia">
					<option value="1">1 (scadente)</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5 (curata)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Valutazione personale</label>
				<select class="form-control" name="rating_personale">
					<option value="1">1 (impreparato/scortese)</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5 (ottimo)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Bar interno</label>
				<div class="radio">
					<label>
						<input type="radio" name="servizio_bar" value="1">Sì
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="servizio_bar" value="0" checked>No
					</label>
				</div>															
			</div>
			<div class="form-group">
				<label>Shop</label>
				<div class="radio">
					<label>
						<input type="radio" name="shop" value="1">Sì
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="shop" value="0" checked>No
					</label>
				</div>															
			</div>
			<div class="form-group">
				<label>Distributori automatici</label>
				<div class="radio">
					<label>
						<input type="radio" name="servizio_distributori" value="1">Sì
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="servizio_distributori" value="0" checked>No
					</label>
				</div>															
			</div>
			<div class="form-group">
				<label>Considerazioni generali</label>
				<textarea class="form-control" rows="3" style="margin: 0px -2px 0px 0px; width: 100%; height: 100px;resize: none;" name="considerazioni_generali" ></textarea>
			</div>
			<div class="form-group">
				<label>Altro</label>
				<textarea class="form-control" rows="3" style="margin: 0px -2px 0px 0px; width: 100%; height: 100px;resize: none;" name="altro" ></textarea>
			</div>
			<div class="row sgp-btn-row">
				<div class="text-center">
					<a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-submit-form">Crea</a>
					<a href="<?=base_url()?>listapalestre" class="btn btn-sm btn-default" role="button">Annulla</a>
				</div>
			</div>
		</form>
		</div>
		</div>
		<!-- MODAL -->
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
</div>
	
<script>
	var url_new_riferimento = '<?=base_url()?>listapalestre/getNuovaPersoneRiferimento';
	var url_add_new_riferimento = '<?=base_url()?>listapalestre/addRowPersonaRiferimento';
	var url_new_contatto = '<?=base_url()?>listapalestre/getContattoForm';
	var url_add_new_contatto = '<?=base_url()?>listapalestre/addRowContatto';
	var url_add_palestra_function = '<?=base_url()?>palestracontroller/creaPalestra';
	var url_redirect = '<?=base_url()?>listapalestre';
	
	$(document).ready(function() {
		
		
		listenerLoad();
							
		/* DATETIME PICKER */
		$('#attiva_dal, #attiva_al').datetimepicker({
			viewMode:'years',
			format:"DD/MM/YYYY"
		});
		
		/* LISTENER ADD CONTATTO BTN */
		$('#sgp-add-contatto').click(function() {
			var testo_modal = "";
			$.ajax({
				type: "POST",
				url: url_new_contatto,
				dataType: "html",
				success:function(data){
					testo_modal = data;
					
					if( testo_modal == -1 ) {
						location.reload();
					}
					$('#modal').html(testo_modal);
					listenerLoad();
					$('#sgp-submit-form-contatto').click(function(e) {
						var form = $('#sgp-add-contatto-form');
						var dataForm = form.serializeArray();
						e.preventDefault();
						$.ajax({
							type: "POST",
							data: dataForm,
							url: url_add_new_contatto,
							dataType: "html",
							success:function(data){
								$('#modal').modal('hide');
								$("#sgp-row-contatti").append(data);
								listenerLoad();
							}
						});
					});
				}
			});
		});
				
		/* LISTENER ADD PERSONA RIFERIMENTO BTN */
		$('#sgp-add-persona-riferimento').click(function() {
			var testo_modal = "";
			$.ajax({
				type: "POST",
				url: url_new_riferimento,
				dataType: "html",
				success:function(data){
					testo_modal = data;
					
					if( testo_modal == -1 ) {
						location.reload();
					}
					$('#modal').html(testo_modal);
					listenerLoad();
					$('#sgp-submit-form-persona-riferimento').click(function(e) {
						var form = $('#sgp-add-riferimento-form');
						var dataForm = form.serializeArray();
						e.preventDefault();
						$.ajax({
							type: "POST",
							data: dataForm,
							url: url_add_new_riferimento,
							dataType: "html",
							success:function(data){
								$('#modal').modal('hide');
								$("#row-persone-riferimento").append(data);
								listenerLoad();
							}
						});
					});
				}
			});
		});
		
		/* LISTENER SUBMIT FORM */
		$('#sgp-submit-form').click(function() {
			var form = $('#sgp-insert-form');

			var fd = new FormData();
			var file_data = $('input[type="file"]')[0].files;//.files; // for multiple files
			var logo = file_data[0];

			fd.append("immagine_logo", logo);

			var other_data = form.serializeArray();
			//console.log('%o', other_data);
			for(j=0; j<other_data.length; j++) {
				//console.log(other_data[j]['name']+" "+other_data[j]['value']);
				fd.append(other_data[j]['name'],other_data[j]['value']);
			}
			$.ajax({
				url: url_add_palestra_function,
				data: fd,
				contentType: false,
				processData: false,
				type: 'POST',
				dataType: "html",
				success: function(data){
					testo_modal = data;
					$('#modal').html(data);
					$('#modal').modal('show');
					reloadPagina();

				},
				error: function() { alert("Error posting feed."); }
			});
		});
	});
	
	
	/* FUNZIONI JS */
	function listenerLoad() {
		/* LISTENER ALTRO NELLA TIPOLOGIA NUMERO */
		var id_tipologie = $('select[name="id_tipologia_numero"]');
		for(var i=0;i<id_tipologie.length;i++) {
			if( $(id_tipologie[i]).val() == "" ) {
				$(id_tipologie[i]).next().show();
			}
		}
		$('select[name="id_tipologia_numero"]').change(function() {
			if( $(this).val() == "" ) {
				$(this).next().show();
			} else {
				$(this).next().hide();
				$(this).next().val('');
			}
		});
		
		/* LISTENER ALTRO NEL RUOLO RIFERIMENTO */
		var id_ruoli = $('select[name="id_ruolo_riferimento"]');
		for(var i=0;i<id_ruoli.length;i++) {
			if( $(id_ruoli[i]).val() == "" ) {
				$(id_ruoli[i]).next().show();
			}
		}
		$('select[name="id_ruolo_riferimento"]').change(function() {
			if( $(this).val() == "" ) {
				$(this).next().show();
			} else {
				$(this).next().hide();
				$(this).next().val('');
			}
		});
		
		/* LISTENER ALTRO IN ATTIVITA' PALESTRA */
		if( $('select[name="id_attivita_palestra"]').val() == "" ) {
			$('select[name="id_attivita_palestra"]').next().show();
		}
		$('select[name="id_attivita_palestra"]').change(function() {
			if( $(this).val() == "" ) {
				$(this).next().show();
			} else {
				$(this).next().hide();
				$(this).next().val('');
			}
		});

		/* LISTENER ALTRO NELL'UBICAZIONE PALESTRA */
		if( $('select[name="id_ubicazione"]').val() == "" ) {
			$('select[name="id_ubicazione"]').next().show();
		}
		$('select[name="id_ubicazione"]').change(function() {
			if( $(this).val() == "" ) {
				$(this).next().show();
			} else {
				$(this).next().hide();
				$(this).next().val('');
			}
		});


		/* LISTENER REMOVE BUTTON */
		$('.sgp-remove-logo').click(function(){
			$(this).parent().remove();
			$('input[name="old_logo"]').attr('value','');
		});

		$('.sgp-remove-contact').click(function(){
			$(this).parent().parent().remove();
		});

		$('.sgp-remove-riferimento').click(function(){
			$(this).parent().parent().remove();
		});
	}
	
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			//location.reload();
			$(location).attr('href', url_redirect);
		});
	}
</script>