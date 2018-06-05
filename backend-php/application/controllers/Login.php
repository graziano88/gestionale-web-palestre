<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		/* MODEL SEMPRE NECESSARI */
		$this->load->model('user', '', TRUE);
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		// NESSUNA
		
	}

	public function index() {
		
		
		if( $this->user->controlloAutenticazione() ) {
			redirect('home', 'refresh');
		} else {
			$data['title'] = 'Login Page';
			$this->load->helper(array('form'));
			//$this->load->view('header', $data);
			$this->load->view('login_view',$data);
			//$this->load->view('footer');
		}
	}
	
	public function login($page) {
		if( $this->user->controlloAutenticazione() ) {
			redirect('home', 'refresh');
		} else {
			$data['title'] = 'Login Page';
			$data['redirect_page'] = $page;
			$this->load->helper(array('form'));
			//$this->load->view('header', $data);
			$this->load->view('login_view',$data);
			//$this->load->view('footer');
		}
	}
	
	public function logout() {
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('home', 'refresh');
	}

}

?>