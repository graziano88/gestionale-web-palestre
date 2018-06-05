<div id="page-wrapper">

	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Inserimento nuovo rinnovo/iscrizione</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
			<form role="form" action="<?=base_url()?>rinnoviiscrizionicontroller/creaRinnovoIscrizione" method="post" id="sgp-insert-form">
				<input name="id_palestra" value="<?=$id_palestra?>" hidden="true">
				<?php
				if( $privilegi == 0 ) {
				?>
				<div class="form-group">
					<label>Desk</label>
					<select class="form-control" name="id_consulente">
						<?=$option_desk?>
					</select>
				</div>
				<?php
				} else {
				?>
				<input name="id_consulente" value="<?=$id_desk?>" hidden="true">
				<?php
				}
				?>
				<div class="form-group">
					<label>Tipo</label>
					<div class="row">
						<div class="col-lg-3 btn-lg text-center sgp-radio-tipo" role="button">
							<div>Rinnovo senza appuntamento</div>
							<input type="radio" name="tipo" id="optionsRadiosInline1" value="1" checked>
						</div>
						<div class="col-lg-3 btn-lg text-center sgp-radio-tipo" role="button">
							<div>Rinnovo con appuntamento</div>
							<input type="radio" name="tipo" id="optionsRadiosInline2" value="2">
						</div>
						<div class="col-lg-3 btn-lg text-center sgp-radio-tipo" role="button">
							<div>Tour spontaneo</div>
							<input type="radio" name="tipo" id="optionsRadiosInline3" value="3">
						</div>
						<div class="col-lg-3 btn-lg text-center sgp-radio-tipo" role="button">
							<div>Appuntamento per nuovo cliente</div>
							<input type="radio" name="tipo" id="optionsRadiosInline3" value="4">
						</div>
					</div>
				</div>
				<script>
					
				</script>
				
				<div id="blocco-input-nome-cognome">
					<div class="form-group">
						<label>Nome</label>
						<input class="form-control sgp-capitalize" name="nome" value="<?=$rinnovo_iscrizione->nome?>">
						<div id="nome-selezionato"></div>
					</div>
					<div class="form-group">
						<label>Cognome</label>
						<input class="form-control sgp-capitalize" name="cognome" value="<?=$rinnovo_iscrizione->cognome?>">
						<div id="cognome-selezionato"></div>
					</div>
				</div>
				<div id="sgp-result-search-missed"></div>
				<div id="sgp-result-search-registrati"></div>
				<input name="come_back" value="0" hidden="true">
				<input name="id_socio_registrato" hidden="true" value="<?=$rinnovo_iscrizione->id_socio_registrato?>">
				<input name="id_concatenazione" hidden="true" value="<?=$rinnovo_iscrizione->id_concatenazione?>">
				<input name="id_rinnovo_iscrizione_passata" hidden="true" value="<?=$rinnovo_iscrizione->id?>">
				<input name="scaduto" value="0" hidden="true">
				
				<div class="form-group">
					<label>Cellulare</label>
					<input class="form-control" name="cellulare" value="<?=$rinnovo_iscrizione->cellulare?>">
				</div>
				<div class="form-group">
					<label>Telefono fisso</label>
					<input class="form-control" name="telefono" value="<?=$rinnovo_iscrizione->telefono?>">
				</div>
				<div class="form-group">
					<label>Email</label>
					<input class="form-control" name="email" value="<?=$rinnovo_iscrizione->email?>">
				</div>
				
				<script>
					var url_search_missed = '<?=base_url()?>listarinnoviiscrizioni/searchRinnoviIscrizioniMissedAjax';
					var url_search_registrati = '<?=base_url()?>listarinnoviiscrizioni/searchRinnoviIscrizioniRegistratiAjax';
					
					$('input[name=nome], input[name=cognome]').keyup(function() {
						var nome = $('input[name=nome]').val();
						var cognome = $('input[name=cognome]').val();
						
									   
						var words_str = '';
						if( nome != '' ) {
							words_str = nome;
							if( cognome != '' ) {
								words_str += ' '+cognome;
							}
						} else {
							if( cognome != '' ) {
								words_str = cognome;
							}
						}
						
						
						
						if( words_str != '' ) {
							post_array = {
								"search_words" : words_str,
								"id_palestra": "<?=$id_palestra?>"
							} 

							$.post(url_search_missed, post_array, function(data) {
								$('#sgp-result-search-missed').html(data);
    						});
							
							$.post(url_search_registrati, post_array, function(data) {
								$('#sgp-result-search-registrati').html(data);
    						});
						} else {
							$('#sgp-result-search-missed').html('');
							$('#sgp-result-search-registrati').html('');
						}
					});
					
				</script>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group">
									<label>Free pass</label>
									<div class="radio">
										<label>
											<input type="radio" name="free_pass" value="1" >Sì
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="free_pass" value="0" checked>No
										</label>
									</div>															
								</div>
								<div id="sgp-abbonamento-field">
									<div class="form-group">
										<label>Tipo Free-pass</label>
										<select class="form-control" name="id_tipo_abbonamento">
											<?=$option_tipi_abbonamento?>
										</select>
									</div>
									<div class="form-group">
										<label>Nome Socio Presentatore</label>
										<input class="form-control" id="sgp-input-search-socio">
										<input name="id_socio_presentatore" value="" hidden="true">
									</div>				
									<div id="sgp-result-search-soci"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-group" id="sgp-missed-field">
					<label>Missed</label>
					<div class="radio">
						<label>
							<input type="radio" name="missed" value="1" >Sì
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="missed" value="0" checked>No
						</label>
					</div>															
				</div>
				<script>
					changeAbbonamentoForm();
					$('input[name=free_pass]').change(function() {
						changeAbbonamentoForm();
					});
					function changeAbbonamentoForm() {
						console.log($('input[name=free_pass]:checked').val());
						if( $('input[name=free_pass]:checked').val() == 1 ) {
							$('#sgp-missed-field').hide();
							$('#sgp-abbonamento-field').show();
						} else {
							$('#sgp-missed-field').show();
							$('#sgp-abbonamento-field').hide();
						}
					}
					
					var url_search_soci = '<?=base_url()?>listarinnoviiscrizioni/searchSociAjax';
					
					$('#sgp-input-search-socio').keyup(function() {
						var nome = $('#sgp-input-search-socio').val();
						
									   
						var words_str = '';
						if( nome != '' ) {
							words_str = nome;
						}
						
						
						
						if( words_str != '' ) {
							post_array = {
								"search_words" : words_str,
								"id_palestra": "<?=$id_palestra?>"
							} 

							$.post(url_search_soci, post_array, function(data) {
								$('#sgp-result-search-soci').html(data);
    						});
							
						} else {
							$('#sgp-result-search-soci').html('');
						}
					});
				</script>
				<div class="form-group">
					<label>Motivazione della frequenza</label>
					<select class="form-control" name="id_motivazione">
						<?=$option_motivazioni?>
					</select>
				</div>
				<div class="form-group">
					<label>Note</label>
					<textarea class="form-control" name="note" rows="5" style="resize: none"><?=$rinnovo_iscrizione->note?></textarea>
				</div>
				<div class="row sgp-btn-row">
					<div class="text-center">
						<span><a href="javascript:void(0)" class="btn btn-sm btn-success disabled sgp-disabled-btn" role="button" title="" id="sgp-submit-form">Inserisci</a></span>
						<a href="<?=base_url()?>listarinnoviiscrizioni" class="btn btn-sm btn-default" role="button">Annulla</a>
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
	var url_submit_function = '<?=base_url()?>rinnoviiscrizionicontroller/creaRinnovoIscrizione';
	var url_redirect = '<?=base_url()?>listarinnoviiscrizioni';
	var url_get_tipi_abbonamento = "<?=base_url()?>listarinnoviiscrizioni/getTipiAbbonamentoOptions";
	
	var id_palestra = "<?=$id_palestra?>";
	loadAllListener();
	
	function listenerCheckContatto() {
		$('input[name=cellulare], input[name=telefono], input[name=email]').keyup(function() {
			checkAllCantatti();
		});
		
	}
	
	function checkAllCantatti() {
		var check_mail = true;
		if( $('input[name=email]').val() != '' ) {
			check_mail = isEmail($('input[name=email]').val());
		}
		var check_contatti = checkContatto();
		if( check_mail ) {
			$('input[name=email]').parent().removeClass('has-error');
		} else {
			$('input[name=email]').parent().addClass('has-error');
		}
		if( check_mail && check_contatti ) {
			$('#sgp-submit-form').removeClass('disabled').removeClass('sgp-disabled-btn');
		} else {
			$('#sgp-submit-form').addClass('disabled').addClass('sgp-disabled-btn');
		}
	}
	
	function isEmail(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}
	
	function checkContatto() {
		//console.log('key');
		if( $('input[name=cellulare]').val() != '' || $('input[name=telefono]').val() != '' || $('input[name=email]').val() != '' ) {
			//$('#sgp-submit-form').removeClass('disabled').removeClass('sgp-disabled-btn');
			return true;
		} else {
			//$('#sgp-submit-form').addClass('disabled').addClass('sgp-disabled-btn');
			return false;
		}
	}
	
	function listenerFreePass() {
		$('input[name=free_pass]').change(function() {
			//var url_get_tipologie_abbonamenti = '<?=base_url()?>listarinnoviiscrizioni/getOptionTipiAbbonamentoAjax/'+id_palestra+'/'+(-1)+'/'+$(this).val();
			//console.log(url_get_tipologie_abbonamenti);
			/*
			$.ajax({
				url: url_get_tipologie_abbonamenti,
				dataType: "html",
				success: function(data){
					$('select[name=id_tipo_abbonamento]').html(data);
					console.log(data);
				},
				error: function() { alert("Error posting feed."); }
			});*/
		});
	}
	
	function listenerTipo() {
		$('.sgp-radio-tipo').click(function() {
			$('input[name=tipo]').attr('checked', false).prop('checked', false);
			
			$(this).children('input[name=tipo]').attr('checked', true).prop('checked', true);
			console.log($(this).children('div').html()+' '+$(this).children('input').val());
		});
	}
	
	function listenerSubmitForm() {
		// LISTENER SUBMIT FORM
		$('#sgp-submit-form').click(function() {
			$('#sgp-insert-form').submit();
		});
	}
	
	function loadAllListener() {
		listenerSubmitForm();
		listenerTipo();
		listenerFreePass();
		listenerCheckContatto();
		checkAllCantatti();
	}
</script>