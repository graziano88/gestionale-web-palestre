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
    
	<style>
		.row-centered {
			text-align:center;
		}
		.col-centered {
			display:inline-block;
			float:none;
			/* inline-block space fix */
			margin-right:-4px;
		}
		
		.sgp-form-error {
			color: red;
			font-weight: bold;
		}
	</style>
</head>

<body>

    <div id="wrapper">

		<h1 class="text-center" style="margin-top: 150px;">Login</h1>
		<div class="row row-centered" style="margin-top: 25px;">
			<div class="col-lg-2 col-centered text-center">
				<div class="sgp-form-error"><?=( isset($error_msg) ? $error_msg : '' )?></div>
			<?php echo validation_errors(); ?>
			<?php echo form_open('verifyLogin'); ?>
				<form role="form">
					<?php if( isset($redirect_page) ) { ?>
					<input type="text" name="redirect_page" value="<?=$redirect_page?>" hidden="true"/>
					<?php } ?>
					<div class="form-group">
						<label for="username">Username:</label>
						<input class="form-control" type="text" id="username" name="username" autofocus/>
					</div>
					<div class="form-group">
						<label for="password">Password:</label>
						<input class="form-control" type="password" id="passowrd" name="password"/>
					</div>
					<input type="submit" value="Login"/>
				</form>
			</div>
		</div>
	</div>
</body>
</html>