<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class RataController extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		/* MODEL SEMPRE NECESSARI */
		$this->load->model('user','',TRUE);
		
		/* MODEL NECESSARI ALL'HEADER_MODEL */
		$this->load->model('personale');
		$this->load->model('palestra');
		$this->load->model('HeaderModel', 'header_model');
		
		/* MODEL NECESSARI IN QUESTO CONTROLLER */
		$this->load->model('rate');
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
	
	function insertRata() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$post_result = $this->input->post();
			
			//var_dump($post_result);
			
			$array_insert_uguale = array();
			
			
			//variabili comuni tra rate
			$array_insert_uguale['id_abbonamento'] = $post_result['id_abbonamento'];
			$array_insert_uguale['id_socio'] = $post_result['id_socio'];
			$array_insert_uguale['id_palestra'] = $post_result['id_palestra'];
			$array_insert_uguale['per'] = $post_result['per'];
			
			$array_tipi = $post_result['tipo'];
			$array_valori_rata = $post_result['valore_rata'];
			$array_date_scadenza = $post_result['data_scadenza'];
			
			$array_id_rate = array();
			
			for($i=0; $i<count($array_tipi); $i++) {
				$array_insert = array();
				
				$array_insert = $array_insert_uguale;
				$array_insert['numero_sequenziale'] = $i+1;
				$array_insert['tipo'] = $array_tipi[$i];
				$array_insert['valore_rata'] = $array_valori_rata[$i];
				$data_normale = str_replace('/', '-', $array_date_scadenza[$i]);
				$array_insert['data_scadenza'] = strtotime($data_normale);
				
				$array_insert['id'] = $this->utility->generateId('rate');
				$array_id_rate[$i] = $array_insert['id'];
				$this->rate->insertRata($array_insert);
			}
			
			//alla fine se c'è successo redirect to showRata(id_prima_rata)
			redirect(base_url().'listarate/showRata/'.$array_id_rate[0], 'refresh');
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	/*
	function editPagamento() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$post_result = $this->input->post();
			
			$id_abbonamento = $post_result['id_abbonamento'];
			$id_pagamento = $post_result['id'];
			
			$pagamento = $this->pagamenti->getPagamento($id_pagamento);
			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);
			
			$array_update = array();
			
			$array_update['importo_pagato'] = $post_result['importo_pagato'];
			
			$pagato_finora = $this->pagamenti->getSumPagamentiAbbonamento($id_abbonamento);
			
			$differenza_importo_pagamento = $pagamento->importo_pagato - $post_result['importo_pagato'];
			
			$pagato_finora_aggiornato = $pagato_finora - $differenza_importo_pagamento;
			
			$residuo_da_pagare = $abbonamento->valore_abbonamento - $pagato_finora_aggiornato;
			$array_update['residuo_da_pagare'] = $residuo_da_pagare;
			
			if( $residuo_da_pagare == 0 ){
				$array_update['tipo'] = 1;
				$array_update['data_prossimo_pagamento'] = 0;
			} else {
				$array_update['tipo'] = 0;
				$data_prossimo_pagamento = str_replace('/', '-', $post_result['data_prossimo_pagamento']);
				$array_update['data_prossimo_pagamento'] = strtotime($data_prossimo_pagamento);
			}
			
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
	*/
	
	function deleteRate($id_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			
			//DELETE PAGAMENTO
			if( $this->rate->deleteAllRateAbbonamento($id_abbonamento) ) {
				$this->load->view('rate/success_delete_rate');
			} else {
				//ERRORE DELETE
				errorMsg('ERRORE DB', 'Eliminazione delle rate non riuscita');
			}
		} else {
			$dati_view['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati_view);
		}
	}
	
}
