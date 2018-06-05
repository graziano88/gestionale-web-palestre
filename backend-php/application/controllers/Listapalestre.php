<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class ListaPalestre extends CI_Controller {
 
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
		$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'listapalestre');
		define('ELEMENTI_PER_PAGINA', 15);
		
	}

	function index() {
		$this->p();
	}
	
	function p($numero_pagina = 1, $filter = '') {
		
		if( $this->user->controlloAutenticazione() ) {
			/* AREA AUTENTICATA */
			
			switch( $filter ) {
				case 'all':
					$title = 'Lista palestre';
					break;
				case 'expiring':
					$title = 'Lista palestre in scadenza';
					break;
				case 'expired':
					$title = 'Lista palestre scadute';
					break;
				default:
					$title = 'Lista palestre';
			}
			
			$controller_redirect = 'listapalestre';
			
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			
			if( $privilegi == 0 ) {	
				
				$data_container = array();
				
				$data_container['debug'] = '';
				
				$data_container['nome_palestra'] = $header['nome_palestra'];//$palestra->nome;
				
				
				$data_container['privilegi'] = $header['privilegi'];
				$data_container['id_palestra'] = $header['id_palestra'];
				
				
				
				
				//$numero_per_pagina = 15;
				
				$start = ELEMENTI_PER_PAGINA*($numero_pagina-1);
				
				$palestre = null;
				$i = $numero_pagina;
				do {
					switch( $filter ) {
						case 'all':
							$numero_palestre = count($this->palestra->getAllPalestre());
							$palestre = $this->palestra->getAllPalestre(ELEMENTI_PER_PAGINA, $start);
							$data_container['sub_titolo_pagina'] = "Lista delle palestre del sistema";
							$data_container['bgcolor_counter_palestre'] = 'panel-primary';
							$data_container['testo_counter_palestre'] = "Numero palestre";
							break;
						case 'expiring':
							$parametri_palestra = $this->sistema->getParametriSistema();
							$numero_palestre = count($this->palestra->getAllPalestreInScadenza($parametri_palestra->alert_scadenza_palestre));
							$palestre = $this->palestra->getAllPalestreInScadenza($parametri_palestra->alert_scadenza_palestre, ELEMENTI_PER_PAGINA, $start);
							$data_container['sub_titolo_pagina'] = "Lista delle palestre in scadenza";
							$data_container['bgcolor_counter_palestre'] = 'panel-yellow';
							$data_container['testo_counter_palestre'] = "Numero palestre in scadenza";
							break;
						case 'expired':
							$numero_palestre = count($this->palestra->getAllPalestreScadute());
							$palestre = $this->palestra->getAllPalestreScadute(ELEMENTI_PER_PAGINA, $start);
							$data_container['sub_titolo_pagina'] = "Lista delle palestre scadute";
							$data_container['bgcolor_counter_palestre'] = 'panel-red';
							$data_container['testo_counter_palestre'] = "Numero palestre scadute";
							break;
						default:
							$numero_palestre = count($this->palestra->getAllPalestre());
							$palestre = $this->palestra->getAllPalestre(ELEMENTI_PER_PAGINA, $start);
							$data_container['sub_titolo_pagina'] = "Lista delle palestre del sistema";
							$data_container['bgcolor_counter_palestre'] = 'panel-primary';
							$data_container['testo_counter_palestre'] = "Numero palestre";
							break;
					}
					// il ciclo e questo servono a fare il refresh dopo l'eliminazione dell'ultimo elemento di una pagina o ha visualizzare correttamente dopo la digitazione manuale di una pagina che non esiste
					$i--;
					$start = ELEMENTI_PER_PAGINA*($i-1);
				} while( count($palestre) < 1 && $i > 0 );
				
				if( $numero_pagina != ($i+1) ) {
					$numero_pagina = ($i+1);
				}
				
				//$numero_palestre = count($palestre);
				$data_container['numero_palestre'] = $numero_palestre;
				$data_container['filter'] = $filter;
								
				
				$numero_pagine = ceil($numero_palestre/ELEMENTI_PER_PAGINA);
				
				$data_container['numero_pagine'] = $numero_pagine;
				$data_container['numero_pagina'] = $numero_pagina;
				
				
				
				
				//for($i=0; $i<count($palestre); $i++) {
				$elenco_palestre_completo = array();
				$i=0;
				if( count($palestre) > 0 ) {
					foreach($palestre as $palestra) {

						$elenco_palestre_completo[$i] = (object)[];
						$elenco_palestre_completo[$i]->id = $palestra->id;
						
						$elenco_palestre_completo[$i]->nome = $palestra->nome;
						if( $palestra->attiva_al < time() ) {
							$elenco_palestre_completo[$i]->nome .= ' (Scaduta)';
						}
						$elenco_palestre_completo[$i]->indirizzo = $palestra->indirizzo.', '.$palestra->cap.', '.$palestra->citta.' ('.$palestra->provincia.')';
						if( $palestra->id_ubicazione != "" ) {
							$ubicazione = $this->palestra->getUbicazione($palestra->id_ubicazione);
							if( $ubicazione != null) {
								$elenco_palestre_completo[$i]->ubicazione = $ubicazione->posizione;
							} else {
								$elenco_palestre_completo[$i]->ubicazione = 'non trovata in elenco';
							}
						} else {
							$elenco_palestre_completo[$i]->ubicazione = "n.d.";
						}
						$elenco_palestre_completo[$i]->sito_web = $palestra->sito_web;
						$elenco_palestre_completo[$i]->email = $palestra->email;

						$i++;

					}
				}
				$data_container['palestre'] = $elenco_palestre_completo;
				
				
				
				
				
				
				$this->load->view('header', $header);
				$this->load->view('lista_palestre', $data_container);
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

	function getFormInsert() {
		if( $this->user->controlloAutenticazione() ) {
			/* AREA AUTENTICATA */
			
			$title = 'Inserimento nuova Palestra';
			$controller_redirect = 'home';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			
			if( $privilegi == 0 ) {
			
				$metodo_insert = "";
				$dati = array();
				
				$ubicazioni = $this->palestra->getAllUbicazioniPalestra();
				$option_ubicazioni = '<option value="-1">n.d.</option>\n';
				if( count($ubicazioni) > 0 ) {
					foreach($ubicazioni as $ubicazione) {
						$option_ubicazioni .= '<option value="'.$ubicazione->id.'">'.$ubicazione->posizione.'</option>\n';
					}
				}
				$option_ubicazioni .= '<option value="" class="sgp-altra-ubicazione">Altro</option>\n';
				$dati['option_ubicazioni'] = $option_ubicazioni;
				
				$attivita_palestra = $this->palestra->getAllAttivitaPalestra();
				$option_attivita_palestra = '<option value="-1">n.d.</option>\n';
				if( count($attivita_palestra) ) {
					foreach($attivita_palestra as $singola_attivita_palestra) {
						$option_attivita_palestra .= '<option value="'.$singola_attivita_palestra->id.'">'.$singola_attivita_palestra->tipo_attivita.'</option>\n';
					}
				}
				$option_attivita_palestra .= '<option value="" class="sgp-altro-ruolo">Altro</option>\n';
				$dati['option_attivita_palestra'] = $option_attivita_palestra;
				
				$data_oggi = time();
				$data_scadenza_default = $data_oggi+31536000;
				$dati['data_oggi'] = date('d/m/Y', $data_oggi);
				$dati['data_scadenza_default'] = date('d/m/Y', $data_scadenza_default);
				
				$this->load->view('header', $header);
				$this->load->view('palestre/form_insert_palestra', $dati);
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
	
	function getFormEdit($id_palestra) {
		if( $this->user->controlloAutenticazione() ) {
			/* AREA AUTENTICATA */
			$palestra = $this->palestra->getPalestra($id_palestra);
			
			
			$title = 'Modifica di "'.$palestra->nome.'"';
			$controller_redirect = 'home';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			
			
			$privilegi = $header['privilegi'];
			
			if( $privilegi == 0 ) {
			
				$dati = array();

				$metodo_edit = "";
		
				if( $palestra != null ) {

					$palestra->attiva_dal = date('d/m/Y', $palestra->attiva_dal);
					if( $palestra->attiva_al != 0 && $palestra->attiva_al != '' ) {
						$palestra->attiva_al = date('d/m/Y', $palestra->attiva_al);
					} 
					

					$dati = $palestra;


					$session_login = $this->session->userdata('logged_in');

					$ruolo = $session_login['ruolo'];
					if($ruolo != 0) {
						$ubicazioni = $this->palestra->getAllUbicazioniPalestra($id_palestra);
					} else {
						$ubicazioni = $this->palestra->getAllUbicazioni();
					}
					$dati->ubicazioni = $ubicazioni;

					$recapiti_telefonici_palestra = $this->recapiti_telefonici->getAllRecapitiPalestra($id_palestra);
					if( count($recapiti_telefonici_palestra) > 0 ) {
						foreach($recapiti_telefonici_palestra as $recapito_telefonico_palestra) {
							$recapito_telefonico_palestra->tipologia_numero = $this->recapiti_telefonici->getTipologia($recapito_telefonico_palestra->id_tipologia_numero)->etichetta;
						}
					}
					$dati->recapiti_telefonici_palestra = $recapiti_telefonici_palestra;
					$tipologie_contatti = $this->recapiti_telefonici->getAllTipologiePalestra($id_palestra);
					$dati->tipologie_contatti = $tipologie_contatti;

					$persone_riferimento_palestra = $this->palestra->getAllPersoneRifPalestra($id_palestra);
					if( count($persone_riferimento_palestra) > 0 ) {
						foreach($persone_riferimento_palestra as $persona_riferimento_palestra) {
							$persona_riferimento_palestra->ruolo = $this->palestra->getRuoloPersonaRif($persona_riferimento_palestra->id_ruolo_personale)->ruolo;
						}
					}
					$dati->persone_riferimento_palestra = $persone_riferimento_palestra;
					$ruoli_persone_riferimento = $this->palestra->getAllRuoliPersoneRifPalestra($id_palestra);
					$dati->ruoli_persone_riferimento = $ruoli_persone_riferimento;

					$attivita_palestra = $this->palestra->getAllAttivitaPalestra($id_palestra);
					$dati->attivita_palestra = $attivita_palestra;
					
					$this->load->view('header', $header);
					$this->load->view('palestre/form_edit_palestra', $dati);
					$this->load->view('footer');

				}  else {
					redirect('listapalestre', 'refresh');
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
	
	function showPalestra($id_palestra) {
		if( $this->user->controlloAutenticazione() ) {
			/* AREA AUTENTICATA */
			
			$palestra = $this->palestra->getPalestra($id_palestra);
			
			
			$title = 'Visualizzazione di "'.$palestra->nome.'"';
			$controller_redirect = 'home';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			
			
			$privilegi = $header['privilegi'];
			
			if( $privilegi == 0 ) {
			
				$dati = array();
				
				$formato_data = 'd/m/Y';
				$date = new DateTime();
				$date->setTimestamp($palestra->attiva_dal);
				$palestra->attiva_dal = $date->format($formato_data);

				if( $palestra->attiva_al != null && $palestra->attiva_al != 0 ) {
					$date->setTimestamp($palestra->attiva_al);
					$palestra->attiva_al = $date->format($formato_data);
				} else {
					$palestra->attiva_al = 'In attività';
				}

				$attivita = $this->palestra->getAttivita($palestra->id_attivita_palestra);
				if( $attivita != null ) {
					$palestra->tipo_attivita = $attivita->tipo_attivita;
				} else  {
					$palestra->tipo_attivita = 'n.d.';
				}

				$ubicazione = $this->palestra->getUbicazione($palestra->id_ubicazione);
				if( $ubicazione != null ) {
					$palestra->ubicazione = $ubicazione->posizione;
				} else {
					$palestra->ubicazione = 'n.d.';
				}

				$palestra->servizio_bar = ( $palestra->servizio_bar == 1 ? 'Sì' : 'No' );

				$palestra->servizio_distributori = ( $palestra->servizio_distributori == 1 ? 'Sì' : 'No' );
				
				$palestra->shop = ($palestra->shop == 1 ? 'Sì' : 'No');
				
				
				$contatti = $this->recapiti_telefonici->getAllRecapitiPalestra($id_palestra);
				$palestra->contatti = array();
				if( count($contatti) > 0 ) {
					$i=0;
					foreach($contatti as $contatto) {

						$palestra->contatti[$i]['tipologia'] = $this->recapiti_telefonici->getTipologia($contatto->id_tipologia_numero)->etichetta;
						$palestra->contatti[$i]['numero'] = $contatto->numero;
						$i++;
					}
				}

				$persone_riferimento = $this->palestra->getAllPersoneRifPalestra($id_palestra);
				$palestra->persone_riferimento = array();
				if( count($persone_riferimento) > 0 ) {
					$i=0;
					foreach($persone_riferimento as $persona_riferimento) {

						$palestra->persone_riferimento[$i] = $persona_riferimento;
						$palestra->persone_riferimento[$i]->ruolo = $this->palestra->getRuoloPersonaRif($persona_riferimento->id_ruolo_personale)->ruolo;
						$i++;
					}
				}

				if( $palestra->considerazioni_generali == null || $palestra->considerazioni_generali == '' ) {
					$palestra->considerazioni_generali = 'Nessuna';
				}
				
				if( $palestra->altro == null || $palestra->altro == '' ) {
					$palestra->altro = '';
				}

				$this->load->view('header', $header);
				$this->load->view('palestre/show_palestra', $palestra);
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
	
	function getFormEditLite($id_palestra) {
		if( $this->user->controlloAutenticazione() ) {
			/* AREA AUTENTICATA */
			$palestra = $this->palestra->getPalestra($id_palestra);
			
			
			$title = 'Modifica di "'.$palestra->nome.'"';
			$controller_redirect = 'home';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			
			
			$privilegi = $header['privilegi'];
			
			if( $privilegi <= 1 ) {
			
				$dati = array();

				$metodo_edit = "";
		
				if( $palestra != null ) {
					/*
					$palestra->attiva_dal = date('d/m/Y', $palestra->attiva_dal);
					if( $palestra->attiva_al != 0 && $palestra->attiva_al != '' ) {
						$palestra->attiva_al = date('d/m/Y', $palestra->attiva_al);
					} 
					
					*/
					$dati = $palestra;


					$session_login = $this->session->userdata('logged_in');

					$ruolo = $session_login['ruolo'];
					if($ruolo != 0) {
						$ubicazioni = $this->palestra->getAllUbicazioniPalestra($id_palestra);
					} else {
						$ubicazioni = $this->palestra->getAllUbicazioni();
					}
					$dati->ubicazioni = $ubicazioni;

					$recapiti_telefonici_palestra = $this->recapiti_telefonici->getAllRecapitiPalestra($id_palestra);
					if( count($recapiti_telefonici_palestra) > 0 ) {
						foreach($recapiti_telefonici_palestra as $recapito_telefonico_palestra) {
							$recapito_telefonico_palestra->tipologia_numero = $this->recapiti_telefonici->getTipologia($recapito_telefonico_palestra->id_tipologia_numero)->etichetta;
						}
					}
					$dati->recapiti_telefonici_palestra = $recapiti_telefonici_palestra;
					$tipologie_contatti = $this->recapiti_telefonici->getAllTipologiePalestra($id_palestra);
					$dati->tipologie_contatti = $tipologie_contatti;

					$persone_riferimento_palestra = $this->palestra->getAllPersoneRifPalestra($id_palestra);
					if( count($persone_riferimento_palestra) > 0 ) {
						foreach($persone_riferimento_palestra as $persona_riferimento_palestra) {
							$persona_riferimento_palestra->ruolo = $this->palestra->getRuoloPersonaRif($persona_riferimento_palestra->id_ruolo_personale)->ruolo;
						}
					}
					$dati->persone_riferimento_palestra = $persone_riferimento_palestra;
					$ruoli_persone_riferimento = $this->palestra->getAllRuoliPersoneRifPalestra($id_palestra);
					$dati->ruoli_persone_riferimento = $ruoli_persone_riferimento;

					$attivita_palestra = $this->palestra->getAllAttivitaPalestra($id_palestra);
					$dati->attivita_palestra = $attivita_palestra;
					
					$this->load->view('header', $header);
					$this->load->view('palestre/form_edit_palestra_lite', $dati);
					$this->load->view('footer');

				}  else {
					redirect('listapalestre', 'refresh');
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
	
	function showInfoPalestra($id_palestra) {
		if( $this->user->controlloAutenticazione() ) {
			/* AREA AUTENTICATA */
			
			$palestra = $this->palestra->getPalestra($id_palestra);
			
			
			$title = 'Visualizzazione di "'.$palestra->nome.'"';
			$controller_redirect = 'home';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			
			
			$privilegi = $header['privilegi'];
			
			if( $privilegi <= 1 ) {
				
				$dati = array();
				
				/*
				$formato_data = 'd/m/Y';
				$date = new DateTime();
				$date->setTimestamp($palestra->attiva_dal);
				$palestra->attiva_dal = $date->format($formato_data);

				if( $palestra->attiva_al != null && $palestra->attiva_al != 0 ) {
					$date->setTimestamp($palestra->attiva_al);
					$palestra->attiva_al = $date->format($formato_data);
				} else {
					$palestra->attiva_al = 'In attività';
				}
				*/
				$attivita = $this->palestra->getAttivita($palestra->id_attivita_palestra);
				if( $attivita != null ) {
					$palestra->tipo_attivita = $attivita->tipo_attivita;
				} else  {
					$palestra->tipo_attivita = 'n.d.';
				}

				$ubicazione = $this->palestra->getUbicazione($palestra->id_ubicazione);
				if( $ubicazione != null ) {
					$palestra->ubicazione = $ubicazione->posizione;
				} else {
					$palestra->ubicazione = 'n.d.';
				}

				$palestra->servizio_bar = ( $palestra->servizio_bar == 1 ? 'Sì' : 'No' );

				$palestra->servizio_distributori = ( $palestra->servizio_distributori == 1 ? 'Sì' : 'No' );
				
				$palestra->shop = ($palestra->shop == 1 ? 'Sì' : 'No');
				
				
				$contatti = $this->recapiti_telefonici->getAllRecapitiPalestra($id_palestra);
				$palestra->contatti = array();
				if( count($contatti) > 0 ) {
					$i=0;
					foreach($contatti as $contatto) {

						$palestra->contatti[$i]['tipologia'] = $this->recapiti_telefonici->getTipologia($contatto->id_tipologia_numero)->etichetta;
						$palestra->contatti[$i]['numero'] = $contatto->numero;
						$i++;
					}
				}

				$persone_riferimento = $this->palestra->getAllPersoneRifPalestra($id_palestra);
				$palestra->persone_riferimento = array();
				if( count($persone_riferimento) > 0 ) {
					$i=0;
					foreach($persone_riferimento as $persona_riferimento) {

						$palestra->persone_riferimento[$i] = $persona_riferimento;
						$palestra->persone_riferimento[$i]->ruolo = $this->palestra->getRuoloPersonaRif($persona_riferimento->id_ruolo_personale)->ruolo;
						$i++;
					}
				}
				/*
				if( $palestra->considerazioni_generali == null || $palestra->considerazioni_generali == '' ) {
					$palestra->considerazioni_generali = 'Nessuna';
				}
				if( $palestra->altro == null || $palestra->altro == '' ) {
					$palestra->altro = '';
				}
				*/

				$this->load->view('header', $header);
				$this->load->view('palestre/show_palestra_lite', $palestra);
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
	
	function confirmDelete($id_palestra) {
		if( !$this->user->controlloAutenticazione() ) {
			echo '-1';
			exit();
		}
		$metodo_eliminazione = "listapalestre/deletePalestra";
		$palestra = $this->palestra->getPalestra($id_palestra);
		if( $palestra != null ) {
			$url_eliminazione = $metodo_eliminazione.'/'.$palestra->id;
			
			$dati['nome_palestra'] = $palestra->nome;
			
			$this->load->view('palestre/confirm_delete_palestra', $dati);
			
		}
	}
	
	function showContattiPalestra($id_palestra) {
		if( $this->user->controlloAutenticazione() ) {
			/* AREA AUTENTICATA */
			
			$palestra = $this->palestra->getPalestra($id_palestra);
			
			
			$title = 'Personale e contatti palestra: "'.$palestra->nome.'"';
			$controller_redirect = 'home';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			
			
			$privilegi = $header['privilegi'];
			
			
			
			$dati = array();
				
				
			$contatti = $this->recapiti_telefonici->getAllRecapitiPalestra($id_palestra);
			$palestra->contatti = array();
			if( count($contatti) > 0 ) {
				$i=0;
				foreach($contatti as $contatto) {

					$contatto->tipologia = $this->recapiti_telefonici->getTipologia($contatto->id_tipologia_numero)->etichetta;
					
				}
			}

			$persone_riferimento = $this->palestra->getAllPersoneRifPalestra($id_palestra);
			$palestra->persone_riferimento = array();
			if( count($persone_riferimento) > 0 ) {
				$i=0;
				foreach($persone_riferimento as $persona_riferimento) {

					$palestra->persone_riferimento[$i] = $persona_riferimento;
					$palestra->persone_riferimento[$i]->ruolo = $this->palestra->getRuoloPersonaRif($persona_riferimento->id_ruolo_personale)->ruolo;
					$i++;
				}
			}
			
			$dati['nome'] = $palestra->nome;
			$dati['contatti'] = $contatti;
			$dati['persone_riferimento'] = $persone_riferimento;

			$this->load->view('header', $header);
			$this->load->view('palestre/show_contatti_e_persone_riferimento_palestra', $dati);
			$this->load->view('footer');
				
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	
	function getContattoForm($id_palestra = '') {
		
		$tipologie_contatti = $this->recapiti_telefonici->getAllTipologiePalestra($id_palestra);
		$option_tipologie_contatto = "";
		if( count($tipologie_contatti) ) {
			foreach($tipologie_contatti as $tipologia_contatto) {
				$option_tipologie_contatto .= '<option value="'.$tipologia_contatto->id.'">'.$tipologia_contatto->etichetta.'</option>\n';
			}
		}
		$option_tipologie_contatto .= '<option value="" class="sgp-altra-tipologia-contatto">Altro</option>\n';
		
		$dati_recapito['option_tipologie_contatto'] = $option_tipologie_contatto;
		$this->load->view('palestre/sub_form_palestra_add_recapito', $dati_recapito);
	}
	
	function editContattoForm($id_contatto, $id_palestra = '') {
		$contatto = $this->recapiti_telefonici->getRecapitoPalestra($id_contatto);
		
		$tipologie_contatti = $this->recapiti_telefonici->getAllTipologiePalestra($id_palestra);
		$option_tipologie_contatto = "";
		if( count($tipologie_contatti) ) {
			foreach($tipologie_contatti as $tipologia_contatto) {
				$selected = '';
				if( $tipologia_contatto->id == $contatto->id_tipologia_numero ) $selected = 'selected';
				$option_tipologie_contatto .= '<option value="'.$tipologia_contatto->id.'" '.$selected.'>'.$tipologia_contatto->etichetta.'</option>\n';
			}
		}
		$option_tipologie_contatto .= '<option value="" class="sgp-altra-tipologia-contatto">Altro</option>\n';
		
		$dati = $contatto;
		$dati->option_tipologie_contatto = $option_tipologie_contatto;
		
		$this->load->view('palestre/sub_form_palestra_edit_recapito', $dati);
	}
	
	function addRowContatto() {
		$post_result = $this->input->post();
		if( $post_result['id_tipologia_numero'] != '' ) {
			$post_result['tipologia_numero'] = $this->recapiti_telefonici->getTipologia($post_result['id_tipologia_numero'])->etichetta;
		} else {
			$post_result['tipologia_numero'] = $post_result['new_tipologia_numero'];
		}
		
		$this->load->view('palestre/new_row_palestra_contatto', $post_result);
	}
	
	function getNuovaPersoneRiferimento($id_palestra = '') {
		
		$ruoli_persone_riferimento = $this->palestra->getAllRuoliPersoneRifPalestra($id_palestra);
		$option_ruoli_persone_riferimento = "";
		if( count($ruoli_persone_riferimento) ) {
			foreach($ruoli_persone_riferimento as $ruolo_persona_riferimento) {
				$option_ruoli_persone_riferimento .= '<option value="'.$ruolo_persona_riferimento->id.'">'.$ruolo_persona_riferimento->ruolo.'</option>\n';
			}
		}
		$option_ruoli_persone_riferimento .= '<option value="" class="sgp-altro-ruolo">Altro</option>\n';
		
		$dati_persona_riferimento['option_ruoli_persone_riferimento'] = $option_ruoli_persone_riferimento;
		
		$this->load->view('palestre/sub_form_palestra_add_persona_rif', $dati_persona_riferimento);
	}
	
	function editNuovaPersoneRiferimento($id_persona_riferimento, $id_palestra = '') {
		$persona_riferimento = $this->palestra->getPersonaRif($id_persona_riferimento);
		$dati_persona_riferimento = $persona_riferimento;
		
		$ruoli_persone_riferimento = $this->palestra->getAllRuoliPersoneRifPalestra($id_palestra);
		$option_ruoli_persone_riferimento = "";
		if( count($ruoli_persone_riferimento) ) {
			foreach($ruoli_persone_riferimento as $ruolo_persona_riferimento) {
				$selected = '';
				if( $persona_riferimento->id_ruolo_personale == $ruolo_persona_riferimento->id ) $selected = 'selected';
				$option_ruoli_persone_riferimento .= '<option value="'.$ruolo_persona_riferimento->id.'" '.$selected.'>'.$ruolo_persona_riferimento->ruolo.'</option>\n';
			}
		}
		$option_ruoli_persone_riferimento .= '<option value="" class="sgp-altro-ruolo">Altro</option>\n';
		
		$dati_persona_riferimento->option_ruoli_persone_riferimento = $option_ruoli_persone_riferimento;
		
		
		$this->load->view('palestre/sub_form_palestra_edit_persona_rif', $dati_persona_riferimento);
	}
	
	function addRowPersonaRiferimento() {
		$post_result = $this->input->post();
		if( $post_result['id_ruolo_riferimento'] != '' ) {
			$post_result['ruolo_riferimento'] = $this->palestra->getRuoloPersonaRif($post_result['id_ruolo_riferimento'])->ruolo;
		} else {
			$post_result['ruolo_riferimento'] = $post_result['new_ruolo_riferimento'];
		}
		
		//var_dump($post_result);
		$this->load->view('palestre/new_row_palestra_persona_rif', $post_result);
	}
	
	function searchPalestre($filter = '') {
		if( !$this->user->controlloAutenticazione() ) {
			echo '-1';
			exit();
		}
		$form_data = $this->input->post();
		
		$parole_cercate = $form_data['search_words'];
		
		$palestre = array();
		
		switch($filter) {
			case 'expiring':
				$parametri_palestra = $this->sistema->getParametriSistema();
				$palestre = $this->palestra->searchPalestra($parole_cercate, $parametri_palestra->alert_scadenza_palestre);
				break;
			case 'expired':
				$palestre = $this->palestra->searchPalestra($parole_cercate, -1);
				break;
			default:
				$palestre = $this->palestra->searchPalestra($parole_cercate);
		}
		
		
		
		foreach($palestre as $palestra) {
			if( $palestra->id_ubicazione != "" ) {
				$ubicazione = $this->palestra->getUbicazione($palestra->id_ubicazione);
				if( $ubicazione != null) {
					$palestra->ubicazione = $ubicazione->posizione;
				} else {
					$palestra->ubicazione = 'Non trovata in elenco';
				}
			} else {
				$palestra->ubicazione = "n.d.";
			}
		}
		
		$dati = array();
		$dati['palestre'] = $palestre;
		
		$this->load->view('palestre/search_result_palestre', $dati);
	}
}
 
?>