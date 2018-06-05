<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

Tabella free_pass

CREATE TABLE `free_pass` (
  `id` varchar(36) NOT NULL,
  `id_desk` varchar(36) NOT NULL,
  `data_ora` int(11) NOT NULL,
  `nome_cliente` varchar(50) NOT NULL,
  `cognome_cliente` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class FreePass extends CI_Model {
	private $tabella_free_pass = 'free_pass';
	/*
	public function insertUtente($dati_utente) {
		//INSERISCE L'UTENTE
		
		return $this->db->insert($this->tabella_personale, $dati_utente);
		
	}
	
	public function deleteUtente($id_utente) {
		//CANCELLA L'UTENTE
		$this->db->where('id', $id_utente);
		return $this->db->delete($this->tabella_personale);
	}
	
	public function deleteAllUtentiPalestra($id_palestra) {
		//elimina tutti gli utenti della palestra selezionata
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_personale);
	}
	
	public function updateUtente($id_utente, $dati_utente) {
		//AGGIORNA I DATI DELL'UTENTE
		$this->db->where('id', $id_utente);
		return $this->db->update($this->tabella_personale, $dati_utente);
		
	}
	// BASTA updateUtente BASTA VARIARE IL CONTENUTO DELL'ARRAY
	
	
	public function getAllUtentiPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutti gli utenti della palestra selezionata (ignora gli utenti con campo id_palestra vuoto)
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->order_by('ruolo', 'ASC');
		$this->db->order_by('username', 'ASC');
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}		
		
	}
	
	public function getAllUtenti($num_voci = null, $start = null) {
		//ottiene tutti gli utenti nel DB
		$this->db->order_by('ruolo', 'ASC');
		$this->db->order_by('id_palestra', 'ASC');
		$this->db->order_by('username', 'ASC');
		$query = $this->db->get($this->tabella_personale, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
		
	}
	
	public function getAllCoordinatori($num_voci = null, $start = null) {
		$this->db->limit($num_voci, $start);
		$this->db->where('coordinatore', 1);
		$this->db->order_by('ruolo', 'ASC');
		$this->db->order_by('username', 'ASC');
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllCoordinatoriPalestra($id_palestra, $num_voci = null, $start = null) {
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('coordinatore', 1);
		$this->db->order_by('ruolo', 'ASC');
		$this->db->order_by('username', 'ASC');
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllCoordinatiByCoordinatore($id_coordinatore, $num_voci = null, $start = null) {
		$this->db->limit($num_voci, $start);
		$this->db->where('id_coordinatore', $id_coordinatore);
		$this->db->order_by('ruolo', 'ASC');
		$this->db->order_by('username', 'ASC');
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllDeskPalestra($id_palestra, $num_voci = null, $start = null) {
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('ruolo', 3);
		$this->db->order_by('username', 'ASC');
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getCoordinatoreByCoordinato($id_coordinato) {
		$this->db->where('id', $id_coordinato);
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0]->id_coordinatore;
		} else {
			return '';
		}
	}
	
	public function getUtente($id_utente) {
		//RESTITUISCE I DATI DELL'UTENTE
		$this->db->where('id', $id_utente);
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
		
	}
	
	public function getPrivilegi($id_utente) {
		//RESTITUISCE IL RUOLO
		$this->db->select('ruolo');
		$this->db->where('id', $id_utente);
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}		
		
	}
	
	public function getIdPalestra($id_utente) {
		//RESTITUISCE L'ID DELLA PALESTRA DI APPARTENENZA
		$this->db->select('id_palestra');
		
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}		
		
	}
	
	
	public function getNumRowsAllUtentiPalestra($id_palestra) {
		$this->db->where('id', $id_palestra);
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function getNumRowsAllUtenti() {
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function checkExistUsername($username) {
		//controlla se esiste lo username selezionato
		$this->db->where('username', $username);
		$this->db->from($this->tabella_personale);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return true;
		} else {
			return false;
		}		
		
	}
	
	public function getRuoloString($ruolo_code) {
		switch($ruolo_code) {
			case 0:
				return 'Super-amministratore';
			case 1:
				return 'Amministratore palestra';
			case 2:
				return 'Personale amministrativo';
			case 3:
				return 'Desk';
		}
	}
	
	public function searchUtente($words, $id_palestra = '') {
		
		$sql = "select * from ".$this->tabella_personale." where id_palestra='".$id_palestra."'";
		$parole_array = explode(' ', $words);
		$numero_parole = count($parole_array);
		if( $numero_parole > 0 ) {
			$sql .= " AND (";
			for( $i=0; $i<$numero_parole; $i++ ) {
				$word = $parole_array[$i];
				$sql .= " (username LIKE '%".$word."%' OR  nome LIKE '%".$word."%' OR cognome LIKE '%".$word."%')";
				if($i != $numero_parole-1) {
					$sql .= " AND";
				}
			}
			$sql .= " )";
		}
		
		$sql .= " GROUP BY id";
		
		$query = $this->db->query($sql);
		return $query->result();
	}
	*/
}

?>