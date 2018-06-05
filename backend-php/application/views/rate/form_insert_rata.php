<div id="page-wrapper">

	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Inserimento nuovo Pagamento</h1>
			</div>
		</div>
		<!--
		<div class="row sgp-btn-row">
			<div class="text-center col-lg-7">
				<a href="<?=base_url()?>listasoci/showSocio/<?=$id_socio?>" class="btn btn-sm btn-info" role="button" title="">Indietro</a>
			</div>
		</div>
		-->
		<div class="row">
			<div class="col-lg-7">
			<form role="form" action="" method="post" id="sgp-crea-rate">
				<input name="valore_abbonamento" value="<?=$valore_abbonamento?>" hidden="true">
				<div class="form-group">
					<label>Numero rate</label>
					<select class="form-control" name="numero_rate">
						<option value="1">1 rata</option>
						<option value="2">2 rate</option>
						<option value="3">3 rate</option>
						<option value="4">4 rate</option>
						<option value="">Manuale</option>
					</select>
					<span id="sgp-numero-rate-manuale" style="display: none">
						N.rate: <br>
						<input class="form-control" name="numero_rate_manuale" type="number">
					</span>
				</div>
				<div class="form-group">
					<label>Scadenza rata a</label>
					<select class="form-control" name="giorni_scadenza">
						<option value="30">30 giorni</option>
						<option value="60">60 giorni</option>
						<option value="90">90 giorni</option>
						<option value="">Manuale</option>
					</select>
					<span id="sgp-giorni-scadenza-manuale" style="display: none">
						N.giorni <br>
						<input class="form-control" name="giorni_scadenza_manuale" type="number">
					</span>
				</div>
				<div class="row sgp-btn-row">
					<div class="text-center">
						<span><a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-submit-form-crea-rate">Genera modulo rate</a></span> <a href="<?=base_url()?>listaabbonamenti/showAbbonamento/<?=$id_abbonamento?>" class="btn btn-sm btn-default" role="button">Annulla</a>
					</div>
				</div>
			</form>
			<form role="form" id="sgp-show-impostazioni-rate" style="display: none">
				<div class="form-group">
					<label>Numero rate: </label>
					<span id="sgp-val-numero-rate"></span>
				</div>
				<div class="form-group">
					<label>Scadenza rata a: </label>
					<span id="sgp-val-giorni-scadenza"></span><span> giorni</span>
				</div>
				<div class="row sgp-btn-row">
					<div class="text-center">
						<span><a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-reset-rate">Re-imposta le rate</a></span>
					</div>
				</div>
			</form>
			<script>
				var url_get_form_rate = '<?=base_url()?>listarate/getFormRate';
				var giorni_scadenza = 0;
				var numero_rate = 0;
				function listenerNumeroRate() {
					$('select[name=numero_rate]').change(function() {
						if( $(this).val() == '' ) {
							$('#sgp-numero-rate-manuale').show();
						} else {
							$('#sgp-numero-rate-manuale').hide();
						}
					});
				}
				listenerNumeroRate();
				function listenerGiorniScadenza() {
					$('select[name=giorni_scadenza]').change(function() {
						if( $(this).val() == '' ) {
							$('#sgp-giorni-scadenza-manuale').show();
						} else {
							$('#sgp-giorni-scadenza-manuale').hide();
						}
					});
				}
				listenerGiorniScadenza();
				
				$('#sgp-submit-form-crea-rate').click(function() {
					mostraImpostazioniRata();
					var form = $('#sgp-crea-rate');
					var dataForm = form.serializeArray();
					$.ajax({
						url: url_get_form_rate,
						data: dataForm,
						type: 'POST',
						dataType: "html",
						success: function(data){
							$('#sgp-subform-rate').html(data);
							$('#sgp-insert-form').show();
						},
						error: function() { alert("Error posting feed."); }
					});
				});
				
				function mostraImpostazioniRata() {
					if( $('select[name=numero_rate]').val() != '' ) {
						numero_rate = $('select[name=numero_rate]').val();
					} else {
						numero_rate = $('input[name=numero_rate_manuale]').val();
					}
					if( numero_rate == 1 ) {
						stringa_numero_rate = 'una rata';
					} else {
						stringa_numero_rate = numero_rate+' rate';
					}
					$('#sgp-val-numero-rate').html(stringa_numero_rate);
					
					if( $('select[name=giorni_scadenza]').val() != '' ) {
						giorni_scadenza = $('select[name=giorni_scadenza]').val();
					} else {
						giorni_scadenza = $('input[name=giorni_scadenza_manuale]').val();
					}
					$('#sgp-val-giorni-scadenza').html(giorni_scadenza);
					
					$('#sgp-crea-rate').hide();
					$('#sgp-show-impostazioni-rate').show();
					
					$('#sgp-reset-rate').click(function() {
						$('#sgp-show-impostazioni-rate').hide();
						$('#sgp-crea-rate').show();
						$('#sgp-subform-rate').html('');
						$('#sgp-insert-form').hide();
					});
				}
			</script>
			<form role="form" action="<?=base_url()?>ratacontroller/insertRata" method="post" id="sgp-insert-form" style="display: none">
				<input name="id_socio" value="<?=$id_socio?>" hidden="true">
				<input name="id_palestra" value="<?=$id_palestra?>" hidden="true">
				<input name="id_abbonamento" value="<?=$id_abbonamento?>" hidden="true">
				
				<div class="form-group">
					<label>Per</label>
					<div class="radio">
						<label>
							<input type="radio" name="per" value="0" checked>Nuova Iscrizione
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="per" value="1" >Rinnovo
						</label>
					</div>															
				</div>
				
				<div id="sgp-subform-rate"></div>
				
				<div class="row sgp-btn-row" id="sgp-send-form-rate">
					<div class="text-center">
						<!--<span><a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-submit-form"></a></span>-->
						<input type="submit" role="button" class="btn btn-sm btn-success" value="Inserisci Rate">
						<a href="<?=base_url()?>listaabbonamenti/showAbbonamento/<?=$id_abbonamento?>" class="btn btn-sm btn-default" role="button">Annulla</a>
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
	function setDateScadenza(id) {
		var rata = id.replace('sgp-scadenza-','');
		
		var data_rata_modificata = $('#'+id+' input').val();
		var data_rata_modificata_array = data_rata_modificata.split('/');
		var data_rata_modificata = data_rata_modificata_array[2]+'-'+data_rata_modificata_array[1]+'-'+data_rata_modificata_array[0];
		var data_unix = Date.parse(data_rata_modificata)/1000;
		
		
		var giorni_scadenza_in_sec =  Number(giorni_scadenza)*86400;
		
		//ciclo
		rata = Number(rata);
		for(var j=rata+1;j<=numero_rate;j++) {
			
			var data_unix = (data_unix+giorni_scadenza_in_sec);

			var data_tmp = new Date(data_unix*1000);

			var data_scadenza_rata = data_tmp.getDate()+'/'+(data_tmp.getMonth()+1)+'/'+data_tmp.getFullYear();
			console.log(data_scadenza_rata);
			$('#sgp-scadenza-'+j+' input').val(data_scadenza_rata);
		}
		
	}
	/*
	var url_submit_form_function = '<?=base_url()?>pagamentocontroller/insertPagamento';
	var url_get_durata_abbonamento = '<?=base_url()?>listaabbonamenti/getInfoTipoAbbonamento';
	

	loadAllListener();
		
	function listenerSubmitForm() {
		// LISTENER SUBMIT FORM
		$('#sgp-submit-form').click(function() {
			var form = $('#sgp-insert-form');
			var dataForm = form.serializeArray();
			$.ajax({
				url: url_submit_form_function,
				data: dataForm,
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
	}
	
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			$(location).attr('href', url_redirect);
		});
	}
	
	function loadAllListener() {
		checkMaxPaid();
		listenerDate();
		listenerMaxPaid();
		listenerSubmitForm();
		listenerDataProssimoPagamento();
	}
	*/
</script>