<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class Listasoci extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		/* MODEL SEMPRE NECESSARI */
		$this->load->model('user','',TRUE);
		
		/* MODEL NECESSARI ALL'HEADER_MODEL */
		$this->load->model('personale');
		$this->load->model('palestra');
		$this->load->model('HeaderModel', 'header_model');
		
		/* MODEL NECESSARI IN QUESTO CONTROLLER */
		$this->load->model('socio');
		$this->load->model('motivazioni');
		$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('abbonamenti');
		$this->load->model('pagamenti');	
		$this->load->model('rate');	
		$this->load->model('contatti');	
		$this->load->model('RinnoviIscrizioni', 'rinnovi_iscrizioni');
		$this->load->model('BonusSocio', 'bonus_socio');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'listasoci');
		define('ELEMENTI_PER_PAGINA', 15);
		
	}
	
	function index() {
		$this->p();
	}
	
	// FATTO
	function p($numero_pagina = 1, $filter = '') {
		
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			
			
			$title = 'Lista soci';
			$controller_redirect = CURRENT_PAGE;
			
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			$id_palestra_session = $header['id_palestra'];
			
			if( $id_palestra_session != '' && $privilegi != 2 ) {
				
				$parametri_palestra = $this->palestra->getParametriPalestraByPalestra($id_palestra_session);
				
				$data_container = array();

				$data_container['debug'] = '';

				//$data_container['nome_palestra'] = $header['nome_palestra'];//$palestra->nome;


				$data_container['privilegi'] = $header['privilegi'];
				//$data_container['id_palestra'] = $header['id_palestra'];

				//$numero_per_pagina = 15;

				$start = ELEMENTI_PER_PAGINA*($numero_pagina-1);


				$i = $numero_pagina;
				do {
					$soci = array();
					switch($filter) {
						case 'new':
							$soci = $this->socio->getAllNewSociPalestra($id_palestra_session, $parametri_palestra->soglia_nuovi_soci, ELEMENTI_PER_PAGINA, $start);
							break;
						default:
							$soci = $this->socio->getAllSociPalestra($id_palestra_session, ELEMENTI_PER_PAGINA, $start);
							break;
					}
					$numero_soci = count($soci);

					$data_container['sub_titolo_pagina'] = "Lista dei soci della palestra";
					$data_container['bgcolor_counter'] = 'panel-primary';
					$data_container['testo_counter'] = "Numero soci sistema";


					// il ciclo e questo servono a fare il refresh dopo l'eliminazione dell'ultimo elemento di una pagina o ha visualizzare correttamente dopo la digitazione manuale di una pagina che non esiste
					$i--;
					//$start = ELEMENTI_PER_PAGINA*($i-1);
				} while( count($soci) < 1 && $i > 0 );

				if( $numero_pagina != ($i+1) ) {
					$numero_pagina = ($i+1);
				}

				$data_container['numero_soci'] = $numero_soci;
				//$data_container['filter'] = $filter;

				$numero_pagine = ceil($numero_soci/ELEMENTI_PER_PAGINA);

				$data_container['numero_pagine'] = $numero_pagine;
				$data_container['numero_pagina'] = $numero_pagina;





				$elenco_soci_completo = array();
				$i=0;
				if( count($soci) > 0 ) {
					foreach($soci as $socio) {
						$id_socio = $socio->id;
						
						$abbonamenti = $this->abbonamenti->getAllAbbonamentiSocio($id_socio);
						$numero_abbonamenti = count($abbonamenti);
						/*
						$abbonamenti_attivi = $this->abbonamenti->getAllAbbonamentiAttiviSocio($id_socio);
						$numero_abbonamenti_attivi = count($abbonamenti_attivi);
						
						$abbonamenti_validi = $this->abbonamenti->getAllAbbonamentiValidiSocio($id_socio);
						$numero_abbonamenti_validi = count($abbonamenti_validi);
						
						$abbonamenti_validi_e_attivi = $this->abbonamenti->getAllAbbonamentiAttiviEValidiSocio($id_socio);
						$numero_abbonamenti_validi_e_attivi = count($abbonamenti_validi_e_attivi);
						
						$abbonamenti_attivi_e_scaduti = $this->abbonamenti->getAllAbbonamentiAttiviScadutiSocio($id_socio);
						$numero_abbonamenti_attivi_e_scaduti = count($abbonamenti_attivi_e_scaduti);
						*/
						$now = time();
						$attivi = 0;
						$non_attivi = 0;
						$scaduti = 0;
						$validi = 0;
						if( $numero_abbonamenti > 0 ) {
							foreach($abbonamenti as $abbonamento ) {
								if( $abbonamento->attivo == 1 ) {
									$attivi++;
									if( $abbonamento->data_fine < $now ) {
										$scaduti++;
									} else {
										$validi++;
									}
								} else {
									$non_attivi++;
								}
							}
						}
						/*
						$pagamenti = $this->pagamenti->getAllPagamentiSocio($id_socio);
						$numero_pagamenti = count($pagamenti);
						*/
						$elenco_soci_completo[$i] = (object)[];
						$elenco_soci_completo[$i]->id = $socio->id;

						$elenco_soci_completo[$i]->nome = $socio->nome;
						$elenco_soci_completo[$i]->cognome = $socio->cognome;
						$elenco_soci_completo[$i]->data_iscrizione_str = date('d/m/Y', $socio->data_iscrizione);
						$elenco_soci_completo[$i]->email = $socio->email;
						$elenco_soci_completo[$i]->numero_abbonamenti = $numero_abbonamenti;
						$elenco_soci_completo[$i]->numero_abbonamenti_attivi = $attivi;//$numero_abbonamenti_attivi;
						$elenco_soci_completo[$i]->numero_abbonamenti_scaduti = $scaduti;
						$elenco_soci_completo[$i]->numero_abbonamenti_validi = $validi;
						$elenco_soci_completo[$i]->lock = ( $numero_abbonamenti > 0 ? true : false );
						//$elenco_soci_completo[$i]->numero_abbonamenti_validi_e_attivi = //$numero_abbonamenti_validi_e_attivi;
						//$elenco_soci_completo[$i]->numero_abbonamenti_attivi_e_scaduti = //$numero_abbonamenti_attivi_e_scaduti;

						$i++;

					}
				}
				$data_container['soci'] = $elenco_soci_completo;

				$data_container['id_palestra'] = $id_palestra_session;




				$this->load->view('header', $header);
				$this->load->view('lista_soci', $data_container);
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
	/*
	function getFormInsert() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$title = 'Inserimento nuovo Socio';
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			$id_palestra = $header['id_palestra'];
			$id_utente_loggato = $header['id_utente'];
						
			$metodo_insert = "";
			$dati = array();

			
			$professioni = $this->socio->getAllProfessioniPalestra($id_palestra);
			$option_professioni = '';
			if( count($professioni) > 0 ) {
				foreach($professioni as $professione) {
					$option_professioni .= '<option value="'.$professione->id.'">'.$professione->professione.'</option>\n';
				}
			}
			$option_professioni .= '<option value="">Altro</option>\n';
			$dati['option_professioni'] = $option_professioni;
			
			$soci_presentatori = $this->socio->getAllSociPalestra($id_palestra);
			$option_soci_presentatori = '<option value="">Nessuno</option>\n';
			if( count($soci_presentatori) > 0 ) {
				foreach($soci_presentatori as $socio_presentatore) {
					$option_soci_presentatori .= '<option value="'.$socio_presentatore->id.'">'.$socio_presentatore->nome.' '.$socio_presentatore->cognome.'</option>\n';
				}
			}
			$dati['option_soci_presentatori'] = $option_soci_presentatori;
			
			$elenco_desk = $this->personale->getAllDeskPalestra($id_palestra);
			$option_desk = '';
			if( count($elenco_desk) > 0 ) {
				foreach($elenco_desk as $desk) {
					$option_desk .= '<option value="'.$desk->id.'" '.( $id_utente_loggato == $desk->id ? 'selected' : '' ).'>'.$desk->nome.' '.$desk->cognome.' ('.$desk->username.')</option>\n';
				}
			}
			$dati['option_desk'] = $option_desk;
			
			$fonti_provenienza = $this->socio->getAllFontiPalestra($id_palestra);
			$option_fonti_provenienza = '';
			if( count($fonti_provenienza) > 0 ) {
				foreach($fonti_provenienza as $fonte_provenienza) {
					$option_fonti_provenienza .= '<option value="'.$fonte_provenienza->id.'">'.$fonte_provenienza->fonte.'</option>\n';
				}
			}
			$option_fonti_provenienza .= '<option value="">Altro</option>\n';
			$dati['option_fonti_provenienza'] = $option_fonti_provenienza;
			
			
			$motivazioni = $this->motivazioni->getAllMotivazioniPalestra($id_palestra);
			$option_motivazioni = '';
			if( count($motivazioni) > 0 ) {
				foreach($motivazioni as $motivazione) {
					$option_motivazioni .= '<option value="'.$motivazione->id.'">'.$motivazione->motivazione.'</option>\n';
				}
			}
			$option_motivazioni .= '<option value="">Altro</option>\n';
			$dati['option_motivazioni'] = $option_motivazioni;
			
			
			
			$dati['id_palestra'] = $id_palestra;

			$this->load->view('header', $header);
			$this->load->view('soci/form_insert_socio', $dati);
			$this->load->view('footer');
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	*/
	
	function getFormPrecompilatoInsert($id_rinnovo_iscrizione, $id_rinnovo_iscrizione_precedente = '', $come_back, $giorni_soglia_desk) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$title = 'Inserimento Dati Nuovo Socio';
			$controller_redirect = CURRENT_PAGE;
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$now = time();
			
			$rinnovo_iscrizione = $this->rinnovi_iscrizioni->getRinnovoIscrizione($id_rinnovo_iscrizione);
			if( $id_rinnovo_iscrizione_precedente == '' ) {
				$id_rinnovo_iscrizione_precedente = $rinnovo_iscrizione;
			}
			$rinnovo_iscrizione_precedente = $this->rinnovi_iscrizioni->getRinnovoIscrizione($id_rinnovo_iscrizione_precedente);
			
			$rinnovo_iscrizione->come_back = $come_back;
			$dati = array();
			$dati['rinnovo_iscrizione'] = $rinnovo_iscrizione;
			
			$id_palestra = $rinnovo_iscrizione->id_palestra;
			
			// CHECK proprietà iscrizione
				if( $rinnovo_iscrizione_precedente->id_tipo_abbonamento != '' && $rinnovo_iscrizione_precedente->free_pass == 1 ) {
					$abbonamento = $this->abbonamenti->getTipologiaAbbonamento($rinnovo_iscrizione_precedente->id_tipo_abbonamento);
					$durata_free_pass = $abbonamento->durata;
					$durata_free_pass_secondi = $durata_free_pass*86400;
					$giorni_gratuiti_socio = $abbonamento->giorni_gratuiti_socio;
				} else {
					$durata_free_pass_secondi = 0;
					$giorni_gratuiti_socio = 0;
				}

				$giorni_soglia_desk_secondi = $giorni_soglia_desk*86400;

				$soglia = $rinnovo_iscrizione_precedente->data_e_ora + $giorni_soglia_desk_secondi + $durata_free_pass_secondi;
				
				if( $soglia < $now ) {
					//scaduto
					$id_desk = $header['id_utente'];
				} else {
					//valido
					$id_desk = $rinnovo_iscrizione_precedente->id_consulente;
					
					if( $rinnovo_iscrizione_precedente->id_socio_presentatore != '' && $rinnovo_iscrizione_precedente->id_tipo_abbonamento != '' ) {
						//il socio presentatore può usufruire del bonus (il rinnovo/iscrizione che possedeva il free-pass non è scaduto)
						$socio_presentatore_free_pass = $this->socio->getSocio($rinnovo_iscrizione_precedente->id_socio_presentatore);
						$dati['socio_presentatore_free_pass'] = $socio_presentatore_free_pass;
						$dati['giorni_gratuiti_socio'] = $giorni_gratuiti_socio;
						$dati['id_tipo_abbonamento'] = $rinnovo_iscrizione_precedente->id_tipo_abbonamento;
						$dati['area_bonus'] = true;
					} else {
						$dati['area_bonus'] = false;
					}
				}
				$dati['id_consulente'] = $id_desk;
			

			$dati['msg_succes_insert'] = 'L\'inserimento è avvenuto con successo, essendo un\'iscrizione completata si può procedere ad inserire il socio nel sistema';

			//option_professioni
			$professioni = $this->socio->getAllProfessioniPalestra($id_palestra);
			$option_professioni = '';
			if( count($professioni) > 0 ) {
				foreach($professioni as $professione) {
					$option_professioni .= '<option value="'.$professione->id.'">'.$professione->professione.'</option>\n';
				}
			}
			//$option_professioni .= '<option value="">Altro</option>\n';
			$dati['option_professioni'] = $option_professioni;


			/*
			//option_soci_presentatori
			$soci_presentatori = $this->socio->getAllSociPalestra($id_palestra);
			$option_soci_presentatori = '<option value="">Nessuno</option>\n';
			if( count($soci_presentatori) > 0 ) {
				foreach($soci_presentatori as $socio_presentatore) {
					$option_soci_presentatori .= '<option value="'.$socio_presentatore->id.'">'.$socio_presentatore->nome.' '.$socio_presentatore->cognome.'</option>\n';
				}
			}
			$dati['option_soci_presentatori'] = $option_soci_presentatori;
			*/

			//$dati['id_consulente'] = $rinnovo_iscrizione->id_consulente;

			//option_fonti_provenienza
			$fonti_provenienza = $this->socio->getAllFontiPalestra($id_palestra);
			$option_fonti_provenienza = '';
			if( count($fonti_provenienza) > 0 ) {
				foreach($fonti_provenienza as $fonte_provenienza) {
					$option_fonti_provenienza .= '<option value="'.$fonte_provenienza->id.'">'.$fonte_provenienza->fonte.'</option>\n';
				}
			}
			//$option_fonti_provenienza .= '<option value="">Altro</option>\n';
			$dati['option_fonti_provenienza'] = $option_fonti_provenienza;


			//option_motivazioni //selected
			$motivazioni = $this->motivazioni->getAllMotivazioniPalestra($id_palestra);
			$option_motivazioni = '';
			if( count($motivazioni) > 0 ) {
				foreach($motivazioni as $motivazione) {
					$option_motivazioni .= '<option value="'.$motivazione->id.'" '.( $rinnovo_iscrizione->id_motivazione == $motivazione->id ? 'selected' : '' ).'>'.$motivazione->motivazione.'</option>\n';
				}
			}
			//$option_motivazioni .= '<option value="">Altro</option>\n';
			$dati['option_motivazioni'] = $option_motivazioni;


			//var_dump($dati);

			
			$this->load->view('header', $header);
			$this->load->view('soci/form_insert_socio_precompilato', $dati);
			$this->load->view('footer');

		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
	function getFormEdit($id_socio) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$socio = $this->socio->getSocio($id_socio);
			
			$title = 'Modifica Socio: '.$socio->nome.' '.$socio->cognome;
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			$id_palestra = $header['id_palestra'];
			$id_utente_loggato = $header['id_utente'];
						
			$metodo_insert = "";
			$dati = array();

			
			$professioni = $this->socio->getAllProfessioniPalestra($id_palestra);
			$option_professioni = '';
			if( count($professioni) > 0 ) {
				foreach($professioni as $professione) {
					$option_professioni .= '<option value="'.$professione->id.'" '.( $professione->id == $socio->id_professione ? 'selected' : '' ).'>'.$professione->professione.'</option>\n';
				}
			}
			$option_professioni .= '<option value="">Altro</option>\n';
			$dati['option_professioni'] = $option_professioni;
			
			$soci_presentatori = $this->socio->getAllSociPalestra($id_palestra);
			$option_soci_presentatori = '<option value="">Nessuno</option>\n';
			if( count($soci_presentatori) > 0 ) {
				foreach($soci_presentatori as $socio_presentatore) {
					if( $socio_presentatore->id != $socio->id ) {
						$option_soci_presentatori .= '<option value="'.$socio_presentatore->id.'" '.( $socio_presentatore->id == $socio->id_socio_presentatore ? 'selected' : '' ).'>'.$socio_presentatore->nome.' '.$socio_presentatore->cognome.'</option>\n';
					}
				}
			}
			$dati['option_soci_presentatori'] = $option_soci_presentatori;
			
			$elenco_desk = $this->personale->getAllDeskPalestra($id_palestra);
			$option_desk = '';
			if( count($elenco_desk) > 0 ) {
				foreach($elenco_desk as $desk) {
					$option_desk .= '<option value="'.$desk->id.'" '.( $socio->id_consulente == $desk->id ? 'selected' : '' ).'>'.$desk->nome.' '.$desk->cognome.' ('.$desk->username.')</option>\n';
				}
			}
			$dati['option_desk'] = $option_desk;
			
			$fonti_provenienza = $this->socio->getAllFontiPalestra($id_palestra);
			$option_fonti_provenienza = '';
			if( count($fonti_provenienza) > 0 ) {
				foreach($fonti_provenienza as $fonte_provenienza) {
					$option_fonti_provenienza .= '<option value="'.$fonte_provenienza->id.'" '.( $fonte_provenienza->id == $socio->id_fonte_provenienza ? 'selected' : '' ).'>'.$fonte_provenienza->fonte.'</option>\n';
				}
			}
			$option_fonti_provenienza .= '<option value="">Altro</option>\n';
			$dati['option_fonti_provenienza'] = $option_fonti_provenienza;
			
			
			$motivazioni = $this->motivazioni->getAllMotivazioniPalestra($id_palestra);
			$option_motivazioni = '';
			if( count($motivazioni) > 0 ) {
				foreach($motivazioni as $motivazione) {
					$option_motivazioni .= '<option value="'.$motivazione->id.'" '.( $motivazione->id == $socio->id_motivazione ? 'selected' : '' ).'>'.$motivazione->motivazione.'</option>\n';
				}
			}
			$option_motivazioni .= '<option value="">Altro</option>\n';
			$dati['option_motivazioni'] = $option_motivazioni;
			
			$socio->data_nascita_str = date('d/m/Y', $socio->data_nascita);
			
			$recapiti_telefonici = $this->recapiti_telefonici->getAllRecapitiSocio($id_socio);
			if( count($recapiti_telefonici) > 0 ) {
				foreach($recapiti_telefonici as $recapito_telefonico) {
					$recapito_telefonico->tipologia_str = $this->recapiti_telefonici->getTipologia($recapito_telefonico->id_tipologia_numero)->etichetta;
				}
			}
			
			$dati['id_palestra'] = $id_palestra;
			$dati['socio'] = $socio;
			$dati['recapiti_telefonici'] = $recapiti_telefonici;

			$this->load->view('header', $header);
			$this->load->view('soci/form_edit_socio', $dati);
			$this->load->view('footer');
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
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
			$this->load->view('soci/sub_form_add_recapito', $dati_recapito);
		} else {
			$dati['redirect_page'] = 'listasoci/getFormInsert';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function addRowContatto() {
		$post_result = $this->input->post();
		if( $post_result['id_tipologia_numero'] != '' ) {
			$post_result['tipologia_numero'] = $this->recapiti_telefonici->getTipologia($post_result['id_tipologia_numero'])->etichetta;
		} else {
			$post_result['tipologia_numero'] = $post_result['new_tipologia_numero'];
		}
		
		$this->load->view('soci/new_row_recapito_socio', $post_result);
	}
	
	function showSocio($id_socio) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$socio = $this->socio->getSocio($id_socio);
			
			// HEADER INITIALIZATION
			$title = 'Visualizzazione Socio: '.$socio->nome.' '.$socio->cognome;
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			
			$recapiti_telefonici = $this->recapiti_telefonici->getAllRecapitiSocio($id_socio);
			$abbonamenti_socio = $this->abbonamenti->getAllAbbonamentiSocio($id_socio);

			$dati_view = array();
			
			$socio->professione = $this->socio->getProfessione($socio->id_professione)->professione;
			if( $socio->id_socio_presentatore != '' ) {
				$socio->socio_presentatore = $this->socio->getSocio($socio->id_socio_presentatore);
			} else {
				$socio->socio_presentatore = null;
			}
			
			$socio->data_iscrizione_str = date('d/m/Y', $socio->data_iscrizione);
			$socio->data_nascita_str = date('d/m/Y', $socio->data_nascita);
			
			$socio->consulente = $this->personale->getUtente($socio->id_consulente);

			$socio->coordinatore = ( $socio->consulente->coordinatore == 0 ? $this->personale->getUtente($socio->consulente->id_coordinatore) : '' );//$socio->id_coordinatore);
			
			$socio->fonte_provenienza = $this->socio->getFonte($socio->id_fonte_provenienza)->fonte;
			$socio->motivazione = $this->motivazioni->getMotivazione($socio->id_motivazione)->motivazione;
			
			$dati_view['socio'] = $socio;
			
			if( count($recapiti_telefonici) > 0 ) {
				foreach( $recapiti_telefonici as $recapito_telefonico ) {
					$recapito_telefonico->tipologia_str = $this->recapiti_telefonici->getTipologia($recapito_telefonico->id_tipologia_numero)->etichetta;
				}
			}
			$dati_view['recapiti_telefonici'] = $recapiti_telefonici;
			
			$now = time();
			$almeno_uno_valido = false;
			if( count($abbonamenti_socio) > 0 ) {
				foreach( $abbonamenti_socio as $abbonamento_socio ) {
					$abbonamento_socio->tipo_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($abbonamento_socio->id_tipo_abbonamento)->tipo;
					$abbonamento_socio->stato = ( $abbonamento_socio->data_fine < $now ? false : true );
					$abbonamento_socio->data_inizio_str = date('d/m/Y', $abbonamento_socio->data_inizio);
					$abbonamento_socio->data_fine_str = date('d/m/Y', $abbonamento_socio->data_fine);
					$abbonamento_socio->pagamenti_abbonamento = $this->pagamenti->getAllPagamentiAbbonamento($abbonamento_socio->id);
					$totale_pagamenti = $this->pagamenti->getSumPagamentiAbbonamento($abbonamento_socio->id);
					$rimanente = $abbonamento_socio->valore_abbonamento - $totale_pagamenti;
					$abbonamento_socio->saldato = ( $rimanente > 0 ? false : true );
					$abbonamento_socio->lock = ( count($abbonamento_socio->pagamenti_abbonamento) > 0 ? true : false );
					
					
					
					if( $now > $abbonamento_socio->data_fine ) {
						$dati_update['attivo'] = 0;
						$this->abbonamenti->updateAbbonamento($abbonamento_socio->id, $dati_update);
					} else {
					
						if( $abbonamento_socio->attivo == 1 ) {
							$almeno_uno_valido = true;
						}
						
					}
				}
			}
			$dati_view['abbonamenti_socio'] = $abbonamenti_socio;
			
			
			$all_tipologie_abbonamenti_validi_attivi_socio = $this->abbonamenti->getAllTipologieAbbonamentiValidiAttiviSocio($id_socio);
			$dati_view['lock_insert_abbonamento'] = false;
			if( count($all_tipologie_abbonamenti_validi_attivi_socio) > 0 ) {
				$all_tipologie_abbonamento = $this->abbonamenti->getAllTipologieAbbonamentoPalestra($socio->id_palestra);
				if( count($all_tipologie_abbonamento) > 0 ) {
					$dati_view['lock_insert_abbonamento'] = true;
				}
			}
			
			// BONUS SOCIO
			$numero_bonus_socio = $this->bonus_socio->numberBonusSocio($id_socio);
			$dati_view['numero_bonus_socio'] = ( $almeno_uno_valido ? $numero_bonus_socio : 0 );
			
			$colloqui_verifica = $this->contatti->getAllColloquiVerificaSocio($id_socio);
			if( count($colloqui_verifica) > 0 ) {
				foreach( $colloqui_verifica as $colloquio ) {
					$colloquio->data = date('d/m/Y', $colloquio->data_e_ora);
					$colloquio->ora = date('H:i', $colloquio->data_e_ora);
					$desk = $this->personale->getUtente($colloquio->id_consulente);
					$colloquio->nome_desk = $desk->nome;
					$colloquio->cognome_desk = $desk->cognome;				
				}
			}
			$dati_view['colloqui_verifica'] = $colloqui_verifica;

			$telefonate = $this->contatti->getAllTelefonateSocio($id_socio);
			if( count($telefonate) > 0 ) {
				foreach( $telefonate as $telefonata ) {
					$telefonata->data = date('d/m/Y', $telefonata->data_e_ora);
					$telefonata->ora = date('H:i', $telefonata->data_e_ora);
					$desk = $this->personale->getUtente($telefonata->id_consulente);
					$telefonata->nome_desk = $desk->nome;
					$telefonata->cognome_desk = $desk->cognome;				
				}
			}
			$dati_view['telefonate'] = $telefonate;
			
			
			$rate = $this->rate->getAllRateSocio($id_socio);
			//$dati_view['rate_scadute'] = false;
			$numero_rate_scadute=0;
			if( count($rate) > 0 ) {
				foreach($rate as $rata)	{
					
					$pagato = $this->pagamenti->getSumPagamentiRata($rata->id);
					$residuo = $rata->valore_rata - $pagato;
					
					if( $now > $rata->data_scadenza && $residuo > 0 ) {
						//$dati_view['rate_scadute'] = true;
						$numero_rate_scadute++;
					}
				}
			}
			$dati_view['numero_rate_scadute'] = $numero_rate_scadute;
			
			//var_dump($dati_view);
			$this->load->view('header', $header);
			$this->load->view('soci/show_socio', $dati_view);
			$this->load->view('footer');
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function AskConfirmDelete($id_socio) {
		if( $this->user->controlloAutenticazione() ) {
			
			//$metodo_eliminazione = "listapalestre/deletePalestra";
			$socio = $this->socio->getSocio($id_socio);
			
			if( $socio != null ) {
				//$url_eliminazione = $metodo_eliminazione.'/'.$palestra->id;

				$dati['nome'] = $socio->nome;
				$dati['cognome'] = $socio->cognome;
				$dati['id_socio'] = $socio->id;

				$this->load->view('soci/confirm_delete_socio', $dati);

			} else {
				$msg = 'ERRORE! UTENTE NON TROVATO';
				$this->utility->errorDB($msg);
			}
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function searchSocio() {
		if( $this->user->controlloAutenticazione() ) {
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			$id_palestra = $session_login['id_palestra'];
			//echo '<script>alert("'.$id_palestra.'");</script>';
			
			if( $privilegi != 2 ) {
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
				$soci = $this->socio->searchSocio($parole_cercate, $id_palestra);
				
				$elenco_soci_completo = array();
				$i=0;
				if( count($soci) > 0 ) {
					foreach($soci as $socio) {
						$id_socio = $socio->id;
						
						$abbonamenti = $this->abbonamenti->getAllAbbonamentiSocio($id_socio);
						$numero_abbonamenti = count($abbonamenti);
						/*
						$abbonamenti_attivi = $this->abbonamenti->getAllAbbonamentiAttiviSocio($id_socio);
						$numero_abbonamenti_attivi = count($abbonamenti_attivi);
						
						$abbonamenti_validi = $this->abbonamenti->getAllAbbonamentiValidiSocio($id_socio);
						$numero_abbonamenti_validi = count($abbonamenti_validi);
						
						$abbonamenti_validi_e_attivi = $this->abbonamenti->getAllAbbonamentiAttiviEValidiSocio($id_socio);
						$numero_abbonamenti_validi_e_attivi = count($abbonamenti_validi_e_attivi);
						
						$abbonamenti_attivi_e_scaduti = $this->abbonamenti->getAllAbbonamentiAttiviScadutiSocio($id_socio);
						$numero_abbonamenti_attivi_e_scaduti = count($abbonamenti_attivi_e_scaduti);
						*/
						$now = time();
						$attivi = 0;
						$non_attivi = 0;
						$scaduti = 0;
						$validi = 0;
						if( $numero_abbonamenti > 0 ) {
							foreach($abbonamenti as $abbonamento ) {
								if( $abbonamento->attivo == 1 ) {
									$attivi++;
									if( $abbonamento->data_fine < $now ) {
										$scaduti++;
									} else {
										$validi++;
									}
								} else {
									$non_attivi++;
								}
							}
						}
						/*
						$pagamenti = $this->pagamenti->getAllPagamentiSocio($id_socio);
						$numero_pagamenti = count($pagamenti);
						*/
						$elenco_soci_completo[$i] = (object)[];
						$elenco_soci_completo[$i]->id = $socio->id;

						$elenco_soci_completo[$i]->nome = $socio->nome;
						$elenco_soci_completo[$i]->cognome = $socio->cognome;
						$elenco_soci_completo[$i]->data_iscrizione_str = date('d/m/Y', $socio->data_iscrizione);
						$elenco_soci_completo[$i]->email = $socio->email;
						$elenco_soci_completo[$i]->numero_abbonamenti = $numero_abbonamenti;
						$elenco_soci_completo[$i]->numero_abbonamenti_attivi = $attivi;//$numero_abbonamenti_attivi;
						$elenco_soci_completo[$i]->numero_abbonamenti_scaduti = $scaduti;
						$elenco_soci_completo[$i]->numero_abbonamenti_validi = $validi;
						$elenco_soci_completo[$i]->lock = ( $numero_abbonamenti > 0 ? true : false );
						//$elenco_soci_completo[$i]->numero_abbonamenti_validi_e_attivi = //$numero_abbonamenti_validi_e_attivi;
						//$elenco_soci_completo[$i]->numero_abbonamenti_attivi_e_scaduti = //$numero_abbonamenti_attivi_e_scaduti;

						$i++;

					}
				}
				$data_container['soci'] = $elenco_soci_completo;

				$this->load->view('soci/search_result_soci', $data_container);
			}
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
}
?>