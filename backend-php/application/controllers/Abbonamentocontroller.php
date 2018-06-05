<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class AbbonamentoController extends CI_Controller {
 
	function __construct() {
		parent::__construct();
		
		// MODEL SEMPRE NECESSARI 
		$this->load->model('user','',TRUE);
		
		// MODEL NECESSARI IN QUESTO CONTROLLER 
		$this->load->model('personale');
		$this->load->model('palestra');
		$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('bonusSocio', 'bonus_socio');
		$this->load->model('pagamenti');
		$this->load->model('socio');
		$this->load->model('abbonamenti');
		$this->load->model('contatti');
		$this->load->model('utility');
		$this->load->library('upload');
	}
	
	function insertAbbonamento() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$post_result = $this->input->post();
			//var_dump($post_result);
			
			$dati_abbonamento = $post_result;
			$dati_abbonamento['id'] = $this->utility->generateId('abbonamenti_socio');
			
			$data_inizio = str_replace('/', '-', $dati_abbonamento['data_inizio']);
			$dati_abbonamento['data_inizio'] = strtotime($data_inizio);
			$data_fine = str_replace('/', '-', $dati_abbonamento['data_fine']);
			$dati_abbonamento['data_fine'] = strtotime($data_fine);
			
			if( $this->abbonamenti->insertAbbonamento($dati_abbonamento) ) {
				/*
				$this->load->view('abbonamenti/success_insert_abbonamento');
				*/
				redirect(base_url().'listaabbonamenti/showAbbonamento/'.$dati_abbonamento['id'], 'refresh');
			} else {
				// ERRORE INSERIMENTO ABBONAMENTO
			}
			
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function editAbbonamento() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$post_result = $this->input->post();
			//var_dump($post_result);
			
			$dati_abbonamento = $post_result;
			
			$id_abbonamento = $dati_abbonamento['id'];
			
			$data_inizio = str_replace('/', '-', $dati_abbonamento['data_inizio']);
			$dati_abbonamento['data_inizio'] = strtotime($data_inizio);
			$data_fine = str_replace('/', '-', $dati_abbonamento['data_fine']);
			$dati_abbonamento['data_fine'] = strtotime($data_fine);
			
			//var_dump($dati_abbonamento);
			
			if( $this->abbonamenti->updateAbbonamento($id_abbonamento, $dati_abbonamento) ) {
				$this->load->view('abbonamenti/success_edit_abbonamento');
			} else {
				// ERRORE INSERIMENTO ABBONAMENTO
			}
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function deleteAbbonamento($id_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);
			$socio = $this->socio->getSocio($abbonamento->id_socio);
			
			$dati_view = array();
			
			$dati_view['nome_socio'] = $socio->nome;
			$dati_view['cognome_socio'] = $socio->cognome;
			
			$pagamenti_abbonamento = $this->pagamenti->getAllPagamentiAbbonamento($id_abbonamento);
			if( count($pagamenti_abbonamento) <= 0 ) {
				if( $this->abbonamenti->deleteAbbonamento($id_abbonamento) ) {

					$this->load->view('abbonamenti/success_delete_abbonamento', $dati_view);

				} else {
					// DELETE FALLITA
				}
			} else {
				// ERRORE NON SI PUO' ELIMINARE UN ABBONAMENTO CON DEI PAGAMENTI EFFETTUATI
			}
			
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function changeStatoAbbonamento($id_abbonamento, $new_stato) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$dati_update['attivo'] = $new_stato;
			echo $this->abbonamenti->updateAbbonamento($id_abbonamento, $dati_update);
			
		} else {
			$url = 'login/login/listasoci';
			redirect($url, 'refresh');
		}
	}
	
	function applicaBonus() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$post_result = $this->input->post();
			
			$id_abbonamento = $post_result['id_abbonamento'];
			$id_bonus = $post_result['id_bonus'];
			
			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);
			$bonus = $this->bonus_socio->getBonus($id_bonus);
			
			$id_socio = $bonus->id_socio;
			
			$durata_bonus = $bonus->numero_giorni_bonus;
			$durata_bonus_secondi = $durata_bonus*$this->utility->SECONDI_PER_GIORNO;
			
			$update_abbonamento['data_fine'] = $abbonamento->data_fine+$durata_bonus_secondi;
			
			if( $this->abbonamenti->updateAbbonamento($id_abbonamento, $update_abbonamento) ) {
				if( $this->bonus_socio->deleteBonus($id_bonus) ) {
					redirect('listaabbonamenti/showAbbonamento/'.$id_abbonamento, 'refresh');
				} else {
					//ERRORE DELETE BONUS USATO
				}
			} else {
				//ERRORE UPDATE ABBONAMENTO
			}
			
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
}
?>