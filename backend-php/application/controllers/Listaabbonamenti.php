<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class Listaabbonamenti extends CI_Controller {
	
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
		$this->load->model('BonusSocio', 'bonus_socio');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'listasoci');
		define('ELEMENTI_PER_PAGINA', 15);
		
	}
	
	function showAbbonamento($id_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$now = time();
			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);
			$socio = $this->socio->getSocio($abbonamento->id_socio);
			
			// HEADER INITIALIZATION
			$title = 'Visualizzazione Abbonamento di: '.$socio->nome.' '.$socio->cognome;
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			
			$dati_view = array();
			
			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);
			
			$socio = $this->socio->getSocio($abbonamento->id_socio);
			
			$abbonamento->nome_socio = $socio->nome;
			$abbonamento->cognome_socio = $socio->cognome;
			
			$abbonamento->tipo_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($abbonamento->id_tipo_abbonamento)->tipo;
			
			$abbonamento->data_inizio_str = date('d/m/Y', $abbonamento->data_inizio);
			$abbonamento->data_fine_str = date('d/m/Y', $abbonamento->data_fine);
			$durata = ($abbonamento->data_fine - $abbonamento->data_inizio)/86400;
			$abbonamento->durata = floor($durata);
			
			$abbonamento->scaduto = ( $now > $abbonamento->data_fine ? true : false );
			
			
			
			$dati_view['abbonamento'] = $abbonamento;
			
			
			$rate = $this->rate->getAllRateAbbonamento($id_abbonamento);
			
			$dati_view['lock_delete_rate'] = false;
			$residuo_totale_da_pagare = $abbonamento->valore_abbonamento;
			$dati_view['rate_da_saldare'] = array();
			$dati_view['rate_saldate'] = array();
			$numero_rate_scadute = 0;
			
			$dati_view['workout_lock'] = true;
			if( count($rate) > 0 ) {
				$dati_view['workout_lock'] = false;
				/*
				//MODIFICA PER SPLIT RATE TRA SALDATE E DA SALDARE
				
				*/
				$i = 0;
				
				foreach( $rate as $rata ) {
					
					if( $this->pagamenti->getNumberPagamentiRata($rata->id) > 0 ) {
						$dati_view['lock_delete_rate'] = true;
					}
					
					$pagato = $this->pagamenti->getSumPagamentiRata($rata->id);
					$residuo = $rata->valore_rata - $pagato;
					
					$residuo_totale_da_pagare -= $pagato;
					
					$rata->pagato = $pagato;
					$rata->residuo = $residuo;
					
					if( $now > $rata->data_scadenza && $residuo > 0 ) {
						$rata->scaduta = true;
						$numero_rate_scadute++;
					} else {
						$rata->scaduta = false;
					}
					
					$rata->data_scadenza_str = date('d/m/Y', $rata->data_scadenza);
					$rata->numero_sequenziale_romano = $this->utility->romanic_number($rata->numero_sequenziale);
					
					
					/*
					//MODIFICA PER SPLIT RATE TRA SALDATE E DA SALDARE
					*/
					if( $rata->residuo > 0 ) {
						$dati_view['rate_da_saldare'][$i] = $rata;
					} else {
						$dati_view['rate_saldate'][$i] = $rata;
					}
					
					
					$i++;
					
				}
			}
			
			$dati_view['rate'] = $rate;
			
			$dati_view['numero_rate_scadute'] = $numero_rate_scadute;
			$dati_view['residuo_da_pagare'] = $residuo_totale_da_pagare;
			
			// BONUS SOCIO
			$numero_bonus_socio = $this->bonus_socio->numberBonusSocio($abbonamento->id_socio);
			$dati_view['numero_bonus_socio'] = $numero_bonus_socio;
			
			
			$this->load->view('header', $header);
			$this->load->view('abbonamenti/show_abbonamento', $dati_view);
			$this->load->view('footer');
			//var_dump($dati_view);
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function getFormInsert($id_socio) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$now = time();
			$socio = $this->socio->getSocio($id_socio);
			
			// HEADER INITIALIZATION
			$title = 'Inserimento Abbonamento per: '.$socio->nome.' '.$socio->cognome;
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$id_palestra = $header['id_palestra'];
			$dati_view = array();
			
			$dati_view['id_socio'] = $id_socio;
			$dati_view['id_palestra'] = $id_palestra;
			
			$all_tipologie_abbonamenti_validi_attivi_socio = $this->abbonamenti->getAllTipologieAbbonamentiValidiAttiviSocio($id_socio);
			
			$tipi_abbonamento = $this->abbonamenti->getAllTipologieAbbonamentoNotFreePassPalestra($id_palestra);
			$option_tipi_abbonamento = '';
			if( count($tipi_abbonamento) > 0 ) {
				foreach($tipi_abbonamento as $tipo_abbonamento) {
					$lock = false;
					for($i=0; $i<count($all_tipologie_abbonamenti_validi_attivi_socio); $i++) {
						if( $all_tipologie_abbonamenti_validi_attivi_socio[$i] == $tipo_abbonamento->id ) {
							$lock = true;
						}
					}
					if(!$lock) {
						$option_tipi_abbonamento .= '<option value="'.$tipo_abbonamento->id.'">'.$tipo_abbonamento->tipo.'</option>\n';
					}
				}
			}
			$dati_view['option_tipi_abbonamento'] = $option_tipi_abbonamento;
			
			$this->load->view('header', $header);
			$this->load->view('abbonamenti/form_insert_abbonamento', $dati_view);
			$this->load->view('footer');
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function getFormEdit($id_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$now = time();
			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);
			$socio = $this->socio->getSocio($abbonamento->id_socio);
			
			// HEADER INITIALIZATION
			$title = 'Modifica Abbonamento di: '.$socio->nome.' '.$socio->cognome;
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$dati_view = array();
			
			$abbonamento->nome_socio = $socio->nome;
			$abbonamento->cognome_socio = $socio->cognome;
			
			$abbonamento->data_inizio_str = date('d/m/Y', $abbonamento->data_inizio);
			$abbonamento->data_fine_str = date('d/m/Y', $abbonamento->data_fine);
			$abbonamento->durata = (($abbonamento->data_fine - $abbonamento->data_inizio)/86400)-1;
			
			$dati_view['abbonamento'] = $abbonamento;
			
			$tipi_abbonamento = $this->abbonamenti->getAllTipologieAbbonamentoNotFreePassPalestra($abbonamento->id_palestra);
			$option_tipi_abbonamento = '';
			if( count($tipi_abbonamento) > 0 ) {
				foreach($tipi_abbonamento as $tipo_abbonamento) {
					$option_tipi_abbonamento .= '<option value="'.$tipo_abbonamento->id.'" '.( $tipo_abbonamento->id == $abbonamento->id_tipo_abbonamento ? 'selected' : '' ).'>'.$tipo_abbonamento->tipo.'</option>\n';
				}
			}
			$dati_view['option_tipi_abbonamento'] = $option_tipi_abbonamento;
			
			//var_dump($dati_view);
			$this->load->view('header', $header);
			$this->load->view('abbonamenti/form_edit_abbonamento', $dati_view);
			$this->load->view('footer');
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}	
	}
	
	function getInfoTipoAbbonamento($id_tipologia_abbonamento) {
		
		$tipologia_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($id_tipologia_abbonamento);
		
		echo json_encode($tipologia_abbonamento);
		
	}
	
	function AskConfirmDelete($id_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
			
			//$metodo_eliminazione = "listapalestre/deletePalestra";
			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);
			$socio = $this->socio->getSocio($abbonamento->id_socio);
			
			if( $abbonamento != null ) {
				//$url_eliminazione = $metodo_eliminazione.'/'.$palestra->id;
				
				$dati['id_socio'] = $socio->id;
				$dati['nome_socio'] = $socio->nome;
				$dati['cognome_socio'] = $socio->cognome;
				$dati['id_abbonamento'] = $abbonamento->id;
				$dati['tipo_abbonamento'] = $this->abbonamenti->getTipologiaAbbonamento($abbonamento->id_tipo_abbonamento)->tipo;

				$this->load->view('abbonamenti/confirm_delete_abbonamento', $dati);

			} else {
				$msg = 'ERRORE! ABBONAMENTO NON TROVATO';
				$this->utility->errorDB($msg);
			}
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function AskBonusEAbbonamento($id_socio) {
		if( $this->user->controlloAutenticazione() ) {
			
			//$metodo_eliminazione = "listapalestre/deletePalestra";
			$abbonamenti_validi_socio = $this->abbonamenti->getAllAbbonamentiAttiviEValidiSocio($id_socio);
			
			$bonus_socio = $this->bonus_socio->getAllBonusSocio($id_socio);
			
			$socio = $this->socio->getSocio($id_socio);
			
			if( $abbonamenti_validi_socio != null ) {
				
				$dati['option_abbonamento'] = '';
				if( count($abbonamenti_validi_socio) ) {
					foreach( $abbonamenti_validi_socio as $abbonamento_valido_socio ) {
						$tipo_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($abbonamento_valido_socio->id_tipo_abbonamento)->tipo;
						$dati['option_abbonamento'] .= '<option value="'.$abbonamento_valido_socio->id.'">'.$tipo_abbonamento.'</option>';
					}
				}
				
				$dati['option_bonus'] = '';
				if( count($bonus_socio) ) {
					foreach( $bonus_socio as $singolo_bonus ) {
						$dati['option_bonus'] .= '<option value="'.$singolo_bonus->id.'">Bonus da '.$singolo_bonus->numero_giorni_bonus.' giorni</option>';
					}
				}
				
				$dati['nome_socio'] = $socio->nome;
				$dati['cognome_socio'] = $socio->cognome;
				
				$this->load->view('abbonamenti/scegli_bonus_e_abbonamento', $dati);

			} else {
				$msg = 'ERRORE! ABBONAMENTO NON TROVATO';
				$this->utility->errorDB($msg);
			}
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function AskBonus($id_socio, $id_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
			
			//$metodo_eliminazione = "listapalestre/deletePalestra";
			
			$bonus_socio = $this->bonus_socio->getAllBonusSocio($id_socio);
			
			$socio = $this->socio->getSocio($id_socio);
			
				
			$dati['id_abbonamento'] = $id_abbonamento;

			$dati['option_bonus'] = '';
			if( count($bonus_socio) ) {
				foreach( $bonus_socio as $singolo_bonus ) {
					$dati['option_bonus'] .= '<option value="'.$singolo_bonus->id.'">Bonus da '.$singolo_bonus->numero_giorni_bonus.' giorni</option>';
				}
			}

			$dati['nome_socio'] = $socio->nome;
			$dati['cognome_socio'] = $socio->cognome;

			$this->load->view('abbonamenti/scegli_bonus', $dati);

		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
}
?>