<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Conferma Eliminazione </h4>
		</div>
		<div class="modal-body text-center">
			<p>Vuoi davvero eliminare il pagamento <strong>Ricevuta n. <?=$numero_ricevuta?></strong> del socio <strong><?=$nome_socio?> <?=$cognome_socio?></strong> dal sistema?</p>
			<p><a href="javascript:void(0)" class="btn btn-sm btn-success" role="button" title="" id="conferma-delete">Sì</a> <a href="#" class="btn btn-danger" role="button" title="" data-dismiss="modal">No</a></p>
		</div>
												<!--
		<div class="modal-footer text-center">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
		-->
	</div>
</div>
<script>
	var url_delete = '<?=base_url()?>pagamentocontroller/deletePagamento';
	var id = '<?=$id_pagamento?>';
	var id_rata = '<?=$id_rata?>';
	var page_redirect = '<?=base_url()?>listarate/showRata/'+id_rata;
	
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
					window.location.replace(page_redirect);
				});
			}

		});
	});
</script>