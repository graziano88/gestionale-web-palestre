<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella parametri_sistema

CREATE TABLE `parametri_sistema` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `alert_scadenza_palestre` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class Sistema extends CI_Model {
	
	
	
	
	/* GESTIONE PARAMETRI PALESTRA */
	
	private $tabella_parametri_sistema = 'parametri_sistema';
	
	public function insertParametriSistema($parametri_sistema) {
		return $this->db->insert($this->tabella_parametri_sistema, $parametri_sistema);
	}
	
	public function deleteParametriSistema($id_parametri_sistema) {
		$this->db->where('id', $id_parametri_sistema);
		return $this->db->delete($this->tabella_parametri_sistema);
	}
	
	public function updateParametriSistema($id_parametri_sistema, $parametri_sistema) {
		$this->db->where('id', $id_parametri_sistema);
		return $this->db->update($this->tabella_parametri_sistema, $parametri_sistema);
	}
	
	public function getParametriSistema() {
		$this->db->from($this->tabella_parametri_sistema);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	
	
}

?>