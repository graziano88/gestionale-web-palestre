<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*



*/

class BonusSocio extends CI_Model {
	
	
	
	
	
	
	
	
	/* MOTIVAZIONI FREQUENZA */
	
	private $tabella_bonus_socio = 'bonus_socio';
	
	public function insertBonus($dati_bonus) {
		return $this->db->insert($this->tabella_bonus_socio, $dati_bonus);
	}
	
	public function deleteBonus($id_bonus) {
		$this->db->where('id', $id_bonus);
		return $this->db->delete($this->tabella_bonus_socio);
	}
	
	public function deleteAllBonusPalestra($id_palestra) {
		//elimina tutte le motivazioni create e associate alla palestra corrispondente a id_palestra (da usare quando si elimina una palestra)
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_bonus_socio);
	}
	
	public function deleteAllBonusSocio($id_socio) {
		//elimina tutte le motivazioni create e associate alla palestra corrispondente a id_palestra (da usare quando si elimina una palestra)
		$this->db->where('id_socio', $id_socio);
		return $this->db->delete($this->tabella_bonus_socio);
	}
	
	public function getAllBonusPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene la lista di tutte le motivazioni della palestra selezionate + le motivazioni con id_palestra vuoto (creati da SU)
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->or_where('id_palestra', '');
		$this->db->order_by('id_socio', 'ASC');
		$this->db->from($this->tabella_bonus_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllBonusSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene la lista di tutte le motivazioni della palestra selezionate + le motivazioni con id_palestra vuoto (creati da SU)
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->order_by('numero_giorni_bonus', 'DESC');
		$this->db->from($this->tabella_bonus_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function numberBonusSocio($id_socio) {
		$bonus_socio = $this->getAllBonusSocio($id_socio);
		return ( count($bonus_socio) > 0 ? count($bonus_socio) : 0 );
	}
	
	public function getAllBonus($num_voci = null, $start = null) {
		//ottiene la lista di tutte le motivazioni in tutte le palestre (per SU)
		$this->db->order_by('id_socio', 'ASC');
		$query = $this->db->get($this->tabella_bonus_socio, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getBonus($id_bonus) {
		$this->db->where('id', $id_bonus);
		$this->db->from($this->tabella_bonus_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
}

?>