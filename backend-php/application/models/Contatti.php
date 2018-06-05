<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*


- Tabella colloqui_verifica

CREATE TABLE `colloqui_verifica` (
  `id` varchar(150) NOT NULL,
  `id_socio` varchar(20) NOT NULL,
  `data_e_ora` int(11) NOT NULL,
  `id_consulente` varchar(20) NOT NULL,
  `descrizione` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella telefonate

CREATE TABLE `telefonate` (
  `id` varchar(150) NOT NULL,
  `id_socio` varchar(20) NOT NULL,
  `data_e_ora` int(11) NOT NULL,
  `id_consulente` varchar(20) NOT NULL,
  `motivo` varchar(250) NOT NULL,
  `esito` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella contatti

CREATE TABLE `contatti` (
  `id` varchar(150) NOT NULL,
  `id_socio` varchar(20) NOT NULL,
  `data_e_ora` int(11) NOT NULL,
  `id_consulente` varchar(20) NOT NULL,
  `motivo` varchar(250) NOT NULL,
  `esito` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

- Tabella contatti_con_socio

CREATE TABLE `contatti_con_soci` (
  `id` varchar(150) NOT NULL,
  `id_socio` varchar(20) NOT NULL,
  `data_e_ora` int(11) NOT NULL,
  `id_consulente` varchar(20) NOT NULL,
  `tipo` int(11) NOT NULL,
  `motivo` varchar(250) NOT NULL,
  `esito` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


*/

class Contatti extends CI_Model {	
	
	
	
	
	/* GESTIONE COLLOQUI DI VERIFICA */
	
	private $tabella_colloqui_verifica = 'colloqui_verifica';
	
	public function insertColloquioVerifica($dati_colloquio) {
		return $this->db->insert($this->tabella_colloqui_verifica, $dati_colloquio);
	}
	
	public function deleteColloquioVerifica($id_colloquio) {
		$this->db->where('id', $id_colloquio);
		return $this->db->delete($this->tabella_colloqui_verifica);
	}
	
	public function deleteAllColloquiVerificaSocio($id_socio) {
		//elimina tutti i colloqui verifica presenti nella palestra
		$this->db->where('id_socio', $id_socio);
		return $this->db->delete($this->tabella_colloqui_verifica);
	}
	
	/*
	public function deleteAllColloquiVerifica() {
		//elimina tutti i colloqui verifica dal DB
	}
	*/
	
	public function updateColloquioVerifica($id_colloquio, $dati_colloquio) {
		$this->db->where('id', $id_colloquio);
		return $this->db->update($this->tabella_colloqui_verifica, $dati_colloquio);
	}
	
	public function getAllColloquiVerificaSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutti i colloqui verifica della palestra selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_colloqui_verifica);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllColloquiVerifica($num_voci = null, $start = null) {
		//ottiene tutti i colloqui verifica presenti nel DB
		$this->db->order_by('data_e_ora', 'ASC');
		$query = $this->db->get($this->tabella_colloqui_verifica, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllColloquiVerificaByConsulente($id_consulente, $num_voci = null, $start = null) {
		$this->db->limit($num_voci, $start);
		$this->db->where('id_consulente', $id_consulente);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_colloqui_verifica);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getColloquioVerifica($id_colloquio) {
		//ottine le info del colloquio verifica solezionato
		$this->db->where('id', $id_colloquio);
		$this->db->from($this->tabella_colloqui_verifica);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function checkExistConsulenteInColloqui($id_consulente) {
		$this->db->where('id_consulente', $id_consulente);
		$this->db->from($this->tabella_colloqui_verifica);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	
	/* GESTIONE TELEFONATE */
	
	private $tabella_telefonate = 'telefonate';
	
	public function insertTelefonata($dati_telefonata) {
		return $this->db->insert($this->tabella_telefonate, $dati_telefonata);
	}
	
	public function deleteTelefonata($id_telefonata) {
		$this->db->where('id', $id_telefonata);
		return $this->db->delete($this->tabella_telefonate);
	}
	
	public function deleteAllTelefonateSocio($id_socio) {
		//elimina tutte le telefonate presenti nella palestra
		$this->db->where('id_socio', $id_socio);
		return $this->db->delete($this->tabella_telefonate);
	}
	
	/*
	public function deleteAllTelefonate() {
		//elimina tutte le telefonate dal DB
	}
	*/
	
	public function updateTelefonata($id_telefonata, $dati_telefonata) {
		$this->db->where('id', $id_telefonata);
		return $this->db->update($this->tabella_telefonate, $dati_telefonata);
	}
	
	public function getAllTelefonateSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutte le telefonate della palestra selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_telefonate);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllTelefonate($num_voci = null, $start = null) {
		//ottiene tutte le telefonate presenti nel DB
		$this->db->order_by('data_e_ora', 'ASC');
		$query = $this->db->get($this->tabella_telefonate, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllTelefonateByConsulente($id_consulente, $num_voci = null, $start = null) {
		$this->db->limit($num_voci, $start);
		$this->db->where('id_consulente', $id_consulente);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_telefonate);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getTelefonata($id_telefonata) {
		//ottine le info della telefonata verifica solezionato
		$this->db->where('id', $id_telefonata);
		$this->db->from($this->tabella_telefonate);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function checkExistConsulenteInTelefonate($id_consulente) {
		$this->db->where('id_consulente', $id_consulente);
		$this->db->from($this->tabella_telefonate);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	
	
	/* GESTIONE CONTATTI */
	
	private $tabella_contatti = 'contatti';
	
	public function insertContatto($dati_contatto) {
		return $this->db->insert($this->tabella_contatti, $dati_contatto);
	}
	
	public function deleteContatto($id_contatto) {
		$this->db->where('id', $id_contatto);
		return $this->db->delete($this->tabella_contatti);
	}
	
	public function deleteAllContattiSocio($id_socio) {
		//elimina tutti i contatti presenti nella palestra
		$this->db->where('id_socio', $id_socio);
		return $this->db->delete($this->tabella_contatti);
	}
	
	public function updateContatto($id_contatto, $dati_contatto) {
		$this->db->where('id', $id_contatto);
		return $this->db->update($this->tabella_contatti, $dati_contatto);
	}
	
	public function getAllContattiSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutti i contatti della palestra selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_contatti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllContatti($num_voci = null, $start = null) {
		//ottiene tutti i contattipresenti nel DB
		$this->db->order_by('data_e_ora', 'ASC');
		$query = $this->db->get($this->tabella_contatti, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllContattiByConsulente($id_consulente, $num_voci = null, $start = null) {
		$this->db->limit($num_voci, $start);
		$this->db->where('id_consulente', $id_consulente);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_contatti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getContatto($id_contatto) {
		//ottine le info del contatto verifica solezionato
		$this->db->where('id', $id_contatto);
		$this->db->from($this->tabella_contatti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function checkExistConsulenteInContatti($id_consulente) {
		$this->db->where('id_consulente', $id_consulente);
		$this->db->from($this->tabella_contatti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return true;
		} else {
			return false;
		}
	}


	
}
?>