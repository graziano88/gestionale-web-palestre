<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella motivazioni_frequenza

CREATE TABLE `motivazioni_frequenza` (
  `id` varchar(20) NOT NULL,
  `id_palestra` varchar(20) NOT NULL,
  `motivazione` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella motivazioni_soci (NON USATA)

CREATE TABLE `motivazioni_soci` (
  `id_socio` varchar(20) NOT NULL,
  `id_motivazione` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class Motivazioni extends CI_Model {
	
	
	
	
	
	
	
	
	/* MOTIVAZIONI FREQUENZA */
	
	private $tabella_motivazioni_frequenza = 'motivazioni_frequenza';
	
	public function insertMotivazione($dati_motivazione) {
		return $this->db->insert($this->tabella_motivazioni_frequenza, $dati_motivazione);
	}
	
	public function deleteMotivazione($id_motivazione) {
		$this->db->where('id', $id_motivazione);
		return $this->db->delete($this->tabella_motivazioni_frequenza);
	}
	
	public function deleteAllMotivazioniPalestra($id_palestra) {
		//elimina tutte le motivazioni create e associate alla palestra corrispondente a id_palestra (da usare quando si elimina una palestra)
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_motivazioni_frequenza);
	}
	
	public function updateMotivazione($id_motivazione, $dati_motivazione) {
		$this->db->where('id', $id_motivazione);
		return $this->db->update($this->tabella_motivazioni_frequenza, $dati_motivazione);
	}
	
	public function getAllMotivazioniPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene la lista di tutte le motivazioni della palestra selezionate + le motivazioni con id_palestra vuoto (creati da SU)
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->or_where('id_palestra', '');
		$this->db->order_by('motivazione', 'ASC');
		$this->db->from($this->tabella_motivazioni_frequenza);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllMotivazioni($num_voci = null, $start = null) {
		//ottiene la lista di tutte le motivazioni in tutte le palestre (per SU)
		$this->db->order_by('motivazione', 'ASC');
		$query = $this->db->get($this->tabella_motivazioni_frequenza, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getMotivazione($id_motivazione) {
		$this->db->where('id', $id_motivazione);
		$this->db->from($this->tabella_motivazioni_frequenza);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	
	
	
	
	
	
	
	/* MOTIVAZIONI SOCI */
	/* TABELLA IN SOSPESO LA MOTIVAZIONE PER ORA E' UNA SOLA E L'ID è INSERITO NEL CAMPO ID_MOTIVAZIONE DEL SOCIO O DI RINNOVI_ISCRIZIONI
	private $tabella_motivazioni_soci = 'motivazioni_soci';
	
	public function insertMotivazioneSocio($dati_motivazione_socio) {
		// in ingresso array(id_socio, id_motivazione)
		
	}
	
	public function deleteMotivazione($id_motivazione) {
		//elimina la motivazione del socio identificata da id_motivazione
	}
	
	public function deleteAllMotivazioniSocio($id_socio) {
		//elimina tutte le motivazioni relative a quel socio
	}
	
	public function updateMotivazione($id_motivazione, $dati_motivazione_socio) {
		// in ingresso id_motivazione_vecchia + array(id_socio, id_motivazione_nuova)
		// fa delete motivazione_vecchia
		// fa insert motivazione_nuova
	}
	
	public function getAllMotivazioniSocio($id_socio, $num_voci = null, $start = null) {
		//restiusce tutti gli id_motivazione legati al socio
	}
	*/
}

?>