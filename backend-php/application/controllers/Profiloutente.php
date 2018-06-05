<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class ProfiloUtente extends CI_Controller {
 
	function __construct() {
		parent::__construct();
		
		/* MODEL SEMPRE NECESSARI */
		$this->load->model('user','',TRUE);
		
		/* MODEL NECESSARI ALL'HEADER_MODEL */
		$this->load->model('palestra');
		$this->load->model('personale');
		$this->load->model('HeaderModel', 'header_model');
		
		/* MODEL NECESSARI IN QUESTO CONTROLLER */
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'profiloutente');
		
	}

	function index() {
		
		if( $this->user->controlloAutenticazione() ) {
		
			$title = "Profilo Utente";
			$controller_redirect = CURRENT_PAGE;
			
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$id_utente = $header['id_utente'];
			
			$utente = $this->personale->getUtente($id_utente);
			
			$utente->data_nascita = date('d/m/Y', $utente->data_nascita);
			
			$utente->sesso_str = ( $utente->sesso == 0 ? 'Maschio' : 'Femmina' );
			
			$utente->ruolo_str = $this->personale->getRuoloString($utente->ruolo);
			
			if( $utente->ruolo != 0 ) {
				// get nome palestra
				$palestra = $this->palestra->getPalestra($utente->id_palestra);
				$utente->nome_palestra = $palestra->nome;
				
				// gestione coordinatore
				$utente->coordinatore_str = ( $utente->coordinatore == 0 ? 'No' : 'Sì' );
				if( $utente->coordinatore == 0 ) {
					$coordinatore = $this->personale->getUtente($utente->id_coordinatore);
					$utente->nome_coordinatore = $coordinatore->nome;
					$utente->cognome_coordinatore = $coordinatore->cognome;
				}
			}
			
			//var_dump($utente);
			
			$this->load->view('header', $header);
			$this->load->view('profilo_utente/profilo_utente.php', $utente);
			$this->load->view('footer');
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function getUpdatePasswordForm($id_utente, $error = '') {
		if( $this->user->controlloAutenticazione() ) {
			$dati['id_utente'] = $id_utente;

			$dati['error_msg'] = '';
			if( $error != '' ) {
				switch($error) {
					case -1:
						$dati['error_msg'] = 'La vecchia password inserita è errata';
						break;
					case -2:
						$dati['error_msg'] = 'La nuova password inserita non è stata ripetuta correttamente';
						break;
					case -3:
						$dati['error_msg'] = 'Errore nella modifica della password';
						break;
				}			
			}
			$this->load->view('profilo_utente/form_edit_password_utente', $dati);
		} else {
			$dati['redirect_page'] = 'profiloUtente';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function updatePassword() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();

			$dati_utente = array();

			$id_utente = $post_result['id_utente'];
			$old_password = $post_result['old_pass'];
			$new_password = $post_result['new_pass'];
			$new_password_2 = $post_result['new_pass_2'];

			$utente = $this->personale->getUtente($id_utente);

			if( $utente->password == md5($old_password) ) {
				if( $new_password == $new_password_2 ) {
					$dati_utente['password'] = md5($new_password);
					if( $this->personale->updateUtente($id_utente, $dati_utente) ) {
						$dati['username'] = $utente->username;
						$this->load->view('profilo_utente/success_edit_password_utente', $dati);
					} else {
						$this->getUpdatePasswordForm($id_utente, -3); // errore nell'update
					}
				} else {
					$this->getUpdatePasswordForm($id_utente, -2); // la nuova password non è stata ripetuta correttamente
				}
			} else {
				$this->getUpdatePasswordForm($id_utente, -1); // vecchia password errata
			}
		} else {
			$dati['redirect_page'] = 'profiloUtente';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function getUpdateDatiAnagraficiForm($id_utente, $error = '') {
		if( $this->user->controlloAutenticazione() ) {
			$dati['error_msg'] = '';
			if( $error != '' ) {
				switch($error) {
					case -1:
						$dati['error_msg'] = 'Errore nella modifica dei dati anagrafici';
						break;
					case -2:
						$dati['error_msg'] = '';
						break;
					case -3:
						$dati['error_msg'] = '';
						break;
				}			
			}

			$utente = $this->personale->getUtente($id_utente);

			$utente->password = ''; //reset password for security (not update in db)

			$utente->data_nascita = date('d/m/Y', $utente->data_nascita);

			$utente = (array)$utente;

			$dati = array_merge($dati, $utente);

			//var_dump($dati);

			$this->load->view('profilo_utente/form_edit_dati_anagrafici_utente', $dati);
		} else {
			$dati['redirect_page'] = 'profiloUtente';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function updateDatiAnagrafici() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();

			$id_utente = $post_result['id_utente'];

			$nuovi_dati_utente = array();
			unset($post_result['id_utente']);
			$nuovi_dati_utente = $post_result;

			$nuovi_dati_utente['data_nascita'] = str_replace('/', '-', $nuovi_dati_utente['data_nascita']);
			$nuovi_dati_utente['data_nascita'] = strtotime($nuovi_dati_utente['data_nascita']);

			if( $this->personale->updateUtente($id_utente, $nuovi_dati_utente) ) {
				$utente = $this->personale->getUtente($id_utente);
				$dati['username'] = $utente->username;
				$this->load->view('profilo_utente/success_edit_dati_anagrafici_utente', $dati);
			} else {
				$this->getUpdateDatiAnagraficiForm($id_utente, -1); // errore nell'update
			}
		} else {
			$dati['redirect_page'] = 'profiloUtente';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
}