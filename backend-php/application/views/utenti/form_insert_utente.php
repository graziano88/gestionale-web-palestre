<div id="page-wrapper">

	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Inserimento nuovo utente</h1>
			</div>
		</div>
		<div class="row sgp-btn-row">
			<div class="text-center col-lg-7">
				<a href="<?=base_url()?>listautenti" class="btn btn-sm btn-info" role="button" title="">Indietro</a>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
			<form role="form" action="" method="post" id="sgp-insert-form">
			
				<div class="form-group">
					<label>Username</label>
					<input class="form-control" name="username">
				</div>
				<div class="form-group">
					<label>Password</label>
					<div class="sgp-error-field sgp-hide" id="sgp-password-check">La password non è stata ripetuta correttamente o è vuota</div>
					<input class="form-control" name="password" type="password">
				</div>
				<div class="form-group">
					<label>Ripeti la password</label>
					<input class="form-control" name="pass_2" type="password">
				</div>
				
				<div class="form-group">
					<label>Nome</label>
					<input class="form-control sgp-capitalize" name="nome">
				</div>
				<div class="form-group">
					<label>Cognome</label>
					<input class="form-control sgp-capitalize" name="cognome">
				</div>
				
				<div class="form-group">
					<label>Ruolo</label>
					<select class="form-control" name="ruolo">
						<?=$option_ruoli;?>
					</select>
				</div>
				<!--
				<div class="sgp-not-SU sgp-hide">
					
					<div class="form-group">
						<label>Palestra</label>
						<div class="sgp-blocco-modifica-per-contatti sgp-hide">Sono presenti dei contatti telefonici, è necessario eliminarli per modificare questo campo</div>
						<select class="form-control" name="id_palestra">
							<?=$option_palestre;?>
						</select>
					</div>
					-->
				<input name="id_palestra" value="<?=$id_palestra?>" hidden="true">
				<div class="form-group">
					<label>Coordinatore</label>
					<div class="radio">
						<label>
							<input type="radio" name="coordinatore" value="1" checked>Sì
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="coordinatore" value="0" >No
						</label>
					</div>															
				</div>
				<div class="form-group sgp-hide" id="id-coordinatore">
					<label>Coordinatore di riferimento</label>
					<select class="form-control" name="id_coordinatore">
					</select>
				</div>
				<!--</div>-->
				
				<div class="form-group">
					<label>Data di nascita</label>
					<div class='input-group date' id='data-nascita'>
						<input type='text' class="form-control" placeholder="gg/mm/aaaa" name="data_nascita" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar">
							</span>
						</span>
					</div>
				</div>
				
				<div class="form-group">
					<label>Sesso</label>
					<div class="radio">
						<label>
							<input type="radio" name="sesso" value="0" checked>Maschio
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="sesso" value="1" >Femmina
						</label>
					</div>															
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
					<label>Email</label>
					<input class="form-control" name="email">
				</div>
				
				<div class="row sgp-btn-row">
					<div class="text-center">
						<span><a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-submit-form">Crea</a></span>
						<a href="<?=base_url()?>listautenti" class="btn btn-sm btn-default" role="button">Annulla</a>
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
	var url_get_coordinatori_options = "<?=base_url()?>listautenti/getCoordinatoriOptions";
	var url_new_contatto = '<?=base_url()?>listautenti/getContattoForm';
	var url_add_new_contatto = '<?=base_url()?>listautenti/addRowContatto';
	var url_add_utente_function = '<?=base_url()?>utenteController/creaUtente';
	var url_redirect = '<?=base_url()?>listautenti';
	
	var ruolo = $('select[name="ruolo"]').val();
	var id_palestra = $('input[name=id_palestra]').val();
	var numero_contatti = 0;
	//listenProv();

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
	
	$('input[name=password], input[name=pass_2]').keyup(function() {
		checkPassword();
	});
	/*
	function listenerRuolo() {
		if( $('select[name=ruolo]').val() == 0 ) {

			$('.sgp-not-SU').hide();
		} else {
			$('.sgp-not-SU').show();
		}
	}
	*/
	function listenerPalestra() {
		id_palestra = $('input[name=id_palestra]').val();
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

	function listenerCoordinatore() {
		if( $('input[name="coordinatore"]:checked').val() == 0 ) {
			$('#id-coordinatore').show();
		} else {
			$('#id-coordinatore').hide();
		}
	}

	function listenerDataNascita() {
		// DATETIME PICKER
		$('#data-nascita').datetimepicker({
			viewMode:'years',
			format:"DD/MM/YYYY"
		});
	}
	
	function listenerAddContatto() {
		
		// LISTENER ADD CONTATTO BTN
		$('#sgp-add-contatto').click(function() {
			ruolo = $('select[name="ruolo"]').val();
			/*if( ruolo > 0 ) {
				id_palestra = $('select[name=id_palestra]').val();	
			} else {
				id_palestra = '';
			}*/
			var testo_modal = "";
			$.ajax({
				type: "POST",
				url: url_new_contatto+'/'+id_palestra,
				dataType: "html",
				success:function(data){
					testo_modal = data;
					
					if( testo_modal == -1 ) {
						location.reload();
					}
					$('#modal').html(testo_modal);
					
					// il listener inerenti al modal sono nella view inserita nel modal
					
				}
			});
		});
	}
	
	function listenerRemoveContact() {
		$('.sgp-remove-contact').click(function(e){
			$(this).parent().parent().remove();
			checkCountContatti();
		});
	}
	
	function checkCountContatti() {
		numero_contatti = $('input[name="id_tipologia_numero[]"]').length;
		if( numero_contatti > 0 ) {
			$('select[name=id_palestra]').attr('readonly', 'readonly').addClass('sgp-blocco-modifica-per-contatti');
			
			if( $('select[name=ruolo]').val() != 0 ) {
				$('#sgp-ruolo-su').hide();
			}
			//$('select[name=id_palestra]').parent().children('label').after('<div class="sgp-blocco-modifica-per-contatti">Sono presenti dei contatti telefonici, è necessario eliminarli per modificare questo campo</div>');
			$('.sgp-blocco-modifica-per-contatti').show();
		} else {
			$('select[name=id_palestra]').removeAttr('readonly').removeClass('sgp-blocco-modifica-per-contatti');
			$('#sgp-ruolo-su').show();
			//$('.sgp-blocco-modifica-per-contatti').remove();
			$('.sgp-blocco-modifica-per-contatti').hide();
		}
	}
	
	function checkPassword() {
		if( $('input[name=password]').val() != $('input[name=pass_2]').val() || $('input[name=password]').val() == '' ) {
			//$('#sgp-submit-form').attr('disabled', 'disabled').addClass('sgp-disabled disabled');
			$('#sgp-submit-form').parent().addClass('sgp-disabled-btn');
			$('#sgp-password-check').show();
	
		} else {
			//$('#sgp-submit-form').removeAttr('disabled').removeClass('sgp-disabled');
			$('#sgp-submit-form').parent().removeClass('sgp-disabled-btn');
			$('#sgp-password-check').hide();
		}
	}
	
	function listenerSubmitForm() {
		// LISTENER SUBMIT FORM
		$('#sgp-submit-form').click(function() {
			var form = $('#sgp-insert-form');
			var dataForm = form.serializeArray();
			$.ajax({
				url: url_add_utente_function,
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
		//listenerRuolo();
		listenerPalestra();
		listenerCoordinatore();
		listenerDataNascita();
		listenerAddContatto();
		listenerRemoveContact();
		listenerSubmitForm();
		checkPassword();
	}
</script>