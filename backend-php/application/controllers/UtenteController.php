<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class UtenteController extends CI_Controller {
	

	function __construct() {
		parent::__construct();
		
		// MODEL SEMPRE NECESSARI 
		$this->load->model('user','',TRUE);
		
		// MODEL NECESSARI IN QUESTO CONTROLLER 
		$this->load->model('personale');
		$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('utility');
		
		// MODEL NECESSARI PER LA DELETE PALESTRA 
		//$this->load->model('personale');
		//$this->load->model('abbonamenti');
		
		// LIBRERIE AGGIUNTIVE 
		$this->load->library('upload');
		
		// COSTANTI NECESSARIE IN QUESTO CONTROLLER 
		// NESSUNA
		
	}
	
	// FATTO!
	function creaUtente() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();

			$utente = array();
			$errore = false;

			$utente['id'] = $this->utility->generateId('pesonale_palestre');

			$utente['username'] = $post_result['username'];
			if( $this->personale->checkExistUsername($utente['username']) ) {
				$this->utility->errorDB("Utente già presente");
				$errore = true;
			} else {

				$utente['password'] = md5($post_result['password']);
				$utente['nome'] = ucwords($post_result['nome']);
				$utente['cognome'] = ucwords($post_result['cognome']);
				$utente['ruolo'] = $post_result['ruolo'];

				if( isset($post_result['id_palestra']) && $utente['ruolo'] > 0 ) {
					$utente['id_palestra'] = $post_result['id_palestra'];
				} else {
					$utente['id_palestra'] = '';
				}

				$utente['coordinatore'] = $post_result['coordinatore'];
				if( $utente['coordinatore'] == 0 ) {
					if( isset($post_result['coordinatore']) ) {

						$utente['id_coordinatore'] = $post_result['id_coordinatore'];
					} else {
						$this->utility->errorDB("ERRORE COORDINATORE DI RIFERIMENTO NON SPECIFICATO");
						$errore = true;
					}
				} else {
					$utente['id_coordinatore'] = '';
				}
				
				$data_nascita = str_replace('/', '-', $post_result['data_nascita']);
				$utente['data_nascita'] = strtotime($data_nascita);
				
				$utente['sesso'] = $post_result['sesso'];
				$utente['indirizzo'] = ucwords($post_result['indirizzo']);
				$utente['cap'] = $post_result['cap'];
				$utente['citta'] = ucwords($post_result['citta']);
				$utente['provincia'] = strtoupper($post_result['provincia']);
				$utente['email'] = $post_result['email'];


				if( !$errore ) {

					if( $this->personale->insertUtente($utente) ) {
						if( isset($post_result['numero']) ) {
							for($i=0; $i<count($post_result['numero']); $i++) {
								$nuovo_contatto = array();
								if( $post_result['id_tipologia_numero'][$i] == '' ) {
									// insert new tipologia numero
									$nuovo_contatto['id_tipologia_numero'] = $this->addNewTipologiaNumero($utente['id_palestra'], $post_result['new_tipologia_numero'][$i]);

								} else {
									$nuovo_contatto['id_tipologia_numero'] = $post_result['id_tipologia_numero'][$i];
								}

								if( $nuovo_contatto['id_tipologia_numero'] ) {

									$nuovo_contatto['id'] = $this->utility->generateId('recapiti_telefonici_personale');
									$nuovo_contatto['id_utente'] = $utente['id'];
									$nuovo_contatto['numero'] = $post_result['numero'][$i];

									if( !$this->recapiti_telefonici->insertRecapitoUtente($nuovo_contatto) ) {
										$this->utility->errorDB('ERRORE INSERIMENTO RECAPITO UTENTE');
									}



								}


							}
						}
						$dati['username'] = $utente['username'];
						$this->load->view('utenti/success_insert_utente', $dati);
					} else {
						$this->utility->errorDB("ERRORE INSERIMENTO UTENTE");
					}
				}

			}
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
		
	// FATTO!
	function modificaProfilo() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();

			$dati_utente = array();
			//var_dump($post_result);

			$id_utente = $post_result['id_utente'];
			$ruolo = $post_result['ruolo'];

			$dati_utente['username'] = $post_result['username'];
			$dati_utente['ruolo'] = $post_result['ruolo'];

			if( $ruolo > 0 ) {
				$dati_utente['coordinatore'] = $post_result['coordinatore'];
				if( $dati_utente['coordinatore'] == 0 ) {
					$dati_utente['id_coordinatore'] = $post_result['id_coordinatore'];
				} else {
					$dati_utente['id_coordinatore'] = '';
				}
			}

			if( $this->personale->updateUtente($id_utente, $dati_utente) ) {
				$dati['username'] = $dati_utente['username'];
				$this->load->view('utenti/success_edit_profilo_utente', $dati);
			} else {
				$this->utility->errorDB('ERRORE! AGGIORNAMENTO DEL PROFILO <strong>'.$dati_utente["username"].'</strong> non è riuscito');
			}
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}			
	}
	
	// FATTO!
	function deleteUtente($id_utente) {
		if( $this->user->controlloAutenticazione() ) {
			// MODEL NECESSARI SOLO PER LA DELETE UTENTE
			$this->load->model('socio');
			$this->load->model('contatti');
			$this->load->model('rinnoviIscrizioni', 'rinnovi_iscrizioni');
			$this->load->model('pagamenti');

			$utente = $this->personale->getUtente($id_utente);


			if( $utente != NULL ) {

				if( $utente->coordinatore == 1 ) {
					$coordinati = $this->personale->getAllCoordinatiByCoordinatore($id_utente);
				} else {
					$coordinati = null;
				}
				if( count($coordinati) == 0 ) {

					$soci = $this->socio->getAllSociByConsulente($id_utente);
					$colloqui_verifica = $this->contatti->getAllColloquiVerificaByConsulente($id_utente);
					$telefonate = $this->contatti->getAllTelefonateByConsulente($id_utente);
					$contatti = $this->contatti->getAllContattiByConsulente($id_utente);
					$rinnovi_iscrizioni = $this->rinnovi_iscrizioni->getAllRinnoviIscrizioniByConsulente($id_utente);
					$pagamenti = $this->pagamenti->getAllPagamentiByDesk($id_utente);

					if( ( count($soci)+count($colloqui_verifica)+count($telefonate)+count($contatti)+count($rinnovi_iscrizioni)+count($pagamenti) ) <= 0 ) {

						// procede all'eliminazione

						// cancellazione dei recapiti_telefonici_personale dell'utente
						if( $this->recapiti_telefonici->deleteAllRecapitiUtente($id_utente) ) {

							// CANCELLAZIONE UTENTE
							if( $this->personale->deleteUtente($id_utente) ) {
								$dati['username'] = $utente->username;
								$this->load->view('utenti/success_delete_utente', $dati);
							} else {
								$message = 'ERRORE, ELIMINAZIONE DELL\'UTENTE <strong>'.$utente->username.'</strong>';
								$this->utility->errorDB($message);
							}

						} else {
							$message = 'ERRORE, ELIMINAZIONE RECAPITI TELEFONICI DELL\'UTENTE <strong>'.$utente->username.'</strong>';
							$this->utility->errorDB($message);
						}

					} else {
						$message = 'L\'utente <strong>'.$utente->username.'</strong> non può essere eliminato perché esistono dei collegamenti tra lui e altri elementi nel sistema (Soci, contatti telefonici con soci, rinnovi d\'iscrizioni o pagamenti)';
						$this->utility->errorDB($message);
					}

				} else {
					$message = 'Non si può eliminare un coordinatore con dei coordinati';
					$this->utility->errorDB($message);
				}

			} else {
				$message = 'ERRORE, NESSUN UTENTE TROVATO';
				$this->utility->errorDB($message);			
			}
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	// FATTO!
	function modificaRecapitoUtente() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();

			$errore = false;

			$recapito_utente = array();

			$id_utente = $post_result['id_utente'];
			$username = $post_result['username'];

			$id_palestra = $post_result['id_palestra'];

			$id_recapito_utente = $post_result['id'];


			$recapito_utente['id_tipologia_numero'] = $post_result['id_tipologia_numero'];
			$recapito_utente['numero'] = $post_result['numero'];

			if( $recapito_utente['id_tipologia_numero'] == '' ) {

				$new_tipologia_numero = $post_result['new_tipologia_numero'];
				if( $new_tipologia_numero != '' ) {

					$recapito_utente['id_tipologia_numero'] = $this->addNewTipologiaNumero($id_palestra, $new_tipologia_numero);

					if( !$recapito_utente['id_tipologia_numero'] ) {
						$errore = true;
						$this->utility->errorDB('ERRORE! INSERIMENTO NUOVA TIPOLOGIA DI NUMERO NEL SISTEMA');	
					}				
				} else {
					$errore = true;
					$this->utility->errorMsg('Dati mancanti', 'ERRORE! Manca la nuova etichetta della tipologia di recapito telefonico');
				}

			}

			if( !$errore ) {
				if( $this->recapiti_telefonici->updateRecapitoUtente($id_recapito_utente, $recapito_utente) ) {
					$dati['username'] = $username;
					$this->load->view('utenti/success_edit_recapito_utente', $dati);
				} else {
					$msg = 'ERRORE! NON E\' STATO POSSIBILE AGGIORNARE IL RECAPITO TELEFONICO DELL\'UTENTE '.$username;
					$this->utility->errorDB($msg);	
				}
			}
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}		
	}
	
	// FATTO!
	function deleteRecapitoUtente($id_recapito_utente) {
		if( $this->user->controlloAutenticazione() ) {
			
			$recapito_utente = $this->recapiti_telefonici->getRecapitoPersonale($id_recapito_utente);
			if( $recapito_utente != null ) {
				$utente = $this->personale->getUtente($recapito_utente->id_utente);

				$dati['username'] = $utente->username;
				$dati['tipologia_str'] = $this->recapiti_telefonici->getTipologia($recapito_utente->id_tipologia_numero)->etichetta;
				$dati['numero'] = $recapito_utente->numero;

				if( $this->recapiti_telefonici->deleteRecapitoUtente($id_recapito_utente) ) {
					$this->load->view('utenti/success_delete_recapito_utente', $dati);
				} else {
					$msg = 'ERRORE! NON E\' STATO POSSIBILE ELIMINARE IL RECAPITO TELEFONICO DELL\'UTENTE '.$username;
					$this->utility->errorDB($msg);	
				}	
			} else {
					$msg = 'ERRORE! RECAPITO NON TROVATO';
					$this->utility->errorDB($msg);	
				}
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	// FATTO!
	function inserimentoRecapitoUtente() {
		if( $this->user->controlloAutenticazione() ) {
			
			$post_result = $this->input->post();

			$errore = false;

			$recapito_utente = array();

			$id_utente = $post_result['id_utente'];
			$username = $post_result['username'];

			$id_palestra = $post_result['id_palestra'];


			$recapito_utente['id_utente'] = $id_utente;
			$recapito_utente['id_tipologia_numero'] = $post_result['id_tipologia_numero'];
			$recapito_utente['numero'] = $post_result['numero'];

			if( $recapito_utente['id_tipologia_numero'] == '' ) {

				$new_tipologia_numero = $post_result['new_tipologia_numero'];
				if( $new_tipologia_numero != '' ) {

					$recapito_utente['id_tipologia_numero'] = $this->addNewTipologiaNumero($id_palestra, $new_tipologia_numero);

					if( !$recapito_utente['id_tipologia_numero'] ) {
						$errore = true;
						$this->utility->errorDB('ERRORE! INSERIMENTO NUOVA TIPOLOGIA DI NUMERO NEL SISTEMA');	
					}				
				} else {
					$errore = true;
					$this->utility->errorMsg('Dati mancanti', 'ERRORE! Manca la nuova etichetta della tipologia di recapito telefonico');
				}

			}

			if( !$errore ) {
				
				$recapito_utente['id'] = $this->utility->generateId('recapiti_telefonici_personale');
				
				if( $this->recapiti_telefonici->insertRecapitoUtente($recapito_utente) ) {
					$dati['username'] = $username;
					$this->load->view('utenti/success_insert_recapito_utente', $dati);
				} else {
					$msg = 'ERRORE! NON E\' STATO POSSIBILE AGGIORNARE IL RECAPITO TELEFONICO DELL\'UTENTE '.$username;
					$this->utility->errorDB($msg);	
				}
			}
			
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	// FATTO!
	function updatePassword() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();

			$dati_utente = array();

			$id_utente = $post_result['id_utente'];
			$new_password = $post_result['new_pass'];
			$new_password_2 = $post_result['new_pass_2'];

			$utente = $this->personale->getUtente($id_utente);

			if( $new_password == $new_password_2 ) {
				$dati_utente['password'] = md5($new_password);
				if( $this->personale->updateUtente($id_utente, $dati_utente) ) {
					$dati['username'] = $utente->username;
					$this->load->view('utenti/success_edit_password_utente', $dati);
				} else {
					$dati['error_msg'] = -2;
					$this->load->view('utenti/form_edit_password_utente', $dati);
					//$this->getUpdatePasswordForm($id_utente, -3); // errore nell'update
				}
			} else {
				$dati['error_msg'] = -1;
				$this->load->view('utenti/form_edit_password_utente', $dati);
				//$this->getUpdatePasswordForm($id_utente, -2); // la nuova password non è stata ripetuta correttamente
			}
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	// FATTO
	function updateDatiAnagrafici() {
		if( $this->user->controlloAutenticazione() ) {
			$post_result = $this->input->post();

			$id_utente = $post_result['id_utente'];

			$nuovi_dati_utente = array();
			unset($post_result['id_utente']);
			$nuovi_dati_utente = array();
			
			$nuovi_dati_utente['nome'] = ucwords($post_result['nome']);
			$nuovi_dati_utente['cognome'] = ucwords($post_result['cognome']);

			$data_nascita = str_replace('/', '-', $post_result['data_nascita']);
			$nuovi_dati_utente['data_nascita'] = strtotime($data_nascita);
			
			$nuovi_dati_utente['sesso'] = $post_result['sesso'];
			$nuovi_dati_utente['indirizzo'] = ucwords($post_result['indirizzo']);
			$nuovi_dati_utente['citta'] = ucwords($post_result['citta']);
			$nuovi_dati_utente['cap'] = $post_result['cap'];
			$nuovi_dati_utente['provincia'] = strtoupper($post_result['provincia']);
			$nuovi_dati_utente['email'] = $post_result['email'];

			if( $this->personale->updateUtente($id_utente, $nuovi_dati_utente) ) {
				$utente = $this->personale->getUtente($id_utente);
				$dati['username'] = $utente->username;
				$this->load->view('utenti/success_edit_dati_anagrafici_utente', $dati);
			} else {
				$this->getUpdateDatiAnagraficiForm($id_utente, -1); // errore nell'update
			}
		} else {
			$dati['redirect_page'] = 'listautenti';
			$this->load->view('login_popoup_view', $dati);
		}
	}
	
	
	/* FUNZIONI DI APPOGGIO */
	// FATTO!
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
	
	
	
}
 
?>
