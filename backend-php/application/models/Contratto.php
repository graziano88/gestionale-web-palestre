<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella condizioni_contratto

CREATE TABLE `condizioni_contratto` (
  `id` varchar(20) NOT NULL,
  `id_palestra` varchar(20) NOT NULL,
  `documento` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


*/

class Contratto extends CI_Model {
	
	
	
	
	
	
	
	
	/* GESTIONE CONTRATTO PALESTRA */
	
	private $tabella_contratti = 'condizioni_contratto';
	
	public function insertContratto($dati_contratto) {
		return $this->db->insert($this->tabella_contratti, $dati_contratto);
	}
	
	public function deleteContratto($id_contratto) {
		$this->db->where('id', $id_contratto);
		return $this->db->delete($this->tabella_contratti);
	}
	
	public function deleteContrattoPalestra($id_palestra) {
		//elimina tutti il contratto della palestre
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_contratti);
	}
	
	public function updateContratto($id_contratto, $dati_contratto) {
		$this->db->where('id', $id_contratto);
		return $this->db->update($this->tabella_contratti, $dati_contratto);
	}
	
	public function getContrattoPalestra($id_palestra) {
		//ottiene tutti il contratto della palestra selezionata
		$this->db->where('id_palestra', $id_palestra);
		$this->db->from($this->tabella_contratti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getContratto($id_contratto) {
		//ottine le info del contratto solezionato
		$this->db->where('id', $id_contratto);
		$this->db->from($this->tabella_contratti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllContratti() {
		//ottiene tutti i contratti presenti nel DB
		
		$query = $this->db->get($this->tabella_contratti, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	
}
?>