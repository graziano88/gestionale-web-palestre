<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
class Pdf extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		
		$this->load->helper('pdf_helper');
		
		
		/* MODEL SEMPRE NECESSARI */
		$this->load->model('user','',TRUE);
		
		/* MODEL NECESSARI ALL'HEADER_MODEL */
		$this->load->model('personale');
		$this->load->model('palestra');
		$this->load->model('HeaderModel', 'header_model');
		
		/* MODEL NECESSARI IN QUESTO CONTROLLER */
		$this->load->model('socio');
		$this->load->model('motivazioni');
		$this->load->model('recapitiTelefonici', 'recapiti_telefonici');
		$this->load->model('abbonamenti');
		$this->load->model('rate');
		$this->load->model('pagamenti');
		$this->load->model('contatti');
		$this->load->model('RinnoviIscrizioni', 'rinnovi_iscrizioni');
		$this->load->model('BonusSocio', 'bonus_socio');
		$this->load->model('utility');
		
		/* LIBRERIE AGGIUNTIVE */
		// NESSUNA		
		
		/* COSTANTI NECESSARIE IN QUESTO CONTROLLER */
		define('CURRENT_PAGE', 'listasoci');
		define('ELEMENTI_PER_PAGINA', 15);
		
	}
	
	function schedaSocio($id_socio) {
		if( $this->user->controlloAutenticazione() ) {
			$dati_pagina = array();

			$socio = $this->socio->getSocio($id_socio);

			$socio->nome_socio_presentatore = '';
			$socio->cognome_socio_presentatore = '';
			if( $socio->id_socio_presentatore != '' ) {
				$socio_presentatore = $this->socio->getSocio($socio->id_socio_presentatore);
				$socio->nome_socio_presentatore = $socio_presentatore->nome;
				$socio->cognome_socio_presentatore = $socio_presentatore->cognome;
			}


			$socio->motivazione = ( $socio->id_motivazione != '' ? $this->motivazioni->getMotivazione($socio->id_motivazione)->motivazione : '' );


			$lista_recapiti = $this->recapiti_telefonici->getAllRecapitiSocio($id_socio);
			if( count($lista_recapiti) > 0 ) {

				foreach( $lista_recapiti as $recapito ) {

					$recapito->tipologia_numero = $this->recapiti_telefonici->getTipologia($recapito->id_tipologia_numero)->etichetta;

				}

			}


			$abbonamenti_validi_attivi = $this->abbonamenti->getAllAbbonamentiAttiviEValidiSocio($id_socio);
			if( count($abbonamenti_validi_attivi) > 0 ) {

				foreach( $abbonamenti_validi_attivi as $abbonamento ) {

					$abbonamento->tipologia_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($abbonamento->id_tipo_abbonamento)->tipo;
					$abbonamento->data_scadenza = date('d/m/Y', $abbonamento->data_fine);

					$totale_pagamenti = $this->pagamenti->getSumPagamentiAbbonamento($abbonamento->id);
					$rimanente = $abbonamento->valore_abbonamento - $totale_pagamenti;
					$abbonamento->saldato = ( $rimanente > 0 ? 'No' : 'Sì' );

				}

			}

			$colloqui_verifica = $this->contatti->getAllColloquiVerificaSocio($id_socio);
			if( count($colloqui_verifica) > 0 ) {
				foreach( $colloqui_verifica as $colloquio ) {
					$colloquio->data = date('d/m/Y', $colloquio->data_e_ora);
					$colloquio->ora = date('H:i', $colloquio->data_e_ora);
					$desk = $this->personale->getUtente($colloquio->id_consulente);
					$colloquio->nome_desk = $desk->nome;
					$colloquio->cognome_desk = $desk->cognome;				
				}
			}

			$telefonate = $this->contatti->getAllTelefonateSocio($id_socio);
			if( count($telefonate) > 0 ) {
				foreach( $telefonate as $telefonata ) {
					$telefonata->data = date('d/m/Y', $telefonata->data_e_ora);
					$telefonata->ora = date('H:i', $telefonata->data_e_ora);
					$desk = $this->personale->getUtente($telefonata->id_consulente);
					$telefonata->nome_desk = $desk->nome;
					$telefonata->cognome_desk = $desk->cognome;				
				}
			}

			$dati_pagina['socio'] = $socio;
			$dati_pagina['recapiti'] = $lista_recapiti;
			$dati_pagina['abbonamenti'] = $abbonamenti_validi_attivi;
			$dati_pagina['colloqui_verifica'] = $colloqui_verifica;
			$dati_pagina['telefonate'] = $telefonate;

			$content = $this->load->view('moduli_pdf/modulo_scheda_socio', $dati_pagina, true);

			$document_title = "Scheda socio - ".$socio->cognome." ".$socio->nome;
			$nome_file = 'scheda_socio_'.$socio->cognome."_".$socio->nome;


			$obj_pdf = $this->header_pdf($document_title);
			$obj_pdf->AddPage('P', 'A4');
			$tagvs = array( 'p' => array(0 => array('h' => 100, 'n' => 0), 1 => array('h' => 100, 'n'
			=> 0)) );
			$obj_pdf->setHtmlVSpace($tagvs);
			$obj_pdf->writeHTML($content, true, false, true, false, '');
			$obj_pdf->Output($nome_file.'.pdf', 'I');

		} else {
			echo "ACCESSO NEGATO";
		}
	}
	
	function workOut($id_abbonamento) {
		if( $this->user->controlloAutenticazione() ) {	
			
			$session_login = $this->session->userdata('logged_in');
			$privilegi = $session_login['ruolo'];
			$id_utente_loggato = $session_login['id_utente'];
			
			$dati_pagina = array();

			$abbonamento = $this->abbonamenti->getAbbonamento($id_abbonamento);

			$rate = $this->rate->getAllRateAbbonamento($id_abbonamento);
			$abbonamento->scadenza_ultima_rata = date('d/m/Y', $abbonamento->data_fine);
			if( count($rate) > 0 ) {
				$index_last_rata = count($rate)-1;
				$abbonamento->scadenza_ultima_rata = date('d/m/Y', $rate[$index_last_rata]->data_scadenza);

				//RATE
				foreach( $rate as $rata ) {
					$rata->data_scadenza_str = date('d/m/Y', $rata->data_scadenza);
					$rata->numero_sequenziale_romano = $this->utility->romanic_number($rata->numero_sequenziale);
				}
			}
			$abbonamento->tipo = $this->abbonamenti->getTipologiaAbbonamento($abbonamento->id_tipo_abbonamento)->tipo;
			$abbonamento->data_inizio_str = date('d/m/Y', $abbonamento->data_inizio);
			$abbonamento->data_fine_str = date('d/m/Y', $abbonamento->data_fine);

			
			$id_socio = $abbonamento->id_socio;
			$socio = $this->socio->getSocio($id_socio);

			if( $socio->id_professione != '' ) {
				$socio->professione = $this->socio->getProfessione($socio->id_professione)->professione;
			} else {
				$socio->professione = '';
			}
			$socio->data_nascita_str = date('d/m/Y', $socio->data_nascita);


			$pagamenti = $this->pagamenti->getAllPagamentiAbbonamento($id_abbonamento);
			if( count($pagamenti) > 0 ) {

				foreach( $pagamenti as $pagamento ) {

					$pagamento->data = date('d/m/Y', $pagamento->data_ora);
					$pagamento->ora = date('H:i', $pagamento->data_ora);
					$pagamento->rata_riferimento = $this->rate->getRata($pagamento->id_rata)->numero_sequenziale;

				}
			}

			if( $privilegi != 3 ) {
				$desk = $this->personale->getUtente($socio->id_consulente);
			} else {
				$desk = $this->personale->getUtente($id_utente_loggato);
			}

			$palestra = $this->palestra->getPalestra($abbonamento->id_palestra);



			$dati_pagina['abbonamento'] = $abbonamento;
			$dati_pagina['rate'] = $rate;
			$dati_pagina['pagamenti'] = $pagamenti;
			$dati_pagina['socio'] = $socio;
			$dati_pagina['desk'] = $desk;
			$dati_pagina['nome_palestra'] = $palestra->nome;
			$dati_pagina['citta_palestra'] = $palestra->citta;
			//$dati_pagina['pagamenti'] = $pagamenti;

			$content = $this->load->view('moduli_pdf/modulo_work_out', $dati_pagina, true);

			$document_title = "Work Out Socio - ".$socio->cognome." ".$socio->nome;
			$nome_file = 'work_out_socio_'.$socio->cognome."_".$socio->nome;


			$obj_pdf = $this->header_pdf($document_title);
			$obj_pdf->AddPage('P', 'A4');
			$tagvs = array( 'p' => array(0 => array('h' => 100, 'n' => 0), 1 => array('h' => 100, 'n'
			=> 0)) );
			$obj_pdf->setHtmlVSpace($tagvs);
			$obj_pdf->writeHTML($content, true, false, true, false, '');


			$dati_pagina_due['nome_palestra'] = $palestra->nome;
			$content = $this->load->view('moduli_pdf/modulo_condizioni_generali', $dati_pagina_due, true);

			$obj_pdf->AddPage('P', 'A4');
			$tagvs = array( 'p' => array(0 => array('h' => 100, 'n' => 0), 1 => array('h' => 100, 'n'
			=> 0)) );
			$obj_pdf->setHtmlVSpace($tagvs);
			$obj_pdf->writeHTML($content, true, false, true, false, '');

			$obj_pdf->Output($nome_file.'.pdf', 'I');
		
		} else {
			echo "ACCESSO NEGATO";
		}
	}
	
	function recapitiSoci($id_palestra) {
		if( $this->user->controlloAutenticazione() ) {	
			
			$session_login = $this->session->userdata('logged_in');
			
			$dati_pagina = array();

			$soci = $this->socio->getAllSociPalestra($id_palestra);
			
			if( count($soci) > 0 ) {
				foreach( $soci as $socio ) {
					$recapiti_socio = array();
					$recapiti_socio = $this->recapiti_telefonici->getAllRecapitiSocio($socio->id);
					
					for($k=0; $k<count($recapiti_socio); $k++) {
						$recapiti_socio[$k]->tipo = $this->recapiti_telefonici->getTipologia($recapiti_socio[$k]->id_tipologia_numero)->etichetta;
					}
					/*
					if( $socio->email != '' ) {
						//echo $socio->id."<br>";
						$obj = new stdClass();
						$obj->tipo = 'E-mail';
						$obj->numero = $socio->email;
						$recapiti_socio[] = $obj;
					}
					
					var_dump($socio->recapiti);*/
					$socio->recapiti = $recapiti_socio;
				}
			}
			
			$palestra = $this->palestra->getPalestra($id_palestra);



			$dati_pagina['soci'] = $soci;
			$dati_pagina['nome_palestra'] = $palestra->nome;
			//$dati_pagina['pagamenti'] = $pagamenti;
			
			$content = $this->load->view('moduli_pdf/elenco_recapiti_socio', $dati_pagina, true);

			$document_title = "Elenco recapiti socio - ".$palestra->nome;
			$nome_file = $palestra->nome.'_elenco_recapiti_socio';


			$obj_pdf = $this->header_pdf($document_title);
			$obj_pdf->AddPage('P', 'A4');
			$tagvs = array( 'p' => array(0 => array('h' => 100, 'n' => 0), 1 => array('h' => 100, 'n'
			=> 0)) );
			$obj_pdf->setHtmlVSpace($tagvs);
			$obj_pdf->writeHTML($content, true, false, true, false, '');
			
			$obj_pdf->Output($nome_file.'.pdf', 'I');
			
		} else {
			echo "ACCESSO NEGATO";
		}
	}
	
	private function header_pdf($document_title = "Documento Pdf") {
		tcpdf();
		$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$obj_pdf->SetCreator(PDF_CREATOR);
		$obj_pdf->SetTitle($document_title);
		$obj_pdf->setPrintHeader(false);
		$obj_pdf->setPrintFooter(false);
		$obj_pdf->SetDefaultMonospacedFont('helvetica');
		$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$obj_pdf->SetFont('helvetica', '', 9);
		$obj_pdf->setFontSubsetting(false);
		
		return $obj_pdf;
	}
	
}
?>