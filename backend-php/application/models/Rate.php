<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella rate

CREATE TABLE `rate` (
  `id` varchar(36) NOT NULL,
  `id_abbonamento` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `id_socio` varchar(36) NOT NULL,
  `numero_sequenziale` int(11) NOT NULL,
  `valore_rata` varchar(36) NOT NULL,
  `tipo` int(11) NOT NULL,
  `per` int(11) NOT NULL,
  `data_scadenza` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class Rate extends CI_Model {
	
	
	
	
	
	
	
	
	/* GESTIONE Pagamenti Socio */
	
	private $tabella_rate = 'rate';
	
	public function insertRata($dati_rata) {
		return $this->db->insert($this->tabella_rate, $dati_rata);
	}
	
	public function deleteRata($id_rata) {
		$this->db->where('id', $id_rata);
		return $this->db->delete($this->tabella_rate);
	}
	
	public function deleteAllRateSocio($id_socio) {
		//elimina tutte le rate del socio selezionato
		$this->db->where('id_socio', $id_socio);
		return $this->db->delete($this->tabella_rate);
	}
	
	public function deleteAllRatePalestra($id_palestra) {
		//elimina tutte le rate presenti nella palestra
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_rate);
	}
	
	public function deleteAllRateAbbonamento($id_abbonamento) {
		//elimina tutte le rate presenti nella palestra
		$this->db->where('id_abbonamento', $id_abbonamento);
		return $this->db->delete($this->tabella_rate);
	}
	
	public function updateRata($id_rata, $dati_rata) {
		$this->db->where('id', $id_rata);
		return $this->db->update($this->tabella_rate, $dati_rata);
	}
	
	public function getAllRateSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutte le rate del socio selezionato
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->order_by('data_scadenza', 'DESC');
		$this->db->from($this->tabella_rate);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllRateAbbonamento($id_abbonamento, $num_voci = null, $start = null) {
		//ottiene tutte le rate del socio selezionato
		$this->db->limit($num_voci, $start);
		$this->db->where('id_abbonamento', $id_abbonamento);
		$this->db->order_by('numero_sequenziale', 'ASC');
		$this->db->from($this->tabella_rate);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getRata($id_rata) {
		//ottine le info della rata solezionata
		$this->db->where('id', $id_rata);
		$this->db->from($this->tabella_rate);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	
}
?>