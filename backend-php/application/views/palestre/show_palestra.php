<div id="page-wrapper">

	<div class="container-fluid">

		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<?php if( $immagine_logo != null && $immagine_logo != '' ) { ?>
			<img src="<?=base_url()?>loghi_uploads/<?=$immagine_logo?>" alt="Logo <?=$nome?>" style="max-height: 100px;">
			<?php } ?> <?=$nome?>
				</h1>
			</div>
		</div>
		<div class="row sgp-btn-row"><!-- ACTION BAR -->
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listapalestre" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a> <a href="<?=base_url()?>listapalestre/getFormEdit/<?=$id;?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title=""><i class="fa fa-pencil"></i> Modifica</a> <button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$id;?>"><i class="fa fa-trash"></i> Elimina</button>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-7">
				<div class="row">
					<div class="col-lg-3"><strong>Ragione sociale:</strong> </div>
					<div class="col-lg-9"><?=$ragione_sociale?></div>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Partita IVA:</strong> </div>
					<div class="col-lg-9"><?=$partita_iva?></div>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Attiva dal:</strong> </div>
					<div class="col-lg-9"><?=$attiva_dal?></div>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Attiva al:</strong> </div>
					<div class="col-lg-9"><?=$attiva_al?></div>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Indirizzo:</strong> </div>
					<div class="col-lg-9"><?=$indirizzo?>, <?=$cap?>, <?=$citta?> (<?=$provincia?>)</div>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Sito web:</strong> </div>
					<?php if( $sito_web != null && $sito_web != '') { ?>
					<div class="col-lg-9"><a href="<?=prep_url($sito_web)?>" target="_blank"><?=$sito_web?></a></div>
					<?php } else { ?>
					<div class="col-lg-9">Nessuno</div>
					<?php } ?>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Email:</strong> </div>
					<?php if( $email != null && $email != '') { ?>
					<div class="col-lg-9"><a href="mailto:<?=$email;?>"><?=$email;?></a></div>
					<?php } else { ?>
					<div class="col-lg-9">Nessuna</div>
					<?php } ?>
				</div>

				<?php 
				if( count($contatti) > 0 ) {
				?>
				<div class="row">
					<div class="col-lg-3"><strong>Recapiti telefonici:</strong> </div>
					<div class="col-lg-9">
				<?php
					foreach($contatti as $contatto) {
				?>


						<div>
							<div><?=$contatto['numero']?> (<?=$contatto['tipologia']?>)</div>
						</div>

				<?php		
					}
				?>
					</div>
				</div>
				<?php
				}
				?>

				<div class="row">
					<div class="col-lg-3"><strong>Attività palestra:</strong> </div>
					<div class="col-lg-9"><?=$tipo_attivita;?></div>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Ubicazione:</strong> </div>
					<div class="col-lg-9"><?=$ubicazione;?></div>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Superficie Totale:</strong> </div>
					<div class="col-lg-9"><?=$mq;?> mq</div>
				</div>

				<?php
					if( ($mq_sala_attrezzi+$mq_sala_corsi+$mq_cadio_fitness+$mq_spinning+$mq_rowing+$mq_arti_marziali+$mq_piscina+$mq_thermarium) > 0 ) {
				?>
				<div class="row">
					<div class="col-lg-12"><strong>Superfici:</strong> </div>
				</div>
				<div class="row">
					<div class="col-lg-5 sgp-sub-group-form" style="margin-left: 15px !important; margin-bottom: 5px;">


				<?php
						if( $mq_sala_attrezzi > 0 ) {
				?>
						<div class="row">
							<div class="col-lg-4"><strong>Sala attrezzi: </strong> </div>
							<div class="col-lg-6 text-right"><?=$mq_sala_attrezzi;?> mq</div>
						</div>
				<?php 
						}
				?>

				<?php
						if( $mq_sala_corsi > 0 ) {
				?>
						<div class="row">
							<div class="col-lg-4"><strong>Sala corsi: </strong> </div>
							<div class="col-lg-6 text-right"><?=$mq_sala_corsi;?> mq</div>
						</div>
				<?php 
						}
				?>

				<?php
						if( $mq_cadio_fitness > 0 ) {
				?>
						<div class="row">
							<div class="col-lg-4"><strong>Cardio fitness: </strong> </div>
							<div class="col-lg-6 text-right"><?=$mq_cadio_fitness;?> mq</div>
						</div>
				<?php 
						}
				?>

				<?php
						if( $mq_spinning > 0 ) {
				?>
						<div class="row">
							<div class="col-lg-4"><strong>Spinning: </strong> </div>
							<div class="col-lg-6 text-right"><?=$mq_spinning;?> mq</div>
						</div>
				<?php 
						}
				?>

				<?php
						if( $mq_rowing > 0 ) {
				?>
						<div class="row">
							<div class="col-lg-4"><strong>Rowing: </strong> </div>
							<div class="col-lg-6 text-right"><?=$mq_rowing;?> mq</div>
						</div>
				<?php 
						}
				?>

				<?php
						if( $mq_arti_marziali > 0 ) {
				?>
						<div class="row">
							<div class="col-lg-4"><strong>Arti marziali: </strong> </div>
							<div class="col-lg-6 text-right"><?=$mq_arti_marziali;?> mq</div>
						</div>
				<?php 
						}
				?>

				<?php
						if( $mq_piscina > 0 ) {
				?>
						<div class="row">
							<div class="col-lg-4"><strong>Piscina: </strong> </div>
							<div class="col-lg-6 text-right"><?=$mq_piscina;?> mq</div>
						</div>
				<?php 
						}
				?>

				<?php
						if( $mq_thermarium > 0 ) {
				?>
						<div class="row">
							<div class="col-lg-4"><strong>Thermarium: </strong> </div>
							<div class="col-lg-6 text-right"><?=$mq_thermarium;?> mq</div>
						</div>
				<?php 
						}
				?>


					</div>
				</div>
				<?php
					}
				?>
				<?php 
				if( count($persone_riferimento) > 0 ) {
				?>
				<div class="row">
					<div class="col-lg-12"><strong>Persone di riferimento:</strong> </div>
				</div>
				<div class="row">
					<div class="col-lg-12 table-responsive">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>Nome</th>
									<th>Cognome</th>
									<th>Ruolo</th>
									<th>Telefono</th>
									<th>Cellulare</th>
									<th>E-mail</th>
								</tr>
							</thead>
							<tbody>
				<?php
					foreach($persone_riferimento as $persona_riferimento) {
				?>
								<tr>
									<td class="col-lg-2"><?=$persona_riferimento->nome?></td>
									<td class="col-lg-2"><?=$persona_riferimento->cognome?></td>
									<td class="col-lg-2"><?=$persona_riferimento->ruolo?></td>
				<?php
						if( $persona_riferimento->telefono != '' ) {
				?>
									<td class="col-lg-2"><?=$persona_riferimento->telefono?></td>
				<?php
						} else {
				?>
									<td class="col-lg-2">n.d.</td>
				<?php
						}
				?>
				<?php
						if( $persona_riferimento->cellulare != '' ) {
				?>
									<td class="col-lg-2"><?=$persona_riferimento->cellulare?></td>
				<?php
						} else {
				?>
									<td class="col-lg-2">n.d.</td>

				<?php
						}
				?>
									<td class="col-lg-2"><?=$persona_riferimento->email?></td>
								</tr>	
				<?php		
					}
				?>
							</tbody>
						</table>
					</div>		
				</div>
				<?php
				}
				?>

				<div class="row">
				<?php
					if( $parcheggi == 0 ) {
				?>
					<div class="col-lg-3"><strong>Parcheggi:</strong> </div>
					<div class="col-lg-9">Nessuno</div>
				<?php
					} else {
				?>
						<div class="col-lg-3"><strong>Parcheggi (quantità):</strong> </div>
						<div class="col-lg-9">
				<?php
						$class_rating = 'sgp-rating-green';
						if( $rating_struttura == 1 ) $class_rating = 'sgp-rating-red';
						if( $rating_struttura == 3 ) $class_rating = 'sgp-rating-middle';
						for($i=0;$i<$rating_struttura; $i++) {
				?>
							<span class="badge <?=$class_rating?>"> </span>
				<?php
						}
						for($i=0; $i<(5-$rating_struttura); $i++) {
				?>
							<span class="badge sgp-rating-disabled"> </span>
				<?php
						}			
				?>
						</div>
				<?php
					}
				?>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Bar interno:</strong> </div>
					<div class="col-lg-9"><?=$servizio_bar;?></div>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Shop:</strong> </div>
					<div class="col-lg-9"><?=$shop;?></div>
				</div>
				<div class="row">
					<div class="col-lg-3"><strong>Distributori automatici:</strong> </div>
					<div class="col-lg-9"><?=$servizio_distributori;?></div>
				</div>

				<div class="row">
					<div class="col-lg-12"><strong>Rating palestra:</strong> </div>
				</div>
				<div class="row">
					<div class="col-lg-5 sgp-sub-group-form" style="margin-left: 15px !important; margin-bottom: 5px;">
						<div class="row">
							<div class="col-lg-4"><strong>Struttura: </strong> </div>
							<div class="col-lg-6 text-right">
							<?php 
							if( $rating_struttura != null && $rating_struttura != '' ) {
								$class_rating = 'sgp-rating-green';
								if( $rating_struttura == 1 ) $class_rating = 'sgp-rating-red';
								if( $rating_struttura == 3 ) $class_rating = 'sgp-rating-middle';
								for($i=0;$i<$rating_struttura; $i++) {
							?>
								<span class="badge <?=$class_rating?>"> </span>
							<?php
								}
								for($i=0; $i<(5-$rating_struttura); $i++) {
							?>
								<span class="badge sgp-rating-disabled"> </span>
							<?php
								}
							} else {
								echo "n.d.";
							}
							?>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4"><strong>Attrezzature: </strong> </div>
							<div class="col-lg-6 text-right">
							<?php 
							if( $rating_attrezzature != null && $rating_attrezzature != '' ) {
								$class_rating = 'sgp-rating-green';
								if( $rating_attrezzature == 1 ) $class_rating = 'sgp-rating-red';
								if( $rating_attrezzature == 3 ) $class_rating = 'sgp-rating-middle';
								for($i=0;$i<$rating_attrezzature; $i++) {
							?>
								<span class="badge <?=$class_rating?>" style="background: green;"> </span>
							<?php
								}
								for($i=0; $i<(5-$rating_attrezzature); $i++) {
							?>
								<span class="badge sgp-rating-disabled"> </span>
							<?php
								}
							} else {
								echo "n.d.";
							}
							?>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4"><strong>Spogliatoi: </strong> </div>
							<div class="col-lg-6 text-right">
							<?php 
							if( $rating_spogliatoi != null && $rating_spogliatoi != '' ) {
								$class_rating = 'sgp-rating-green';
								if( $rating_spogliatoi == 1 ) $class_rating = 'sgp-rating-red';
								if( $rating_spogliatoi == 3 ) $class_rating = 'sgp-rating-middle';
								for($i=0;$i<$rating_spogliatoi; $i++) {
							?>
								<span class="badge <?=$class_rating?>" style="background: green;"> </span>
							<?php
								}
								for($i=0; $i<(5-$rating_spogliatoi); $i++) {
							?>
								<span class="badge sgp-rating-disabled"> </span>
							<?php
								}
							} else {
								echo "n.d.";
							}
							?>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4"><strong>Pulizia: </strong> </div>
							<div class="col-lg-6 text-right">
							<?php 
							if( $rating_pulizia != null && $rating_pulizia != '' ) {
								$class_rating = 'sgp-rating-green';
								if( $rating_pulizia == 1 ) $class_rating = 'sgp-rating-red';
								if( $rating_pulizia == 3 ) $class_rating = 'sgp-rating-middle';
								for($i=0;$i<$rating_pulizia; $i++) {
							?>
								<span class="badge <?=$class_rating?>" style="background: green;"> </span>
							<?php
								}
								for($i=0; $i<(5-$rating_pulizia); $i++) {
							?>
								<span class="badge sgp-rating-disabled"> </span>
							<?php
								}
							} else {
								echo "n.d.";
							}
							?>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4"><strong>Personale: </strong> </div>
							<div class="col-lg-6 text-right">
							<?php 
							if( $rating_personale != null && $rating_personale != '' ) {
								$class_rating = 'sgp-rating-green';
								if( $rating_personale == 1 ) $class_rating = 'sgp-rating-red';
								if( $rating_personale == 3 ) $class_rating = 'sgp-rating-middle';
								for($i=0;$i<$rating_personale; $i++) {
							?>
								<span class="badge <?=$class_rating?>" style="background: green;"> </span>
							<?php
								}
								for($i=0; $i<(5-$rating_personale); $i++) {
							?>
								<span class="badge sgp-rating-disabled"> </span>
							<?php
								}
							} else {
								echo "n.d.";
							}
							?>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-3"><strong>Considerazioni Generali:</strong> </div>
					<div class="col-lg-9"><?=$considerazioni_generali;?></div>
				</div>
				<?php if( $altro != '' ) { ?>
				<div class="row">
					<div class="col-lg-3"><strong>Altro:</strong> </div>
					<div class="col-lg-9"><?=$altro;?></div>
				</div>
				<?php } ?>
			</div>
		</div>
			
		<div class="row sgp-btn-row"><!-- ACTION BAR -->
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listapalestre" class="btn btn-sm btn-info sgp-back-btn" role="button" title=""><i class="fa fa-arrow-left"></i> Indietro</a> <a href="<?=base_url()?>listapalestre/getFormEdit/<?=$id;?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title=""><i class="fa fa-pencil"></i> Modifica</a> <button type="button" class="btn btn-danger btn-sm sgp-delete" data-toggle="modal" data-target="#modal" id="delete-<?=$id;?>"><i class="fa fa-trash"></i> Elimina</button>
			</div>
		</div>
		
		<!-- MODAL -->
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
</div>
	
