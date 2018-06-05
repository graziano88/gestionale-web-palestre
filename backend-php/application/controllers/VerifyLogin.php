<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class VerifyLogin extends CI_Controller {
 
	function __construct() {
		parent::__construct();
		$this->load->model('user','',TRUE);
		$this->load->helper('cookie');
	}
 
	function index() {
		//This method will have the credentials validation
		$this->load->library('form_validation');

		//$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		//$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
		
		if( !$this->check_database() ) {//if($this->form_validation->run() == FALSE) {
			//Field validation failed.  User redirected to login page
			
			$data['error_msg'] = 'Credenziali errate';
			$data['title'] = 'Login Page';
			$this->load->view('login_view', $data);
			
		} else {
			//Go to private area
			$redirect_page = $this->input->post('redirect_page');	
			if( $redirect_page != "" ) {
				redirect($redirect_page, 'refresh');
			} else {
				redirect('home', 'refresh');
			}
		}

	}
 
	function check_database() {//function check_database($password) {
		//Field validation succeeded.  Validate against database
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		//query the database
		$result = $this->user->controlloCredenziali($username, $password);

		if($result) {
			
			$dati_sessione = $this->user->generateCredentials($username, md5($password), time());
			$dati_sessione['id_utente'] = $result[0]->id;
			$dati_sessione['ruolo'] = $result[0]->ruolo;
			$dati_sessione['id_palestra'] = $result[0]->id_palestra;
			$dati_sessione['username'] = $username;
			
			
			$this->session->set_userdata('logged_in', $dati_sessione);
			
			
			// GESTIONE COOKIE (LA CREAZIONE DEL COOKIE E' DISABILITATA IN ATTESA DI ESSERE IMPLEMENTATA)
			$cookie_value = $dati_sessione['user_password_time'].';'.$dati_sessione['time'].';'.$dati_sessione['id_utente'].';'.$dati_sessione['ruolo'].';'.$dati_sessione['id_palestra'].';'.$dati_sessione['username'];
						
			$cookie = array(
				'name'   => 'logged_in',
				'value'  => $cookie_value,
				'expire' => time()+86500
				);
			//set_cookie($cookie);
			
			/*	OTTENERE LE INFORMAZIONI DA COOKIE (DA IMPLEMENTARE IN UNA FUNZIONE A PARTE)
			$cookie_info = get_cookie('logged_in');
			
			$cookie_value_array = explode(';', $cookie_info);
			
			$logged_in_array = array();
			$logged_in_array['user_password_time'] = $cookie_value_array[0];
			$logged_in_array['time'] = $cookie_value_array[1];
			$logged_in_array['id_utente'] = $cookie_value_array[2];
			$logged_in_array['ruolo'] = $cookie_value_array[3];
			$logged_in_array['id_palestra'] = $cookie_value_array[4];
			$logged_in_array['username'] = $cookie_value_array[5];
			*/
			
			return TRUE;
		}
		else {
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
		}
	}
}
?>