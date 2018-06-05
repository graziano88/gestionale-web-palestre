<div id="page-wrapper">

	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Inserimento colloquio di verifica</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
			<form role="form" action="" method="post" id="sgp-insert-form">
				<input name="id_socio" value="<?=$id_socio?>" hidden="true">
				<?php
				if( $privilegi == 3 ) {
				?>
				<div class="form-group">
					<label>Consulente</label>
					<input name="id_consulente" value="<?=$desk_loggato->id?>" hidden="true">
					<?=$desk_loggato->cognome?> <?=$desk_loggato->nome?>
				</div>
				<?php
				} else {
				?>
				<div class="form-group">
					<label>Consulente</label>
					<select class="form-control" name="id_consulente">
						<?=$option_desk?>
					</select>
				</div>
				<?php
				}
				?>
				
				<div class="form-group">
					<label>Descrizione colloquio</label>
					<textarea class="form-control" rows="3" style="margin: 0px -2px 0px 0px; width: 100%; height: 100px;resize: none;" name="descrizione" ></textarea>
				</div>

				
				
				<div class="row sgp-btn-row">
					<div class="text-center">
						<span><a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="sgp-submit-form">Inserisci</a></span>
						<a href="<?=base_url()?>listasoci/showSocio/<?=$id_socio?>" class="btn btn-sm btn-default" role="button">Annulla</a>
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
	
	var id_palestra = '<?=$id_palestra?>';
	
	var url_insert_function = '<?=base_url()?>contatticontroller/creaColloquio';
	var url_redirect = '<?=base_url()?>listasoci/showSocio/<?=$id_socio?>';
	
	loadAllListener();
	
	function listenerSubmitForm() {
		// LISTENER SUBMIT FORM
		$('#sgp-submit-form').click(function() {
			var form = $('#sgp-insert-form');
			var dataForm = form.serializeArray();
			$.ajax({
				url: url_insert_function,
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
		listenerSubmitForm();
	}
</script>