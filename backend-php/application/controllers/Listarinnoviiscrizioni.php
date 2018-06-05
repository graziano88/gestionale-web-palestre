<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class Listarinnoviiscrizioni extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		/* MODEL SEMPRE NECESSARI */
		$this->load->model('user','',TRUE);
		
		/* MODEL NECESSARI ALL'HEADER_MODEL */
		$this->load->model('personale');
		$this->load->model('palestra');
		$this->load->model('HeaderModel', 'header_model');
		
		/* MODEL NECESSARI IN QUESTO CONTROLLER */
		$this->load->model('RinnoviIscrizioni', 'rinnovi_iscrizioni');
		$this->load->model('abbonamenti');
		$this->load->model('socio');
		$this->load->model('motivazioni');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'listarinnoviiscrizioni');
		define('ELEMENTI_PER_PAGINA', 15);
		
	}
	
	function index() {
		$this->p();
	}
		
	function p($numero_pagina = 1, $filter = '', $return_msg = '') {
		//GET RINNOVI/ISCRIZIONE DEL DESK LOGGATO
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$title = 'Lista Rinnovi/Iscrizione';
			$controller_redirect = CURRENT_PAGE;

			$header = $this->header_model->getHeader($title, $controller_redirect);

			$privilegi = $header['privilegi'];
			$id_palestra_session = $header['id_palestra'];
			
			if(( $privilegi == 3 || $privilegi == 0 ) && $id_palestra_session != '' ) {
				
				$now = time();
				
				$parametri_palestra = $this->palestra->getParametriPalestraByPalestra($id_palestra_session);
				
				$start = ELEMENTI_PER_PAGINA*($numero_pagina-1);
				
				$free_pass_trasformati = $this->checkValiditaFreePass($id_palestra_session);
				
				$i = $numero_pagina;
				do {
					
				
					$id_desk = ( $privilegi == 3 ? $header['id_utente'] : '' );

					
					switch( $filter ) {
						case 'desk':
							if($id_desk != '' ) {
								$rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getAllRinnoviIscrizioniByConsulente($id_desk, ELEMENTI_PER_PAGINA, $start);
								$numero_rinnovi_iscrizioni = count($this->rinnovi_iscrizioni->getAllRinnoviIscrizioniByConsulente($id_desk));
								$desk = $this->personale->getUtente($id_desk);
								$data_container['sub_titolo_pagina'] = "Lista relativa al desk ".$desk->nome.' '.$desk->cognome;
							} else {
								$rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getAllRinnoviIscrizioniPalestra($id_palestra_session, ELEMENTI_PER_PAGINA, $start);
								$numero_rinnovi_iscrizioni = count($this->rinnovi_iscrizioni->getAllRinnoviIscrizioniPalestra($id_palestra_session, $id_desk));
								$data_container['sub_titolo_pagina'] = "Lista relativa a tutti i desk della palestra";
							}
							break;
						case 'fpwe':
							//free-pass in scadenza
							$rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getRinnoviIscrizioniFreePassWillExpired($id_palestra_session, $parametri_palestra->alert_scadenza_freepass, $id_desk, ELEMENTI_PER_PAGINA, $start);
							
							$numero_rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniFreePassWillExpired($id_palestra_session, $parametri_palestra->alert_scadenza_freepass, $id_desk);
							$data_container['sub_titolo_pagina'] = "Free-pass in scadenza".( $id_desk == '' ? ' (tutti i desks)' : ' (solo tuoi)' );
							break;
						case 'fpe':
							//free-pass scaduti
							$rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getRinnoviIscrizioniFreePassExpired($id_palestra_session, $id_desk, ELEMENTI_PER_PAGINA, $start);
							$numero_rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniFreePassExpired($id_palestra_session, $id_desk);
							$data_container['sub_titolo_pagina'] = "Free-pass scaduti".( $id_desk == '' ? ' (tutti i desks)' : ' (solo tuoi)' );
							break;
						case 'missed':
							//missed
							if( $privilegi == 0 ) {
								$rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getRinnoviIscrizioniMissed($id_palestra_session, $id_desk, ELEMENTI_PER_PAGINA, $start);
								$numero_rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniMissed($id_palestra_session);
							} else {
								$scadenza_missed_desk = $parametri_palestra->scadenza_missed;
								
								$rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getRinnoviIscrizioniMissedDesk($id_palestra_session, $id_desk, $scadenza_missed_desk, ELEMENTI_PER_PAGINA, $start);
								$numero_rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniMissedDesk($id_palestra_session, $id_desk, $scadenza_missed_desk);
							}
							$data_container['sub_titolo_pagina'] = "Missed".( $id_desk == '' ? ' (tutti i desks)' : ' (solo tuoi)' );
							break;
						case 'mia':
							
							$soglie = $parametri_palestra;
							
							$limite_scadenza_alert_giorni = $soglie->secondo_alert_missed;
							
							$rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getRinnoviIscrizioniMissedAlertDesk($id_palestra_session, $id_desk, $soglie->primo_alert_missed, $limite_scadenza_alert_giorni, ELEMENTI_PER_PAGINA, $start);
							
							$numero_rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniMissedAlertDesk($id_palestra_session, $id_desk, $soglie->primo_alert_missed, $limite_scadenza_alert_giorni);
							$data_container['sub_titolo_pagina'] = "Missed - I alert";
							break;
						case 'miia':
							
							$soglie = $parametri_palestra;
							
							$limite_scadenza_alert_giorni = $soglie->scadenza_missed;
							
							$rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getRinnoviIscrizioniMissedAlertDesk($id_palestra_session, $id_desk, $soglie->secondo_alert_missed, $limite_scadenza_alert_giorni, ELEMENTI_PER_PAGINA, $start);
							
							$numero_rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniMissedAlertDesk($id_palestra_session, $id_desk, $soglie->secondo_alert_missed, $limite_scadenza_alert_giorni);
							$data_container['sub_titolo_pagina'] = "Missed - II alert";
							break;
						default:
							$rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getAllRinnoviIscrizioniPalestra($id_palestra_session, ELEMENTI_PER_PAGINA, $start);
							$numero_rinnovi_iscrizioni = count($this->rinnovi_iscrizioni->getAllRinnoviIscrizioniPalestra($id_palestra_session));
							$data_container['sub_titolo_pagina'] = "Lista relativa a tutti i desk della palestra";
							break;
					}
					
					

					
					$data_container['number_missed'] = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniMissed($id_palestra_session, $id_desk);;
					$data_container['number_free_pass_will_expire'] = $this->rinnovi_iscrizioni->getNumberRinnoviIscrizioniFreePassWillExpired($id_palestra_session, $parametri_palestra->alert_scadenza_freepass, $id_desk);
					

					// il ciclo e questo servono a fare il refresh dopo l'eliminazione dell'ultimo elemento di una pagina o ha visualizzare correttamente dopo la digitazione manuale di una pagina che non esiste
					$i--;
					//$start = ELEMENTI_PER_PAGINA*($i-1);
				} while( count($rinnovi_iscrizioni) < 1 && $i > 0 );

				if( $numero_pagina != ($i+1) ) {
					$numero_pagina = ($i+1);
				}

				$data_container['numero_rinnovi_iscrizioni'] = $numero_rinnovi_iscrizioni;
				//$data_container['filter'] = $filter;

				$numero_pagine = ceil($numero_rinnovi_iscrizioni/ELEMENTI_PER_PAGINA);
				
				if( $filter == 'a' ) $filter = '';
				$data_container['filter'] = $filter;
				$data_container['numero_pagine'] = $numero_pagine;
				$data_container['numero_pagina'] = $numero_pagina;
				
				$now = time();
				$soglia_desk = $parametri_palestra;
				$giorni_scadenza_secondi = $soglia_desk->scadenza_missed*86400;
				
				if( count($rinnovi_iscrizioni) > 0 ) {
					foreach( $rinnovi_iscrizioni as $rinnovo_iscrizione ) {
						if( $rinnovo_iscrizione->id_tipo_abbonamento != '' ) {
							$abbonamento = $this->abbonamenti->getTipologiaAbbonamento($rinnovo_iscrizione->id_tipo_abbonamento);
							$rinnovo_iscrizione->tipo_abbonamento = $abbonamento->tipo;
							$durata = $abbonamento->durata;
						} else {
							$rinnovo_iscrizione->tipo_abbonamento = 'n.d.';
							$durata = 0;
						}
						
						$durata_free_pass_secondi = $durata*86400;
						
						$soglia = $rinnovo_iscrizione->data_e_ora + $durata_free_pass_secondi;
						
						$rinnovo_iscrizione->scaduto = ( $soglia > $now ? 0 : 1 );
						
						$desk = $this->personale->getUtente($rinnovo_iscrizione->id_consulente);
						
						if( $id_desk == $rinnovo_iscrizione->id_consulente ) {
							
							$soglia = $rinnovo_iscrizione->data_e_ora + $durata + $giorni_scadenza_secondi;
							
							if( $now >= $soglia ) {
								$rinnovo_iscrizione->delete_lock = true;
							} else {
								$rinnovo_iscrizione->delete_lock = false;
							}
						} else {
							$rinnovo_iscrizione->delete_lock = true;
						}
						
						$rinnovo_iscrizione->nome_desk = $desk->nome;
						$rinnovo_iscrizione->cognome_desk = $desk->cognome;
						
						$rinnovo_iscrizione->data_str = date('d/m/Y', $rinnovo_iscrizione->data_e_ora);
						$rinnovo_iscrizione->ora_str = date('H:i', $rinnovo_iscrizione->data_e_ora);
					}
				}
				
				$data_container['rinnovi_iscrizioni'] = $rinnovi_iscrizioni;
				
				$data_container['free_pass_trasformati'] = $free_pass_trasformati;
				//var_dump($rinnovi_iscrizioni);
				
				$data_container['return_msg'] = '';
				if( $return_msg != '' ) {
					$data_container['return_msg_type'] = $return_msg;
					switch($return_msg) {
						case 'success':
							$data_container['return_msg'] = 'Inserimento riuscito correttamente';
							break;
						case 'failed':
							$data_container['return_msg'] = 'Inserimento non riuscito';
							break;
					}
				}
				
				$this->load->view('header', $header);
				$this->load->view('lista_rinnovi_iscrizioni', $data_container);
				$this->load->view('footer');

			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
	function showRinnovoIscrizione($id_rinnovo_iscrizione) {
		//GET RINNOVI/ISCRIZIONE DEL DESK LOGGATO
		
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$title = 'Rinnovo/Iscrizione';
			$controller_redirect = CURRENT_PAGE;

			$header = $this->header_model->getHeader($title, $controller_redirect);

			$privilegi = $header['privilegi'];
			$id_palestra_session = $header['id_palestra'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				// AREA DI LAVORO
				
				$rinnovo_iscrizione = $this->rinnovi_iscrizioni->getRinnovoIscrizione($id_rinnovo_iscrizione);
				
				switch( $rinnovo_iscrizione->tipo ) {
					case 1:
						$rinnovo_iscrizione->tipo_rinnovo_iscrizione = 'Rinnovo senza appuntamento';
						break;
					case 2:
						$rinnovo_iscrizione->tipo_rinnovo_iscrizione = 'Rinnovo con appuntamento';
						break;
					case 3:
						$rinnovo_iscrizione->tipo_rinnovo_iscrizione = 'Tour spontaneo';
						break;
					case 4:
						$rinnovo_iscrizione->tipo_rinnovo_iscrizione = 'Appuntamento per nuovo cliente';
						break;
					default:
						$rinnovo_iscrizione->tipo_rinnovo_iscrizione = '';
						break;
				}
				$coordinatore = $this->personale->getUtente($rinnovo_iscrizione->id_coordinatore);

				if( $rinnovo_iscrizione->id_tipo_abbonamento != '' ) {
					$rinnovo_iscrizione->tipo_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($rinnovo_iscrizione->id_tipo_abbonamento)->tipo;
				} else {
					$rinnovo_iscrizione->tipo_abbonamento = 'n.d.';
				}

				$desk = $this->personale->getUtente($rinnovo_iscrizione->id_consulente);
				$rinnovo_iscrizione->nome_desk = $desk->nome;
				$rinnovo_iscrizione->cognome_desk = $desk->cognome;
				
				if( $rinnovo_iscrizione->id_coordinatore != '' ) {
					$coordinatore = $this->personale->getUtente($rinnovo_iscrizione->id_coordinatore);
					$rinnovo_iscrizione->nome_coordinatore = $coordinatore->nome;
					$rinnovo_iscrizione->cognome_coordinatore = $coordinatore->cognome;
				} else {
					$rinnovo_iscrizione->nome_coordinatore = '';
					$rinnovo_iscrizione->cognome_coordinatore = '';
				}
				
				if( $rinnovo_iscrizione->id_motivazione != '' ) {
					$rinnovo_iscrizione->motivazione_frequenza = $this->motivazioni->getMotivazione($rinnovo_iscrizione->id_motivazione)->motivazione;
				} else {
					$rinnovo_iscrizione->motivazione_frequenza = 'n.d.';
				}
				
				if( $rinnovo_iscrizione->id_socio_registrato != '' ) {
					
					$socio_registrato = $this->socio->getSocio($rinnovo_iscrizione->id_socio_registrato);
					$rinnovo_iscrizione->nome_socio_registrato = $socio_registrato->nome;
					$rinnovo_iscrizione->cognome_socio_registrato = $socio_registrato->cognome;
				} else {
					$rinnovo_iscrizione->nome_socio_registrato = '';
					$rinnovo_iscrizione->cognome_socio_registrato = '';
				}
				
				$rinnovo_iscrizione->data_str = date('d/m/Y', $rinnovo_iscrizione->data_e_ora);
				$rinnovo_iscrizione->ora_str = date('H:i', $rinnovo_iscrizione->data_e_ora);
				
				if( $rinnovo_iscrizione->id_socio_presentatore != '' ) {
					$socio_presentatore_free_pass = $this->socio->getSocio($rinnovo_iscrizione->id_socio_presentatore);
					$rinnovo_iscrizione->id_socio_presentatore = $socio_presentatore_free_pass->id;
					$rinnovo_iscrizione->nome_socio_presentatore = $socio_presentatore_free_pass->nome;
					$rinnovo_iscrizione->cognome_socio_presentatore = $socio_presentatore_free_pass->cognome;
				}
				//var_dump($rinnovo_iscrizione);
				
				$dati['rinnovo_iscrizione'] = $rinnovo_iscrizione;
				$this->load->view('header', $header);
				$this->load->view('rinnovi_iscrizioni/show_rinnovo_iscrizione', $dati);
				$this->load->view('footer');
			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
	function getFormInsert() {
		
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$title = 'Inserisci Rinnovo/Iscrizione';
			$controller_redirect = CURRENT_PAGE;

			
			$header = $this->header_model->getHeader($title, $controller_redirect);

			$privilegi = $header['privilegi'];
			$id_palestra = $header['id_palestra'];
			$data_container['id_palestra'] = $id_palestra;
			
			if( $privilegi == 3 ) { //|| $privilegi == 0 ) {
				// AREA DI LAVORO
				
				
				
				/*if( $privilegi == 0 ) {
					$data_container['option_desk'] = $this->getOptionDesk($id_palestra);
				} else {*/
				$data_container['id_desk'] = $header['id_utente'];
				//}
				
				$data_container['option_tipi_abbonamento'] = $this->getOptionTipiAbbonamento($id_palestra);
				
				$data_container['option_motivazioni'] = $this->getOptionMotivazione($id_palestra);
				
				$this->load->view('header', $header);
				$this->load->view('rinnovi_iscrizioni/form_insert_rinnovo_iscrizione', $data_container);
				$this->load->view('footer');
				

			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
	function getFormInsertPrecompilato($id_rinnovo_iscrizione) {
		
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$title = 'Inserisci Rinnovo/Iscrizione';
			$controller_redirect = CURRENT_PAGE;

			
			$header = $this->header_model->getHeader($title, $controller_redirect);

			$privilegi = $header['privilegi'];
			$id_palestra = $header['id_palestra'];
			$data_container['id_palestra'] = $id_palestra;
			
			if( $privilegi == 3 ) { //|| $privilegi == 0 ) {
				// AREA DI LAVORO
				
				$rinnovo_iscrizione = $this->rinnovi_iscrizioni->getRinnovoIscrizione($id_rinnovo_iscrizione);
				
				$data_container['rinnovo_iscrizione'] = $rinnovo_iscrizione;
				
				/*if( $privilegi == 0 ) {
					$data_container['option_desk'] = $this->getOptionDesk($id_palestra);
				} else {*/
				$data_container['id_desk'] = $header['id_utente'];
				//}
				
				$data_container['option_tipi_abbonamento'] = $this->getOptionTipiAbbonamento($id_palestra);
				
				$data_container['option_motivazioni'] = $this->getOptionMotivazione($id_palestra, $rinnovo_iscrizione->id_motivazione);
				
				$this->load->view('header', $header);
				$this->load->view('rinnovi_iscrizioni/form_insert_rinnovo_iscrizione_precompilato', $data_container);
				$this->load->view('footer');
				

			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
	function getFormEdit($id_rinnovo_iscrizione) {
		
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$title = 'Modifica Rinnovo/Iscrizione';
			$controller_redirect = CURRENT_PAGE;

			$header = $this->header_model->getHeader($title, $controller_redirect);

			$privilegi = $header['privilegi'];
			$id_palestra_session = $header['id_palestra'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				// AREA DI LAVORO
				
				$rinnovo_iscrizione = $this->rinnovi_iscrizioni->getRinnovoIscrizione($id_rinnovo_iscrizione);
				
				$data_container['option_tipi_abbonamento'] = $this->getOptionTipiAbbonamento($id_palestra, $rinnovo_iscrizione->id_tipo_abbonamento);
				
				$data_container['option_motivazioni'] = $this->getOptionMotivazione($id_palestra, $rinnovo_iscrizione->id_motivazione);
				

			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
	function AskConfirmDelete($id_rinnovo_iscrizione) {
	
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				// AREA DI LAVORO
				
				$rinnovo_iscrizione = $this->rinnovi_iscrizioni->getRinnovoIscrizione($id_rinnovo_iscrizione);
				//var_dump($rinnovo_iscrizione);
				$dati['id_rinnovo_iscrizione'] = $rinnovo_iscrizione->id;
				$dati['nome'] = $rinnovo_iscrizione->nome;
				$dati['cognome'] = $rinnovo_iscrizione->cognome;
				$this->load->view('rinnovi_iscrizioni/confirm_delete_rinnovo_iscrizione', $dati);
				

			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
	function AskConfirmIscrizione($id_rinnovo_iscrizione) {
	
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				// AREA DI LAVORO
				
				$rinnovo_iscrizione = $this->rinnovi_iscrizioni->getRinnovoIscrizione($id_rinnovo_iscrizione);
				//var_dump($rinnovo_iscrizione);
				$dati['id_rinnovo_iscrizione'] = $rinnovo_iscrizione->id;
				$dati['nome'] = $rinnovo_iscrizione->nome;
				$dati['cognome'] = $rinnovo_iscrizione->cognome;
				$this->load->view('rinnovi_iscrizioni/confirm_iscrizione_rinnovo_iscrizione', $dati);
				

			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
	function searchRinnoviIscrizioni() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				// AREA DI LAVORO
				$now = time();
				
				$post_result = $this->input->post();
				
				//var_dump($post_result);
				
				$id_desk = '';
				switch( $post_result['filter'] ) {
					case 'desk':
						$id_desk = $session_login['id_utente'];
					
				}
				
				$search_results = $this->rinnovi_iscrizioni->searchRinnovoIscrizione($post_result['search_words'], $post_result['id_palestra'], $id_desk);
				//var_dump($search_results);
				if( count($search_results) > 0 ) {
					foreach($search_results as $result) {
						$desk = $this->personale->getUtente($result->id_consulente);
						if( $result->id_tipo_abbonamento != '' ) {
							$abbonamento = $this->abbonamenti->getTipologiaAbbonamento($result->id_tipo_abbonamento);
							$result->tipo_abbonamento = $abbonamento->tipo;
							$durata = $abbonamento->durata;
						} else {
							$result->tipo_abbonamento = 'n.d.';
							$durata = 0;
						}
						
						$durata_free_pass_secondi = $durata*86400;
						
						$soglia = $result->data_e_ora + $durata_free_pass_secondi;
						
						$result->scaduto = ( $soglia > $now ? 0 : 1 );
						
						$result->nome_desk = $desk->nome;
						$result->cognome_desk = $desk->cognome;
						$result->data_str = date('d/m/Y', $result->data_e_ora);
						$result->ora_str = date('H:i', $result->data_e_ora);
					}
				}
				
				
				
				
				$dati['rinnovi_iscrizioni'] = $search_results;
				$dati['privilegi'] = $privilegi;
				
				
				$this->load->view('rinnovi_iscrizioni/search_result_rinnovi_iscrizioni', $dati);

			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function searchRinnoviIscrizioniMissedAjax() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			$id_desk = $session_login['id_utente'];
			$id_palestra = $session_login['id_palestra'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				// AREA DI LAVORO
								
				$post_result = $this->input->post();
				
				//var_dump($post_result);
				
				$now = time();
				$soglia = $this->palestra->getParametriPalestraByPalestra($id_palestra);//$this->palestra->getSogliaMissedDeskByPalestra($id_palestra);
				$giorni_scadenza_secondi = $soglia->scadenza_missed*86400;
				
				if( $post_result['search_words'] != '' ) {
					$search_results = $this->rinnovi_iscrizioni->searchInMissed($post_result['search_words'], $post_result['id_palestra']);

					$rinnovi_iscrizioni_missed = array();
					if( count($search_results) > 0 ) {
						for( $i=0;$i<count($search_results);$i++ ) {
							
							$rinnovi_iscrizioni_missed[$i] = new stdClass();
							
							$soglia_scadenza = $now-$giorni_scadenza_secondi;
							if( $search_results[$i]->free_pass == 1 ) {
								$tipologia_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($search_results[$i]->id_tipo_abbonamento);
								$durata_secondi = $tipologia_abbonamento->durata*86400;
								$soglia_scadenza -= $durata_secondi;
							}
							if( $soglia_scadenza <= $search_results[$i]->data_e_ora ) {
								$rinnovi_iscrizioni_missed[$i]->scaduto = 0;
							} else {
								$rinnovi_iscrizioni_missed[$i]->scaduto = 1;
								
							}
							
							if( $search_results[$i]->id_consulente == $id_desk ) 
								$rinnovi_iscrizioni_missed[$i]->proprieta = 1;
							else
								$rinnovi_iscrizioni_missed[$i]->proprieta = 0;
							
							
							$rinnovi_iscrizioni_missed[$i]->id = $search_results[$i]->id;
							$rinnovi_iscrizioni_missed[$i]->nome = $search_results[$i]->nome;
							$rinnovi_iscrizioni_missed[$i]->cognome = $search_results[$i]->cognome;
							$rinnovi_iscrizioni_missed[$i]->cellulare = $search_results[$i]->cellulare;
							$rinnovi_iscrizioni_missed[$i]->telefono = $search_results[$i]->telefono;
							$rinnovi_iscrizioni_missed[$i]->email = $search_results[$i]->email;
							$rinnovi_iscrizioni_missed[$i]->free_pass = $search_results[$i]->free_pass;
							

							$data_ora = $search_results[$i]->data_e_ora;
							$rinnovi_iscrizioni_missed[$i]->data_str = date('d/m/Y', $data_ora);
							$rinnovi_iscrizioni_missed[$i]->ora_str = date('H:i', $data_ora);
						}

						
						$dati = array();
						$dati['rinnovi_iscrizioni'] = $rinnovi_iscrizioni_missed;
						$this->load->view('rinnovi_iscrizioni/search_missed_rinnovi_iscrizioni', $dati);
					} else {
						echo '';
					}
				} else {
					echo '';
				}
				

			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function searchRinnoviIscrizioniRegistratiAjax() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				// AREA DI LAVORO
								
				$post_result = $this->input->post();
				
				//var_dump($post_result);
				
				if( $post_result['search_words'] != '' ) {
					$search_results = $this->rinnovi_iscrizioni->searchInRegistrati($post_result['search_words'], $post_result['id_palestra']);

					$rinnovi_iscrizioni_registrati = array();
					if( count($search_results) > 0 ) {
						for( $i=0;$i<count($search_results);$i++ ) {
							$rinnovi_iscrizioni_registrati[$i] = new stdClass();
							$rinnovi_iscrizioni_registrati[$i]->id = $search_results[$i]->id;
							$rinnovi_iscrizioni_registrati[$i]->nome = $search_results[$i]->nome;
							$rinnovi_iscrizioni_registrati[$i]->cognome = $search_results[$i]->cognome;
							$rinnovi_iscrizioni_registrati[$i]->cellulare = $search_results[$i]->cellulare;
							$rinnovi_iscrizioni_registrati[$i]->telefono = $search_results[$i]->telefono;
							$rinnovi_iscrizioni_registrati[$i]->email = $search_results[$i]->email;

							$data_ora = $search_results[$i]->data_e_ora;
							$rinnovi_iscrizioni_registrati[$i]->data_str = date('d/m/Y', $data_ora);
							$rinnovi_iscrizioni_registrati[$i]->ora_str = date('H:i', $data_ora);
						}


						$dati = array();
						$dati['rinnovi_iscrizioni'] = $rinnovi_iscrizioni_registrati;
						$this->load->view('rinnovi_iscrizioni/search_registrati_rinnovi_iscrizioni', $dati);
					} else {
						echo '';
					}
				} else {
					echo '';
				}
				

			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function searchSociAjax() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				// AREA DI LAVORO
								
				$post_result = $this->input->post();
				
				//var_dump($post_result);
				
				if( $post_result['search_words'] != '' ) {
					$soci = $this->socio->searchSocio($post_result['search_words'], $post_result['id_palestra']);

					$return_soci = array();
					if( count($soci) > 0 ) {
						

						$dati = array();
						$dati['soci'] = $soci;
						$this->load->view('rinnovi_iscrizioni/search_soci_presentatori', $dati);
					} else {
						echo '';
					}
				} else {
					echo '';
				}
				

			} else {
				// PRIVILEGI NON AMMESSI
				redirect('home', 'refresh');
			}
		} else {
			// NON AUTENTICATO
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function getRinnovoIscrizioneAjax($id_rinnovo_iscrizione) {
		
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				// AREA DI LAVORO
				$rinnovo_iscrizione = $this->rinnovi_iscrizioni->getRinnovoIscrizione($id_rinnovo_iscrizione);
				
				$parametri_palestra = $this->palestra->getParametriPalestraByPalestra($rinnovo_iscrizione->id_palestra);
				
				$now = time();
				$giorni_scadenza_secondi = $parametri_palestra->scadenza_missed*86400;
				$soglia_scadenza = $now-$giorni_scadenza_secondi;
				if( $rinnovo_iscrizione->free_pass == 1 ) {
					$tipologia_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($rinnovo_iscrizione->id_tipo_abbonamento);
					$durata_secondi = $tipologia_abbonamento->durata*86400;
					$soglia_scadenza -= $durata_secondi;
				}
				if( $soglia_scadenza <= $rinnovo_iscrizione->data_e_ora ) {
					$rinnovo_iscrizione->scaduto = 0;
				} else {
					$rinnovo_iscrizione->scaduto = 1;
				}
				
				echo json_encode($rinnovo_iscrizione);
					
			} else {
				
			}
		} else {
			
		}
	}
	
	function getOptionTipiAbbonamentoAjax($id_palestra, $id_selected = '' ) {
		
		$tipi_abbonamento = $this->abbonamenti->getAllTipologieAbbonamentoFreePassPalestra($id_palestra);
		
		$option_tipi_abbonamento = '<option value="" selected></option>';
		if( count($tipi_abbonamento) > 0 ) {
			foreach( $tipi_abbonamento as $tipo_abbonamento ) {
				$option_tipi_abbonamento .= '<option value="'.$tipo_abbonamento->id.'" '.( $tipo_abbonamento->id == $id_selected ? 'selected' : '' ).'>'.$tipo_abbonamento->tipo.' (Durata: '.$tipo_abbonamento->durata.' giorni)</option>\n';
			}
		}
		
		echo $option_tipi_abbonamento;
	}
	
	// funzioni di appoggio
	
	function getOptionTipiAbbonamento($id_palestra, $id_selected = '' ) {
		
		$tipi_abbonamento = $this->abbonamenti->getAllTipologieAbbonamentoFreePassPalestra($id_palestra);
		
		$option_tipi_abbonamento = '<option value="" selected></option>';
		if( count($tipi_abbonamento) > 0 ) {
			foreach( $tipi_abbonamento as $tipo_abbonamento ) {
				$option_tipi_abbonamento .= '<option value="'.$tipo_abbonamento->id.'" '.( $tipo_abbonamento->id == $id_selected ? 'selected' : '' ).'>'.$tipo_abbonamento->tipo.'</option>\n';
			}
		}
		
		return $option_tipi_abbonamento;
	}
	
	function getOptionMotivazione($id_palestra, $id_selected = '') {
		$motivazioni = $this->motivazioni->getAllMotivazioniPalestra($id_palestra);
				
		$option_motivazioni = '<option value="" selected></option>';
		if( count($motivazioni) > 0 ) {
			foreach( $motivazioni as $motivazione ) {
				$option_motivazioni .= '<option value="'.$motivazione->id.'" '.( $motivazione->id == $id_selected ? 'selected' : '' ).'>'.$motivazione->motivazione.'</option>\n';
			}
		}
		
		return $option_motivazioni;
	}
	
	function getOptionDesk($id_palestra, $id_selected = '') {
		$desks = $this->personale->getAllDeskPalestra($id_palestra);
			
		$option_desk = '';
		if( count($desks) > 0 ) {
			foreach( $desks as $desk ) {
				$option_desk .= '<option value="'.$desk->id.'" '.( $desk->id == $id_selected ? 'selected' : '' ).'>'.$desk->nome.' '.$desk->cognome.'</option>\n';
			}
		}
		
		return $option_desk;
	}
	
	function checkValiditaFreePass($id_palestra) {
		
		$now = time();
		
		$parametri_palestra = $this->palestra->getParametriPalestraByPalestra($id_palestra);
		
		$giorni_soglia_desk = $parametri_palestra->scadenza_missed;
		//Soglia = data_e_ora + i giorni del free_pass + giorni_soglia_desk
		$nuovi_free_pass_scaduti = $this->rinnovi_iscrizioni->getRinnoviIscrizioniFreePassExpiredByBloccoScadenza($id_palestra);
		$j = 0;
		if( count($nuovi_free_pass_scaduti) > 0 ) {
			
			foreach( $nuovi_free_pass_scaduti as $nuovo_free_pass_scaduto ) {
				//Soglia = data_e_ora + i giorni del free_pass + $giorni_soglia_desk
				$abbonamento = $this->abbonamenti->getTipologiaAbbonamento($nuovo_free_pass_scaduto->id_tipo_abbonamento);
				$durata_abbonamento = $abbonamento->durata;
				$durata_abbonamento_secondi = $durata_abbonamento*86400;
				
				$giorni_soglia_desk_secondi = $giorni_soglia_desk*86400;
				
				$soglia = $nuovo_free_pass_scaduto->data_e_ora + $durata_abbonamento_secondi + $giorni_soglia_desk_secondi;
				
				if( $soglia <= $now ) {
				
					$new_entry_missed = new stdClass;

					$new_entry_missed = clone($nuovo_free_pass_scaduto);

					$new_entry_missed->id = $this->utility->generateId('rinnovi_e_iscrizioni');
					$new_entry_missed->id_tipo_abbonamento = '';
					$new_entry_missed->id_socio_presentatore = '';
					$new_entry_missed->free_pass = 0;
					$new_entry_missed->missed = 1;

					// data e ora = data_e_ora del R/I con free_pass scaduto 

					$new_entry_missed->data_e_ora = $nuovo_free_pass_scaduto->data_e_ora + 1;

					$this->rinnovi_iscrizioni->insertRinnovoIscrizione($new_entry_missed);

					$update = array();
					$update['blocco_scadenza_freepass'] = 1;
					$this->rinnovi_iscrizioni->updateRinnovoIscrizione($nuovo_free_pass_scaduto->id, $update);
					$j++;
				}
			}
			
		}
		return $j;
	}
	
}	