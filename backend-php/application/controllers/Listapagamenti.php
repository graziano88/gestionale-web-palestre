<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class ListaPagamenti extends CI_Controller {
	
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
		//$this->load->model('motivazioni');
		//$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('abbonamenti');
		$this->load->model('pagamenti');
		$this->load->model('rate');
		$this->load->model('utility');
		$this->load->model('fatturePalestra', 'fatture_palestra');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'listasoci');
		define('ELEMENTI_PER_PAGINA', 15);
		
	}
	
	function showPagamento($id_pagamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$now = time();
			$pagamento = $this->pagamenti->getPagamento($id_pagamento);
			$socio = $this->socio->getSocio($pagamento->id_socio);
			$abbonamento = $this->abbonamenti->getAbbonamento($pagamento->id_abbonamento);
			$tipologia_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($abbonamento->id_tipo_abbonamento)->tipo;
			
			// HEADER INITIALIZATION
			$title = 'Visualizzazione Pagamento del socio: '.$socio->nome.' '.$socio->cognome;
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$dati_view = array();
			
			$pagamento->data = date('d/m/Y', $pagamento->data_ora);
			$pagamento->ora = date('H:i', $pagamento->data_ora);
			$pagamento->desk = $this->personale->getUtente($pagamento->id_desk);
			
			
			$dati_view['pagamento'] = $pagamento;
			
			$dati_view['id_socio'] = $socio->id;
			$dati_view['nome_socio'] = $socio->nome;
			$dati_view['cognome_socio'] = $socio->cognome;
			
			$dati_view['tipologia_abbonamento'] = $tipologia_abbonamento;
			
			//var_dump($dati_view);
			$this->load->view('header', $header);
			$this->load->view('pagamenti/show_pagamento', $dati_view);
			$this->load->view('footer');
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function getFormInsert($id_rata) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			// HEADER INITIALIZATION
			$rata = $this->rate->getRata($id_rata);
			$socio = $this->socio->getSocio($rata->id_socio);
			
			$title = 'Visualizzazione Pagamento del socio: '.$socio->nome.' '.$socio->cognome;
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$id_utente_loggato = $header['id_utente'];
			
			$dati_view = array();
			
			
			$dati_view['id_abbonamento'] = $rata->id_abbonamento;
			$dati_view['id_socio'] = $rata->id_socio;
			$dati_view['id_palestra'] = $rata->id_palestra;
			$dati_view['id_desk'] = $id_utente_loggato;
			$dati_view['id_rata'] = $id_rata;
			
			
			$pagato = $this->pagamenti->getSumPagamentiRata($id_rata);
			$dati_view['importo_pagato_default'] = $rata->valore_rata - $pagato;
			
			$dati_view['acconto'] = false;
			if( $dati_view['importo_pagato_default'] == $rata->valore_rata ) {
				$dati_view['acconto'] = true;
			}
			
			$elenco_desk = $this->personale->getAllDeskPalestra($rata->id_palestra);
			$option_desk = '';
			if( count($elenco_desk) > 0 ) {
				foreach($elenco_desk as $desk) {
					$option_desk .= '<option value="'.$desk->id.'" '.( $id_utente_loggato == $desk->id ? 'selected' : '' ).'>'.$desk->nome.' '.$desk->cognome.' ('.$desk->username.')</option>\n';
				}
			}
			$dati_view['option_desk'] = $option_desk;
			
			$this->load->view('header', $header);
			$this->load->view('pagamenti/form_insert_pagamento', $dati_view);
			$this->load->view('footer');
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function getFormEdit($id_pagamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$pagamento = $this->pagamenti->getPagamento($id_pagamento);
			
			$id_rata = $pagamento->id_rata;
			
			$rata = $this->rate->getRata($id_rata);
			$socio = $this->socio->getSocio($pagamento->id_socio);
			
			$title = 'Visualizzazione Pagamento del socio: '.$socio->nome.' '.$socio->cognome;
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$id_utente_loggato = $header['id_utente'];
			
			$dati_view = array();
			
			
			$dati_view['id_abbonamento'] = $pagamento->id_abbonamento;
			$dati_view['id_socio'] = $pagamento->id_socio;
			$dati_view['id_palestra'] = $pagamento->id_palestra;
			$dati_view['id_rata'] = $id_rata;
			
			
			$elenco_desk = $this->personale->getAllDeskPalestra($pagamento->id_palestra);
			$option_desk = '';
			if( count($elenco_desk) > 0 ) {
				foreach($elenco_desk as $desk) {
					$option_desk .= '<option value="'.$desk->id.'" '.( $id_utente_loggato == $desk->id ? 'selected' : '' ).'>'.$desk->nome.' '.$desk->cognome.' ('.$desk->username.')</option>\n';
				}
			}
			$dati_view['option_desk'] = $option_desk;
			
			$pagato = $this->pagamenti->getSumPagamentiRata($id_rata);
			$dati_view['importo_pagato_default'] = $rata->valore_rata - $pagato + $pagamento->importo_pagato;
			
			$pagamento->data = date('d/m/Y', $pagamento->data_ora);
			$pagamento->ora = date('H:i', $pagamento->data_ora);
			$pagamento->desk = $this->personale->getUtente($pagamento->id_desk);
			//$pagamento->data_prossimo_pagamento_str = date('d/m/Y', $pagamento->data_prossimo_pagamento);
			//$pagamento->scaduto = ( $pagamento->data_prossimo_pagamento < $now ? true : false);
			
			$dati_view['pagamento'] = $pagamento;
			
			//var_dump($dati_view);
			
			$this->load->view('header', $header);
			$this->load->view('pagamenti/form_edit_pagamento', $dati_view);
			$this->load->view('footer');
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function AskConfirmDelete($id_pagamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$pagamento = $this->pagamenti->getPagamento($id_pagamento);
			
			//$abbonamento = $this->abbonamenti->getAbbonamento($pagamento->id_abbonamento);
			$socio = $this->socio->getSocio($pagamento->id_socio);
						
			$dati_view = array();
			
			$dati_view['numero_ricevuta'] = $pagamento->numero_ricevuta;
			$dati_view['nome_socio'] = $socio->nome;
			$dati_view['cognome_socio'] = $socio->cognome;
			
			$dati_view['id_pagamento'] = $pagamento->id;
			$dati_view['id_rata'] = $pagamento->id_rata;
			
			$this->load->view('pagamenti/confirm_delete_pagamento', $dati_view);
			
		} else {
			$dati_view['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati_view);
		}
	}
	
}