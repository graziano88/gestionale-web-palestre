<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class Pagamentocontroller extends CI_Controller {
	
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
		$this->load->model('fatturePalestra', 'fatture_palestra');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'listasoci');
		define('ELEMENTI_PER_PAGINA', 15);
		
	}
	
	function insertPagamento() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$post_result = $this->input->post();
			
			
			$array_insert = array();
			
			$array_insert = $post_result;
			
			$id_palestra = $post_result['id_palestra'];
			$id_abbonamento = $post_result['id_abbonamento'];
			
			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);
			
			//var_dump($post_result);
			$anno = $this->utility->getCurrentYear();
			if( $this->utility->checkAnnoRicevutePalestra($id_palestra, $anno) ) {
				$data_ora = time();
				$numero_ricevuta = $this->fatture_palestra->getNumerazioneFatturePalestra($id_palestra, $anno)+1;
				
				//$pagato_finora = $this->pagamenti->getSumPagamentiAbbonamento($id_abbonamento);
				
				//$residuo_da_pagare = $abbonamento->valore_abbonamento - $pagato_finora - $post_result['importo_pagato'];
				
				$array_insert['id'] = $this->utility->generateId('pagamenti_socio');
				$array_insert['id_rata'] = $post_result['id_rata'];
				$array_insert['id_socio'] = $post_result['id_socio'];
				$array_insert['id_abbonamento'] = $post_result['id_abbonamento'];
				$array_insert['id_palestra'] = $post_result['id_palestra'];
				$array_insert['data_ora'] = $data_ora;
				$array_insert['id_desk'] = $post_result['id_desk'];
				$array_insert['importo_pagato'] = $post_result['importo_pagato'];
				$array_insert['numero_ricevuta'] = $post_result['numero_ricevuta'];//$numero_ricevuta;
				
				/*
				$array_insert['residuo_da_pagare'] = $residuo_da_pagare;
				if( $residuo_da_pagare == 0 ) {
					$array_insert['tipo'] = 1;
					$array_insert['data_prossimo_pagamento'] = 0;
				} else {
					$array_insert['tipo'] = 0;
					$data_prossimo_pagamento = str_replace('/', '-', $post_result['data_prossimo_pagamento']);
					$array_insert['data_prossimo_pagamento'] = strtotime($data_prossimo_pagamento);
				}
				*/
				if( $this->pagamenti->insertPagamento($array_insert) ) {
					//$this->fatture_palestra->addFatturaPalestra($id_palestra, $anno);
					$this->load->view('pagamenti/success_insert_pagamento');
				} else {
					//ERRORE INSERT
					errorMsg('ERRORE DB', 'ERRORE NELL\'INSERIMENTO DEL PAGAMENTO');
				}
				//var_dump($array_insert);
			} else {
				//ERRORE Ricevuta
				errorMsg('ERRORE DB', 'ERRORE NEL SISTEMA DELLE RICEVUTE');
			}
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function editPagamento() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$post_result = $this->input->post();
						
			$id_abbonamento = $post_result['id_abbonamento'];
			$id_pagamento = $post_result['id'];
			
			$pagamento = $this->pagamenti->getPagamento($id_pagamento);
			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);
			
			$array_update = array();
			
			$array_update['numero_ricevuta'] = $post_result['numero_ricevuta'];
			$array_update['importo_pagato'] = $post_result['importo_pagato'];
			
			
			if( $this->pagamenti->updatePagamento($id_pagamento, $array_update) ) {
				$this->load->view('pagamenti/success_edit_pagamento');
			} else {
				//ERRORE EDIT
				errorMsg('ERRORE DB', 'ERRORE NELLA MODIFICA DEL PAGAMENTO');
			}
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function deletePagamento($id_pagamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$pagamento = $this->pagamenti->getPagamento($id_pagamento);
			$socio = $this->socio->getSocio($pagamento->id_socio);
			
			$dati_view = array();
			
			$dati_view['nome_socio'] = $socio->nome;
			$dati_view['cognome_socio'] = $socio->cognome;
			$dati_view['numero_ricevuta'] = $pagamento->numero_ricevuta;
			
			//DELETE PAGAMENTO
			if( $this->pagamenti->deletePagamento($id_pagamento) ) {
				$this->load->view('pagamenti/success_delete_pagamento', $dati_view);
			} else {
				//ERRORE DELETE
				errorMsg('ERRORE DB', 'Eliminazione del pagamento non riuscita');
			}
		} else {
			$dati_view['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati_view);
		}
	}
	
}
