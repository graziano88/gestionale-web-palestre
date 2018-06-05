<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Login </h4>
		</div>
		<div class="modal-body text-center">
			<div class="sgp-error-msg">Sessione scaduta rifare il login</div>
			<?php echo form_open('verifyLogin'); ?>
			<form role="form">
				<?php if( isset($redirect_page) ) { ?>
				<input type="text" name="redirect_page" value="<?=$redirect_page?>" hidden="true"/>
				<?php } ?>
				<div class="form-group">
					<label for="username">Username:</label>
					<input class="form-control" type="text" id="username" name="username"/>
				</div>
				<div class="form-group">
					<label for="password">Password:</label>
					<input class="form-control" type="password" id="passowrd" name="password"/>
				</div>
				<input type="submit" value="Login"/>
			</form>
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
	
	function listenerRuolo() {
		//if( $('select[name=ruolo]').val() == 0 ) {
		if( ruolo_impostato == 0 ) {
			$('.sgp-not-SU').hide();
		} else {
			$('.sgp-not-SU').show();
		}
	}
	/*
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
		listenerRuolo();
		//listenerPalestra();
		listenerCoordinatore();
	}

</script>