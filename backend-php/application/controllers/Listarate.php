<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class Listarate extends CI_Controller {
	
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
		$this->load->model('rate');
		//$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('abbonamenti');
		$this->load->model('pagamenti');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'listasoci');
		define('ELEMENTI_PER_PAGINA', 15);
		
	}
	
	function showRata($id_rata) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$now = time();
			$rata = $this->rate->getRata($id_rata);
			$socio = $this->socio->getSocio($rata->id_socio);
			
			// HEADER INITIALIZATION
			$title = 'Visualizzazione Rata di: '.$socio->nome.' '.$socio->cognome;
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			
			$dati_view = array();
			
			$pagamenti = $this->pagamenti->getAllPagamentiRata($id_rata);
			
			$importo_pagato = $this->pagamenti->getSumPagamentiRata($id_rata);
			
			$rata->pagato = $importo_pagato;
			$rata->residuo = $rata->valore_rata - $importo_pagato;
			$rata->data_scadenza_str = date('d/m/Y', $rata->data_scadenza);
			$rata->numero_rata_romano = $this->utility->romanic_number($rata->numero_sequenziale);
			
			$rata->scaduta = ( $now > $rata->data_scadenza && $rata->residuo > 0 ? true : false );
			
			$dati_view['rata'] = $rata;
			
			$dati_view['nome_socio'] = $socio->nome;
			$dati_view['cognome_socio'] = $socio->cognome;
			
			$dati_view['lock_insert_pagamento'] = false;
			if( count($pagamenti) > 0 ) {
				$first = true;
				foreach( $pagamenti as $pagamento ) {
					$pagamento->data = date('d/m/Y', $pagamento->data_ora);
					$pagamento->ora = date('H:i', $pagamento->data_ora);
					$pagamento->desk = $this->personale->getUtente($pagamento->id_desk);
					
					/*
					if( $pagamento->data_prossimo_pagamento != 0 ) {
						$pagamento->data_prossimo_pagamento_str = date('d/m/Y', $pagamento->data_prossimo_pagamento);
					} else {
						$pagamento->data_prossimo_pagamento_str = '-';
					}
					$pagamento->scaduto = ( $pagamento->data_prossimo_pagamento < $now ? true : false);
					if( $pagamento->residuo_da_pagare == 0 ) {
						$dati_view['lock_insert_pagamento'] = true;
					}
					*/
					$pagamento->lock = ( $first ? false : true );
					$first = false;
				}
			}
			
			$dati_view['pagamenti'] = $pagamenti;
			
			
			$this->load->view('header', $header);
			$this->load->view('rate/show_rata', $dati_view);
			$this->load->view('footer');
			//var_dump($dati_view);
			
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	function getFormInsert($id_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			$now = time();
			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);
			$abbonamento->tipo_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($abbonamento->id_tipo_abbonamento)->tipo;
			
			$socio = $this->socio->getSocio($abbonamento->id_socio);
			
			// HEADER INITIALIZATION
			$title = 'Inserimento Rate per l\'abbonamento '.$abbonamento->tipo_abbonamento.' del socio: '.$socio->nome.' '.$socio->cognome;
			$controller_redirect = 'listasoci';
			$header = $this->header_model->getHeader($title, $controller_redirect);
			
			$id_palestra = $header['id_palestra'];
			$dati_view = array();
			
			$dati_view['id_socio'] = $socio->id;
			$dati_view['id_palestra'] = $id_palestra;
			$dati_view['id_abbonamento'] = $id_abbonamento;
			$dati_view['valore_abbonamento'] = $abbonamento->valore_abbonamento;
			
			
			
			$this->load->view('header', $header);
			$this->load->view('rate/form_insert_rata', $dati_view);
			$this->load->view('footer');
			
		} else {
			//If no session, redirect to login page
			$url = 'login/login/'.CURRENT_PAGE;
			redirect($url, 'refresh');
		}
	}
	
	/*
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
			
			$tipi_abbonamento = $this->abbonamenti->getAllTipologieAbbonamentoPalestra($abbonamento->id_palestra);
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
	*/
	
	function AskConfirmDelete($id_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
							
			$dati['id_abbonamento'] = $id_abbonamento;
				
			$this->load->view('rate/confirm_delete_rate', $dati);

		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function getFormRate() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();
			
			$now = time();
			
			if( $post_result['numero_rate'] == '' ) {
				$numero_rate = $post_result['numero_rate_manuale'];
			} else {
				$numero_rate = $post_result['numero_rate'];
			}
			
			if( $post_result['giorni_scadenza'] == '' ) {
				$durata_rate = $post_result['giorni_scadenza_manuale'];
			} else {
				$durata_rate = $post_result['giorni_scadenza'];
			}
			
			$valore_abbonamento = $post_result['valore_abbonamento'];
			
			$minimo_dividendo = $this->utility->minimoDividendoDivisibile($valore_abbonamento, $numero_rate);
			$valore_rata = $minimo_dividendo / $numero_rate;
			$plus_valore_ultima_rata = $valore_abbonamento - $minimo_dividendo;
			
			$durata_rate_unix = $durata_rate*86400;
			$scadenza_unix = $now + $durata_rate_unix;
			
			for($i=0; $i<$numero_rate; $i++) {
				
				// imposto il subform di una rata
				
				if($i == $numero_rate-1 ) {
					$tipo = 1; //saldo
					$dati['valore_rata'] = $valore_rata + $plus_valore_ultima_rata;
				} else {
					$tipo = 0; //acconto
					$dati['valore_rata'] = $valore_rata;
				}
				$dati['numero_rata'] = $i+1;
				$dati['numero_rata_romano'] = $this->utility->romanic_number($i+1);
				$dati['tipo'] = $tipo;
				$dati['scadenza_rata_str'] = date('d/m/Y', $scadenza_unix);
				
				//var_dump($dati);
				$this->load->view('rate/sub_form_insert_rata', $dati);
				$scadenza_unix += $durata_rate_unix;
				
			}
			
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	 
}
?>