<script>
	var url_confirm_delete = '<?=base_url()?>listapalestre/confirmDelete';
	var url_delete = '<?=base_url()?>palestracontroller/deletePalestra';
	var url_redirect = '<?=base_url()?>listapalestre';
	
	$(document).ready(function(){
		listenerModal();
	});
		
	function listenerModal() {
		$('button[data-toggle="modal"]').click(function() {

			var id_button = $(this).attr("id");
			var classi = $(this).attr("class");
			//alert(classi);
			if (classi.indexOf("sgp-delete") >= 0) {
				/* DELETE */

				var id = id_button.replace('delete-','');
				//alert(id_user);

				// il testo_modal sarà restituito dalla chiamata ajax
				var testo_modal = '';
				$.ajax({
					type: "POST",
					url: url_confirm_delete+'/'+id, 
					dataType: "html",
					success:function(data){
						testo_modal = data;
						if( testo_modal == -1 ) {
							location.reload();
						}
						$('#modal').html(testo_modal);


						$('#conferma-delete-palestra').click(function(){
							$.ajax({
								type: "POST",
								url: url_delete+'/'+id, 
								dataType: "html",
								success:function(data){
									testo_modal = data;
									$('#modal').html(testo_modal);

									reloadPagina();
								}

							});
						});
					}

				});	

			}
			
		});
	}
	
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			//location.reload();
			$(location).attr('href', url_redirect);
		});
	}
</script>
		