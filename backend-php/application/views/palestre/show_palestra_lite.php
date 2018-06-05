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
		<!--
		<div class="row sgp-btn-row">
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listapalestre/getFormEdit/<?=$id;?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title=""><i class="fa fa-pencil"></i> Modifica Informazioni</a>
			</div>
		</div>
		 -->
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
			</div>
		</div>
			
		<div class="row sgp-btn-row"><!-- ACTION BAR -->
			<div class="col-lg-7 text-center">
				<a href="<?=base_url()?>listapalestre/getFormEditLite/<?=$id;?>" class="btn btn-sm btn-warning sgp-edit-btn" role="button" title=""><i class="fa fa-pencil"></i> Modifica Informazioni</a>
			</div>
		</div>
		
		<!-- MODAL -->
		<div id="modal" class="modal fade" role="dialog"></div>
	</div>
</div>
	
<script>
	
	var url_redirect = '<?=base_url()?>listapalestre';
	
	function reloadPagina() {
		$('#modal').on('hidden.bs.modal', function () {
			//location.reload();
			$(location).attr('href', url_redirect);
		});
	}
</script>
		