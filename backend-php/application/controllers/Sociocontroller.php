<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class SocioController extends CI_Controller {
 
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
	
	function creaSocio() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();

			$socio = array();
			$errore = false;

			$socio['id'] = $this->utility->generateId('soci_palestra');
			
			$socio['id_palestra'] = $post_result['id_palestra'];
			$socio['data_iscrizione'] = time();
			
			$socio['nome'] = ucwords($post_result['nome']);
			$socio['cognome'] = ucwords($post_result['cognome']);
			
			$data_nascita = str_replace('/', '-', $post_result['data_nascita']);
			$socio['data_nascita'] = strtotime($data_nascita);
			
			$socio['sesso'] = $post_result['sesso'];
			$socio['indirizzo'] = ucwords($post_result['indirizzo']);
			$socio['cap'] = $post_result['cap'];
			$socio['citta'] = ucwords($post_result['citta']);
			$socio['provincia'] = strtoupper($post_result['provincia']);
			$socio['email'] = $post_result['email'];
			
			$socio['nato_a'] = ucwords($post_result['nato_a']);
			
			$socio['id_professione'] = $post_result['id_professione'];
			
			$socio['id_socio_presentatore'] = '';//$post_result['id_socio_presentatore'];
			
			$socio['id_consulente'] = $post_result['id_consulente'];
			
			/* DISABILITATO IL COORDINATORE SI OTTIENE DAL CONSULENTE
			$id_coordinatore = $this->personale->getCoordinatoreByCoordinato($socio['id_consulente']);
			$socio['id_coordinatore'] = ( $id_coordinatore != '' ? $id_coordinatore : $socio['id_consulente'] );
			*/
			
			$socio['id_fonte_provenienza'] = $post_result['id_fonte_provenienza'];
			$socio['id_motivazione'] = $post_result['id_motivazione'];
			
			
			if( $this->socio->insertSocio($socio) ) {
				if( isset($post_result['numero']) ) {
					for($i=0; $i<count($post_result['numero']); $i++) {
						$nuovo_contatto = array();
						if( $post_result['id_tipologia_numero'][$i] == '' ) {
							// insert new tipologia numero
							$nuovo_contatto['id_tipologia_numero'] = $this->addNewTipologiaNumero($socio['id_palestra'], $post_result['new_tipologia_numero'][$i]);

						} else {
							$nuovo_contatto['id_tipologia_numero'] = $post_result['id_tipologia_numero'][$i];
						}

						if( $nuovo_contatto['id_tipologia_numero'] ) {

							$nuovo_contatto['id'] = $this->utility->generateId('recapiti_telefonici_soci');
							$nuovo_contatto['id_socio'] = $socio['id'];
							$nuovo_contatto['numero'] = $post_result['numero'][$i];

							if( !$this->recapiti_telefonici->insertRecapitoSocio($nuovo_contatto) ) {
								$this->utility->errorDB('ERRORE INSERIMENTO RECAPITO SOCIO');
							}
						}
					}
				}
				$dati_update_by_concatenazione = array();
				// GESTIONE BONUS
				if( isset($post_result['id_socio_presentatore']) ) {
					$id_socio_presentatore = $post_result['id_socio_presentatore'];
					if( $id_socio_presentatore != '' ) {
						
						$array_bonus = array();
						
						$array_bonus['id'] = $this->utility->generateId('bonus_socio');
						$array_bonus['id_socio'] = $id_socio_presentatore;
						$array_bonus['id_palestra'] = $post_result['id_palestra'];
						$array_bonus['numero_giorni_bonus'] = $post_result['numero_giorni_bonus_socio_presentatore'];
						$array_bonus['id_tipo_abbonamento'] = $post_result['id_tipo_abbonamento'];
						
						$this->bonus_socio->insertBonus($array_bonus);
						
						$dati_update_by_concatenazione['blocco_scadenza_freepass'] = 1;
					}					
				}
				
				// GESTIONE TABELLA RINNOVI/ISCRIZIONI
				if( isset($post_result['id_rinnovo_iscrizione']) ) {
					if( $post_result['id_rinnovo_iscrizione'] != '' ) {
						
						$dati_rinnovo_iscrizione['id_socio_registrato'] = $socio['id'];
						if( $post_result['come_back'] == 1 ) {
							$dati_rinnovo_iscrizione['come_back'] = 1;
						}
						$dati_rinnovo_iscrizione['missed'] = 0;
						
						if( $this->rinnovi_iscrizioni->updateRinnovoIscrizione($post_result['id_rinnovo_iscrizione'], $dati_rinnovo_iscrizione) ) {
							
							$dati_update_by_concatenazione['id_socio_registrato'] = $socio['id'];
							$dati_update_by_concatenazione['mostra'] = 0;
							$this->rinnovi_iscrizioni->updateAllRinnoviIscrizioniByConcatenazione($post_result['id_concatenazione'], $dati_update_by_concatenazione);
							
						}
						
						
					}
				}
				/*			
				$dati['nome'] = $socio['nome'];
				$dati['cognome'] = $socio['cognome'];
				$this->load->view('soci/success_insert_socio', $dati);
				*/
				redirect(base_url().'listasoci/showSocio/'.$socio['id'], 'refresh');
				
			} else {

				$this->errorDB("ERRORE INSERIMENTO SOCIO");
			}
			
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function modificaSocio() {
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
			
			/* DISABILITATO IL COORDINATORE SI OTTIENE DAL CONSULENTE
			$id_coordinatore = $this->personale->getCoordinatoreByCoordinato($dati_socio['id_consulente']);
			$dati_socio['id_coordinatore'] = ( $id_coordinatore != '' ? $id_coordinatore : $dati_socio['id_consulente'] );
			*/
			
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
		
	function deleteSocio($id_socio = '') {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$socio = $this->socio->getSocio($id_socio);
			$dati['nome'] = $socio->nome;
			$dati['cognome'] = $socio->cognome;

			//elimina socio da presentazioni
			if( $this->socio->updateSocioPresentatore($id_socio, '') ) {

				//elimina i recapiti telefonici del socio
				if( $this->recapiti_telefonici->deleteAllRecapitiSocio($id_socio) ) {

					//elimina tutti i colloqui di verifica fatti al socio
					if( $this->contatti->deleteAllColloquiVerificaSocio($id_socio) ) {
						//elimina tutte le telefonate fatta al socio
						if( $this->contatti->deleteAllTelefonateSocio($id_socio) ) {
							//elimina tutti i contatti che ci sono stati con il socio
							if( $this->contatti->deleteAllContattiSocio($id_socio) ) {
								//elimina pagamenti del socio
								if( $this->pagamenti->deleteAllPagamentiSocio($id_socio) ) {
									//elimina abbonamenti del socio
									if( $this->abbonamenti->deleteAllAbbonamentiSocio($id_socio) ) {
										//elimina i riferimenti in rinnovo/iscrizione
										if( $this->rinnovi_iscrizioni->removeRiferimentiSocio($id_socio) ) {
											//elimina eventali bonus
											if( $this->bonus_socio->deleteAllBonusSocio($id_socio) ) {
												//ELIMINA DEFINITIVAMENTE SOCIO
												if( $this->socio->deleteSocio($id_socio) ) {
													$this->load->view('soci/success_delete_socio', $dati);
												}
											} else {
												//ERRORE ELIMINAZIONE BONUS
											}
										}
									} else {
										//ERRORE ELIMINAZIONE ABBONAMENTI
									}
								} else {
									// ERRORE ELIMINAZIONE PAGAMENTI
								}
							} else {
								//ERRORE ELIMINAZIONE CONTATTI AVUTI CON IL SOCIO
							}
						} else {
							//ERRORE ELIMINAZIONE TELEFONATE FATTE AL SOCIO
						}
					} else {
						//ERRORE ELIMINAZIONE COLLOQUI DI VERIFICA
					}
				} else {
					//ERRORE ELIMINAZIONE RECAPITI TELEFONICI
				}			
			} else {
				//ERRORE ELIMINAZIONE DA SOCI PRESENTATORI
			}
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	
 
}
 
?>