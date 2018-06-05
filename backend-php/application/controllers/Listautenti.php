<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class ListaUtenti extends CI_Controller {
 
	function __construct() {
		parent::__construct();
		
		/* MODEL SEMPRE NECESSARI */
		$this->load->model('user','',TRUE);
		
		/* MODEL NECESSARI ALL'HEADER_MODEL */
		$this->load->model('personale');
		$this->load->model('palestra');
		$this->load->model('HeaderModel', 'header_model');
		
		/* MODEL NECESSARI IN QUESTO CONTROLLER */
		$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('contatti');
		$this->load->model('RinnoviIscrizioni', 'rinnovi_iscrizioni');
		$this->load->model('socio');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'listautenti');
		define('ELEMENTI_PER_PAGINA', 15);
		
	}

	function index() {
		$this->p();
	}
	
	// FATTO
	function p($numero_pagina = 1, $filter = '') {
		
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
					
			$title = 'Lista utenti';
			$controller_redirect = CURRENT_PAGE;//'listautenti';
			
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			$id_palestra_session = $header['id_palestra'];
			
			
			
			if( $privilegi <= 1 && $id_palestra_session != '' ) {	
				
				$data_container = array();
				
				$data_container['debug'] = '';
				
				//$data_container['nome_palestra'] = $header['nome_palestra'];//$palestra->nome;
				
				
				$data_container['privilegi'] = $header['privilegi'];
				$data_container['id_utente'] = $header['id_utente'];
				
				
				
				
				//$numero_per_pagina = 15;
				
				$start = ELEMENTI_PER_PAGINA*($numero_pagina-1);
				
				$utenti = (object)[];
				$i = $numero_pagina;
				do {
					$utenti = array();
					$utenti = $this->personale->getAllUtentiPalestra($id_palestra_session, ELEMENTI_PER_PAGINA, $start);
					
					$numero_utenti = count($utenti);
					//$numero_utenti = count($this->personale->getAllUtentiPalestra());
					
					/*if( $privilegi == 0 ) {
						$utenti = $this->personale->getAllUtenti(ELEMENTI_PER_PAGINA, $start);
					} else {
						$utenti = $this->personale->getAllUtentiPalestra($id_palestra_session, ELEMENTI_PER_PAGINA, $start);
					}*/
					
					$data_container['sub_titolo_pagina'] = "Lista degli utenti del sistema";
					$data_container['bgcolor_counter'] = 'panel-primary';
					$data_container['testo_counter'] = "Numero utenti sistema";
					
					
					// il ciclo e questo servono a fare il refresh dopo l'eliminazione dell'ultimo elemento di una pagina o ha visualizzare correttamente dopo la digitazione manuale di una pagina che non esiste
					$i--;
					//$start = ELEMENTI_PER_PAGINA*($i-1);
				} while( count($utenti) < 1 && $i > 0 );
				
				if( $numero_pagina != ($i+1) ) {
					$numero_pagina = ($i+1);
				}
				
				//$numero_palestre = count($utenti);
				$data_container['numero_utenti'] = $numero_utenti;
				$data_container['filter'] = $filter;
								
				
				$numero_pagine = ceil($numero_utenti/ELEMENTI_PER_PAGINA);
				
				$data_container['numero_pagine'] = $numero_pagine;
				$data_container['numero_pagina'] = $numero_pagina;
				
				
				
			
				
				$elenco_utenti_completo = array();
				$i=0;
				if( count($utenti) > 0 ) {
					foreach($utenti as $utente) {

						$elenco_utenti_completo[$i] = (object)[];
						$elenco_utenti_completo[$i]->id = $utente->id;
						
						$elenco_utenti_completo[$i]->nome = $utente->nome;
						$elenco_utenti_completo[$i]->cognome = $utente->cognome;
						$elenco_utenti_completo[$i]->username = $utente->username;						
						$elenco_utenti_completo[$i]->ruolo = $utente->ruolo;
						$elenco_utenti_completo[$i]->ruolo_str = $this->personale->getRuoloString($utente->ruolo);
						$elenco_utenti_completo[$i]->nome_palestra = ( $utente->ruolo > 0 ? $this->palestra->getPalestra($utente->id_palestra)->nome : '' );
						$elenco_utenti_completo[$i]->email = $utente->email;
						
						
					
						$elenco_utenti_completo[$i]->delete_lock = $this->checkExistUtente($utente->id);

						$i++;

					}
				}
				$data_container['utenti'] = $elenco_utenti_completo;
				
				
				
				
				
				
				$this->load->view('header', $header);
				$this->load->view('lista_utenti', $data_container);
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

	// FATTO
	function getFormInsert() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$title = 'Inserimento nuovo Utente';
			$controller_redirect = 'listautenti';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			$id_palestra = $header['id_palestra'];
			
			if( $privilegi <= 1 ) {
			
				$metodo_insert = "";
				$dati = array();
				
				$option_ruoli = '';
				/*
				if( $privilegi == 0 ) {
					$option_ruoli .= '<option value="0" id="sgp-ruolo-su">Super-amministratore</option>\n';
				}*/
				$option_ruoli .= '<option value="1">Amministratore palestra</option>\n';
				$option_ruoli .= '<option value="2">Personale amministrativo</option>\n';
				$option_ruoli .= '<option value="3">Desk</option>\n';
				
				$dati['option_ruoli'] = $option_ruoli;
				
				/*
				$palestre = $this->palestra->getAllPalestre();
				$option_palestre = '';
				if( count($palestre) > 0 ) {
					
					foreach($palestre as $palestra) {
						$option_palestre .= ( $privilegi == 0 || $palestra->id == $header['id_palestra'] ? '<option value="'.$palestra->id.'">'.$palestra->nome.'</option>\n' : '' );
					}
				}
				$dati['option_palestre'] = $option_palestre;
				*/
				$dati['id_palestra'] = $id_palestra;
				
				$this->load->view('header', $header);
				$this->load->view('utenti/form_insert_utente', $dati);
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
		
	// FATTO
	function showUtente($id_utente) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$utente = $this->personale->getUtente($id_utente);
			
			
			$title = 'Visualizzazione dell\'utente "'.$utente->username.'"';
			$controller_redirect = 'listautenti';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			
			
			$privilegi = $header['privilegi'];
			
			
			
			if( $privilegi < 3 ) {
				
				if( $privilegi <= $utente->ruolo ) {
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
						} else {
							$coordinati = $this->personale->getAllCoordinatiByCoordinatore($id_utente);
							$utente->coordinati = $coordinati;
						}
					}
					
					$contatti = $this->recapiti_telefonici->getAllRecapitiUtente($id_utente);
					if( count($contatti) > 0 ) {
						foreach($contatti as $contatto) {
							$contatto->tipologia_str = $this->recapiti_telefonici->getTipologia($contatto->id_tipologia_numero)->etichetta;
						}
					}
					
					$utente->contatti = $contatti;
					
					$utente->delete_lock = $this->checkExistUtente($utente->id);
					
					$this->load->view('header', $header);
					$this->load->view('utenti/show_utente.php', $utente);
					$this->load->view('footer');
					
				} else {
					redirect('listautenti', 'refresh');
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
	
	// FATTO
	function AskConfirmDelete($id_utente) {
		if( $this->user->controlloAutenticazione() ) {
			
			//$metodo_eliminazione = "listapalestre/deletePalestra";
			$utente = $this->personale->getUtente($id_utente);
			if( $utente != null ) {
				//$url_eliminazione = $metodo_eliminazione.'/'.$palestra->id;

				$dati['username'] = $utente->username;
				$dati['ruolo_str'] = $this->personale->getRuoloString($utente->ruolo);
				$dati['id_utente'] = $utente->id;

				$this->load->view('utenti/confirm_delete_utente', $dati);

			} else {
				$msg = 'ERRORE! UTENTE NON TROVATO';
				$this->utility->errorDB($msg);
			}
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	// FATTO
	function getUpdatePasswordForm($id_utente, $error = '') {
		if( $this->user->controlloAutenticazione() ) {
			$dati['id_utente'] = $id_utente;

			$dati['error_msg'] = '';
			if( $error != '' ) {
				switch($error) {
					case -1:
						$dati['error_msg'] = 'La nuova password inserita non è stata ripetuta correttamente';
						break;
					case -2:
						$dati['error_msg'] = 'Errore nella modifica della password';
						break;
				}			
			}
			$this->load->view('utenti/form_edit_password_utente', $dati);
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	// FATTO
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

			$this->load->view('utenti/form_edit_dati_anagrafici_utente', $dati);
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	// FATTO
	function searchUtente() {
		if( $this->user->controlloAutenticazione() ) {
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			$id_palestra = $session_login['id_palestra'];
			//echo '<script>alert("'.$id_palestra.'");</script>';
			
			if( $privilegi <= 1 ) {
				$form_data = $this->input->post();

				$parole_cercate = $form_data['search_words'];

				$utenti = array();
				/*
				if( $privilegi == 1 ) {
					$utenti = $this->personale->searchUtente($parole_cercate, $id_palestra);
				} else {
					$utenti = $this->personale->searchUtente($parole_cercate);
				}
				*/
				$utenti = $this->personale->searchUtente($parole_cercate, $id_palestra);
				
				//$elenco_utenti_completo = array();
				//$i = 0;
				if( count($utenti) > 0 ) {
					foreach($utenti as $utente) {

						$utente->ruolo_str = $this->personale->getRuoloString($utente->ruolo);
						$utente->nome_palestra = ( $utente->ruolo > 0 ? $this->palestra->getPalestra($utente->id_palestra)->nome : '' );

					}
				}
				$dati = array();

				$dati['utenti'] = $utenti;

				$this->load->view('utenti/search_result_utenti', $dati);
			}
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	// FATTO
	function getCoordinatoriOptions($id_palestra) {
		
		$coordinatori_palestra = $this->personale->getAllCoordinatoriPalestra($id_palestra);
		$option_coordinatori_palestra = '';
		if( count($coordinatori_palestra) > 0 ) {
			foreach( $coordinatori_palestra as $coordinatore_palestra ) {
				$option_coordinatori_palestra .= '<option value="'.$coordinatore_palestra->id.'">'.$coordinatore_palestra->nome.' '.$coordinatore_palestra->cognome.'</option>\n';
			}
		}
		echo $option_coordinatori_palestra;
	}
	
	// FATTO
	function getContattoForm($id_palestra = '') {
		if( $this->user->controlloAutenticazione() ) {
			$tipologie_contatti = $this->recapiti_telefonici->getAllTipologiePalestra($id_palestra);
			$option_tipologie_contatto = "";
			if( count($tipologie_contatti) ) {
				foreach($tipologie_contatti as $tipologia_contatto) {
					$option_tipologie_contatto .= '<option value="'.$tipologia_contatto->id.'">'.$tipologia_contatto->etichetta.'</option>\n';
				}
			}
			$option_tipologie_contatto .= '<option value="" class="sgp-altra-tipologia-contatto">Altro</option>\n';

			$dati_recapito['option_tipologie_contatto'] = $option_tipologie_contatto;
			$this->load->view('utenti/sub_form_add_recapito', $dati_recapito);
		} else {
			$dati['redirect_page'] = 'listautenti/getFormInsert';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	// FATTO
	function getUpdateProfiloForm($id_utente, $error = '') {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$dati = array();
			$dati['error_msg'] = '';
			if( $error != '' ) {
				switch($error) {
					case -1:
						$dati['error_msg'] = 'Errore nella modifica dei dati profilo';
						break;
					case -2:
						$dati['error_msg'] = '';
						break;
					case -3:
						$dati['error_msg'] = '';
						break;
				}			
			}
			
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			
			
			if( $privilegi <= 1 ) {
				$utente = $this->personale->getUtente($id_utente);
				
				$ruolo = $utente->ruolo;
				$id_palestra = $utente->id_palestra;
				
				$option_ruoli = '';
				/*
				if( $privilegi == 0 ) {
					$option_ruoli .= '<option value="0" id="sgp-ruolo-su" '.( $ruolo == 0 ? 'selected' : '' ).'>Super-amministratore</option>\n';
				}*/
				$option_ruoli .= '<option value="1" '.( $ruolo == 1 ? 'selected' : '' ).'>Amministratore palestra</option>\n';
				$option_ruoli .= '<option value="2" '.( $ruolo == 2 ? 'selected' : '' ).'>Personale amministrativo</option>\n';
				$option_ruoli .= '<option value="3" '.( $ruolo == 3 ? 'selected' : '' ).'>Desk</option>\n';
				
				$dati['option_ruoli'] = $option_ruoli;
				
				
				$coordinatore = $utente->coordinatore;
				
				$recapiti_telefonici = $this->recapiti_telefonici->getAllRecapitiUtente($id_utente);
				
				//$dati['numero_recapiti_telefonici'] = count($recapiti_telefonici);
				
				$coordinati = $this->personale->getAllCoordinatiByCoordinatore($id_utente);
				
				$dati['numero_coordinati'] = count($coordinati);
				
				/*
				$option_palestre = '';
				$palestre = $this->palestra->getAllPalestre();

				if( count($palestre) > 0 ) {
					foreach($palestre as $palestra) {
						$option_palestre .= '<option value="'.$palestra->id.'" '.( $id_palestra == $palestra->id ? 'selected' : '' ).'>'.$palestra->nome.'</option>\n';
					}
				}
				$dati['option_palestre'] = $option_palestre;
				*/
								
				$option_coordinatori = '';
				$coordinatori_palestra = $this->personale->getAllCoordinatoriPalestra($id_palestra);
				$option_coordinatori = '';
				if( count($coordinatori_palestra) > 0 ) {
					foreach( $coordinatori_palestra as $coordinatore_palestra ) {
						if( $coordinatore_palestra->id != $utente->id ) {
							$option_coordinatori .= '<option value="'.$coordinatore_palestra->id.'" '.( $utente->id_coordinatore == $coordinatore_palestra->id ? 'selected' : '' ).'>'.$coordinatore_palestra->nome.' '.$coordinatore_palestra->cognome.'</option>\n';
						}
					}
				}
				$dati['option_coordinatori'] = $option_coordinatori;

				$dati['utente'] = $utente;

				$this->load->view('utenti/form_edit_dati_profilo', $dati);
				//}
			} else {
				redirect('home', 'refresh');
			}
			
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
		
		
		
	}
	
	// FATTO
	function editContattiUtente($id_utente) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$title = 'Recapiti Utente';
			$controller_redirect = 'listautenti';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			
			if( $privilegi <= 1 ) {
				
				$contatti = $this->recapiti_telefonici->getAllRecapitiUtente($id_utente);
				$utente = $this->personale->getUtente($id_utente);

				if( count($contatti) > 0 ) {

					foreach( $contatti as $contatto ) {

						$contatto->tipologia_str = $this->recapiti_telefonici->getTipologia($contatto->id_tipologia_numero)->etichetta;

					}

				}

				$dati['contatti'] = $contatti;
				$dati['username'] = $utente->username;
				$dati['id_utente'] = $utente->id;

				$this->load->view('header', $header);
				$this->load->view('utenti/show_recapiti_utente', $dati);
				$this->load->view('footer');
			}else {
				redirect(base_url().'home', 'refresh');
			}
		} else {
			//If no session, redirect to login page
			$url = base_url().'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	// FATTO
	function getFormEditRecapitoUtente($id_contatto) {
		if( $this->user->controlloAutenticazione() ) {
			$contatto = $this->recapiti_telefonici->getRecapitoPersonale($id_contatto);
			$utente = $this->personale->getUtente($contatto->id_utente);
			
			
			$id_palestra = $utente->id_palestra;
			
			$tipologie_contatti = $this->recapiti_telefonici->getAllTipologiePalestra($id_palestra);
			$option_tipologie_contatto = "";
			if( count($tipologie_contatti) ) {
				foreach($tipologie_contatti as $tipologia_contatto) {
					$option_tipologie_contatto .= '<option value="'.$tipologia_contatto->id.'" '.( $contatto->id_tipologia_numero == $tipologia_contatto->id ? 'selected' : '' ).'>'.$tipologia_contatto->etichetta.'</option>\n';
				}
			}
			$option_tipologie_contatto .= '<option value="" class="sgp-altra-tipologia-contatto">Altro</option>\n';
			
			$dati = array();
			$dati['contatto'] = $contatto;
			$dati['id_utente'] = $utente->id;
			$dati['id_palestra'] = $id_palestra;
			$dati['username'] = $utente->username;
			$dati['option_tipologie_contatto'] = $option_tipologie_contatto;
			$this->load->view('utenti/form_edit_recapito_utente', $dati);
			
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	// FATTO
	function AskConfirmDeleteRecapito($id_recapito) {
		if( $this->user->controlloAutenticazione() ) {
			
			$recapito_utente = $this->recapiti_telefonici->getRecapitoPersonale($id_recapito);
			$utente = $this->personale->getUtente($recapito_utente->id_utente);
			
			if( $recapito_utente != null ) {
				//$url_eliminazione = $metodo_eliminazione.'/'.$palestra->id;
				
				$dati['id'] = $recapito_utente->id;
				$dati['username'] = $utente->username;
				$dati['tipologia_str'] = $this->recapiti_telefonici->getTipologia($recapito_utente->id_tipologia_numero)->etichetta;
				$dati['numero'] = $recapito_utente->numero;

				$this->load->view('utenti/confirm_delete_recapito_utente', $dati);

			} else {
				$msg = 'ERRORE! RECAPITO NON TROVATO';
				$this->utility->errorDB($msg);
			}
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function getFormInsertRecapitoUtente($id_utente) {
		if( $this->user->controlloAutenticazione() ) {
			$utente = $this->personale->getUtente($id_utente);
			
			$id_palestra = $utente->id_palestra;

			$dati['id_utente'] = $id_utente;
			$dati['username'] = $utente->username;
			$dati['id_palestra'] = $id_palestra;
			
			$tipologie_contatti = $this->recapiti_telefonici->getAllTipologiePalestra($id_palestra);
			$option_tipologie_contatto = "";
			if( count($tipologie_contatti) ) {
				foreach($tipologie_contatti as $tipologia_contatto) {
					$option_tipologie_contatto .= '<option value="'.$tipologia_contatto->id.'">'.$tipologia_contatto->etichetta.'</option>\n';
				}
			}
			$option_tipologie_contatto .= '<option value="" class="sgp-altra-tipologia-contatto">Altro</option>\n';
			
			$dati['option_tipologie_contatto'] = $option_tipologie_contatto;
			
			$this->load->view('utenti/form_insert_recapito_utente', $dati);
			
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
		
	}
	
	// FATTO
	function addRowContatto() {
		$post_result = $this->input->post();
		if( $post_result['id_tipologia_numero'] != '' ) {
			$post_result['tipologia_numero'] = $this->recapiti_telefonici->getTipologia($post_result['id_tipologia_numero'])->etichetta;
		} else {
			$post_result['tipologia_numero'] = $post_result['new_tipologia_numero'];
		}
		
		$this->load->view('utenti/new_row_recapito_utente', $post_result);
	}
	
	function checkExistUtente($id_utente) {
		return ( 
			$this->contatti->checkExistConsulenteInColloqui($id_utente) || 
			$this->contatti->checkExistConsulenteInTelefonate($id_utente) || 
			$this->contatti->checkExistConsulenteInContatti($id_utente) || 
			$this->personale->checkExistConsulenteAsCoordinatore($id_utente) || 
			$this->rinnovi_iscrizioni->checkExistConsulenteInRinnoviIscrizioni($id_utente) || 
			$this->socio->checkExistConsulenteInSoci($id_utente) ? true : false
		);
	}
	
}
 
?>