<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class ContattiController extends CI_Controller {
 
	function __construct() {
		parent::__construct();
		
		// MODEL SEMPRE NECESSARI 
		$this->load->model('user','',TRUE);
		
		// MODEL NECESSARI IN QUESTO CONTROLLER 
		$this->load->model('personale');
		$this->load->model('palestra');
		$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('pagamenti');
		$this->load->model('socio');
		$this->load->model('abbonamenti');
		$this->load->model('contatti');
		$this->load->model('RinnoviIscrizioni', 'rinnovi_iscrizioni');
		$this->load->model('BonusSocio', 'bonus_socio');
		$this->load->model('utility');
		$this->load->library('upload');
	}
	
	function index() {}
	
	function creaColloquio() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();

			$colloquio_verifica = array();
			$errore = false;

			$colloquio_verifica['id'] = $this->utility->generateId('colloqui_verifica');
			$colloquio_verifica['id_socio'] = $post_result['id_socio'];
			$colloquio_verifica['data_e_ora'] = time();
			$colloquio_verifica['id_consulente'] = $post_result['id_consulente'];
			$colloquio_verifica['descrizione'] = $post_result['descrizione'];
			
			if( $this->contatti->insertColloquioVerifica($colloquio_verifica) ) {
				$this->load->view('contatti/success_insert_colloquio');
			} else {
				$this->utility->errorMsg("ERRORE DB", 'Errore durante l\'inserimento');
			}
			
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function creaTelefonata() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();

			$telefonata = array();
			$errore = false;

			$telefonata['id'] = $this->utility->generateId('telefonate');
			$telefonata['id_socio'] = $post_result['id_socio'];
			$telefonata['data_e_ora'] = time();
			$telefonata['id_consulente'] = $post_result['id_consulente'];
			$telefonata['motivo'] = $post_result['motivo'];
			$telefonata['esito'] = $post_result['esito'];
			
			if( $this->contatti->insertTelefonata($telefonata) ) {
				$this->load->view('contatti/success_insert_telefonata');
			} else {
				$this->utility->errorMsg("ERRORE DB", 'Errore durante l\'inserimento');
			}
			
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	/*
	function modificaColloquio() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();
			
			$id_socio = $post_result['id_socio'];
			$id_palestra = $post_result['id_palestra'];
			
			$dati_socio = array();
			
			$dati_socio['nome'] = ucwords($post_result['nome']);
			$dati_socio['cognome'] = ucwords($post_result['cognome']);
			
			$data_nascita = str_replace('/', '-', $post_result['data_nascita']);
			$dati_socio['data_nascita'] = strtotime($data_nascita);
			
			$dati_socio['sesso'] = $post_result['sesso'];
			$dati_socio['indirizzo'] = ucwords($post_result['indirizzo']);
			$dati_socio['cap'] = $post_result['cap'];
			$dati_socio['citta'] = ucwords($post_result['citta']);
			$dati_socio['provincia'] = strtoupper($post_result['provincia']);
			$dati_socio['email'] = $post_result['email'];
			
			$dati_socio['nato_a'] = ucwords($post_result['nato_a']);
			
			$dati_socio['id_professione'] = $post_result['id_professione'];
			
			//$dati_socio['id_socio_presentatore'] = $post_result['id_socio_presentatore'];
			
			//$dati_socio['id_consulente'] = $post_result['id_consulente'];
			
			
			$dati_socio['id_fonte_provenienza'] = $post_result['id_fonte_provenienza'];
			$dati_socio['id_motivazione'] = $post_result['id_motivazione'];
			
			
			if( $this->socio->updateSocio($id_socio, $dati_socio) ) {
				$this->recapiti_telefonici->deleteAllRecapitiSocio($id_socio);
				// insert new recapiti
				if( isset($post_result['numero']) ) {
					for($i=0; $i<count($post_result['numero']); $i++) {
						$nuovo_contatto = array();
						if( $post_result['id_tipologia_numero'][$i] == '' ) {
							// insert new tipologia numero
							$nuovo_contatto['id_tipologia_numero'] = $this->utility->addNewTipologiaNumero($id_palestra, $post_result['new_tipologia_numero'][$i]);

						} else {
							$nuovo_contatto['id_tipologia_numero'] = $post_result['id_tipologia_numero'][$i];
						}

						if( $nuovo_contatto['id_tipologia_numero'] ) {

							$nuovo_contatto['id'] = $this->utility->generateId('recapiti_telefonici_soci');
							$nuovo_contatto['id_socio'] = $id_socio;
							$nuovo_contatto['numero'] = $post_result['numero'][$i];

							if( !$this->recapiti_telefonici->insertRecapitoSocio($nuovo_contatto) ) {
								$this->utility->errorDB('ERRORE INSERIMENTO RECAPITO SOCIO');
							}
						}
					}
				}
				
				$dati['nome'] = $dati_socio['nome'];
				$dati['cognome'] = $dati_socio['cognome'];
				$this->load->view('soci/success_edit_socio', $dati);

			}
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	*/
	function deleteColloquio($id_colloquio) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			//$dati = array();
			//$colloquio = $this->contatti->getColloquioVerifica($id_colloquio);
			
			if( $this->contatti->deleteColloquioVerifica($id_colloquio) ) {
				$this->load->view('contatti/success_delete_colloquio');
			} else {
				$this->utility->errorMsg("ERRORE DB", 'Errore durante l\'eliminazione del colloquio');
			}
			
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function deleteTelefonata($id_telefonata) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			
			//$dati = array();
			//$colloquio = $this->contatti->getColloquioVerifica($id_colloquio);
			
			if( $this->contatti->deleteTelefonata($id_telefonata) ) {
				$this->load->view('contatti/success_delete_telefonata');
			} else {
				$this->utility->errorMsg("ERRORE DB", 'Errore durante l\'eliminazione della telefonata');
			}
			
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	
 
}
 
?>