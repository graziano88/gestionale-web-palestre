<div id="page-wrapper">

	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Modifica pagamento <small>Ricevuta n. <?=$pagamento->numero_ricevuta?></small>
				</h1>
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
				<input name="id" value="<?=$pagamento->id?>" hidden="true">
				<input name="id_socio" value="<?=$id_socio?>" hidden="true">
				<input name="id_abbonamento" value="<?=$pagamento->id_abbonamento?>" hidden="true">
				<input name="id_palestra" value="<?=$id_palestra?>" hidden="true">
				<input name="id_rata" value="<?=$id_rata?>" hidden="true">
				<?php
				if( $privilegi <= 2 ) {
				?>
				<div class="form-group">
					<label>Desk</label>
					<select class="form-control" name="id_desk">
						<?=$option_desk?>
					</select>
				</div>
				<?php
				} else {
				?>
				<input name="id_desk" value="<?=$pagamento->desk->id?>" hidden="true">
				<?php
				}
				?>
				<div class="form-group">
					<label>Numero ricevuta</label>
					<input class="form-control" name="numero_ricevuta" value="<?=$pagamento->numero_ricevuta?>" autofocus>
				</div>
				<div class="form-group">
					<label>Importo Pagato</label>
					<input class="form-control" name="importo_pagato" value="<?=$pagamento->importo_pagato?>">
				</div>
				
				
				<div class="row sgp-btn-row">
					<div class="text-center">
						<span><a href="javascript:void(0)" class="btn btn-sm btn-warning" role="button" title="" id="sgp-submit-form">Modifica</a></span>
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
	var url_submit_form_function = '<?=base_url()?>pagamentocontroller/editPagamento';
	var url_get_durata_abbonamento = '<?=base_url()?>listaabbonamenti/getInfoTipoAbbonamento';
	
	var max_paid = <?=$importo_pagato_default?>;
	var id_rata = '<?=$id_rata?>';
	
	var url_redirect = '<?=base_url()?>listarate/showRata/'+id_rata;
	
	var numero_contatti = 0;
	//listenProv();

	loadAllListener();
	/*
	function listenerDate() {
		// DATETIME PICKER
		$('#data-prossimo-pagamento').datetimepicker({
			viewMode:'years',
			format:"DD/MM/YYYY"
		});
	}
	*/
	function listenerMaxPaid() {
		$('input[name="importo_pagato"]').on('input', function() {
			checkMaxPaid();
		});
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
	
	function checkMaxPaid() {
		var valore_pagato = $('input[name="importo_pagato"]').val();
		if( valore_pagato > max_paid ) {
			$('#sgp-submit-form').parent().addClass('sgp-disabled-btn');
		} else {
			$('#sgp-submit-form').parent().removeClass('sgp-disabled-btn');
			if( valore_pagato == max_paid ) {
				$('#data-prossimo-pagamento').parent().hide();
			} else {
				$('#data-prossimo-pagamento').parent().show();
				var currentdate = new Date(); 
				var currentdate_str = ("0" + (currentdate.getDate()+1)).slice(-2) + "/" + ("0" + (currentdate.getMonth()+1)).slice(-2)  + "/" + currentdate.getFullYear();
				$('input[name="data_prossimo_pagamento"]').val(currentdate_str);
			}
		}
	}
	
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			$(location).attr('href', url_redirect);
		});
	}
	
	function loadAllListener() {
		//listenerDate();
		checkMaxPaid();
		listenerMaxPaid();
		listenerSubmitForm();
	}
</script>