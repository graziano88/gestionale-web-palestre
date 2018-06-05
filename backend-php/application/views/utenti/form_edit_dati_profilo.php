<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Modifica Dati Profilo </h4>
		</div>
		<div class="modal-body text-left">
			<div class="sgp-form-error"><?=$error_msg?></div>
			<form role="form" action="" method="post" id="sgp-edit-dati-profilo-form">
				<div class="form-group">
					<!--<label>Persona di Riferimento</label>-->
					<input name="id_utente" value="<?=$utente->id?>" hidden="true">
					<input name="ruolo" value="<?=$utente->ruolo?>" hidden="true">
					<div class="form-group">
						<label>Username</label>
						<input class="form-control" name="username" type="text" value="<?=$utente->username?>">
					</div>
					
					<div class="form-group">
						<label>Ruolo</label>
						<select class="form-control" name="ruolo">
							<?=$option_ruoli;?>
						</select>
					</div>
					<!--<div class="sgp-not-SU sgp-hide">-->
						
					<div class="form-group">
						<label>Coordinatore</label>
						<div class="radio">
							<label>
								<input type="radio" name="coordinatore" value="1" <?=( $utente->coordinatore == 1 ? 'checked' : '' )?>>Sì
							</label>
						</div>
						<?php
						if( $numero_coordinati == 0 ) {
						?>
						<div class="radio">
							<label>
								<input type="radio" name="coordinatore" value="0" <?=( $utente->coordinatore == 0 ? 'checked' : '' )?>>No
							</label>
						</div>	
						<?php
						} else {
						?>
						<label>Esistono degli utenti coordinati da <?=$utente->username?></label>
						<?php
						}
						?>														
					</div>
					<div class="form-group sgp-hide" id="id-coordinatore">
						<label>Coordinatore di riferimento</label>
						<select class="form-control" name="id_coordinatore">
							<?=$option_coordinatori;?>
						</select>
					</div>
					<!--</div>-->
				</div>
				<div class="row">
					<div class="text-center">
						<a href="javascript:void(0)" class="btn btn-sm btn-warning" role="button" title="" id="sgp-submit-form-edit-dati-profilo">Modifica</a>
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
<script>
	var url_get_coordinatori_options = "<?=base_url()?>listautenti/getCoordinatoriOptions";
	var url_edit_profilo = "<?=base_url()?>/utenteController/modificaProfilo"
	
	var coordinatore_impostato = <?=$utente->coordinatore?>;
	var ruolo_impostato = <?=$utente->ruolo?>;
	
	
	
	loadAllListener();
/*	
	$('select[name=ruolo]').change(function() {
		listenerRuolo();
	});

	$('select[name=id_palestra]').change(function() {
		listenerPalestra();
	});
*/
	$('input[name="coordinatore"]').change(function() {
		listenerCoordinatore();
	});
	
	$('#sgp-submit-form-edit-dati-profilo').click(function(e) {
		
		var form = $('#sgp-edit-dati-profilo-form');
		var dataForm = form.serializeArray();
		e.preventDefault();
		$.ajax({
			type: "POST",
			data: dataForm,
			url: url_edit_profilo,
			dataType: "html",
			success:function(data){
				//$('#modal').modal('hide');
				$('#modal').html(data);
			}
		});
	});
	
	function initializationFields() {
		/*if( coordinatore_impostato == 1 && ruolo_impostato != 0 && numero_coordinati > 0 ) {
			$('select[name=id_palestra]').attr('readonly', 'readonly').addClass('sgp-blocco-modifica-per-contatti');
			
		}*/
	}
	/*
	function listenerRuolo() {
		//if( $('select[name=ruolo]').val() == 0 ) {
		if( ruolo_impostato == 0 ) {
			$('.sgp-not-SU').hide();
		} else {
			$('.sgp-not-SU').show();
		}
	}
	
	function listenerPalestra() {
		id_palestra = $('select[name=id_palestra]').val();
		$.ajax({
			url: url_get_coordinatori_options+'/'+id_palestra,
			dataType: "html",
			success:function(data){
				if( data != '' ) {
					$('select[name=id_coordinatore]').html(data);
					$('input[name="coordinatore"]').filter('[value=0]').parent().show();
					$('#id-coordinatore').show();
				} else {
					$('input[name="coordinatore"]').filter('[value=1]').prop('checked', true);
					$('input[name="coordinatore"]').filter('[value=0]').parent().hide();
				}
				listenerCoordinatore();
			}
		});
	}
	*/
	
	function listenerCoordinatore() {
		//listenerPalestra();
		if( $('input[name="coordinatore"]:checked').val() == 0 ) {
			$('#id-coordinatore').show();
		} else {
			$('#id-coordinatore').hide();
		}
	}
	
	function loadAllListener() {
		//listenerRuolo();
		//listenerPalestra();
		listenerCoordinatore();
	}

</script>