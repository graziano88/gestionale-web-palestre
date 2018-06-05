<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HeaderModel extends CI_Model {
	
	public function getHeader($title, $controller_redirect) {
		$header = array();
		$header['title'] = $title;//'Lista palestre';
		$header['controller_redirect'] = $controller_redirect;//'listapalestre';

		$session_login = $this->session->userdata('logged_in');
		$privilegi = $session_login['ruolo'];
		
		$header['id_utente'] = $session_login['id_utente'];
		
		switch($privilegi) {
		//if( $privilegi == 0 ) {
			case 0:
				$id_palestra = $session_login['id_palestra']; // vuoto nel caso di SU

				$palestra = $this->palestra->getPalestra($id_palestra);
				if( $palestra != null ) {
					$header['nome_palestra'] = $palestra->nome;
				} else {
					$header['nome_palestra'] = '';
				}

				$utente = $this->personale->getUtente($session_login['id_utente']);
				$header['nome_cognome'] = $utente->nome.' '.$utente->cognome;

				$header['privilegi'] = $privilegi;
				$header['id_palestra'] = $id_palestra;

				$numero_palestre = 0;
				$palestre = $this->palestra->getAllPalestre();
				$header['elenco_palestre'] = $palestre;
				break;
			default:
				$id_palestra = $session_login['id_palestra']; // vuoto nel caso di SU

				$palestra = $this->palestra->getPalestra($id_palestra);
				if( $palestra != null ) {
					$header['nome_palestra'] = $palestra->nome;
				} else {
					$header['nome_palestra'] = '';
				}
				
				$utente = $this->personale->getUtente($session_login['id_utente']);
				$header['nome_cognome'] = $utente->nome.' '.$utente->cognome;

				$header['privilegi'] = $privilegi;
				$header['id_palestra'] = $id_palestra;
				
				$header['elenco_palestre'] = null;
				break;
		}
		
		return $header;
	}
}