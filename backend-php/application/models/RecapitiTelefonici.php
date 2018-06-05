<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella recapiti_telefonici_palestre

CREATE TABLE `recapiti_telefonici_palestre` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `id_tipologia_numero` varchar(36) NOT NULL,
  `numero` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella recapiti_telefonici_personale

CREATE TABLE `recapiti_telefonici_personale` (
  `id` varchar(36) NOT NULL,
  `id_utente` varchar(36) NOT NULL,
  `id_tipologia_numero` varchar(36) NOT NULL,
  `numero` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella recapiti_telefonici_soci

CREATE TABLE `recapiti_telefonici_soci` (
  `id` varchar(36) NOT NULL,
  `id_socio` varchar(36) NOT NULL,
  `id_tipologia_numero` varchar(36) NOT NULL,
  `numero` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella tipologia_numero_telefonico

CREATE TABLE `tipologia_numero_telefonico` (
  `id` varchar(10) NOT NULL,
  `id_palestra` varchar(20) NOT NULL,
  `etichetta` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class RecapitiTelefonici extends CI_Model {
	
	
	
	
	
	
	
	
	/* RECAPITI PALESTRA */
	
	private $tabella_recapiti_palestre = 'recapiti_telefonici_palestre';
	
	public function insertRecapitoPalestra($dati_recapito) {
		return $this->db->insert($this->tabella_recapiti_palestre, $dati_recapito);
	}
	
	public function deleteRecapitoPalestra($id_recapito) {
		$this->db->where('id', $id_recapito);
		return $this->db->delete($this->tabella_recapiti_palestre);
	}
	
	public function deleteAllRecapitiPalestra($id_palestra) {
		//elimina tutti i recapiti della palestra selezionata
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_recapiti_palestre);
	}
	
	public function updateRecapitoPalestra($id_recapito, $dati_recapito) {
		$this->db->where('id', $id_recapito);
		return $this->db->update($this->tabella_recapiti_palestre, $dati_recapito);
	}
	
	public function getAllRecapitiPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutti i recapiti della palestra selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->from($this->tabella_recapiti_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllRecapitiPalestre($num_voci = null, $start = null) {
		//ottiene tutti i recapiti delle palestre nel DB
		
		$query = $this->db->get($this->tabella_recapiti_palestre, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getRecapitoPalestra($id_recapito) {
		$this->db->where('id', $id_recapito);
		$this->db->from($this->tabella_recapiti_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	
	
	
	
	
	
	
	/* RECAPITI PERSONALE (UTENTI)*/
	
	private $tabella_recapiti_personale = 'recapiti_telefonici_personale';
	
	public function insertRecapitoUtente($dati_recapito) {
		return $this->db->insert($this->tabella_recapiti_personale, $dati_recapito);
	}
	
	public function deleteRecapitoUtente($id_recapito) {
		$this->db->where('id', $id_recapito);
		return $this->db->delete($this->tabella_recapiti_personale);
	}
	
	public function deleteAllRecapitiUtente($id_utente) {
		//elimina tutti i recapiti dell'utente selezionato
		$this->db->where('id_utente', $id_utente);
		return $this->db->delete($this->tabella_recapiti_personale);
	}
	
	public function updateRecapitoUtente($id_recapito, $dati_recapito) {
		$this->db->where('id', $id_recapito);
		return $this->db->update($this->tabella_recapiti_personale, $dati_recapito);
	}
	
	public function getAllRecapitiUtente($id_utente, $num_voci = null, $start = null) {
		//ottiene tutti i recapiti dell'utente selezionato
		$this->db->limit($num_voci, $start);
		$this->db->where('id_utente', $id_utente);
		$this->db->from($this->tabella_recapiti_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllRecapitiUtenti($num_voci = null, $start = null) {
		//ottiene tutti i recapiti di tutti gli utenti nel DB
		
		$query = $this->db->get($this->tabella_recapiti_personale, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getRecapitoPersonale($id_recapito) {
		$this->db->where('id', $id_recapito);
		$this->db->from($this->tabella_recapiti_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	
	
	
	
	
	
	
	/* RECAPITI SOCI */
	
	private $tabella_recapiti_soci = 'recapiti_telefonici_soci';
	
	public function insertRecapitoSocio($dati_recapito) {
		return $this->db->insert($this->tabella_recapiti_soci, $dati_recapito);
	}
	
	public function deleteRecapitoSocio($id_recapito) {
		$this->db->where('id', $id_recapito);
		return $this->db->delete($this->tabella_recapiti_soci);
	}
	
	public function deleteAllRecapitiSocio($id_socio) {
		//elimina tutti i recapiti del socio selezionato
		$this->db->where('id_socio', $id_socio);
		return $this->db->delete($this->tabella_recapiti_soci);
	}
	
	public function updateRecapitoSocio($id_recapito, $dati_recapito) {
		$this->db->where('id', $id_recapito);
		return $this->db->update($this->tabella_recapiti_soci, $dati_recapito);
	}
	
	public function getAllRecapitiSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutti i recapiti del socio selezionato
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->from($this->tabella_recapiti_soci);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllRecapitiSoci($num_voci = null, $start = null) {
		//ottiene tutti i recapiti di tutti i soci nel DB
		
		$query = $this->db->get($this->tabella_recapiti_soci, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getRecapitoSocio($id_recapito) {
		$this->db->where('id', $id_recapito);
		$this->db->from($this->tabella_recapiti_soci);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	
	
	
	
	
	
	
	/* TIPOLOGIE NUMERI TELEFONICO */
	
	private $tabella_tipologia_numeri = 'tipologia_numero_telefonico';
	
	public function insertTipologia($dati_tipologia) {
		return $this->db->insert($this->tabella_tipologia_numeri, $dati_tipologia);
	}
	
	public function deleteTipologia($id_tipologia) {
		$this->db->where('id', $id_tipologia);
		return $this->db->delete($this->tabella_tipologia_numeri);
	}
	
	public function deleteAllTipologiePalestre($id_palestra) {
		//elimina tutte le tipologie di numeri create o associate alla palestra selezionata
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_tipologia_numeri);
	}
	
	public function updateTipologia($id_tipologia, $dati_tipologia) {
		$this->db->where('id', $id_tipologia);
		return $this->db->update($this->tabella_tipologia_numeri, $dati_tipologia);
	}
	
	public function getAllTipologiePalestra($id_palestra = '', $num_voci = null, $start = null) {
		//ottiene tutte le tipologie di numero telefonico della palestra selezionata + quelle con id_palestra vuoto
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->or_where('id_palestra', '');
		$this->db->order_by('etichetta', 'ASC');
		$this->db->from($this->tabella_tipologia_numeri);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
		
	}
	
	public function getAllTipologie($num_voci = null, $start = null) {
		//ottiene tutte le tipologie di numero telefonico nel DB
		$this->db->order_by('etichetta', 'ASC');
		$this->db->order_by('id_palestra', 'ASC');
		$query = $this->db->get($this->tabella_tipologia_numeri, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getTipologia($id_tipologia) {
		$this->db->where('id', $id_tipologia);
		$this->db->from($this->tabella_tipologia_numeri);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
}

?>