<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Conferma Eliminazione </h4>
		</div>
		<div class="modal-body text-center">
			<p>Vuoi davvero eliminare la tipologia abbonamento <strong><?=$nome_tipologia_abbonamento?></strong> dalla palestra?</p>
			<p><a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="conferma-delete">Sì</a> <a href="#" class="btn btn-danger" role="button" title="" data-dismiss="modal">No</a></p>
		</div>
	</div>
</div>
<script>
	var url_delete = '<?=base_url()?>parametripalestra/deleteTipologiaAbbonamento';
	var id = '<?=$id_tipologia_abbonamento?>';
	
	$('#conferma-delete').click(function(){
		$.ajax({
			type: "POST",
			url: url_delete+'/'+id, 
			dataType: "html",
			success:function(data){
				testo_modal = data;
				$('#modal').html(testo_modal);

				$('#modal').on('hidden.bs.modal', function () {
					//location.reload();
					var page_redirect = '<?=base_url()?>parametripalestra';
					window.location.replace(page_redirect);
				});
			}

		});
	});
</script>