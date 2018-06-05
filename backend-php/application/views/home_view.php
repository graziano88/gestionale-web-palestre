<div id="page-wrapper">

	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<?=($nome_palestra != '' ? $nome_palestra : 'Tutte le palestre'); ?> <small>Statistics Overview</small>
				</h1>
				<ol class="breadcrumb">
					<li class="active">
						<i class="fa fa-dashboard"></i> Dashboard
					</li>
				</ol>
			</div>
		</div>
		<!-- /.row -->
		<?php if( $privilegi == 0 && $id_palestra == "" ) { ?>
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-trophy fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$numero_palestre; ?></div>
								<div>Numero palestre</div>
							</div>
						</div>
					</div>
					<a href="listapalestre">
						<div class="panel-footer">
							<span class="pull-left">Vedi dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<?php if( $numero_palestre_in_scadenza > 0 ) { ?>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-red">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-trophy fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$numero_palestre_in_scadenza?></div>
								<div>Palestre in Scadenza</div>
							</div>
						</div>
					</div>
					<a href="listapalestre/p/1/expiring">
						<div class="panel-footer">
							<span class="pull-left">Vedi dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<?php } ?>
			<?php if( $numero_palestre_scadute > 0 ) { ?>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-red">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-trophy fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$numero_palestre_scadute?></div>
								<div>Palestre non attive</div>
							</div>
						</div>
					</div>
					<a href="listapalestre/p/1/expired">
						<div class="panel-footer">
							<span class="pull-left">Vedi dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
		
		<div class="row">
		
			<?php
			if( $id_palestra != "" ) {
				if( $privilegi == 3 ) {
					if( $numero_free_pass_in_scadenza > 0 ) {
					
			?>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-red">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-warning fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$numero_free_pass_in_scadenza; ?></div>
								<div>I tuoi Free-pass in scadenza</div>
							</div>
						</div>
					</div>
					<a href="<?=base_url()?>listarinnoviiscrizioni/p/1/fpwe">
						<div class="panel-footer">
							<span class="pull-left">Vedi Dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<?php
					}
					if( $numero_utenti_missed > 0 ) {
			?>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-yellow">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-warning fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$numero_utenti_missed; ?></div>
								<div>I tuoi utenti missed</div>
							</div>
						</div>
					</div>
					<a href="<?=base_url()?>listarinnoviiscrizioni/p/1/missed">
						<div class="panel-footer">
							<span class="pull-left">Vedi Dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<?php
					}
					if( $number_rinnovi_iscrizioni_I_alert_missed > 0 ) {
			?>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-red">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-warning fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$number_rinnovi_iscrizioni_I_alert_missed; ?></div>
								<div>Missed I Alert</div>
							</div>
						</div>
					</div>
					<a href="<?=base_url()?>listarinnoviiscrizioni/p/1/mia">
						<div class="panel-footer">
							<span class="pull-left">Vedi Dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<?php
					}
					if( $number_rinnovi_iscrizioni_II_alert_missed > 0 ) {
			?>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-red">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-warning fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$number_rinnovi_iscrizioni_II_alert_missed; ?></div>
								<div>Missed II Alert</div>
							</div>
						</div>
					</div>
					<a href="<?=base_url()?>listarinnoviiscrizioni/p/1/miia">
						<div class="panel-footer">
							<span class="pull-left">Vedi Dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<?php
					}
				} else {
					if( $numero_free_pass_in_scadenza > 0 ) {
			?>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-red">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-warning fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$numero_free_pass_in_scadenza; ?></div>
								<div>Free-pass in scadenza</div>
							</div>
						</div>
					</div>
					<a href="<?=base_url()?>listarinnoviiscrizioni/p/1/fpwe">
						<div class="panel-footer">
							<span class="pull-left">Vedi Dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<?php
					}
					if( $numero_utenti_missed > 0 ) {
			?>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-red">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-warning fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$numero_utenti_missed; ?></div>
								<div>Utenti missed</div>
							</div>
						</div>
					</div>
					<a href="<?=base_url()?>listarinnoviiscrizioni/p/1/missed">
						<div class="panel-footer">
							<span class="pull-left">Vedi Dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<?php
					}
				}
			}
			?>
		</div>
		<?php if( $privilegi != 2 ) { ?>
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-users fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$numero_iscritti; ?></div>
								<div><?=($id_palestra==""?'Iscritti nelle palestre':'Iscritti in palestra'); ?></div>
							</div>
						</div>
					</div>
					<?php if( $id_palestra != "" ) { ?>
					<a href="<?=base_url()?>listasoci">
						<div class="panel-footer">
							<span class="pull-left">Vedi dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
					<?php } ?>
				</div>
			</div>
				<?php if( $id_palestra != "" ) { ?>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-green">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-users fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$numero_nuovi_iscritti; ?></div>
								<div>Nuovi iscritti <?=($id_palestra==""?'nelle palestre':''); ?></div>
							</div>
						</div>
					</div>
					<a href="<?=base_url()?>listasoci/p/1/new">
						<div class="panel-footer">
							<span class="pull-left">Vedi dettagli</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
				<?php } ?>
			<?php } ?>
			
			
		</div>
		<!-- /.row -->

	</div>
	<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->