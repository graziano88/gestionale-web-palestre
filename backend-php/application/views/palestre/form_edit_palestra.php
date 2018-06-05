<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Modifica della palestra: "<?=$nome?>"</h1>
			</div>
		</div>
		<div class="row sgp-btn-row">
			<div class="text-center col-lg-7">
				<a href="<?=base_url()?>listapalestre" class="btn btn-sm btn-info" role="button" title="">Indietro</a>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
			<form role="form" action="" method="post" id="sgp-edit-form">
			<input hidden="true" name="id_palestra" value="<?=$id?>" />
			<div class="form-group">
				<label>Nome</label>
				<input class="form-control sgp-capitalize" name="nome" value="<?=$nome;?>">
			</div>
			<div class="form-group">
				<label>Ragione sociale</label>
				<input class="form-control sgp-capitalize" name="ragione_sociale" value="<?=$ragione_sociale;?>">
			</div>
			<div class="form-group">
				<label>P.iva</label>
				<input class="form-control" name="partita_iva" value="<?=$partita_iva;?>">
			</div>
			<div class="form-group">
				<label>Attiva dal</label>
				<div class='input-group date' id='attiva_dal'>
					<input type='text' class="form-control" placeholder="gg/mm/aaaa" name="attiva_dal" value="<?=$attiva_dal;?>"/>
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar">
						</span>
					</span>
				</div>
			</div>
			<div class="form-group">
				<label>Attiva al</label>
				<div class='input-group date' id='attiva_al'>
					<input type='text' class="form-control" placeholder="gg/mm/aaaa" name="attiva_al" value="<?=$attiva_al;?>" />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar">
						</span>
					</span>
				</div>
			</div>
			
			<div class="form-group">
				<label>Logo</label>
				<?php if( $immagine_logo != null || $immagine_logo != "" ) { ?>
				<div><img src="<?=base_url()?>loghi_uploads/<?=$immagine_logo?>" alt="Logo <?=$nome?>" style="max-width: 200px;margin-bottom: 5px;"><a href="javascript:void(0)" class="btn btn-danger btn-sm sgp-remove-logo"><i class="fa fa-minus"></i></a></div>
				<?php } ?>
				<input name="old_logo" value="<?=$immagine_logo?>" hidden="true">
				<input type="file" name="immagine_logo">
			</div>
			
			<div class="form-group">
				<label>Indirizzo</label>
				<input class="form-control sgp-capitalize" name="indirizzo" value="<?=$indirizzo;?>">
			</div>
			<div class="form-group">
				<label>Città</label>
				<input class="form-control sgp-capitalize" name="citta" value="<?=$citta;?>">
			</div>
			<div class="form-group">
				<label>CAP</label>
				<input class="form-control" name="cap" maxlength="5" value="<?=$cap;?>">
			</div>
			<div class="form-group">
				<label>Provincia</label>
				<input class="form-control sgp-uppercase" name="provincia" maxlength="2" value="<?=$provincia;?>">
			</div>
			
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
			<?php
			if( count($recapiti_telefonici_palestra) > 0 ) {

				foreach($recapiti_telefonici_palestra as $recapito_telefonico ) {	
			?>
							<tr>
								<td class="col-lg-2">
									<a href="javascript:void(0)" class="btn btn-danger btn-sm sgp-remove-contact"><i class="fa fa-minus"></i></a>
									<button type="button" class="btn btn-warning btn-sm sgp-edit-contatto" data-toggle="modal" data-target="#modal" id="sgp-edit-contatto-<?=$recapito_telefonico->id?>"><i class="fa fa-pencil"></i></button>
									<input name="id_recapito_telefonico[]" value="<?=$recapito_telefonico->id?>" hidden="true">
								</td>
								<td class=""><input name="id_tipologia_numero[]" value="<?=$recapito_telefonico->id_tipologia_numero?>" hidden="true"><?=$recapito_telefonico->tipologia_numero?><input class="" name="new_tipologia_numero[]" hidden="true" value=""></td>
								<td class=""><input name="numero[]" value="<?=$recapito_telefonico->numero?>" hidden="true"><?=$recapito_telefonico->numero?></td>
							</tr>
			<?php		
				}
			}
			?>
						</tbody>
					</table>
					<div class="row text-center">
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal" id="sgp-add-contatto"><i class="fa fa-plus"></i> Aggiungi contatto</button>
					</div>
				</div>		
			</div>
			
			<div class="form-group">
				<label>Sito web</label>
				<input class="form-control" name="sito_web" value="<?=$sito_web;?>">
			</div>
			<div class="form-group">
				<label>Email</label>
				<input class="form-control" name="email" value="<?=$email;?>">
			</div>
			
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
			<?php
			if( count($persone_riferimento_palestra) > 0 ) {
				foreach($persone_riferimento_palestra as $persona_riferimento_palestra) {
			?>
							<tr>
								<td class="col-lg-2">
									<a href="javascript:void(0)" class="btn btn-danger btn-sm sgp-remove-riferimento"><i class="fa fa-minus"></i></a>
									<button type="button" class="btn btn-warning btn-sm sgp-edit-riferimento" data-toggle="modal" data-target="#modal" id="sgp-edit-persona-riferimento-<?=$persona_riferimento_palestra->id?>"><i class="fa fa-pencil"></i></button>
									<input name="id_riferimento[]" value="<?=$persona_riferimento_palestra->id?>" hidden="true">
								</td>
								<td class=""><input name="nome_riferimento[]" value="<?=$persona_riferimento_palestra->nome?>" hidden="true"><?=$persona_riferimento_palestra->nome?></td>
								<td class=""><input name="cognome_riferimento[]" value="<?=$persona_riferimento_palestra->cognome?>" hidden="true"><?=$persona_riferimento_palestra->cognome?></td>
								<td class=""><input name="id_ruolo_riferimento[]" value="<?=$persona_riferimento_palestra->id_ruolo_personale?>" hidden="true"><input name="new_ruolo_riferimento[]" value="" hidden="true"><?=$persona_riferimento_palestra->ruolo?></td>
								<td class="">
									<input name="telefono_riferimento[]" value="<?=$persona_riferimento_palestra->telefono?>" hidden="true">
			<?php
					if( $persona_riferimento_palestra->telefono != '' ) {
			?>
									<?=$persona_riferimento_palestra->telefono?>
			<?php
					} else {
			?>
									n.d.
			<?php
					}
			?>
								</td>
								<td class="">
									<input name="cellulare_riferimento[]" value="<?=$persona_riferimento_palestra->cellulare?>" hidden="true">
			<?php
					if( $persona_riferimento_palestra->cellulare != '' ) {
			?>
									<?=$persona_riferimento_palestra->cellulare?>
			<?php
					} else {
			?>
								
									n.d.

			<?php
					}
			?>			
								</td>
								<td class="col-lg-4"><input name="email_riferimento[]" value="<?=$persona_riferimento_palestra->email?>" hidden="true"><?=$persona_riferimento_palestra->email?></td>
							</tr>	
			<?php		
				}
			}
			?>
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
					<option value="-1">n.d.</option>
					<?php 
					foreach( $attivita_palestra as $attivita_palestra_item ) {
						$selected = '';
						if( $attivita_palestra_item->id == $id_attivita_palestra ) { 
							$selected = 'selected';
						}
					?>
					<option value="<?=$attivita_palestra_item->id?>" <?=$selected?>><?=$attivita_palestra_item->tipo_attivita?></option>
					<?php } ?>
					<option value="">Altro</option>	
				</select>
				<input class="form-control sgp-hide sgp-capitalize" name="new_attivita_palestra">
			</div>
			
			<div class="form-group">
				<label>Mq</label>
				<input class="form-control" name="mq" value="<?=$mq;?>">
			</div>
			
			<div class="form-group">
				<label>Mq sala attrezzi</label>
				<input class="form-control" name="mq_sala_attrezzi" value="<?=$mq_sala_attrezzi;?>">
			</div>
			<div class="form-group">
				<label>Mq sala corsi</label>
				<input class="form-control" name="mq_sala_corsi" value="<?=$mq_sala_corsi;?>">
			</div>
			<div class="form-group">
				<label>Mq cadio fitness</label>
				<input class="form-control" name="mq_cadio_fitness" value="<?=$mq_cadio_fitness;?>">
			</div>
			<div class="form-group">
				<label>Mq spinning</label>
				<input class="form-control" name="mq_spinning" value="<?=$mq_spinning;?>">
			</div>
			<div class="form-group">
				<label>Mq rowing</label>
				<input class="form-control" name="mq_rowing" value="<?=$mq_rowing;?>">
			</div>
			<div class="form-group">
				<label>Mq arti marziali</label>
				<input class="form-control" name="mq_arti_marziali" value="<?=$mq_arti_marziali;?>">
			</div>
			<div class="form-group">
				<label>Mq piscina</label>
				<input class="form-control" name="mq_piscina" value="<?=$mq_piscina;?>">
			</div>
			<div class="form-group">
				<label>Mq thermarium</label>
				<input class="form-control" name="mq_thermarium" value="<?=$mq_thermarium;?>">
			</div>
			
			<div class="form-group">
				<label>Ubicazione</label>
				<select class="form-control" name="id_ubicazione">
					<option value="-1">n.d.</option>
					<?php 
					foreach( $ubicazioni as $ubicazione ) {
						$selected = '';
						if( $ubicazione->id == $id_ubicazione ) { 
							$selected = 'selected';
						}
					?>
					<option value="<?=$ubicazione->id;?>" <?=$selected?>><?=$ubicazione->posizione;?></option>
					<?php } ?>	
					<option value="">Altro</option>					
				</select>
				<input class="form-control sgp-hide sgp-capitalize" name="new_ubicazione">
			</div>
			<div class="form-group">
				<label>Parcheggi</label>
				<select class="form-control" name="parcheggi">
					<option value="0" <?=($parcheggi == 0 ? 'selected' : '');?>>0 (assenti)</option>
					<option value="1" <?=($parcheggi == 1 ? 'selected' : '');?>>1</option>
					<option value="2" <?=($parcheggi == 2 ? 'selected' : '');?>>2</option>
					<option value="3" <?=($parcheggi == 3 ? 'selected' : '');?>>3</option>
					<option value="4" <?=($parcheggi == 4 ? 'selected' : '');?>>4</option>
					<option value="5" <?=($parcheggi == 5 ? 'selected' : '');?>>5 (ampi)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Valutazione struttura</label>
				<select class="form-control" name="rating_struttura">
					<option value="1" <?=($rating_struttura == 1 ? 'selected' : '');?>>1 (fattiscente)</option>
					<option value="2" <?=($rating_struttura == 2 ? 'selected' : '');?>>2</option>
					<option value="3" <?=($rating_struttura == 3 ? 'selected' : '');?>>3</option>
					<option value="4" <?=($rating_struttura == 4 ? 'selected' : '');?>>4</option>
					<option value="5" <?=($rating_struttura == 5 ? 'selected' : '');?>>5 (nuova)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Valutazione attrezzature</label>
				<select class="form-control" name="rating_attrezzature">
					<option value="1" <?=($rating_attrezzature == 1 ? 'selected' : '');?>>1 (scadenti)</option>
					<option value="2" <?=($rating_attrezzature == 2 ? 'selected' : '');?>>2</option>
					<option value="3" <?=($rating_attrezzature == 3 ? 'selected' : '');?>>3</option>
					<option value="4" <?=($rating_attrezzature == 4 ? 'selected' : '');?>>4</option>
					<option value="5" <?=($rating_attrezzature == 5 ? 'selected' : '');?>>5 (ottimo stato)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Valutazione spogliatoi</label>
				<select class="form-control" name="rating_spogliatoi">
					<option value="1" <?=($rating_spogliatoi == 1 ? 'selected' : '');?>>1 (scadenti)</option>
					<option value="2" <?=($rating_spogliatoi == 2 ? 'selected' : '');?>>2</option>
					<option value="3" <?=($rating_spogliatoi == 3 ? 'selected' : '');?>>3</option>
					<option value="4" <?=($rating_spogliatoi == 4 ? 'selected' : '');?>>4</option>
					<option value="5" <?=($rating_spogliatoi == 5 ? 'selected' : '');?>>5 (ottimo stato)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Valutazione pulizia</label>
				<select class="form-control" name="rating_pulizia">
					<option value="1" <?=($rating_pulizia == 1 ? 'selected' : '');?>>1 (scadente)</option>
					<option value="2" <?=($rating_pulizia == 2 ? 'selected' : '');?>>2</option>
					<option value="3" <?=($rating_pulizia == 3 ? 'selected' : '');?>>3</option>
					<option value="4" <?=($rating_pulizia == 4 ? 'selected' : '');?>>4</option>
					<option value="5" <?=($rating_pulizia == 5 ? 'selected' : '');?>>5 (curata)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Valutazione personale</label>
				<select class="form-control" name="rating_personale">
					<option value="1" <?=($rating_personale == 1 ? 'selected' : '');?>>1 (impreparato/scortese)</option>
					<option value="2" <?=($rating_personale == 2 ? 'selected' : '');?>>2</option>
					<option value="3" <?=($rating_personale == 3 ? 'selected' : '');?>>3</option>
					<option value="4" <?=($rating_personale == 4 ? 'selected' : '');?>>4</option>
					<option value="5" <?=($rating_personale == 5 ? 'selected' : '');?>>5 (ottimo)</option>
				</select>
			</div>
			<div class="form-group">
				<label>Bar interno</label>
				<div class="radio">
					<label>
						<input type="radio" name="servizio_bar" value="1" <?=($servizio_bar == 1 ? 'checked' : '');?>>Sì
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="servizio_bar" value="0" <?=($servizio_bar == 0 ? 'checked' : '');?>>No
					</label>
				</div>															
			</div>
			<div class="form-group">
				<label>Shop</label>
				<div class="radio">
					<label>
						<input type="radio" name="shop" value="1" <?=($shop == 1 ? 'checked' : '');?>>Sì
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="shop" value="0"  <?=($shop == 0 ? 'checked' : '');?>>No
					</label>
				</div>															
			</div>
			<div class="form-group">
				<label>Distributori automatici</label>
				<div class="radio">
					<label>
						<input type="radio" name="servizio_distributori" value="1" <?=($servizio_distributori == 1 ? 'checked' : '');?>>Sì
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="servizio_distributori" value="0" <?=($servizio_distributori == 0 ? 'checked' : '');?>>No
					</label>
				</div>															
			</div>
			<div class="form-group">
				<label>Considerazioni generali</label>
				<textarea class="form-control" rows="3" style="margin: 0px -2px 0px 0px; width: 100%; height: 100px;resize: none;" name="considerazioni_generali" ><?=$considerazioni_generali;?></textarea>
			</div>
			<div class="form-group">
				<label>Altro</label>
				<textarea class="form-control" rows="3" style="margin: 0px -2px 0px 0px; width: 100%; height: 100px;resize: none;" name="altro" ><?=$altro;?></textarea>
			</div>
			<div class="row sgp-btn-row">
				<div class="text-center">
					<a href="javascript:void(0)" class="btn btn-sm btn-warning" role="button" title="" id="sgp-submit-form">Modifica</a>
					<a href="<?=base_url()?>listapalestre" class="btn btn-sm btn-default" role="button" title="">Annulla</a>
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
	var id_palestra = '<?=$id?>';
	var url_new_riferimento = '<?=base_url()?>listapalestre/getNuovaPersoneRiferimento/'+id_palestra;
	var url_add_new_riferimento = '<?=base_url()?>listapalestre/addRowPersonaRiferimento';
	var url_edit_new_riferimento = '<?=base_url()?>listapalestre/editNuovaPersoneRiferimento';
	var url_new_contatto = '<?=base_url()?>listapalestre/getContattoForm/'+id_palestra;
	var url_add_new_contatto = '<?=base_url()?>listapalestre/addRowContatto';
	var url_edit_contatto_form = '<?=base_url()?>listapalestre/editContattoForm';
	var url_edit_palestra_function = '<?=base_url()?>Palestracontroller/modificaPalestra';//test';//
	var url_redirect = '<?=base_url()?>listapalestre';
	
	$(document).ready(function() {
		
		
		listenerLoad();
							
		/* DATETIME PICKER */
		$('#attiva_dal, #attiva_al').datetimepicker({
			viewMode:'years',
			format:"DD/MM/YYYY"
		});


		/* LISTENER ADD BUTTON */
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
		
		listenerModalBtn();
		
		
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


		/* LISTENER SUBMIT BUTTON */
		$('#sgp-submit-form').click(function() {
			var form = $('#sgp-edit-form');

			var fd = new FormData();
			var file_data = $('input[type="file"]')[0].files;//.files; // for multiple files
			var logo = file_data[0];
			//console.log('%o', logo);
			/*for(var i = 0;i<file_data.length;i++){
				fd.append("file_"+i, file_data[i]);
			}*/
			fd.append("immagine_logo", logo);

			var other_data = form.serializeArray();
			//console.log('%o', other_data);
			for(j=0; j<other_data.length; j++) {
				//console.log(other_data[j]['name']+" "+other_data[j]['value']);
				fd.append(other_data[j]['name'],other_data[j]['value']);
			}
			/*$.each(other_data,function(key,input){
				alert(input.key);
				fd.append(input.key,input.value);
			});*/
			//console.log(fd);
			
			$.ajax({
				url: url_edit_palestra_function,
				data: fd,
				contentType: false,
				processData: false,
				type: 'POST',
				dataType: "html",
				success: function(data){
					testo_modal = data;
					$('#modal').html(data);
					$('#modal').modal('show');
					//$('body').html(data);
					reloadPagina();
				}
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
	
	function listenerModalBtn() {
		
		$('button[data-toggle="modal"]').click(function() {

			var id_button = $(this).attr("id");
			var classi = $(this).attr("class");
			
			var riga = $(this).parent().parent();
			//alert(classi);
			if (classi.indexOf("sgp-edit-riferimento") >= 0) {
				var id = id_button.replace('sgp-edit-persona-riferimento-','');
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_edit_new_riferimento+'/'+id+'/'+id_palestra, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);
						listenerLoad();
						
						$('#sgp-submit-form-persona-riferimento').click(function(e) {
							var form = $('#sgp-edit-riferimento-form');
							var dataForm = form.serializeArray();
							e.preventDefault();
							$.ajax({
								type: "POST",
								data: dataForm,
								url: url_add_new_riferimento,
								dataType: "html",
								success:function(data){
									$('#modal').modal('hide');
									$(riga).remove();
									$('#row-persone-riferimento').append(data);
									listenerLoad();
									listenerModalBtn();
								}
							});
						});
						
					}

				});	

			} else if(classi.indexOf("sgp-edit-contatto") >= 0) {
				var id = id_button.replace('sgp-edit-contatto-','');
				var testo_modal = '';
				console.log(riga);
				$.ajax({
					type: "POST",
					url: url_edit_contatto_form+'/'+id+'/'+id_palestra, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);
						listenerLoad();
						
						$('#sgp-submit-form-contatto').click(function(e) {
							var form = $('#sgp-edit-contatto-form');
							var dataForm = form.serializeArray();
							e.preventDefault();
							$.ajax({
								type: "POST",
								data: dataForm,
								url: url_add_new_contatto,
								dataType: "html",
								success:function(data){
									$('#modal').modal('hide');
									
									$(riga).remove();
									$('#sgp-row-contatti').append(data);
									listenerLoad();
									listenerModalBtn();
								}
							});
						});
						
					}

				});	
			}
		});
	}
	
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			//location.reload();
			$(location).attr('href', url_redirect);
		});
	}
</script>
		