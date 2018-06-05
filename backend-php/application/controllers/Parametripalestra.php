<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class ParametriPalestra extends CI_Controller {
 
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
		$this->load->model('abbonamenti');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'parametripalestra');
		
	}
	
	
	/* AREA PARAMETRI */
	function index() {
		
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$controller_redirect = 'parametripalestra';
			$title = 'Parametri Palestra';
			
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			$id_palestra = $header['id_palestra'];
			
			if( $privilegi <= 1 && $id_palestra != '' ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				$palestra = $this->palestra->getPalestra($id_palestra);
				
				/*
				$soglie_missed_desk = $this->palestra->getSogliaMissedDeskByPalestra($id_palestra);
				
				$parametri_palestra = $this->sistema->getParametriSistema();
				
				$data_container['soglie_missed_desk'] = $soglie_missed_desk;
				
				*/
				$parametri_palestra = $this->palestra->getParametriPalestraByPalestra($id_palestra);
				
				$tipologie_abbonamenti = $this->abbonamenti->getAllTipologieAbbonamentoPalestra($id_palestra);
				
				$tipologie_abbonamenti_sistema = array();
				$tipologie_abbonamenti_palestra = array();
				if( count($tipologie_abbonamenti) > 0 ) {
					foreach( $tipologie_abbonamenti as $tipologia_abbonamento ) {
						if( $tipologia_abbonamento->id_palestra == '' ) {
							$tipologie_abbonamenti_sistema[] = $tipologia_abbonamento;
						} else {
							$abbonamenti_per_tipologia = $this->abbonamenti->getAllAbbonamentiByIdTipoAbbonamento($tipologia_abbonamento->id);
							$tipologia_abbonamento->lock = ( count($abbonamenti_per_tipologia) > 0 ? true : false );
							$tipologie_abbonamenti_palestra[] = $tipologia_abbonamento;
						}
					}
				}
				
				$data_container['parametri_palestra'] = $parametri_palestra;
				$data_container['tipologie_abbonamenti'] = $tipologie_abbonamenti;
				$data_container['tipologie_abbonamenti_sistema'] = $tipologie_abbonamenti_sistema;
				$data_container['tipologie_abbonamenti_palestra'] = $tipologie_abbonamenti_palestra;
				$data_container['nome_palestra'] = $palestra->nome;
				
				$this->load->view('header', $header);
				$this->load->view('parametri_palestra/show_parametri_palestra', $data_container);
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
	
	/* PARAMETRI PALESTRA */
	function getFormEditSoglieMissedDesk($id_parametri_palestra) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				$parametri_palestra = $this->palestra->getParametriPalestra($id_parametri_palestra);
				
				$soglie_missed_desk = array();
				$soglie_missed_desk['id'] = $parametri_palestra->id;
				$soglie_missed_desk['primo_alert_missed'] = $parametri_palestra->primo_alert_missed;
				$soglie_missed_desk['secondo_alert_missed'] = $parametri_palestra->secondo_alert_missed;
				$soglie_missed_desk['scadenza_missed'] = $parametri_palestra->scadenza_missed;
				
				$dati['soglie_missed_desk'] = $soglie_missed_desk;

				$this->load->view('parametri_palestra/form_edit_soglie_missed_desk', $dati);
			} else {
				redirect('home', 'refresh');
			}	
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function editSoglieMissedDesk() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				$post_result = $this->input->post();
				
				$update_array = array();
				
				$update_array['primo_alert_missed'] = $post_result['primo_alert_missed'];
				$update_array['secondo_alert_missed'] = $post_result['secondo_alert_missed'];
				$update_array['scadenza_missed'] = $post_result['scadenza_missed'];
				
				if( $this->palestra->updateParametriPalestra($post_result['id'], $update_array) ) {
					$this->load->view('parametri_palestra/success_edit_soglie_missed_desk');
				} else {
					//ERRORE EDIT SOGLIA
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
	
	function getFormEditSogliaAlertAbbonamento($id_parametri_palestra) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				$parametri_palestra = $this->palestra->getParametriPalestra($id_parametri_palestra);
				
				$soglia_abbonamento = array();
				$soglia_abbonamento['id'] = $parametri_palestra->id;
				$soglia_abbonamento['alert_scadenza_abbonamento'] = $parametri_palestra->alert_scadenza_abbonamento;
				$soglia_abbonamento['alert_scadenza_freepass'] = $parametri_palestra->alert_scadenza_freepass;
				
				$dati['soglia_abbonamento'] = $soglia_abbonamento;

				$this->load->view('parametri_palestra/form_edit_soglia_alert_abbonamento', $dati);
			} else {
				redirect('home', 'refresh');
			}	
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function editSogliaAlertAbbonamento() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				$post_result = $this->input->post();
				
				$update_array = array();
				
				$update_array['alert_scadenza_abbonamento'] = $post_result['alert_scadenza_abbonamento'];
				$update_array['alert_scadenza_freepass'] = $post_result['alert_scadenza_freepass'];
				
				if( $this->palestra->updateParametriPalestra($post_result['id'], $update_array) ) {
					$this->load->view('parametri_palestra/success_edit_soglia_alert_abbonamento');
				} else {
					//ERRORE EDIT SOGLIA
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
	
	function getFormEditSogliaNuoviSoci($id_parametri_palestra) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				$parametri_palestra = $this->palestra->getParametriPalestra($id_parametri_palestra);
				
				$soglia_nuovi_soci = array();
				$soglia_nuovi_soci['id'] = $parametri_palestra->id;
				$soglia_nuovi_soci['soglia_nuovi_soci'] = $parametri_palestra->soglia_nuovi_soci;
				
				$dati['soglia_nuovi_soci'] = $soglia_nuovi_soci;

				$this->load->view('parametri_palestra/form_edit_soglia_nuovi_soci', $dati);
			} else {
				redirect('home', 'refresh');
			}	
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function editSogliaNuoviSoci() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				$post_result = $this->input->post();
				
				$update_array = array();
				
				$update_array['soglia_nuovi_soci'] = $post_result['soglia_nuovi_soci'];
				
				if( $this->palestra->updateParametriPalestra($post_result['id'], $update_array) ) {
					$this->load->view('parametri_palestra/success_edit_soglia_nuovi_soci');
				} else {
					//ERRORE EDIT SOGLIA
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
	
	function getFormEditTipologiaAbbonamento($id_tipologia_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				$tipologia_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($id_tipologia_abbonamento);
				
				
				
				$dati['tipologia_abbonamento'] = $tipologia_abbonamento;

				$this->load->view('parametri_palestra/form_edit_tipologia_abbonamento', $dati);
			} else {
				redirect('home', 'refresh');
			}	
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function editTipologiaAbbonamento() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				$post_result = $this->input->post();
				
				$update_array = array();
				
				$update_array['tipo'] = $post_result['tipo'];
				$update_array['durata'] = $post_result['durata'];
				
				if( isset( $post_result['giorni_gratuiti_socio'] ) ) {
					$update_array['giorni_gratuiti_socio'] = $post_result['giorni_gratuiti_socio'];
				} else {
					$update_array['costo_base'] = $post_result['costo_base'];					
				}
				/*
				$update_array['freepass'] = $post_result['freepass'];
				$update_array['giorni_gratuiti_socio'] = ( $post_result['freepass'] == 1 ? $post_result['giorni_gratuiti_socio'] : 0 );
				*/
				
				if( $this->abbonamenti->updateTipologiaAbbonamento($post_result['id'], $update_array) ) {
					$this->load->view('parametri_palestra/success_edit_tipologia_abbonamento');
				} else {
					//ERRORE EDIT SOGLIA
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
	
	function getFormInsertTipologiaAbbonamento() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			$id_palestra = $session_login['id_palestra'];
			
			if( $privilegi <= 1 && $id_palestra != '' ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				
				
				$dati['id_palestra'] = $id_palestra;

				$this->load->view('parametri_palestra/form_insert_tipologia_abbonamento', $dati);
			} else {
				redirect('home', 'refresh');
			}	
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function insertTipologiaAbbonamento() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				$post_result = $this->input->post();
				
				$insert_array = array();
				
				
				$insert_array['id'] = $this->utility->generateId('tipologie_abbonamento');
				$insert_array['id_palestra'] = $post_result['id_palestra'];
				$insert_array['tipo'] = ucwords($post_result['tipo']);
				$insert_array['durata'] = $post_result['durata'];
				$insert_array['costo_base'] = $post_result['costo_base'];
				$insert_array['freepass'] = $post_result['freepass'];
				$insert_array['giorni_gratuiti_socio'] = ( $post_result['freepass'] == 1 ? $post_result['giorni_gratuiti_socio'] : 0 );
				
				if( $this->abbonamenti->insertTipologiaAbbonamento($insert_array) ) {
					$this->load->view('parametri_palestra/success_insert_tipologia_abbonamento');
				} else {
					//ERRORE EDIT SOGLIA
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
	
	function askConfirmDeleteTipologiaAbbonamento($id_tipologia_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				$tipologia_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($id_tipologia_abbonamento);
				
				$dati['nome_tipologia_abbonamento'] = $tipologia_abbonamento->tipo;
				$dati['id_tipologia_abbonamento'] = $id_tipologia_abbonamento;

				$this->load->view('parametri_palestra/confirm_delete_tipologia_abbonamento', $dati);
			} else {
				redirect('home', 'refresh');
			}	
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function deleteTipologiaAbbonamento($id_tipologia_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				
				$abbonamenti_per_tipologia = $this->abbonamenti->getAllAbbonamentiByIdTipoAbbonamento($id_tipologia_abbonamento);
				if( count($abbonamenti_per_tipologia) <= 0 ) {
					if( $this->abbonamenti->deleteTipologiaAbbonamento($id_tipologia_abbonamento) ) {
						$this->load->view('parametri_palestra/success_delete_tipologia_abbonamento');
					} else {
						//ERRORE DELETE TIPOLOGIA ABBONAMENTO
					}
				} else {
					$this->utility->errorMsg("OPERAZIONE NEGATA", "Non è possibile procedere a questa eliminazione");
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
	
	
	
	
	
	
	
	/* PARAMETRI SISTEMA */
	function getFormEditParametriSistema($id_parametri_sistema) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 0 ) {	
				// accesso consetito a SUPERadmin e adminPalestra
				$parametri_sistema = $this->sistema->getParametriSistema();

				$dati['parametri_sistema'] = $parametri_sistema;

				$this->load->view('gestione_variabili/form_edit_parametri_sistema', $dati);
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
					$this->load->view('gestione_variabili/success_edit_parametri_sistema');
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