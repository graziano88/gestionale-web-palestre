<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class ParametriSistema extends CI_Controller {
 
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
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'parametrisistema');
		
	}
	
	
	/* AREA PARAMETRI */
	function index() {
		
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$controller_redirect = 'parametrisistema';
			$title = 'Impostazioni Sistema';
			
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			$id_palestra = $header['id_palestra'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				
				$parametri_sistema = $this->sistema->getParametriSistema();
				
				
				$data_container['parametri_sistema'] = $parametri_sistema;
				
				$this->load->view('header', $header);
				$this->load->view('parametri_sistema/show_parametri_sistema', $data_container);
				$this->load->view('footer');
				
			} else {
				redirect('home', 'refresh');
			}
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}	
	
	
	/* PARAMETRI SISTEMA */
	function getFormEditParametriSistema() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 0 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				$parametri_sistema = $this->sistema->getParametriSistema();

				$dati['parametri_sistema'] = $parametri_sistema;

				$this->load->view('parametri_sistema/form_edit_parametri_sistema', $dati);
			} else {
				redirect('home', 'refresh');
			}	
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function editParametriSistema() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 0 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				$post_result = $this->input->post();
				
				$update_array = array();
				
				$update_array['alert_scadenza_palestre'] = $post_result['alert_scadenza_palestre'];
				
				if( $this->sistema->updateParametriSistema($post_result['id'], $update_array) ) {
					$this->load->view('parametri_sistema/success_edit_parametri_sistema');
				} else {
					//ERRORE EDIT PARAMETRI SISTEMA
				}
				
			} else {
				redirect('home', 'refresh');
			}	
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
}
 
?>