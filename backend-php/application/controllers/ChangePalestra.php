<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class ChangePalestra extends CI_Controller {
 
	function __construct() {
		parent::__construct();
		$this->load->model('user','',TRUE);		
	}

	function setPalestra($pagina, $id_palestra = '') {
		
		if( $this->user->controlloAutenticazione() ) {
			/* AREA AUTENTICATA */
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			if( $privilegi == 0 ) {
				$session_login['id_palestra'] = $id_palestra;
				$this->session->set_userdata('logged_in', $session_login);
			} else {
				$id_palestra = $session_login['id_palestra'];
			}
			
			redirect($pagina, 'refresh');
			
		} else {
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}
	}

	function logout() {
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('home', 'refresh');
	}
 
}
 
?>