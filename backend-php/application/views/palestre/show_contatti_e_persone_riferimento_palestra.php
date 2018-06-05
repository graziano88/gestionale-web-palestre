<div id="page-wrapper">

	<div class="container-fluid">

		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Contatti e persone di riferimento palestra <small><?=$nome?></small>
				</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
				

				<?php 
				if( count($contatti) > 0 ) {
				?>
				<div class="row">
					<div class="col-lg-9"><strong>Recapiti telefonici palestra:</strong> </div>
				</div>
				<div class="row">
					<div class="col-lg-9 table-responsive">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>Tipo</th>
									<th>Numero</th>
								</tr>
							</thead>
							<tbody>
				<?php
					foreach($contatti as $contatto) {
				?>
								<tr>
									<td class="col-lg-6"><?=$contatto->tipologia?></td>
									<td class="col-lg-6"><?=$contatto->numero?></td>
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
				
				<?php 
				if( count($persone_riferimento) > 0 ) {
				?>
				<div class="row">
					<div class="col-lg-9"><strong>Persone di riferimento:</strong> </div>
				</div>
				<div class="row">
					<div class="col-lg-9 table-responsive">
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
				
			</div>
		</div>
			
		
	</div>
</div>
	
<script>
	
</script>
		