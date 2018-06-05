<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->

    <!-- Custom CSS -->
    <link href="<?php echo base_url('public/bootstrap/css/sb-admin.css') ?>" rel="stylesheet">
    <!--<link href="css/sb-admin.css" rel="stylesheet">-->

    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url('public/bootstrap/css/plugins/morris.css') ?>" rel="stylesheet">
    <!--<link href="css/plugins/morris.css" rel="stylesheet">-->

    <!-- Custom Fonts -->
    <link href="<?php echo base_url('public/bootstrap/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css">
    <!--<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">-->
    
    <!-- datetimepicker CSS -->
    <link href="<?php echo base_url('public/bootstrap/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet" type="text/css">
    <!--<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">-->
    
    <!-- jQuery -->
    <script src="<?php echo base_url('public/bootstrap/js/jquery.js') ?>"></script>
    <!--<script src="js/jquery.js"></script>-->
    
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url('public/bootstrap/js/bootstrap.min.js') ?>"></script>
    <!--<script src="js/bootstrap.min.js"></script>-->
    
    <!-- Bootstrap moment -->
    <script src="<?php echo base_url('public/bootstrap/js/moment.js') ?>"></script>
    <!--<script src="js/moment.js"></script>-->
    
    <!-- Bootstrap datetimepicker -->
    <script src="<?php echo base_url('public/bootstrap/js/bootstrap-datetimepicker.min.js') ?>"></script>
    <!--<script src="js/bootstrap-datetimepicker.min.js"></script>-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <button type="button" class="navbar-toggle" id="sgp-menu-button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <script>
					$(document).ready(function() {
						$('#sgp-menu-button').click(function() {
							
							if( $('#sgp-sidebar').is(':visible') ) {
								$('#sgp-sidebar').hide();
								$('#wrapper').addClass('wrapper-without-sidebar');
							} else {
								$('#sgp-sidebar').show();
								$('#wrapper').removeClass('wrapper-without-sidebar');
							}
						});
					});
				</script>
                <a class="navbar-brand" href="<?=base_url()?>home">Gestione <?=($nome_palestra != '' ? $nome_palestra : 'Palestre'); ?></a>
            </div>
            <!-- Top Menu Items -->
			<ul class="nav navbar-right top-nav">
				<?php if( $privilegi == 0 ) { ?>
               <!-- CAMPO PALESTRE SOLO PER SUPER AMMINISTRATORI -->
               <li class="dropdown">
				   <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-trophy"></i> <span class="sgp-hide-label">Palestre</span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                    	<li>
                            <a href="<?=base_url()?>ChangePalestra/setPalestra/<?=$controller_redirect;?>"><i class="fa fa-fw fa-trophy"></i> Tutte</a>
                        </li>
                        
						<?php
							if( isset($elenco_palestre) ) {
								if( count($elenco_palestre) > 0 ) {
						?>
						<li class="divider"></li>
                        <li>
                        <?php
									foreach($elenco_palestre as $palestra) {
						?>
                        <li>
                            <a href="<?=base_url()?>ChangePalestra/setPalestra/<?=$controller_redirect;?>/<?=$palestra->id?>"><i class="fa fa-fw fa-trophy"></i> <?=$palestra->nome; ?></a>
                        </li>
						<?php
									}
								}
							}
						?>
                        <!--
                        
                            <a href="#"><i class="fa fa-fw fa-plus"></i> Add Palestra</a>
                        </li>
                        -->
                    </ul>
                </li>
                <?php } ?>
                <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="sgp-hide-label"><?=$nome_cognome;?></span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?=base_url()?>profiloutente"><i class="fa fa-fw fa-user"></i> Profilo</a>
                        </li>
						<?php if( $privilegi == 0 ) { ?>
						<li>
							<a href="<?=base_url()?>parametrisistema"><i class="fa fa-fw fa-gear"></i> Impostazioni</a>
						</li>
						<?php } ?>
                        <li class="divider"></li>
                        <li>
                            <a href="<?=base_url();?>login/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav" id="sgp-sidebar">
                    <li><!-- <li class="active"> -->
                        <a href="<?=base_url()?>home"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <?php if( $privilegi == 0 && $nome_palestra == '' ) { ?>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#palestre"><i class="fa fa-fw fa-trophy"></i> Palestre<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="palestre" class="collapse">
                            <li>
                                <a href="<?=base_url();?>listapalestre">Lista palestre</a>
                            </li>
                            <li>
                                <a href="<?=base_url();?>listapalestre/p/1/expiring">Palestre in scadenza</a>
                            </li>
                            <li>
                                <a href="<?=base_url();?>listapalestre/p/1/expired">Palestre scadute</a>
                            </li>
                        </ul>
                    </li>
                    <?php } else if( $privilegi == 0 && $nome_palestra != '' ) {  ?>
                    <li>
                        <a href="<?=base_url()?>ChangePalestra/setPalestra/<?=$controller_redirect;?>"><i class="fa fa-fw fa-trophy"></i> Tutte le Palestra</a>
                    </li>
                    <?php } ?>
                    <?php if( $nome_palestra != '' ) { ?>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#palestra"><i class="fa fa-fw fa-trophy"></i> Palestra<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="palestra" class="collapse">
                            <?php if( $privilegi <= 1 ) { ?>
                            <li>
                                <a href="<?=base_url();?>listautenti">Personale Palestre</a>
                            </li>
                            <li>
                                <a href="<?=base_url();?>parametripalestra">Parametri Palestra</a>
                            </li>
                            <?php } ?>
                            
                            <?php if( $privilegi <= 1 ) { ?>
                            <li>
                                <a href="<?=base_url();?>listapalestre/showInfoPalestra/<?=$id_palestra?>">Info Palestra</a>
                            </li>
                            <?php } ?>
                            <li>
                                <a href="<?=base_url();?>listapalestre/showContattiPalestra/<?=$id_palestra?>">Contatti palestra</a>
                            </li>
                        </ul>
                    </li>
					<?php if( $privilegi != 2 ) { ?>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#iscritti"><i class="fa fa-fw fa-users"></i> Iscritti<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="iscritti" class="collapse">
                            <li>
                                <a href="<?=base_url()?>listasoci">Lista iscritti</a>
                            </li>
                            <li>
                                <a href="<?=base_url()?>listasoci/p/1/new">Nuovi iscritti</a>
                            </li>
							
                            <?php if( $privilegi == 0 || $privilegi == 3 ) { ?>
                            <li>
                                <a href="<?=base_url()?>listarinnoviiscrizioni">Rinnovi/iscrizioni</a>
                            </li>
                            <li>
                                <a href="<?=base_url()?>listarinnoviiscrizioni/getFormInsert">Inserisci rinnovo/iscrizione</a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php } ?>
                    
                     <?php if( $privilegi == 0 ) { ?>
					<li>
						<a href="<?=base_url()?>parametrisistema"><i class="fa fa-fw fa-gear"></i> Impostazioni</a>
					</li>
					<?php } ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>