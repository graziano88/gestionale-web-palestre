<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utility extends CI_Model {
	
	
	public $SECONDI_PER_GIORNO = 86400;
	
	public function generateId($table) {
		
		do {
			
			//$id_generated = $this->gen_uuid();
			$id_generated = $this->guidv4();
			
			$query = $this->db->get_where($table, array('id' => $id_generated));
			
		} while ($query->num_rows() > 0);
		
		return $id_generated;
	}
	
	public function generateIdConcatenazione($table) {
		
		do {
			
			//$id_generated = $this->gen_uuid();
			$id_generated = $this->guidv4();
			
			$query = $this->db->get_where($table, array('id_concatenazione' => $id_generated));
			
		} while ($query->num_rows() > 0);
		
		return $id_generated;
	}
	
	function gen_uuid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,

			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}
	
	function guidv4() {	// funzione migliore di gen_uuid()
		$data = openssl_random_pseudo_bytes(16);
		assert(strlen($data) == 16);

		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
	
	
	function errorDB($message) {
		$dati['message'] = $message;
		
		$this->load->view('error_msg/error_insert_db', $dati);
		
		//exit();
	}
	
	function errorMsg($title, $message) {
		$dati['title'] = $title;
		$dati['message'] = $message;
		
		$this->load->view('error_msg/error_msg', $dati);
		
		//exit();
	}
	
	/*
	public function generateString($max_length) {
		
		$str_length = $max_length;//rand(1, $max_length);
		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$string = '';
		 for ($i = 0; $i < $str_length; $i++) {
			  $string .= $characters[rand(0, strlen($characters) - 1)];
		 }
		 
		 return $string;
		
	}
	*/
	
	
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
	
	function checkAnnoRicevutePalestra($id_palestra, $anno) {
		if( $this->fatture_palestra->checkExistAnnoPalestra($id_palestra, $anno) ) {
			return true;
		} else {
			$dati_insert = array();
			$dati_insert['anno'] = $anno;
			$dati_insert['id_palestra'] = $id_palestra;
			$dati_insert['id'] = $this->generateId('fatture_palestra');
			if( $this->fatture_palestra->insertAnnoPalestra($dati_insert) ) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	function getCurrentYear() {
		$now = time();
		return date('Y', $now);
	}
	
	function minimoDividendoDivisibile($numero, $divisore) {
		$minimo_dividendo = $numero;
		for($i=0; $i<$numero; $i++) {

			if( ($minimo_dividendo % $divisore) == 0 ) {
				break;
			} else {
				$minimo_dividendo--;
			}

		}
		return $minimo_dividendo;
	}
	
	function romanic_number($integer, $upcase = true) { 
		$table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
		$return = ''; 
		while($integer > 0) { 
			foreach($table as $rom=>$arb) { 
				if($integer >= $arb) { 
					$integer -= $arb; 
					$return .= $rom; 
					break; 
				} 
			} 
		} 

		return $return; 
	}
	
}

?>