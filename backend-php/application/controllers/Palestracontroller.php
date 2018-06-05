<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class PalestraController extends CI_Controller {
 
	function __construct() {
		parent::__construct();
		
		/* MODEL SEMPRE NECESSARI */
		$this->load->model('user','',TRUE);
		
		/* MODEL NECESSARI IN QUESTO CONTROLLER */
		$this->load->model('palestra');
		$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('contatti');
		$this->load->model('contratto');
		
		/* MODEL NECESSARI PER LA DELETE PALESTRA */
		$this->load->model('personale');
		$this->load->model('socio'); 
		$this->load->model('vociBudget', 'voci_budget');
		$this->load->model('abbonamenti');
		$this->load->model('BonusSocio', 'bonus_socio');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		$this->load->library('upload');
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		// NESSUNA
		
	}
	
	function creaPalestra() {
		$post_result = $this->input->post();
		
		$palestra = array();
		
		$palestra['id'] = $this->utility->generateId('palestre');
		
		$palestra['ragione_sociale'] = ucwords($post_result['ragione_sociale']);
		$palestra['nome'] = ucwords($post_result['nome']);
		
		$attiva_dal = str_replace('/', '-', $post_result['attiva_dal']);
		$palestra['attiva_dal'] = strtotime($attiva_dal);
		
		$attiva_al = str_replace('/', '-', $post_result['attiva_al']);
		$palestra['attiva_al'] = strtotime($attiva_al);
		
		
		
		$debug = '';
		
		$palestra['immagine_logo'] = '';
		if(isset($_FILES['immagine_logo']['name'])) {
			$logo_name = $palestra['id'];
			$config = array(
				'file_name' => $logo_name,
				'upload_path' => "./loghi_uploads/",
				'allowed_types' => "gif|jpg|png|jpeg|pdf",
				'overwrite' => TRUE,
				'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
			);
			
			$this->upload->initialize($config);
			if( $this->upload->do_upload('immagine_logo') ) {
				$data = array('upload_data' => $this->upload->data());
				$palestra['immagine_logo'] = $data['upload_data']['file_name'];
			}
			else {
				$error = array('error' => $this->upload->display_errors());
				$debug = $this->upload->display_errors();
				echo "ERRRORE UPLOAD LOGO PALESTRA";
				exit();
			}
		}
		
		$palestra['indirizzo'] = ucwords($post_result['indirizzo']);
		$palestra['citta'] = ucwords($post_result['citta']);
		$palestra['cap'] = $post_result['cap'];
		$palestra['provincia'] = strtoupper($post_result['provincia']);
		$palestra['partita_iva'] = $post_result['partita_iva'];
		
		$palestra['sito_web'] = $post_result['sito_web'];
		$palestra['email'] = $post_result['email'];
		
		if( $post_result['id_attivita_palestra'] == -1 ) {
			$palestra['id_attivita_palestra'] = "";
		} else if( $post_result['id_attivita_palestra'] == '' ) {
			// insert new attività
			$palestra['id_attivita_palestra'] = $this->addNewAttivita($palestra['id'], $post_result['new_attivita_palestra']);
			
		} else {
			$palestra['id_attivita_palestra'] = $post_result['id_attivita_palestra'];
		}
		
		$palestra['mq'] = $post_result['mq'];
		
		$palestra['mq_sala_attrezzi'] = $post_result['mq_sala_attrezzi'];
		$palestra['mq_sala_corsi'] = $post_result['mq_sala_corsi'];
		$palestra['mq_cadio_fitness'] = $post_result['mq_cadio_fitness'];
		$palestra['mq_spinning'] = $post_result['mq_spinning'];
		$palestra['mq_rowing'] = $post_result['mq_rowing'];
		$palestra['mq_arti_marziali'] = $post_result['mq_arti_marziali'];
		$palestra['mq_piscina'] = $post_result['mq_piscina'];
		$palestra['mq_thermarium'] = $post_result['mq_thermarium'];
		
		if( $post_result['id_ubicazione'] == -1 ) {
			$palestra['id_ubicazione'] = "";
		} else if( $post_result['id_ubicazione'] == '' ) {
			// insert new ubicazione
			$palestra['id_ubicazione'] = $this->addNewUbicazione($palestra['id'], $post_result['new_ubicazione']);
			
		} else {
			$palestra['id_ubicazione'] = $post_result['id_ubicazione'];
		}
		
		$palestra['parcheggi'] = $post_result['parcheggi'];
		$palestra['rating_struttura'] = $post_result['rating_struttura'];
		$palestra['rating_attrezzature'] = $post_result['rating_attrezzature'];
		$palestra['rating_spogliatoi'] = $post_result['rating_spogliatoi'];
		$palestra['rating_pulizia'] = $post_result['rating_pulizia'];
		$palestra['rating_personale'] = $post_result['rating_personale'];
		
		if( !isset($post_result['servizio_bar']) ) {
			$palestra['servizio_bar'] = 0;
		} else {
			$palestra['servizio_bar'] = $post_result['servizio_bar'];
		}
		
		if( !isset($post_result['shop']) ) {
			$palestra['shop'] = 0;
		} else {
			$palestra['shop'] = $post_result['shop'];
		}
		
		if( !isset($post_result['servizio_distributori']) ) {
			$palestra['servizio_distributori'] = 0;
		} else {
			$palestra['servizio_distributori'] = $post_result['servizio_bar'];
		}
		
		$palestra['considerazioni_generali'] = $post_result['considerazioni_generali'];
		$palestra['altro'] = $post_result['altro'];
		
		if( $this->palestra->insertPalestra($palestra) ) {
			
			/*
			//INSERIMENTO SOGLIE DESK DI DEFAULT (DA ELIMINARE)
			$array_soglia = array();
			$array_soglia['id'] = $this->utility->generateId('soglie_missed_desk');
			$array_soglia['id_palestra'] = $palestra['id'];
			$array_soglia['giorni_primo_alert'] = 3;
			$array_soglia['giorni_secondo_alert'] = 6;
			$array_soglia['scadenza'] = 8;
			$this->palestra->insertSogliaMissedDesk($array_soglia);
			*/
			
			//INSERIMENTO PARAMETRI PALESTRA DI DEFAULT
			$array_parametri_palestra = array();
			$array_parametri_palestra['id'] = $this->utility->generateId('parametri_palestra');
			$array_parametri_palestra['id_palestra'] = $palestra['id'];
			$array_parametri_palestra['primo_alert_missed'] = 4;
			$array_parametri_palestra['secondo_alert_missed'] = 8;
			$array_parametri_palestra['scadenza_missed'] = 10;
			$array_parametri_palestra['alert_scadenza_abbonamento'] = 7;
			$array_parametri_palestra['alert_scadenza_freepass'] = 7;
			$array_parametri_palestra['soglia_nuovo_socio'] = 7;
			$array_parametri_palestra['alert_scadenza_rata'] = 7;
			$this->palestra->insertParametriPalestra($array_parametri_palestra);
			
			
			if( isset($post_result['numero']) ) {
				for($i=0; $i<count($post_result['numero']); $i++) {
					$nuovo_contatto = array();
					if( $post_result['id_tipologia_numero'][$i] == '' ) {
						// insert new tipologia numero
						$nuovo_contatto['id_tipologia_numero'] = $this->addNewTipologiaNumero($palestra['id'], $post_result['new_tipologia_numero'][$i]);
						
					} else {
						$nuovo_contatto['id_tipologia_numero'] = $post_result['id_tipologia_numero'][$i];
					}
					
					if( $nuovo_contatto['id_tipologia_numero'] ) {
						
						$nuovo_contatto['id'] = $this->utility->generateId('recapiti_telefonici_palestre');
						$nuovo_contatto['id_palestra'] = $palestra['id'];
						$nuovo_contatto['numero'] = $post_result['numero'][$i];

						if( !$this->recapiti_telefonici->insertRecapitoPalestra($nuovo_contatto) ) {
							$this->errorDB('ERRORE INSERIMENTO CONTATTO PALESTRA');
						}
						
					}




				}
			}
			
			if( isset($post_result['nome_riferimento']) ) {
				for($i=0; $i<count($post_result['nome_riferimento']); $i++) {
					
					$nuova_persona_riferimento = array();
					
					if($post_result['id_ruolo_riferimento'][$i] == '') {
						// insert new ruolo riferimento
						$nuova_persona_riferimento['id_ruolo_personale'] = $this->addNewRuoloPersonaRiferimento($palestra['id'], $post_result['new_ruolo_riferimento'][$i]);
					} else {
						$nuova_persona_riferimento['id_ruolo_personale'] = $post_result['id_ruolo_riferimento'][$i];
					}
					
					if( $nuova_persona_riferimento['id_ruolo_personale'] ) {
						$nuova_persona_riferimento['id'] = $this->utility->generateId('persone_riferimento_palestra');
						$nuova_persona_riferimento['id_palestra'] = $palestra['id'];
						$nuova_persona_riferimento['nome'] = ucwords($post_result['nome_riferimento'][$i]);
						$nuova_persona_riferimento['cognome'] = ucwords($post_result['cognome_riferimento'][$i]);
						$nuova_persona_riferimento['telefono'] = $post_result['telefono_riferimento'][$i];
						$nuova_persona_riferimento['cellulare'] = $post_result['cellulare_riferimento'][$i];
						$nuova_persona_riferimento['email'] = $post_result['email_riferimento'][$i];

						if( !$this->palestra->insertPersonaRif($nuova_persona_riferimento) ) {
							$this->errorDB('ERRORE INSERIMENTO PERSONA DI RIFERIMENTO');
						}
					}
				}
			}
			
			// inserimento terminato correttamente
			$dati['nome_palestra'] = $palestra['nome'];
			$this->load->view('palestre/success_insert_palestra', $dati);
		} else {
			// remove logo
			if( isset($data) ) {
				$file_name = $data['upload_data']['file_name'];
				unlink('./loghi_uploads/'.$file_name);
			}
			$this->errorDB("ERRORE INSERIMENTO PALESTRA");
		}
	}
	
	
	function modificaPalestra() {
		$post_result = $this->input->post();
		
		$palestra = array();
		
		$id_palestra = $post_result['id_palestra'];
		$palestra['id'] = $id_palestra;
		
		$palestra['nome'] = ucwords($post_result['nome']);
		$palestra['ragione_sociale'] = ucwords($post_result['ragione_sociale']);
		$palestra['partita_iva'] = $post_result['partita_iva'];
		
		$attiva_dal = str_replace('/', '-', $post_result['attiva_dal']);
		$palestra['attiva_dal'] = strtotime($attiva_dal);
		
		$attiva_al = str_replace('/', '-', $post_result['attiva_al']);
		$palestra['attiva_al'] = strtotime($attiva_al);

		
		if( $post_result['old_logo'] == '' || isset($_FILES['immagine_logo']['name']) ) {
			/* elimino il vecchio logo */
			if( $post_result['old_logo'] != '' && isset($_FILES['immagine_logo']['name']) ) {
				$path_logo = './loghi_uploads/'.$post_result['old_logo'];
				unlink($path_logo);
			}
			if( $post_result['old_logo'] == '' ) {
				$path_logo = './loghi_uploads/'.$id_palestra.'.*';
				array_map('unlink', glob($path_logo));
			}
			
			$palestra['immagine_logo'] = '';
			
			/* inserisco l'eventuale nuovo logo */
			if(isset($_FILES['immagine_logo']['name'])) {
				$logo_name = $palestra['id'];
				$config = array(
					'file_name' => $logo_name,
					'upload_path' => "./loghi_uploads/",
					'allowed_types' => "gif|jpg|png|jpeg|pdf",
					'overwrite' => TRUE,
					'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)
				);

				$this->upload->initialize($config);
				if( $this->upload->do_upload('immagine_logo') ) {

					$data = array('upload_data' => $this->upload->data());
					$palestra['immagine_logo'] = $data['upload_data']['file_name'];
				}
				else {
					$error = array('error' => $this->upload->display_errors());
					$debug = $this->upload->display_errors();
					echo "ERRRORE UPLOAD LOGO PALESTRA";
					exit();
				}
			}
		} else {
			$palestra['immagine_logo'] = $post_result['old_logo'];
		}
		
		$palestra['indirizzo'] = ucwords($post_result['indirizzo']);
		$palestra['citta'] = ucwords($post_result['citta']);
		$palestra['cap'] = $post_result['cap'];
		$palestra['provincia'] = strtoupper($post_result['provincia']);
		
		$palestra['sito_web'] = $post_result['sito_web'];
		$palestra['email'] = $post_result['email'];
		
		if( $post_result['id_attivita_palestra'] == -1 ) {
			$palestra['id_attivita_palestra'] = "";
		} else if( $post_result['id_attivita_palestra'] == '' ) {
			// insert new attività palestra
			$palestra['id_attivita_palestra'] = $this->addNewAttivita($palestra['id'], $post_result['new_attivita_palestra']);
			
		} else {
			$palestra['id_attivita_palestra'] = $post_result['id_attivita_palestra'];
		}
		
		$palestra['mq'] = $post_result['mq'];
		
		$palestra['mq_sala_attrezzi'] = $post_result['mq_sala_attrezzi'];
		$palestra['mq_sala_corsi'] = $post_result['mq_sala_corsi'];
		$palestra['mq_cadio_fitness'] = $post_result['mq_cadio_fitness'];
		$palestra['mq_spinning'] = $post_result['mq_spinning'];
		$palestra['mq_rowing'] = $post_result['mq_rowing'];
		$palestra['mq_arti_marziali'] = $post_result['mq_arti_marziali'];
		$palestra['mq_piscina'] = $post_result['mq_piscina'];
		$palestra['mq_thermarium'] = $post_result['mq_thermarium'];
		
		if( $post_result['id_ubicazione'] == -1 ) {
			$palestra['id_ubicazione'] = "";
		} else if( $post_result['id_ubicazione'] == '' ) {
			// insert new ubicazione
			$palestra['id_ubicazione'] = $this->addNewUbicazione($palestra['id'], $post_result['new_ubicazione']);
			
		} else {
			$palestra['id_ubicazione'] = $post_result['id_ubicazione'];
		}
		
		$palestra['parcheggi'] = $post_result['parcheggi'];
		$palestra['rating_struttura'] = $post_result['rating_struttura'];
		$palestra['rating_attrezzature'] = $post_result['rating_attrezzature'];
		$palestra['rating_spogliatoi'] = $post_result['rating_spogliatoi'];
		$palestra['rating_pulizia'] = $post_result['rating_pulizia'];
		$palestra['rating_personale'] = $post_result['rating_personale'];
		
		if( !isset($post_result['servizio_bar']) ) {
			$palestra['servizio_bar'] = 0;
		} else {
			$palestra['servizio_bar'] = $post_result['servizio_bar'];
		}
		
		if( !isset($post_result['shop']) ) {
			$palestra['shop'] = 0;
		} else {
			$palestra['shop'] = $post_result['shop'];
		}
		
		if( !isset($post_result['servizio_distributori']) ) {
			$palestra['servizio_distributori'] = 0;
		} else {
			$palestra['servizio_distributori'] = $post_result['servizio_bar'];
		}
		
		$palestra['considerazioni_generali'] = $post_result['considerazioni_generali'];
		$palestra['altro'] = $post_result['altro'];
		
		if( $this->palestra->updatePalestra($id_palestra, $palestra) ) {
			// MODIFICA TABELLA palestre RIUSCITA
			// ora si modificano i contatti e le persone di riferimento
			
			// CONTATTI PALESTRA
			// prende tutti i contatti della palestra
			if( isset($post_result['id_recapito_telefonico']) ) {
				$contatti_palestra = $this->recapiti_telefonici->getAllRecapitiPalestra($id_palestra);
				for($j=0; $j<count($contatti_palestra); $j++) {
					$id_contatto = $contatti_palestra[$j]->id;
					$salvare = false;
					for($k=0; $k<count($post_result['id_recapito_telefonico']); $k++) {
						$id_contatto_post = $post_result['id_recapito_telefonico'][$k];
						if($id_contatto == $id_contatto_post ) {
							$salvare = true;
							break;
						}
					}
					if(!$salvare) {
						//elimino il contatto
						$this->recapiti_telefonici->deleteRecapitoPalestra($id_contatto);
					}
				}
				for($i=0; $i<count($post_result['id_recapito_telefonico']); $i++) {
					$id_recapito = $post_result['id_recapito_telefonico'][$i];


					$recapito['id_palestra'] = $id_palestra;
					$recapito['numero'] = $post_result['numero'][$i];
					
					if( $post_result['id_tipologia_numero'][$i] == '' ) {
						// insert nuova tipologia numero
						$recapito['id_tipologia_numero'] = $this->addNewTipologiaNumero($palestra['id'], $post_result['new_tipologia_numero'][$i]);
						
					} else {
						$recapito['id_tipologia_numero'] = $post_result['id_tipologia_numero'][$i];
					}
					
					if( $recapito['id_tipologia_numero'] ) {
						if( $id_recapito != '' ) {
							$recapito['id'] = $id_recapito;
							// si fa l'update
							if( !$this->recapiti_telefonici->updateRecapitoPalestra($id_recapito, $recapito) ) {
								$this->errorDB('ERRORE UPDATE CONTATTO PALESTRA');
							}
						} else {
							$recapito['id'] = $this->utility->generateId('recapiti_telefonici_palestre');
							// si fa la insert
							if( !$this->recapiti_telefonici->insertRecapitoPalestra($recapito) ) {
								$this->errorDB('ERRORE INSERIMENTO CONTATTO PALESTRA');
							}
						}
					}
				}
			} else {
				// nessun contatto passato
				// elimina eventuali vecchi contatti della palestra
				$this->recapiti_telefonici->deleteAllRecapitiPalestra($id_palestra);
			}
			
			
			// PERSONE RIFERIMENTO
			// prende tutte le persone di riferimento della palestra
			if( isset($post_result['id_riferimento']) ) {
				
				$persone_riferimento_palestra = $this->palestra->getAllPersoneRifPalestra($id_palestra);
				for($j=0; $j<count($persone_riferimento_palestra); $j++) {
					$id_persona_riferimento = $persone_riferimento_palestra[$j]->id;
					$salvare = false;
					for($k=0; $k<count($post_result['id_riferimento']); $k++) {
						$id_persona_riferimento_post = $post_result['id_riferimento'][$k];
						if($id_persona_riferimento == $id_persona_riferimento_post ) {
							$salvare = true;
							break;
						}
					}
					if(!$salvare) {
						//elimino la persona di riferimento
						$this->palestra->deletePersonaRif($id_persona_riferimento);
					}
				}
				
				for($i=0; $i<count($post_result['id_riferimento']); $i++) {
					$id_riferimento = $post_result['id_riferimento'][$i];

					$riferimento = array();

					$riferimento['id_palestra'] = $id_palestra;
					$riferimento['nome'] = ucwords($post_result['nome_riferimento'][$i]);
					$riferimento['cognome'] = ucwords($post_result['cognome_riferimento'][$i]);
					$riferimento['telefono'] = $post_result['telefono_riferimento'][$i];
					$riferimento['cellulare'] = $post_result['cellulare_riferimento'][$i];
					$riferimento['email'] = $post_result['email_riferimento'][$i];



					if($post_result['id_ruolo_riferimento'][$i] == '') {
						// insert new ruolo riferimento
						$riferimento['id_ruolo_personale'] = $this->addNewRuoloPersonaRiferimento($palestra['id'], $post_result['new_ruolo_riferimento'][$i]);
					} else {
						$riferimento['id_ruolo_personale'] = $post_result['id_ruolo_riferimento'][$i];
					}

					if( $riferimento['id_ruolo_personale'] ) {
						if( $id_riferimento != '' ) {
							// update
							$riferimento['id'] = $id_riferimento;
							if( !$this->palestra->updatePersonaRif($id_riferimento, $riferimento) ) {
								$this->errorDB('ERRORE UPDATE PERSONA DI RIFERIMENTO');
							}
						} else {
							// insert
							$riferimento['id'] = $this->utility->generateId('persone_riferimento_palestra');
							if( !$this->palestra->insertPersonaRif($riferimento) ) {
								$this->errorDB('ERRORE INSERIMENTO PERSONA DI RIFERIMENTO');
							}
						}
					}
				}
			} else {
				// nessuna persona di riferimento passata
				// elimina eventuali vecchie persone di riferimento della palestra
				$this->palestra->deleteAllPersoneRifPalestra($id_palestra);
			}
			
			// modifica terminata correttamente
			$dati['nome_palestra'] = $palestra['nome'];
			$this->load->view('palestre/success_edit_palestra', $dati);
			
		} else {
			$this->errorDB('ERRORE, MODIFICA NON RIUSCITA');
		}
		
	}
	
	function modificaPalestraLite() {
		if( $this->user->controlloAutenticazione() ) {
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			
			if( $privilegi <= 1 ) {
				$post_result = $this->input->post();

				$palestra = array();

				$id_palestra = $post_result['id_palestra'];
				$palestra['id'] = $id_palestra;

				$palestra['nome'] = ucwords($post_result['nome']);
				$palestra['ragione_sociale'] = ucwords($post_result['ragione_sociale']);
				$palestra['partita_iva'] = $post_result['partita_iva'];


				if( $post_result['old_logo'] == '' || isset($_FILES['immagine_logo']['name']) ) {
					/* elimino il vecchio logo */
					if( $post_result['old_logo'] != '' && isset($_FILES['immagine_logo']['name']) ) {
						$path_logo = './loghi_uploads/'.$post_result['old_logo'];
						unlink($path_logo);
					}
					if( $post_result['old_logo'] == '' ) {
						$path_logo = './loghi_uploads/'.$id_palestra.'.*';
						array_map('unlink', glob($path_logo));
					}

					$palestra['immagine_logo'] = '';

					/* inserisco l'eventuale nuovo logo */
					if(isset($_FILES['immagine_logo']['name'])) {
						$logo_name = $palestra['id'];
						$config = array(
							'file_name' => $logo_name,
							'upload_path' => "./loghi_uploads/",
							'allowed_types' => "gif|jpg|png|jpeg|pdf",
							'overwrite' => TRUE,
							'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)
						);

						$this->upload->initialize($config);
						if( $this->upload->do_upload('immagine_logo') ) {

							$data = array('upload_data' => $this->upload->data());
							$palestra['immagine_logo'] = $data['upload_data']['file_name'];
						}
						else {
							$error = array('error' => $this->upload->display_errors());
							$debug = $this->upload->display_errors();
							echo "ERRRORE UPLOAD LOGO PALESTRA";
							exit();
						}
					}
				} else {
					$palestra['immagine_logo'] = $post_result['old_logo'];
				}

				$palestra['indirizzo'] = ucwords($post_result['indirizzo']);
				$palestra['citta'] = ucwords($post_result['citta']);
				$palestra['cap'] = $post_result['cap'];
				$palestra['provincia'] = strtoupper($post_result['provincia']);

				$palestra['sito_web'] = $post_result['sito_web'];
				$palestra['email'] = $post_result['email'];

				if( $post_result['id_attivita_palestra'] == -1 ) {
					$palestra['id_attivita_palestra'] = "";
				} else if( $post_result['id_attivita_palestra'] == '' ) {
					// insert new attività palestra
					$palestra['id_attivita_palestra'] = $this->addNewAttivita($palestra['id'], $post_result['new_attivita_palestra']);

				} else {
					$palestra['id_attivita_palestra'] = $post_result['id_attivita_palestra'];
				}

				if( $post_result['id_ubicazione'] == -1 ) {
					$palestra['id_ubicazione'] = "";
				} else if( $post_result['id_ubicazione'] == '' ) {
					// insert new ubicazione
					$palestra['id_ubicazione'] = $this->addNewUbicazione($palestra['id'], $post_result['new_ubicazione']);

				} else {
					$palestra['id_ubicazione'] = $post_result['id_ubicazione'];
				}

				$palestra['parcheggi'] = $post_result['parcheggi'];

				if( !isset($post_result['servizio_bar']) ) {
					$palestra['servizio_bar'] = 0;
				} else {
					$palestra['servizio_bar'] = $post_result['servizio_bar'];
				}

				if( !isset($post_result['shop']) ) {
					$palestra['shop'] = 0;
				} else {
					$palestra['shop'] = $post_result['shop'];
				}

				if( !isset($post_result['servizio_distributori']) ) {
					$palestra['servizio_distributori'] = 0;
				} else {
					$palestra['servizio_distributori'] = $post_result['servizio_bar'];
				}

				if( $this->palestra->updatePalestra($id_palestra, $palestra) ) {
					// MODIFICA TABELLA palestre RIUSCITA
					// ora si modificano i contatti e le persone di riferimento

					// CONTATTI PALESTRA
					// prende tutti i contatti della palestra
					if( isset($post_result['id_recapito_telefonico']) ) {
						$contatti_palestra = $this->recapiti_telefonici->getAllRecapitiPalestra($id_palestra);
						for($j=0; $j<count($contatti_palestra); $j++) {
							$id_contatto = $contatti_palestra[$j]->id;
							$salvare = false;
							for($k=0; $k<count($post_result['id_recapito_telefonico']); $k++) {
								$id_contatto_post = $post_result['id_recapito_telefonico'][$k];
								if($id_contatto == $id_contatto_post ) {
									$salvare = true;
									break;
								}
							}
							if(!$salvare) {
								//elimino il contatto
								$this->recapiti_telefonici->deleteRecapitoPalestra($id_contatto);
							}
						}
						for($i=0; $i<count($post_result['id_recapito_telefonico']); $i++) {
							$id_recapito = $post_result['id_recapito_telefonico'][$i];


							$recapito['id_palestra'] = $id_palestra;
							$recapito['numero'] = $post_result['numero'][$i];

							if( $post_result['id_tipologia_numero'][$i] == '' ) {
								// insert nuova tipologia numero
								$recapito['id_tipologia_numero'] = $this->addNewTipologiaNumero($palestra['id'], $post_result['new_tipologia_numero'][$i]);

							} else {
								$recapito['id_tipologia_numero'] = $post_result['id_tipologia_numero'][$i];
							}

							if( $recapito['id_tipologia_numero'] ) {
								if( $id_recapito != '' ) {
									$recapito['id'] = $id_recapito;
									// si fa l'update
									if( !$this->recapiti_telefonici->updateRecapitoPalestra($id_recapito, $recapito) ) {
										$this->errorDB('ERRORE UPDATE CONTATTO PALESTRA');
									}
								} else {
									$recapito['id'] = $this->utility->generateId('recapiti_telefonici_palestre');
									// si fa la insert
									if( !$this->recapiti_telefonici->insertRecapitoPalestra($recapito) ) {
										$this->errorDB('ERRORE INSERIMENTO CONTATTO PALESTRA');
									}
								}
							}
						}
					} else {
						// nessun contatto passato
						// elimina eventuali vecchi contatti della palestra
						$this->recapiti_telefonici->deleteAllRecapitiPalestra($id_palestra);
					}


					// PERSONE RIFERIMENTO
					// prende tutte le persone di riferimento della palestra
					if( isset($post_result['id_riferimento']) ) {

						$persone_riferimento_palestra = $this->palestra->getAllPersoneRifPalestra($id_palestra);
						for($j=0; $j<count($persone_riferimento_palestra); $j++) {
							$id_persona_riferimento = $persone_riferimento_palestra[$j]->id;
							$salvare = false;
							for($k=0; $k<count($post_result['id_riferimento']); $k++) {
								$id_persona_riferimento_post = $post_result['id_riferimento'][$k];
								if($id_persona_riferimento == $id_persona_riferimento_post ) {
									$salvare = true;
									break;
								}
							}
							if(!$salvare) {
								//elimino la persona di riferimento
								$this->palestra->deletePersonaRif($id_persona_riferimento);
							}
						}

						for($i=0; $i<count($post_result['id_riferimento']); $i++) {
							$id_riferimento = $post_result['id_riferimento'][$i];

							$riferimento = array();

							$riferimento['id_palestra'] = $id_palestra;
							$riferimento['nome'] = ucwords($post_result['nome_riferimento'][$i]);
							$riferimento['cognome'] = ucwords($post_result['cognome_riferimento'][$i]);
							$riferimento['telefono'] = $post_result['telefono_riferimento'][$i];
							$riferimento['cellulare'] = $post_result['cellulare_riferimento'][$i];
							$riferimento['email'] = $post_result['email_riferimento'][$i];



							if($post_result['id_ruolo_riferimento'][$i] == '') {
								// insert new ruolo riferimento
								$riferimento['id_ruolo_personale'] = $this->addNewRuoloPersonaRiferimento($palestra['id'], $post_result['new_ruolo_riferimento'][$i]);
							} else {
								$riferimento['id_ruolo_personale'] = $post_result['id_ruolo_riferimento'][$i];
							}

							if( $riferimento['id_ruolo_personale'] ) {
								if( $id_riferimento != '' ) {
									// update
									$riferimento['id'] = $id_riferimento;
									if( !$this->palestra->updatePersonaRif($id_riferimento, $riferimento) ) {
										$this->errorDB('ERRORE UPDATE PERSONA DI RIFERIMENTO');
									}
								} else {
									// insert
									$riferimento['id'] = $this->utility->generateId('persone_riferimento_palestra');
									if( !$this->palestra->insertPersonaRif($riferimento) ) {
										$this->errorDB('ERRORE INSERIMENTO PERSONA DI RIFERIMENTO');
									}
								}
							}
						}
					} else {
						// nessuna persona di riferimento passata
						// elimina eventuali vecchie persone di riferimento della palestra
						$this->palestra->deleteAllPersoneRifPalestra($id_palestra);
					}

					// modifica terminata correttamente
					$dati['nome_palestra'] = $palestra['nome'];
					$this->load->view('palestre/success_edit_palestra', $dati);

				} else {
					$this->errorDB('ERRORE, MODIFICA NON RIUSCITA');
				}
			} else {
				
				$this->utility->errorMsg("ACCESSO NEGATO!", 'Non è possibile fare questa modifica con i tuoi privilegi');
		
			}
		} else {
			$dati['redirect_page'] = 'listasoci';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	/* FUNZIONI DI APPOGGIO */
	function addNewAttivita($id_palestra, $new_attivita_palestra) {
		$nuova_attivita = array();
		$nuova_attivita['tipo_attivita'] = ucwords($new_attivita_palestra);
		$nuova_attivita['id'] = $this->utility->generateId('attivita_palestre');
		$nuova_attivita['id_palestra'] = $id_palestra;

		if( $this->palestra->insertAttivita($nuova_attivita) ) {
			return $nuova_attivita['id'];
		} else {
			return "";
		}
	}
	
	function addNewUbicazione($id_palestra, $new_ubicazione) {
		$nuova_ubicazione = array();
		$nuova_ubicazione['posizione'] = ucwords($new_ubicazione);
		$nuova_ubicazione['id'] = $this->utility->generateId('ubicazioni_palestre');
		$nuova_ubicazione['id_palestra'] = $id_palestra;

		if( $this->palestra->insertUbicazione($nuova_ubicazione) ) {
			return $nuova_ubicazione['id'];
		} else {
			return "";
		}
	}
	
	function addNewTipologiaNumero($id_palestra, $new_tipologia_numero) {
		$nuova_tipologia_numero = array();

		$nuova_tipologia_numero['etichetta'] = ucwords($new_tipologia_numero);
		$nuova_tipologia_numero['id'] = $this->utility->generateId('tipologia_numero_telefonico');
		$nuova_tipologia_numero['id_palestra'] = $id_palestra;


		if( $this->recapiti_telefonici->insertTipologia($nuova_tipologia_numero) ) {
			// recapiti_telefonici_palestre

			return $nuova_tipologia_numero['id'];

		} else {
			return false;
		}
		
	}
	
	function addNewRuoloPersonaRiferimento($id_palestra, $new_ruolo_riferimento) {
		$nuovo_ruolo = array();
		$nuovo_ruolo['id'] = $this->utility->generateId('ruoli_personale_palestra');
		$nuovo_ruolo['id_palestra'] = $id_palestra;
		$nuovo_ruolo['ruolo'] = ucwords($new_ruolo_riferimento);

		if( $this->palestra->insertRuoloPersonaRif($nuovo_ruolo) ) {
		  return $nuovo_ruolo['id'];
		} else {
			return false;
		}
	}
	
	function errorDB($message = 'Generico') {
		
		$this->utility->errorDB($message);
		
	}
	
	function deletePalestra($id_palestra) {
		// DELETE DISABILITATA SOLO PER IL DEBUG
		
		$logo = $this->palestra->getLogo($id_palestra);
		
		//elimina i soci della palestra e ciò che è strettamente collegato all'id_socio
		$this->deleteSociPalestra($id_palestra);
		
		//elimina gli utenti legati alla palestra e ciò che è strettamente collegato all'id_utente
		$this->deleteUtentiPalestra($id_palestra);
		
		//elimina le voci_collaborazioni legate alla palestra e le voci specifiche legate ad esse
		$this->deleteVociCollaborazioni($id_palestra);
		
		//elimina ogni cosa strettamente collegata alla palestra
		$this->deleteCollegamentiPalestra($id_palestra);
		
		/* VRCCHIO SISTEMA
		//elimina la soglia missed desk
		$this->palestra->deleteSogliaMissedDeskByPalestra($id_palestra);
		*/
		
		//elimina i parametri della palestra
		$this->palestra->deleteParametriPalestraByPalestra($id_palestra);
		
		//elimina la PALESTRA
		$this->palestra->deletePalestra($id_palestra);
		
		//elimina l'eventuale logo
		if( $logo != '' || $logo != null ) {
			$path_logo = './loghi_uploads/'.$logo;
			unlink($path_logo);
		}
		
		$this->load->view('palestre/success_delete_palastra');
		
	}
	
	function deleteSociPalestra($id_palestra) {
		
		$soci = $this->socio->getAllSociPalestra($id_palestra);
		
		if( count($soci) > 0 ) {
			foreach($soci as $socio) {
				$id_socio = $socio->id;
				//elimina i recapiti telefonici del socio
				$this->recapiti_telefonici->deleteAllRecapitiSocio($id_socio);

				//elimina tutti i colloqui di verifica fatti al socio
				$this->contatti->deleteAllColloquiVerificaSocio($id_socio);

				//elimina tutte le telefonate fatta al socio
				$this->contatti->deleteAllTelefonateSocio($id_socio);

				//elimina tutti i contatti che ci sono stati con il socio
				$this->contatti->deleteAllContattiSocio($id_socio);

				//elimina tutti gli abbonamenti del socio
				$this->abbonamenti->deleteAllAbbonamentiSocio($id_socio);
				
				//elimina i bonus del socio
				$this->bonus_socio->deleteAllBonusSocio($id_socio);

				//elimina il socio
				$this->socio->deleteSocio($id_socio);

			}
		}
		return true;
		
	}
	
	function deleteUtentiPalestra($id_palestra) {
		$utenti = $this->personale->getAllUtentiPalestra($id_palestra);
		if( count($utenti) > 0 ) {
			foreach($utenti as $utente) {
				$id_utente = $utente->id;

				//elimina tutti i recapiti del utente della palestra
				$this->recapiti_telefonici->deleteAllRecapitiUtente($id_utente);

				//elimina l'utente
				$this->personale->deleteUtente($id_utente);

			}
		}
		return true;
	}
	
	function deleteVociCollaborazioni($id_palestra) {
		$voci_collaborazione = $this->voci_budget->getAllVociCollaborazionePalestra($id_palestra);
		if( count($voci_collaborazione) > 0 ) {
			foreach($voci_collaborazione as $voce_collaborazione) {
				$id_voce_collaborazione = $voce_collaborazione->id;

				//elimina tutte le voci specifiche legate alla voce_collaborazione
				$this->voci_budget->deleteAllVociSpecificheVoceCollaborazione($id_voce_collaborazione);

				//elimina la voce_collaborazione
				$this->voci_budget->deleteVoceCollaborazione($id_voce_collaborazione);

			}
		}
		return true;
	}
	
	function deleteCollegamentiPalestra($id_palestra) {
		
		$this->load->model('pagamenti');
		$this->load->model('motivazioni');
		$this->load->model('contratto');
		$this->load->model('rinnoviIscrizioni', 'rinnovi_iscrizioni');
		
		/* VOCI BUDGET */
		//elimina voci_funzionamento della palestra
		$this->voci_budget->deleteAllVociFunzionamentoPalestra($id_palestra);
		
		//elimina voci_prodotti della palestra
		$this->voci_budget->deleteAllVociProdottiPalestra($id_palestra);
		
		//elimina voci_pubblicita della palestra
		$this->voci_budget->deleteAllVociPubblicitaPalestra($id_palestra);
		
		//elimina voci_investimenti della palestra
		$this->voci_budget->deleteAllVociInvestimentiPalestra($id_palestra);
		
		//elimina voci_gestione_straordinaria della palestra
		$this->voci_budget->deleteAllVociGestioneStraordinariaPalestra($id_palestra);
		
		
		/* SOCIO */
		//elimina professioni della palestra
		$this->socio->deleteAllProfessioniPalestra($id_palestra);
		
		//elimina fonti_provenienza della palestra
		$this->socio->deleteAllFontiPalestra($id_palestra);
		
		
		/* RINNOVI E ISCRIZIONI */
		//elimina rinnovi_e_iscrizioni della palestra
		$this->rinnovi_iscrizioni->deleteAllRinnoviIscrizioniPalestra($id_palestra);
		
		$this->rinnovi_iscrizioni->deleteAllFreePassPalestra($id_palestra);
		
		
		/* PAGAMENTI */
		//elimina pagamenti_soci della palestra
		$this->pagamenti->deleteAllPagamentiPalestra($id_palestra);
		
		
		/* MOTIVAZIONI */
		//elimina motivazioni_frequenza della palestra
		$this->motivazioni->deleteAllMotivazioniPalestra($id_palestra);
		
		
		/* ABBONAMENTI */
		//elimina abbonamenti_soci della palestra
		$this->abbonamenti->deleteAllAbbonamentiPalestra($id_palestra);		//ridondante dovrebbe già essere stato eseguito correttamente da deleteAllAbbonamentiSocio
		
		//elimina tipologie_abbonamento della palestra
		$this->abbonamenti->deleteAllTipologieAbbonamentoPalestra($id_palestra);
		
		
		/* CONTRATTO */
		//elimina condizioni_contratto della palestra
		$this->contratto->deleteContrattoPalestra($id_palestra);
		
		
		/* RECAPITI TELEFONICI */
		//elimina recapiti_telefonici_palestra della palestra
		$this->recapiti_telefonici->deleteAllRecapitiPalestra($id_palestra);
		
		//elimina tipologia_numeri_telefonici della palestra
		$this->recapiti_telefonici->deleteAllTipologiePalestre($id_palestra);
		
		
		/* PALESTRA	*/
		//elimina ubicazioni_palestra della palestra
		$this->palestra->deleteAllUbicazioniPalestra($id_palestra);
		
		//elimina attività_palestre della palestra
		$this->palestra->deleteAllAttivitaPalestra($id_palestra);
		
		//elimina persone_riferimento_palestre della palestra
		$this->palestra->deleteAllPersoneRifPalestra($id_palestra);
		
		//elimina ruoli_personale_palestra della palestra
		$this->palestra->deleteAllRuoliPersoneRifPalestra($id_palestra);
		
		
		return true;
	}
 
}
 
?>