<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class Rinnoviiscrizionicontroller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		/* MODEL SEMPRE NECESSARI */
		$this->load->model('user','',TRUE);
		
		/* MODEL NECESSARI ALL'HEADER_MODEL */
		$this->load->model('personale');
		$this->load->model('palestra');
		$this->load->model('HeaderModel', 'header_model');
		
		/* MODEL NECESSARI IN QUESTO CONTROLLER */
		$this->load->model('RinnoviIscrizioni', 'rinnovi_iscrizioni');
		$this->load->model('motivazioni');
		$this->load->model('socio');
		//$this->load->model('motivazioni');
		//$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('abbonamenti');
		$this->load->model('pagamenti');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'listarinnoviiscrizioni');
		define('ELEMENTI_PER_PAGINA', 15);
		
	}
	
	function creaRinnovoIscrizione() {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 3 ) { //|| $privilegi == 0 ) {
				
				$post_result = $this->input->post();
				$data_ora = time();

				//var_dump($post_result);
				
				$id_palestra = $post_result['id_palestra'];
				
				$parametri_palestra = $this->palestra->getParametriPalestraByPalestra($id_palestra);

				$rinnovo_iscrizione = array();

				
				
				//$rinnovo_iscrizione = $post_result;

				$rinnovo_iscrizione['id'] = $this->utility->generateId('rinnovi_e_iscrizioni');
				$rinnovo_iscrizione['id_palestra'] = $post_result['id_palestra'];
				$rinnovo_iscrizione['tipo'] = $post_result['tipo'];
				$rinnovo_iscrizione['nome'] = ucwords($post_result['nome']);
				$rinnovo_iscrizione['cognome'] = ucwords($post_result['cognome']);
				$id_socio_registrato = $post_result['id_socio_registrato'];
				$id_concatenazione = $post_result['id_concatenazione'];
				if( $id_concatenazione == '' ) {
					$id_concatenazione = $this->utility->generateIdConcatenazione('rinnovi_e_iscrizioni');
				}
				$rinnovo_iscrizione['id_concatenazione'] = $id_concatenazione;
				
				$rinnovo_iscrizione['cellulare'] = $post_result['cellulare'];
				$rinnovo_iscrizione['telefono'] = $post_result['telefono'];
				$rinnovo_iscrizione['email'] = $post_result['email'];
				$rinnovo_iscrizione['free_pass'] = $post_result['free_pass'];
				$rinnovo_iscrizione['id_tipo_abbonamento'] = $post_result['id_tipo_abbonamento'];
				$rinnovo_iscrizione['id_motivazione'] = $post_result['id_motivazione'];
				$rinnovo_iscrizione['note'] = $post_result['note'];
				$rinnovo_iscrizione['id_socio_registrato'] = $post_result['id_socio_registrato'];
				$rinnovo_iscrizione['id_socio_presentatore'] = $post_result['id_socio_presentatore'];
				
				$missed = $post_result['missed'];
				
				$rinnovo_iscrizione['missed'] = ( $post_result['free_pass'] == 1 ? 0 : 1 );
				
				$rinnovo_iscrizione['data_e_ora'] = $data_ora;
				//$rinnovo_iscrizione['id_consulente'] = $desk->id;
				if( $post_result['scaduto'] == 0 ) {
					$desk = $this->personale->getUtente($post_result['id_consulente']);
				} else {
					$desk = $this->personale->getUtente($session_login['id_utente']);
				}
				$rinnovo_iscrizione['id_consulente'] = $desk->id;
				$rinnovo_iscrizione['id_coordinatore'] = $desk->id_coordinatore;	
				
				
				$come_back = $post_result['come_back'];
				$rinnovo_iscrizione['come_back'] = 0;
				if( $id_socio_registrato != '' && $missed == 0 ) {
					$rinnovo_iscrizione['mostra'] = 0;
				} else {
					$rinnovo_iscrizione['mostra'] = 1;
				}
				
				//var_dump($rinnovo_iscrizione);
				
				
				//var_dump($rinnovo_iscrizione);
				if( $this->rinnovi_iscrizioni->insertRinnovoIscrizione($rinnovo_iscrizione) ) {
					if( $rinnovo_iscrizione['free_pass'] == 0 ) {
						if( $id_socio_registrato == '' ) {
							// utente non tra i soci
							
							if( $missed == 0 ) {
								// non è missed
								// carico modulo precompilato per inserire il socio, se l'inserimento ha successo il missed in rinnovi/iscrizioni verrà settato a 0, mostra=0 su tutti i concatenati, id_socio_registrato su tutti i concatenati
								
								// al modulo passerò l'id_concanetazione e il valore di $come_back
								
								
								// GENERERATE FORM INSERT SOCIO
								$giorni_soglia_desk = $parametri_palestra->scadenza_missed;
								if( $post_result["id_rinnovo_iscrizione_passata"] == "") {
									$id_rinnovo_iscrizione_passata = $rinnovo_iscrizione['id'];
								} else {
									$id_rinnovo_iscrizione_passata = $post_result["id_rinnovo_iscrizione_passata"];
								}
								redirect(base_url().'listasoci/getFormPrecompilatoInsert/'.$rinnovo_iscrizione['id'].'/'.$id_rinnovo_iscrizione_passata.'/'.$come_back.'/'.$giorni_soglia_desk);
								//$this->getFormPrecompilato($rinnovo_iscrizione, $desk, $id_concatenazione, $come_back);
								
							} else {
								// è un missed quindi ho finito l'inserimento
								// solo inserimento rinnovo/iscrizione, stampare successo
								$this->returnRedirect('success');
							}
						} else {
							// utente già registrato
							
							// SE MISSED == 0 MODIFICO MISSED A 0 E MOSTRA A 0 IN TUTTI I CONCATENATI
							if( $missed == 0 ) {
								
								$dati_update['missed'] = 0;
								$this->rinnovi_iscrizioni->updateRinnovoIscrizione($rinnovo_iscrizione['id'], $dati_update);
								
								$dati_update_by_concatenazione['mostra'] = 0;
								$this->rinnovi_iscrizioni->updateAllRinnoviIscrizioniByConcatenazione($id_concatenazione, $dati_update_by_concatenazione);
								
							}
							
							//CARICA LA PAGINA DEL SOCIO CON UN REDIRECT listasoci/showSocio/id_socio_registrato
							redirect(base_url().'listasoci/showSocio/'.$id_socio_registrato, 'refresh');
						}
					} else {
						// solo inserimento rinnovo/iscrizione, stampare successo
						/*
						$id_desk = $desk->id;
						$free_pass = array();
						$free_pass['id'] = $this->utility->generateId('free_pass');
						$free_pass['id_palestra'] = $id_palestra;
						$free_pass['id_desk'] = $id_desk;						
						$free_pass['id_rinnovo_iscrizione'] = $rinnovo_iscrizione['id'];
						$free_pass['data_ora'] = $data_ora;
						
						if( $this->rinnovi_iscrizioni->insertFreePass($free_pass) ) {
							$this->returnRedirect('success');
						} else {
							//ERRORE INSERT FREE PASS
							$this->returnRedirect('failed');
						}*/
						$this->returnRedirect('success');
					}
				} else {
					// ERRORE DI INSERIMENTO
					$this->returnRedirect('failed');
				}
			
			} else {
				redirect('home', 'refresh');
			}
		} else {
			$dati['redirect_page'] = 'listarinnoviiscrizioni';
			$this->load->view('login_popoup_view', $dati);
		}
	}	
	
	function comeBackRinnovoIscrizione($id_rinnovo_iscrizione) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				
				
				if( $privilegi == 0 ) {
					$id_desk = '';
				} else {
					$id_desk = $session_login['id_utente'];
				}
				
				// si cambia MISSED in 0 e COME_BACK in 1 nella tabella rinnovi_iscrizioni
				$update_array = array();
				$update_array['missed'] = 0;
				$update_array['come_back'] = 1;
				
				if( $this->rinnovi_iscrizioni->updateRinnovoIscrizione($id_rinnovo_iscrizione, $update_array) ) {
					
					$rinnovo_iscrizione = $this->rinnovi_iscrizioni->getRinnovoIscrizione($id_rinnovo_iscrizione);
					
					$id_palestra = $rinnovo_iscrizione->id_palestra;
					
					//si dirotta su crea socio (come nel caso del missed=1 in creaRinnovoIscrizione)
					$dati['msg_succes_insert'] = 'L\'inserimento è avvenuto con successo, essendo un\'iscrizione Come Back si può procedere ad inserire il socio nel sistema';

					$dati['id'] = $rinnovo_iscrizione->id;
					$dati['id_palestra'] = $id_palestra;
					$dati['nome'] = $rinnovo_iscrizione->nome;
					$dati['cognome'] = $rinnovo_iscrizione->cognome;
					$dati['cellulare'] = $rinnovo_iscrizione->cellulare;
					$dati['telefono'] = $rinnovo_iscrizione->telefono;
					$dati['email'] = $rinnovo_iscrizione->email;
					
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


					//option_soci_presentatori
					$soci_presentatori = $this->socio->getAllSociPalestra($id_palestra);
					$option_soci_presentatori = '<option value="">Nessuno</option>\n';
					if( count($soci_presentatori) > 0 ) {
						foreach($soci_presentatori as $socio_presentatore) {
							$option_soci_presentatori .= '<option value="'.$socio_presentatore->id.'">'.$socio_presentatore->nome.' '.$socio_presentatore->cognome.'</option>\n';
						}
					}
					$dati['option_soci_presentatori'] = $option_soci_presentatori;

					
					//option_desk //selected
					$elenco_desk = $this->personale->getAllDeskPalestra($id_palestra);
					$option_desk = '';
					if( count($elenco_desk) > 0 ) {
						foreach($elenco_desk as $desk_sel) {
							$option_desk .= '<option value="'.$desk_sel->id.'" '.( $id_desk == $desk_sel->id ? 'selected' : '' ).'>'.$desk_sel->nome.' '.$desk_sel->cognome.' ('.$desk_sel->username.')</option>\n';
						}
					}
					$dati['option_desk'] = $option_desk;
					
					$dati['id_consulente'] = $session_login['id_utente'];

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


					$title = 'Inserimento Dati Nuovo Socio';
					$controller_redirect = CURRENT_PAGE;

					$header = $this->header_model->getHeader($title, $controller_redirect);
					$this->load->view('header', $header);
					$this->load->view('rinnovi_iscrizioni/form_insert_socio_precompilato', $dati);
					$this->load->view('footer');
					
				} else {
					// ERRORE UPDATE RINNOVO/ISCRIZIONE
				}
				
				
			} else {
				redirect('home', 'refresh');
			}
			
		} else {
			$dati['redirect_page'] = 'listarinnoviiscrizioni';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function returnRedirect($type_return) {
		redirect('listarinnoviiscrizioni/p/1/a/'.$type_return, 'refresh');
	}
	
	function deleteRinnovoIscrizione($id_rinnovo_iscrizione) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA

			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				
				if( $this->rinnovi_iscrizioni->deleteFreePassByRinnovoIscrizione($id_rinnovo_iscrizione) ) {
					if( $this->rinnovi_iscrizioni->deleteRinnovoIscrizione($id_rinnovo_iscrizione) ) {

						$this->load->view('rinnovi_iscrizioni/success_delete_rinnovo_iscrizione');

					} else {
						// ERRORE ELIMINAZIONE RINNOVO/ISCRIZIONE
					}
				} else {
					// ERRORE FREE PASS REGISTRATO
				}
			} else {
				redirect('home', 'refresh');
			}
			
		} else {
			$dati['redirect_page'] = 'listarinnoviiscrizioni';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	function annullaRinnovoIscrizione($id_rinnovo_iscrizione) {
		if( $this->user->controlloAutenticazione() ) {
			// AREA AUTENTICATA

			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi == 3 || $privilegi == 0 ) {
				
				if( $this->rinnovi_iscrizioni->deleteFreePassByRinnovoIscrizione($id_rinnovo_iscrizione) ) {
					if( $this->rinnovi_iscrizioni->deleteRinnovoIscrizione($id_rinnovo_iscrizione) ) {

						redirect('listarinnoviiscrizioni', 'refresh');

					} else {
						// ERRORE ELIMINAZIONE RINNOVO/ISCRIZIONE
					}
				} else {
					// ERRORE FREE PASS REGISTRATO
				}
			} else {
				redirect('home', 'refresh');
			}
			
		} else {
			$dati['redirect_page'] = 'listarinnoviiscrizioni';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	
	// FUNZIONI DI APPOGGIO
	/* RIVISTO TUTTO IL PROCESSO
	function getFormPrecompilato($rinnovo_iscrizione, $desk, $id_concatenazione, $come_back) {
		$dati = $rinnovo_iscrizione;
		
		$id_palestra = $rinnovo_iscrizione['id_palestra'];
		
		$dati['id_concatenazione'] = $id_concatenazione;
		$dati['come_back'] = $come_back;
		
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


		//option_soci_presentatori
		$soci_presentatori = $this->socio->getAllSociPalestra($id_palestra);
		$option_soci_presentatori = '<option value="">Nessuno</option>\n';
		if( count($soci_presentatori) > 0 ) {
			foreach($soci_presentatori as $socio_presentatore) {
				$option_soci_presentatori .= '<option value="'.$socio_presentatore->id.'">'.$socio_presentatore->nome.' '.$socio_presentatore->cognome.'</option>\n';
			}
		}
		$dati['option_soci_presentatori'] = $option_soci_presentatori;


		$dati['id_consulente'] = $desk->id;

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
				$option_motivazioni .= '<option value="'.$motivazione->id.'" '.( $rinnovo_iscrizione['id_motivazione'] == $motivazione->id ? 'selected' : '' ).'>'.$motivazione->motivazione.'</option>\n';
			}
		}
		//$option_motivazioni .= '<option value="">Altro</option>\n';
		$dati['option_motivazioni'] = $option_motivazioni;


		$title = 'Inserimento Dati Nuovo Socio';
		$controller_redirect = CURRENT_PAGE;
		//var_dump($dati);
		
		$header = $this->header_model->getHeader($title, $controller_redirect);
		$this->load->view('header', $header);
		$this->load->view('rinnovi_iscrizioni/form_insert_socio_precompilato', $dati);
		$this->load->view('footer');
	}
	*/
}	