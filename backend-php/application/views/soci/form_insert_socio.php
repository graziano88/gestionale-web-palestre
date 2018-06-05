<div id="page-wrapper">

	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Inserimento nuovo utente</h1>
			</div>
		</div>
		<div class="row sgp-btn-row">
			<div class="text-center col-lg-7">
				<a href="<?=base_url()?>listasoci" class="btn btn-sm btn-info" role="button" title="">Indietro</a>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
			<form role="form" action="" method="post" id="sgp-insert-form">
				<input name="id_palestra" value="<?=$id_palestra?>" hidden="true">
				<div class="form-group">
					<label>Nome</label>
					<input class="form-control sgp-capitalize" name="nome">
				</div>
				<div class="form-group">
					<label>Cognome</label>
					<input class="form-control sgp-capitalize" name="cognome">
				</div>
				
				
				<!--	CAMPO UPLOAD FOTO	-->
				
				
				
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
				
				<div class="form-group">
					<label>Nato a (città): </label>
					<input class="form-control sgp-capitalize" name="nato_a">
				</div>
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
					<label>Professione</label>
					<select class="form-control" name="id_professione">
						<?=$option_professioni?>
					</select>
				</div>
				<div class="form-group">
					<label>Email</label>
					<input class="form-control" name="email">
				</div>
				<div class="form-group">
					<label>Socio presentatore (facoltativo)</label>
					<select class="form-control" name="id_socio_presentatore">
						<?=$option_soci_presentatori?>
					</select>
				</div>
				<div class="form-group">
					<label>Desk registrazione: </label>
					<select class="form-control" name="id_consulente">
						<?=$option_desk?>
					</select>
				</div>
				<div class="form-group">
					<label>Fonte di provenienza: </label>
					<select class="form-control" name="id_fonte_provenienza">
						<?=$option_fonti_provenienza?>
					</select>
				</div>
				<div class="form-group">
					<label>Motivazione frequenza: </label>
					<select class="form-control" name="id_motivazione">
						<?=$option_motivazioni?>
					</select>
				</div>
				
				<!-- CONTATTI -->
				<div class="row">
					<div class="col-lg-12"><strong>Recapiti Telefonici:</strong> </div>
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
							<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal" id="sgp-add-contatto"><i class="fa fa-plus"></i> Aggiungi recapito telefonico</button>
						</div>
					</div>		
				</div>

				
				
				<div class="row sgp-btn-row">
					<div class="text-center">
						<span><a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-submit-form">Crea</a></span>
						<a href="<?=base_url()?>listasoci" class="btn btn-sm btn-default" role="button">Annulla</a>
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
	var url_get_coordinatori_options = "<?=base_url()?>listasoci/getCoordinatoriOptions";
	var url_new_contatto = '<?=base_url()?>listasoci/getContattoForm';
	
	var id_palestra = '<?=$id_palestra?>';
	
	var url_add_socio_function = '<?=base_url()?>sociocontroller/creaSocio';
	var url_redirect = '<?=base_url()?>listasoci';
	
	//var ruolo = $('select[name="ruolo"]').val();
	//var id_palestra = $('select[name=id_palestra]').val();
	var numero_contatti = 0;
	//listenProv();

	loadAllListener();
	
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
			//checkCountContatti();
		});
	}
	
	/*
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
	*/
	
	function listenerSubmitForm() {
		// LISTENER SUBMIT FORM
		$('#sgp-submit-form').click(function() {
			var form = $('#sgp-insert-form');
			var dataForm = form.serializeArray();
			$.ajax({
				url: url_add_socio_function,
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
		listenerDataNascita();
		listenerAddContatto();
		listenerRemoveContact();
		listenerSubmitForm();
	}
</script>