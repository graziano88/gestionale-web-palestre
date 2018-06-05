<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella soci_palestra

CREATE TABLE `soci_palestra` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `data_iscrizione` int(11) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `foto` varchar(250) NOT NULL,
  `indirizzo` varchar(150) NOT NULL,
  `citta` varchar(100) NOT NULL,
  `cap` varchar(5) NOT NULL,
  `provincia` varchar(2) NOT NULL,
  `nato_a` varchar(100) NOT NULL,
  `data_nascita` int(11) NOT NULL,
  `sesso` int(11) NOT NULL,
  `id_professione` varchar(36) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_socio_presentatore` varchar(36) NOT NULL,
  `id_consulente` varchar(36) NOT NULL,
  `id_coordinatore` varchar(36) NOT NULL,
  `id_fonte_provenienza` varchar(36) NOT NULL,
  `id_motivazione` varchar(36) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella professioni

CREATE TABLE `professioni` (
  `id` varchar(10) NOT NULL,
  `id_palestra` varchar(20) NOT NULL,
  `professione` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella fonti_provenienza

CREATE TABLE `fonti_provenienza` (
  `id` varchar(10) NOT NULL,
  `id_palestra` varchar(20) NOT NULL,
  `fonte` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class Socio extends CI_Model {
	
	
	
	
	
	
	
	
	/* GESTIONE SOCIO */
	
	private $tabella_soci = 'soci_palestra';
	
	public function insertSocio($dati_socio) {
		return $this->db->insert($this->tabella_soci, $dati_socio);
	}
	
	public function deleteSocio($id_socio) {
		$this->db->where('id', $id_socio);
		return $this->db->delete($this->tabella_soci);
	}
	
	public function deleteAllSociPalestra($id_palestra) {
		//elimina tutti i soci presenti nella palestra
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_soci);
	}
	
	/*
	public function deleteAllSoci() {
		//elimina tutti i soci dal DB (da non usare) ((DISABILITATA))
	}
	*/
	
	public function updateSocio($id_socio, $dati_socio) {
		$this->db->where('id', $id_socio);
		return $this->db->update($this->tabella_soci, $dati_socio);
	}
	
	public function updateSocioPresentatore($old_id_socio_presentatore, $new_id_socio_presentatore) {
		$this->db->where('id_socio_presentatore', $old_id_socio_presentatore);
		$array_update = array();
		$array_update['id_socio_presentatore'] = $new_id_socio_presentatore;
		
		return $this->db->update($this->tabella_soci, $array_update);
		
	}
	
	public function getAllSociPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutti i soci della palestra selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->from($this->tabella_soci);
		$this->db->order_by('cognome', 'ASC');
		$this->db->order_by('nome', 'ASC');
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllSoci($num_voci = null, $start = null) {
		//ottiene tutti i soci presenti nel DB
		$this->db->order_by('id_palestra', 'ASC');
		$this->db->order_by('cognome', 'ASC');
		$this->db->order_by('nome', 'ASC');
		$query = $this->db->get($this->tabella_soci, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllNewSoci($giorni_soglia, $num_voci = null, $start = null) {
		//ottiene tutti i soci presenti nel DB
		$now = time();
		$giorni_soglia_in_secondi = $giorni_soglia*86400;
		$soglia = $now-$giorni_soglia_in_secondi;
		
		$this->db->limit($num_voci, $start);
		$this->db->where('data_iscrizione >=', $soglia);
		$this->db->from($this->tabella_soci);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllNewSociPalestra($id_palestra, $giorni_soglia, $num_voci = null, $start = null) {
		//ottiene tutti i soci presenti nel DB
		$now = time();
		$giorni_soglia_in_secondi = $giorni_soglia*86400;
		$soglia = $now-$giorni_soglia_in_secondi;
		
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('data_iscrizione >=', $soglia);
		$this->db->from($this->tabella_soci);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllSociByConsulente($id_consulente, $num_voci = null, $start = null) {
		$this->db->limit($num_voci, $start);
		$this->db->where('id_consulente', $id_consulente);
		$this->db->from($this->tabella_soci);
		$this->db->order_by('cognome', 'ASC');
		$this->db->order_by('nome', 'ASC');
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getSocio($id_socio) {
		//ottine le info del socio solezionato
		$this->db->where('id', $id_socio);
		$this->db->from($this->tabella_soci);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function getIdPalestraSocio($id_socio) {
		//ottiene l'id_palestra del socio selezionato
		$this->db->select('id_palestra');
		$this->db->where('id', $id_socio);
		$this->db->from($this->tabella_soci);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			$result = $query->result();
			return $result[0]['id_palestra'];
		} else {
			return NULL;
		}
	}
	
	public function searchSocio($words, $id_palestra) {
		$sql = "SELECT * FROM ".$this->tabella_soci." WHERE id_palestra='".$id_palestra."' ";
		$parole_array = explode(' ', $words);
		$numero_parole = count($parole_array);
		if( $numero_parole > 0 ) {
			$sql .= " AND (";
			for( $i=0; $i<$numero_parole; $i++ ) {
				$word = $parole_array[$i];
				$sql .= " (nome LIKE '%".$word."%' OR cognome LIKE '%".$word."%')";
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
	
	public function checkExistConsulenteInSoci($id_consulente) {
		$this->db->where('id_consulente', $id_consulente);
		$this->db->or_where('id_coordinatore', $id_consulente);
		$this->db->from($this->tabella_soci);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	
	
	/* GESTIONE PROFESSIONI */
	
	private $tabella_professioni = 'professioni';
	
	public function insertProfessione($dati_professione) {
		return $this->db->insert($this->tabella_professioni, $dati_professione);
	}
	
	public function deleteProfessione($id_professione) {
		$this->db->where('id', $id_professione);
		return $this->db->delete($this->tabella_professioni);
	}
	
	public function deleteAllProfessioniPalestra($id_palestra) {
		//elimina tutte le professioni create e associate alla palestra corrispondente a id_palestra (da usare quando si elimina una palestra)
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_professioni);
	}
	
	public function updateProfessione($id_professione, $dati_professione) {
		$this->db->where('id', $id_professione);
		return $this->db->update($this->tabella_professioni, $dati_professione);
	}
	
	public function getAllProfessioniPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutte le professioni della palestra selezionata + quelle con id_palestra vuoto
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->or_where('id_palestra', '');
		$this->db->from($this->tabella_professioni);
		$this->db->order_by('professione', 'ASC');
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllProfessioni($num_voci = null, $start = null) {
		//ottiene tutte le professioni presenti nel DB
		$this->db->order_by('professione', 'ASC');
		$this->db->order_by('id_palestra', 'ASC');
		$query = $this->db->get($this->tabella_professioni, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getProfessione($id_professione) {
		//ottine le info della professione selezionata
		$this->db->where('id', $id_professione);
		$this->db->from($this->tabella_professioni);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	
	
	
	
	
	
	
	/* GESTIONE FONTI DI PROVENIENZA */
	
	private $tabella_fonti_provenienza = 'fonti_provenienza';
	
	public function insertFonte($dati_fonte) {
		return $this->db->insert($this->tabella_fonti_provenienza, $dati_fonte);
	}
	
	public function deleteFonte($id_fonte) {
		$this->db->where('id', $id_fonte);
		return $this->db->delete($this->tabella_fonti_provenienza);
	}
	
	public function deleteAllFontiPalestra($id_palestra) {
		//elimina tutte le fonti create e associate alla palestra corrispondente a id_palestra (da usare quando si elimina una palestra)
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_fonti_provenienza);
	}
	
	public function updateFonte($id_fonte, $dati_fonte) {
		$this->db->where('id', $id_fonte);
		return $this->db->update($this->tabella_fonti_provenienza, $dati_fonte);
	}
	
	public function getAllFontiPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutte le fonti della palestra selezionata + quelle con id_palestra vuoto
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->or_where('id_palestra', '');
		$this->db->from($this->tabella_fonti_provenienza);
		$this->db->order_by('fonte', 'ASC');
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllFonti($num_voci = null, $start = null) {
		//ottiene tutte le fonti presenti nel DB
		$this->db->order_by('fonte', 'ASC');
		$this->db->order_by('id_palestra', 'ASC');
		$query = $this->db->get($this->tabella_fonti_provenienza, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getFonte($id_fonte) {
		//ottine le info della fonte selezionata
		$this->db->where('id', $id_fonte);
		$this->db->from($this->tabella_fonti_provenienza);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
}

?>