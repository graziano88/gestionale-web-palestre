<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella fatture_palestra

CREATE TABLE `fatture_palestra` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `anno` int(11) NOT NULL,
  `numero_totale` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class FatturePalestra extends CI_Model {
	
	
	
	
	
	
	
	
	/* GESTIONE Pagamenti Socio */
	
	private $tabella_fatture_palestra = 'fatture_palestra';
	
	public function addFatturaPalestra($id_palestra, $anno) {
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('anno', $anno);
		$this->db->set('numero_totale', 'numero_totale+1', FALSE);
		$this->db->update($this->tabella_fatture_palestra);
	}
	
	public function deleteFatturePalestra($id_palestra) {
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_fatture_palestra);
	}
	
	public function getNumerazioneFatturePalestra($id_palestra, $anno) {
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('anno', $anno);
		$this->db->from($this->tabella_fatture_palestra);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0]->numero_totale;
		} else {
			return NULL;
		}
	}
	
	public function checkExistAnnoPalestra($id_palestra, $anno) {
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('anno', $anno);
		$this->db->from($this->tabella_fatture_palestra);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function insertAnnoPalestra($dati_insert) {
		return $this->db->insert($this->tabella_fatture_palestra, $dati_insert);
	}
	
}
?>