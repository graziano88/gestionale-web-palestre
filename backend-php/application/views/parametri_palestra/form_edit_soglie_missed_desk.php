<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Modifica soglie missed desk</h4>
		</div>
		<div class="modal-body">



			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-8">
							<form role="form" action="" method="post" id="sgp-edit-form">
								<input name="id" value="<?=$soglie_missed_desk['id']?>" hidden="true">
								<div class="form-group">
									<label>I Alert dopo:</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="primo_alert_missed" value="<?=$soglie_missed_desk['primo_alert_missed']?>" autofocus>
										<span class="input-group-addon">
											<span class="">Giorni</span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>II Alert dopo:</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="secondo_alert_missed" value="<?=$soglie_missed_desk['secondo_alert_missed']?>">
										<span class="input-group-addon">
											<span class="">Giorni</span>
										</span>
									</div>
								</div>
								<div class="sgp-error-msg" id="sgp-scadenza-errata" style="display: none">
									La scadenza deve essere uguale o maggiore all'ultimo alert (II alert dopo: <span></span> giorni)
								</div>
								<div class="form-group">
									<label>Scadenza dopo:</label>
									<div class='input-group'>
										<input class="form-control" type="number" name="scadenza_missed" value="<?=$soglie_missed_desk['scadenza_missed']?>">
										<span class="input-group-addon">
											<span class="">Giorni</span>
										</span>
									</div>
								</div>
								<script>
									$('input[name=primo_alert_missed]').keyup(function(){
										console.log($(this).val());
										checkPrimoAlert();
										checkScadenza()
									}).change(function(){
										checkPrimoAlert();
										checkScadenza()
									});
									
									$('input[name=secondo_alert_missed]').keyup(function(){
										checkSecondoAlert();
										checkScadenza()
									}).change(function(){
										checkSecondoAlert();
										checkScadenza()
									});
									
									
									$('input[name=scadenza_missed]').keyup(function(){
										//check che sia maggiore di 	primo_alert_missed+secondo_alert_missed	
										checkScadenza();
									});
									$('input[name=scadenza_missed]').change(function(){
										//check che sia maggiore di 	primo_alert_missed+secondo_alert_missed	
										checkScadenza();
									});
									
									function checkPrimoAlert() {
										var valore = parseInt($('input[name=primo_alert_missed]').val())*2;
										$('input[name=secondo_alert_missed]').val(valore);
										
										var scadenza = valore+1;
										$('input[name=scadenza_missed]').val(scadenza);
									}
									
									function checkSecondoAlert() {
										var secondo_alert = parseInt($('input[name=secondo_alert_missed]').val());
										var primo_alert = parseInt($('input[name=primo_alert_missed]').val());
										var scadenza = secondo_alert+1;
										$('input[name=scadenza_missed]').val(scadenza);	
									}
									
									function checkScadenza() {
										//var primo_alert = parseInt($('input[name=primo_alert_missed]').val());
										var secondo_alert = parseInt($('input[name=secondo_alert_missed]').val());
										
										var totale = secondo_alert;
										var scadenza = parseInt($('input[name=scadenza_missed]').val());
										
										if( scadenza < totale ) {
											//errore
											$('#sgp-submit-form').addClass('disabled');
											$('#sgp-scadenza-errata').show().children('span').html(totale);
										} else {
											$('#sgp-submit-form').removeClass('disabled');
											$('#sgp-scadenza-errata').hide()
										}
									}
									
								</script>

								<div class="row sgp-btn-row">
									<div class="text-center">
										<span><a href="javascript:void(0)" class="btn btn-sm btn-warning" role="button" title="" id="sgp-submit-form">Modifica</a></span>
										<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
									</div>
								</div>
							</form>
						</div>
						<div class="col-lg-2"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	
<script>
	var url_submit_form_function = '<?=base_url()?>parametripalestra/editSoglieMissedDesk';
	
	$('#sgp-submit-form').click(function() {
		var form = $('#sgp-edit-form');
		var dataForm = form.serializeArray();
		$.ajax({
			url: url_submit_form_function,
			data: dataForm,
			type: 'POST',
			dataType: "html",
			success: function(data){
				var testo_modal = data;
				$('#modal').html(testo_modal);
				/*
				$('#modal').on('hidden.bs.modal', function () {
					window.location.replace(page_redirect);
				});
				*/
				reloadPagina();
			}
		});
	});
		
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			location.reload();
		});
	}
</script>