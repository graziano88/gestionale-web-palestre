<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class Home extends CI_Controller {
 
	function __construct() {
		parent::__construct();
		
		/* MODEL SEMPRE NECESSARI */
		$this->load->model('user','',TRUE);
		
		/* MODEL NECESSARI ALL'HEADER_MODEL */
		$this->load->model('personale');
		$this->load->model('palestra');
		$this->load->model('HeaderModel', 'header_model');
		$this->load->model('sistema');
		
		/* MODEL NECESSARI IN QUESTO CONTROLLER */
		$this->load->model('socio');
		$this->load->model('abbonamenti');
		$this->load->model('RinnoviIscrizioni', 'rinnovi_iscrizioni');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		// NESSUNA
		
	}

	function index() {
		
		if( $this->user->controlloAutenticazione() ) {
			/* AREA AUTENTICATA */
			
			$title = 'Home';
			$controller_redirect = 'home';
			
			$header = $this->header_model->getHeader($title, $controller_redirect);
		
			
			$data_container = array();
			$privilegi = $header['privilegi'];
			$id_utente = $header['id_utente'];
			
			$id_palestra = $header['id_palestra']; // vuoto nel caso di SU
			
			$data_container['nome_palestra'] = $header['nome_palestra'];
			
			$data_container['privilegi'] = $privilegi;
			$data_container['id_palestra'] = $id_palestra;
			
			
			$header = array_merge($header, $data_container);
			
			$numero_palestre = 0;
			if( $privilegi == 0 ) {
				$header['elenco_palestre'] = $this->palestra->getAllPalestre();
				$numero_palestre = count($header['elenco_palestre']);
			}
			$data_container['numero_palestre'] = $numero_palestre;
			
			
			if( $privilegi == 0 || $id_palestra == "" ) {
				
				$parametri_sistema = $this->sistema->getParametriSistema();
				
				$palestre_in_scadenza = $this->palestra->getAllPalestreInScadenza($parametri_sistema->alert_scadenza_palestre);
				$palestre_scadude = $this->palestra->getAllPalestreScadute();
				
				if( $id_palestra == '' ) {
					$soci_iscritti = $this->socio->getAllSoci();
					$nuovi_soci = NULL;
					$number_rinnovi_iscrizioni_missed = NULL;
					$number_rinnovi_iscrizioni_free_pass_scadenza = NULL;
				} else {
					$parametri_palestra = $this->palestra->getParametriPalestraByPalestra($id_palestra);
					
					$soci_iscritti = $this->socio->getAllSociPalestra($id_palestra);
					$nuovi_soci = $this->socio->getAllNewSoci($parametri_palestra->soglia_nuovi_soci);
					$number_rinnovi_iscrizioni_free_pass_scadenza = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniFreePassWillExpired($id_palestra, $parametri_palestra->alert_scadenza_freepass);
					$number_rinnovi_iscrizioni_missed = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniMissed($id_palestra);
				
				}
				$abbonamenti_in_scadenza = NULL;
				
			} else {
				$parametri_palestra = $this->palestra->getParametriPalestraByPalestra($id_palestra);
				
				$soci_iscritti = $this->socio->getAllSociPalestra($id_palestra);
				$nuovi_soci = $this->socio->getAllNewSociPalestra($id_palestra, $parametri_palestra->soglia_nuovi_soci);
				$palestre_in_scadenza = NULL;
				$palestre_scadude = NULL;
				$abbonamenti_in_scadenza = $this->abbonamenti->getAllAbbonamentiInScadenzaPalestra($id_palestra, $parametri_palestra->alert_scadenza_abbonamento);
				
				
				$number_rinnovi_iscrizioni_free_pass_scadenza = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniFreePassWillExpired($id_palestra, $parametri_palestra->alert_scadenza_freepass, $id_utente);
				
				/*
				$soglie$soglie = $this->palestra->getSogliaMissedDeskByPalestra($id_palestra);
				$scadenza_missed_desk = $soglie->scadenza;
				*/
				
				$number_rinnovi_iscrizioni_missed = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniMissedDesk($id_palestra, $id_utente, $parametri_palestra->scadenza_missed);
				
				$limite_scadenza_alert_giorni = $parametri_palestra->secondo_alert_missed;

				$number_rinnovi_iscrizioni_I_alert_missed = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniMissedAlertDesk($id_palestra, $id_utente, $parametri_palestra->primo_alert_missed, $limite_scadenza_alert_giorni);
				$data_container['number_rinnovi_iscrizioni_I_alert_missed'] = $number_rinnovi_iscrizioni_I_alert_missed;
				
				$limite_scadenza_alert_giorni = $parametri_palestra->scadenza_missed;
				
				$number_rinnovi_iscrizioni_II_alert_missed = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniMissedAlertDesk($id_palestra, $id_utente, $parametri_palestra->secondo_alert_missed, $limite_scadenza_alert_giorni);
				
				$data_container['number_rinnovi_iscrizioni_II_alert_missed'] = $number_rinnovi_iscrizioni_II_alert_missed;
				echo '<script>console.log("'.$data_container['number_rinnovi_iscrizioni_II_alert_missed'].'")</script>';
			}
			$data_container['numero_iscritti'] = count($soci_iscritti);
			$data_container['numero_nuovi_iscritti'] = count($nuovi_soci);
			$data_container['numero_abbonamenti_in_scadenza'] = count($abbonamenti_in_scadenza);
			$data_container['numero_free_pass_in_scadenza'] = $number_rinnovi_iscrizioni_free_pass_scadenza;
			$data_container['numero_utenti_missed'] = $number_rinnovi_iscrizioni_missed;
			$data_container['numero_palestre_in_scadenza'] = count($palestre_in_scadenza);
			$data_container['numero_palestre_scadute'] = count($palestre_scadude);
			
			$this->load->view('header', $header);
			$this->load->view('home_view', $data_container);
			$this->load->view('footer');
		} else {
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}
	}
 
}
 
?>