<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella abbonamenti_socio

CREATE TABLE `abbonamenti_socio` (
  `id` varchar(36) NOT NULL,
  `id_socio` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `id_tipo_abbonamento` varchar(36) NOT NULL,
  `data_inizio` int(11) NOT NULL,
  `data_fine` int(11) NOT NULL,
  `valore_abbonamento` float NOT NULL,
  `attivo` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

- Tabella tipologie_abbonamento

CREATE TABLE `tipologie_abbonamento` (
  `id` varchar(20) NOT NULL,
  `id_palestra` varchar(20) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `durata` int(11) NOT NULL,
  `costo_base` int(11) NOT NULL,
  `freepass` int(11) NOT NULL,
  `giorni_gratuiti_socio` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class Abbonamenti extends CI_Model {
	
	
	
	
	
	
	
	
	/* ABBONAMENTI SOCI */
	
	private $tabella_abbonamenti_socio = 'abbonamenti_socio';
	
	public function insertAbbonamento($dati_abbonamento) {
		return $this->db->insert($this->tabella_abbonamenti_socio, $dati_abbonamento);
	}
	
	public function deleteAbbonamento($id_abbonamento) {
		$this->db->where('id', $id_abbonamento);
		return $this->db->delete($this->tabella_abbonamenti_socio);
	}
	
	public function deleteAllAbbonamentiSocio($id_socio) {
		//elimina tutti gli abbonamenti legati al socio (occasioni: se si elimina un socio)
		$this->db->where('id_socio', $id_socio);
		return $this->db->delete($this->tabella_abbonamenti_socio);
		
	}
	
	public function deleteAllAbbonamentiPalestra($id_palestra) {
		//elimina tutti gli abbonamenti legati alla palestra (occasioni: se si elimina una palestra, prima usare questo si usa il deleteAllAbbonamentiSocio al momento dell'eliminazione dei soci)
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_abbonamenti_socio);
		
	}
	
	public function updateAbbonamento($id_abbonamento, $dati_abbonamento) {
		$this->db->where('id', $id_abbonamento);
		return $this->db->update($this->tabella_abbonamenti_socio, $dati_abbonamento);
	}
	
	public function getAllAbbonamentiSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti del socio selezionato
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->order_by('attivo', 'DESC');
		$this->db->order_by('data_inizio', 'DESC');
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllTipologieAbbonamentiValidiAttiviSocio($id_socio, $num_voci = null, $start = null) {
		$now = time();
		$this->db->limit($num_voci, $start);
		$this->db->select('id_tipo_abbonamento');
		$this->db->where('id_socio', $id_socio);
		$this->db->where('attivo', 1);
		$this->db->where('data_fine >=', $now);
		$this->db->order_by('attivo', 'DESC');
		$this->db->order_by('data_inizio', 'DESC');
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			$id_tipi_abbonamento = array();
			$results = $query->result();
			if( count($results) > 0 ) {
				$i = 0;
				foreach( $results as $result ) {
					$id_tipi_abbonamento[$i] = $result->id_tipo_abbonamento;
					$i++;
				}
			}
			return $id_tipi_abbonamento;
		} else {
			return NULL;
		}
	}
	
	public function getAllAbbonamentiPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti della palestra selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAbbonamentiByIdTipoAbbonamento($id_tipo_abbonamento, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti della palestra selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_tipo_abbonamento', $id_tipo_abbonamento);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAbbonamentiScadutiPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti scaduti della palestra selezionata
		$now = time();
		
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('data_fine <', $now);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAbbonamentiAttiviScadutiSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti scaduti della palestra selezionata
		$now = time();
		
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->where('data_fine <', $now);
		$this->db->where('attivo', 1);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAbbonamentiValidiPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti validi (non scaduti) della palestra selezionata
		$now = time();
		
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('data_fine >=', $now);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAbbonamentiValidiSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti validi (non scaduti) del socio selezionata
		$now = time();
		
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->where('data_fine >=', $now);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAbbonamentiInScadenzaPalestra($id_palestra, $giorni_preavviso, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti in scadenza della palestra selezionata
		$now = time();
		$secondi_preavviso = $giorni_preavviso*86400;
		$soglia = $secondi_preavviso+$now;
		
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('data_fine <', $soglia);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAbbonamentiAttiviPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti attivi della palestra selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('attivo', 1);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAbbonamentiAttiviSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti attivi del socio selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->where('attivo', 1);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAbbonamentiAttiviEValidiSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti attivi e non scaduti per un socio
		
		$now = time();
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->where('attivo', 1);
		$this->db->where('data_fine >=', $now);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAbbonamentiNonAttiviPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutti gli abbonamenti non attivi della palestra selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('attivo', 0);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAbbonamento($id_abbonamento) {
		//ottine le info dell'abbonamento selezionato
		$this->db->where('id', $id_abbonamento);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}		
	}
	
	/* SI OTTINE AGENDO CON UPDATEABBONAMENTO PASSANDO UN ARRAY CON SOLO LO STATO
	public function updateStatoAbbonamentoSocio($id_abbonamento, $nuovo_stato) {
		//aggiorna il campo 'Attivo' con la variabile $nuovo_stato (0 o 1)
	}
	*/
	
	public function checkScadenza($id_abbonamento, $giorni_preavviso) {
		//controlla che la data_fine relativa alla scadenza dell'abbonamento non abbia superato ad oggi la soglia per il preavviso
		//ritorna true se la scadenza si avvicina
		$this->db->select('data_fine');
		$this->db->where('id', $id_abbonamento);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			$result = $query->result();
			$data_fine = $result[0]['data_fine'];
			$now = time();
			$secondi_preavviso = $giorni_preavviso*86400;
			$soglia = $secondi_preavviso+$now;
			if( $soglia > $data_fine ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false; // errore l'abbonamento non esiste
		}
	}
	
	public function checkValiditaAbbonamento($id_abbonamento) {
		//controlla che l'abbonamento non sia scaduto
		//ritorna true se valido, false se scaduto
		$now = time();
		
		$this->db->select('data_fine');
		$this->db->where('id', $id_abbonamento);
		$this->db->from($this->tabella_abbonamenti_socio);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			$result = $query->result();
			$data_fine = $result[0]['data_fine'];
			if( $data_fine > $now ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false; // errore l'abbonamento non esiste
		}
	}
	
	
	
	
	
	
	
	
	/* TIPOLOGIE ABBONAMENTO */
	
	private $tabella_tipologie_abbonamento = 'tipologie_abbonamento';
	
	public function insertTipologiaAbbonamento($dati_tipologia) {
		return $this->db->insert($this->tabella_tipologie_abbonamento, $dati_tipologia);
	}
	
	public function deleteTipologiaAbbonamento($id_tipologia) {
		$this->db->where('id', $id_tipologia);
		return $this->db->delete($this->tabella_tipologie_abbonamento);
	}
	
	public function deleteAllTipologieAbbonamentoPalestra($id_palestra) {
		//elimina tutte le tipologie relative alla palestra
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_tipologie_abbonamento);
	}
	
	public function updateTipologiaAbbonamento($id_tipologia, $dati_tipologia) {
		$this->db->where('id', $id_tipologia);
		return $this->db->update($this->tabella_tipologie_abbonamento, $dati_tipologia);
	}
	
	public function getAllTipologieAbbonamentoPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottine tutte le tipologie di abbonamento della palestra + quelle con id_palestra vuoto
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->or_where('id_palestra', '');
		$this->db->order_by('tipo', 'ASC');
		$this->db->from($this->tabella_tipologie_abbonamento);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllTipologieAbbonamentoFreePassPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottine tutte le tipologie di abbonamento della palestra + quelle con id_palestra vuoto
		$this->db->limit($num_voci, $start);
		$where = '( id_palestra = "'.$id_palestra.'" OR id_palestra = "" )';
		$this->db->where($where);
		//$this->db->where('id_palestra', $id_palestra);
		//$this->db->or_where('id_palestra', '');
		$this->db->where('freepass', 1);
		$this->db->order_by('tipo', 'ASC');
		$this->db->from($this->tabella_tipologie_abbonamento);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllTipologieAbbonamentoNotFreePassPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottine tutte le tipologie di abbonamento della palestra + quelle con id_palestra vuoto
		$this->db->limit($num_voci, $start);
		$where = '( id_palestra = "'.$id_palestra.'" OR id_palestra = "" )';
		$this->db->where($where);
		//$this->db->where('id_palestra', $id_palestra);
		//$this->db->or_where('id_palestra', '');
		$this->db->where('freepass', 0);
		$this->db->order_by('tipo', 'ASC');
		$this->db->from($this->tabella_tipologie_abbonamento);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllTipologieAbbonamento($num_voci = null, $start = null) {
		//ottine tutte le tipologie di abbonamento (per SU)
		
		$query = $this->db->get($this->tabella_tipologie_abbonamento, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getTipologiaAbbonamento($id_tipologia) {
		$this->db->where('id', $id_tipologia);
		$this->db->from($this->tabella_tipologie_abbonamento);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	
}

?>