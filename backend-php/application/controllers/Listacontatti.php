<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class Listacontatti extends CI_Controller {
	
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
	
	
	function getFormInsertColloquio($id_socio) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$title = 'Inserimento nuovo Colloquio di verifica';
			$controller_redirect = 'listasoci/showSocio'.$id_socio;
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			$id_palestra = $header['id_palestra'];
			$id_utente_loggato = $header['id_utente'];
						
			$metodo_insert = "";
			$dati = array();
			
			if( $privilegi != 3 ) {
				$elenco_desk = $this->personale->getAllDeskPalestra($id_palestra);
				$option_desk = '';
				if( count($elenco_desk) > 0 ) {
					foreach($elenco_desk as $desk) {
						$option_desk .= '<option value="'.$desk->id.'" '.( $id_utente_loggato == $desk->id ? 'selected' : '' ).'>'.$desk->cognome.' '.$desk->nome.' ('.$desk->username.')</option>\n';
					}
				}
				$dati['option_desk'] = $option_desk;
			} else {
				$dati['desk_loggato'] = $this->personale->getUtente($id_utente_loggato);
			}
			
			$dati['id_socio'] = $id_socio;
			$dati['privilegi'] = $privilegi;

			$this->load->view('header', $header);
			$this->load->view('contatti/form_insert_colloquio', $dati);
			$this->load->view('footer');
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
	function getFormInsertTelefonata($id_socio) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$title = 'Inserimento nuovo Colloquio di verifica';
			$controller_redirect = 'listasoci/showSocio'.$id_socio;
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$privilegi = $header['privilegi'];
			$id_palestra = $header['id_palestra'];
			$id_utente_loggato = $header['id_utente'];
						
			$metodo_insert = "";
			$dati = array();
			
			if( $privilegi != 3 ) {
				$elenco_desk = $this->personale->getAllDeskPalestra($id_palestra);
				$option_desk = '';
				if( count($elenco_desk) > 0 ) {
					foreach($elenco_desk as $desk) {
						$option_desk .= '<option value="'.$desk->id.'" '.( $id_utente_loggato == $desk->id ? 'selected' : '' ).'>'.$desk->cognome.' '.$desk->nome.' ('.$desk->username.')</option>\n';
					}
				}
				$dati['option_desk'] = $option_desk;
			} else {
				$dati['desk_loggato'] = $this->personale->getUtente($id_utente_loggato);
			}
			
			$dati['id_socio'] = $id_socio;
			$dati['privilegi'] = $privilegi;

			$this->load->view('header', $header);
			$this->load->view('contatti/form_insert_telefonata', $dati);
			$this->load->view('footer');
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
		
	}
	
	/*
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
	*/
	
	/*
	function showColloquio($id_socio) {
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
					$desk = $this->utente->getUtente($colloquio->id_consulente);
					$colloquio->nome_desk = $desk->nome;
					$colloquio->cognome_desk = $desk->cognome;				
				}
			}
			$dati_view['colloqui_verifica'] = $colloqui_verifica;

			$telefonate = $this->contatti->getAllColloquiVerificaSocio($id_socio);
			if( count($telefonate) > 0 ) {
				foreach( $telefonate as $telefonata ) {
					$telefonata->data = date('d/m/Y', $telefonata->data_e_ora);
					$telefonata->ora = date('H:i', $telefonata->data_e_ora);
					$desk = $this->utente->getUtente($telefonata->id_consulente);
					$telefonata->nome_desk = $desk->nome;
					$telefonata->cognome_desk = $desk->cognome;				
				}
			}
			$dati_view['telefonate'] = $telefonate;
			
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
	*/
	
	function AskConfirmDeleteColloquio($id_colloquio) {
		if( $this->user->controlloAutenticazione() ) {
			
			//$metodo_eliminazione = "listapalestre/deletePalestra";
			$colloquio = $this->contatti->getColloquioVerifica($id_colloquio);
			
			if( $colloquio != null ) {
				//$url_eliminazione = $metodo_eliminazione.'/'.$palestra->id;

				
				$dati['id_colloquio'] = $colloquio->id;
				$dati['id_socio'] = $colloquio->id_socio;

				$this->load->view('contatti/confirm_delete_colloquio', $dati);

			} else {
				$msg = 'ERRORE! COLLOQUIO NON TROVATO';
				$this->utility->errorDB($msg);
			}
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	// DA FARE
	function AskConfirmDeleteTelefonata($id_telefonata) {
		if( $this->user->controlloAutenticazione() ) {
			
			//$metodo_eliminazione = "listapalestre/deletePalestra";
			$telefonata = $this->contatti->getTelefonata($id_telefonata);
			
			if( $telefonata != null ) {
				//$url_eliminazione = $metodo_eliminazione.'/'.$palestra->id;

				
				$dati['id_telefonata'] = $telefonata->id;
				$dati['id_socio'] = $telefonata->id_socio;

				$this->load->view('contatti/confirm_delete_telefonata', $dati);

			} else {
				$msg = 'ERRORE! COLLOQUIO NON TROVATO';
				$this->utility->errorDB($msg);
			}
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
}
?>