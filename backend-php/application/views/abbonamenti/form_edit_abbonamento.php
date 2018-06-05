<div id="page-wrapper">

	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Inserimento nuovo Abbonamento</h1>
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
			<form role="form" action="" method="post" id="sgp-insert-form">
				<input name="id" value="<?=$abbonamento->id?>" hidden="true">
				<input name="id_palestra" value="<?=$abbonamento->id_palestra?>" hidden="true">
				<input name="id_socio" value="<?=$abbonamento->id_socio?>" hidden="true">
				<div class="form-group">
					<label>Tipo Abbonamento</label>
					<select class="form-control" name="id_tipo_abbonamento">
						<?=$option_tipi_abbonamento?>
					</select>
				</div>
				<div class="form-group">
					<label>Data Inizio</label>
					<div class='input-group date' id='data-inizio'>
						<input type='text' class="form-control" placeholder="gg/mm/aaaa" name="data_inizio" value="<?=$abbonamento->data_inizio_str?>" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar">
							</span>
						</span>
					</div>
				</div>
				<div class="form-group">
					<label>Data Fine</label>
					<div class='input-group date' id='data-fine'>
						<input type='text' class="form-control" placeholder="gg/mm/aaaa" name="data_fine" value="<?=$abbonamento->data_fine_str?>" disabled/>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar">
							</span>
						</span>
					</div>
					<input name="data_fine" hidden="true">
				</div>
				<div class="form-group">
					<label>Valore abbonamento</label>
					<input class="form-control" name="valore_abbonamento" value="<?=$abbonamento->valore_abbonamento?>" >
				</div>
				<div class="form-group">
					<label>Durata abbonamento (giorni)</label>
					<input class="form-control" name="durata_abbonamento" value="<?=$abbonamento->durata?>" disabled>
				</div>
				<div class="form-group">
					<label>Attivo</label>
					<div class="radio">
						<label>
							<input type="radio" name="attivo" value="1" <?=( $abbonamento->attivo ? 'checked' : '' )?> >Sì
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="attivo" value="0" <?=( !$abbonamento->attivo ? 'checked' : '' )?> >No
						</label>
					</div>															
				</div>
				
				
				<div class="row sgp-btn-row">
					<div class="text-center">
						<span><a href="javascript:void(0)" class="btn btn-sm btn-warning" role="button" title="" id="sgp-submit-form">Modifica</a></span>
						<a href="<?=base_url()?>listasoci/showSocio/<?=$abbonamento->id_socio?>" class="btn btn-sm btn-default" role="button">Annulla</a>
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
	var url_submit_form_function = '<?=base_url()?>abbonamentocontroller/editAbbonamento';
	var url_get_durata_abbonamento = '<?=base_url()?>listaabbonamenti/getInfoTipoAbbonamento';
	
	
	var id_palestra = '<?=$abbonamento->id_palestra?>';
	var id_socio = '<?=$abbonamento->id_socio?>';
	
	var durata = 0;
	
	var url_redirect = '<?=base_url()?>listasoci/showSocio/'+id_socio;
	
	//var ruolo = $('select[name="ruolo"]').val();
	//var id_palestra = $('select[name=id_palestra]').val();
	var numero_contatti = 0;
	//listenProv();

	loadAllListener();
	
	function listenerDate() {
		// DATETIME PICKER
		$('#data-inizio, #data-fine').datetimepicker({
			viewMode:'years',
			format:"DD/MM/YYYY"
		}).on('dp.change', function(e) { setDataFine(); });
		/*.change(function() {
			
		});*/
	}
	
	function listenerTipoAbbonamento() {
		$('select[name="id_tipo_abbonamento"]').change(function() {
			//var id_tipo_abbonamento = $('select[name="id_tipo_abbonamento"]').val();
			//alert(id_tipo_abbonamento);
			//console.log(id_tipo_abbonamento);
			getInfoTipoAbbonamento();
		});
	}
	
	function getInfoTipoAbbonamento() {
		var id_tipo_abbonamento = $('select[name="id_tipo_abbonamento"]').val();
		$.ajax({
			url: url_get_durata_abbonamento+'/'+id_tipo_abbonamento,
			dataType: "json",
			success: function(dati){
				//console.log(dati);
				durata = dati.durata;
				setDurata();
				$('input[name="valore_abbonamento"]').val(dati.costo_base);
				setDataFine();
			},
			error: function() { alert('Error');}
		});
	}
	
	function setDurata() {
		$('input[name="durata_abbonamento"]').val(durata);
	}
	
	function setDataFine() {
		var data_inizio = $('input[name="data_inizio"]').val();
		var data_inizio_array = data_inizio.split('/');
		var data_inizio = data_inizio_array[2]+'-'+data_inizio_array[1]+'-'+data_inizio_array[0];
		var data_unix = Date.parse(data_inizio)/1000;
		
		
		var durata_in_sec = durata*86400;
		
		var data_fine_unix = (data_unix+durata_in_sec)*1000;
		
		var data_tmp = new Date(data_fine_unix);
		
		var data_fine = data_tmp.getDate()+'/'+(data_tmp.getMonth()+1)+'/'+data_tmp.getFullYear();
		
		//console.log(data_fine);
		
		var new_data = data_fine;
		$('input[name="data_fine"]').val(new_data);
	}
	
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
		getInfoTipoAbbonamento();
		listenerDate();
		setDurata();
		listenerTipoAbbonamento();
		listenerSubmitForm();
	}
</script>