<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella palestre

CREATE TABLE `palestre` (
  `id` varchar(36) NOT NULL,
  `ragione_sociale` varchar(150) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `attiva_dal` int(11) NOT NULL,
  `attiva_al` int(11) DEFAULT NULL,
  `immagine_logo` varchar(250) DEFAULT NULL,
  `indirizzo` varchar(150) NOT NULL,
  `citta` varchar(100) NOT NULL,
  `cap` varchar(5) NOT NULL,
  `provincia` varchar(2) NOT NULL,
  `partita_iva` varchar(50) NOT NULL,
  `sito_web` varchar(250) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `id_attivita_palestra` varchar(36) DEFAULT NULL,
  `mq` float NOT NULL,
  `mq_sala_attrezzi` float NOT NULL DEFAULT '0',
  `mq_sala_corsi` float NOT NULL DEFAULT '0',
  `mq_cadio_fitness` float NOT NULL DEFAULT '0',
  `mq_spinning` float NOT NULL DEFAULT '0',
  `mq_rowing` float NOT NULL DEFAULT '0',
  `mq_arti_marziali` float NOT NULL DEFAULT '0',
  `mq_piscina` float NOT NULL DEFAULT '0',
  `mq_thermarium` float NOT NULL DEFAULT '0',
  `id_ubicazione` varchar(36) NOT NULL,
  `parcheggi` int(11) NOT NULL,
  `rating_struttura` int(11) DEFAULT NULL,
  `rating_attrezzature` int(11) DEFAULT NULL,
  `rating_spogliatoi` int(11) DEFAULT NULL,
  `rating_pulizia` int(11) DEFAULT NULL,
  `rating_personale` int(11) DEFAULT NULL,
  `servizio_bar` int(11) NOT NULL,
  `shop` int(11) NOT NULL DEFAULT '0',
  `servizio_distributori` int(11) NOT NULL,
  `considerazioni_generali` text,
  `altro` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella ubicazioni_palestre

CREATE TABLE `ubicazioni_palestre` (
  `id` varchar(10) NOT NULL,
  `id_palestra` varchar(20) NOT NULL,
  `posizione` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella attivita_palestre

CREATE TABLE `attivita_palestre` (
  `id` varchar(10) NOT NULL,
  `id_palestra` varchar(20) NOT NULL,
  `tipo_attivita` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella persone_riferimento_palestra

CREATE TABLE `persone_riferimento_palestra` (
  `id` varchar(20) NOT NULL,
  `id_palestra` varchar(20) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `id_ruolo_personale` varchar(10) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `cellulare` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella ruoli_personale_palestra

CREATE TABLE `ruoli_personale_palestra` (
  `id` varchar(10) NOT NULL,
  `id_palestra` varchar(20) NOT NULL,
  `ruolo` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



**** PARAMETRI PALESTRA ****

- Tabella parametri_palestra

CREATE TABLE `parametri_palestra` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `primo_alert_missed` int(11) NOT NULL,
  `secondo_alert_missed` int(11) NOT NULL,
  `scadenza_missed` int(11) NOT NULL,
  `alert_scadenza_abbonamento` int(11) NOT NULL,
  `alert_scadenza_freepass` int(11) NOT NULL,
  `soglia_nuovi_soci` int(11) NOT NULL,
  `alert_scadenza_rata` int(11) NOT NULL DEFAULT '7'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class Palestra extends CI_Model {
	
	
	
	/* GESTIONE PALESTRA */
	
	private $tabella_palestre = 'palestre';
	
	public function insertPalestra($dati_palestra) {
		return $this->db->insert($this->tabella_palestre, $dati_palestra);
	}
	
	public function deletePalestra($id_palestra) {
		$this->db->where('id', $id_palestra);
		return $this->db->delete($this->tabella_palestre);
	}
	
	public function updatePalestra($id_palestra, $dati_palestra) {
		$this->db->where('id', $id_palestra);
		return $this->db->update($this->tabella_palestre, $dati_palestra);
	} // GLI UPDATE SINGOLI SI FANNO VARIANDO L'ARRAY $DATI_PALESTRA IN UPDATEPALESTRA
	
	public function getAllPalestre($num_voci = null, $start = null) {
		//ottiene tutte le palestre nel DB
		$this->db->order_by('attiva_dal', 'ASC');
		$query = $this->db->get($this->tabella_palestre, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
		
	}
	
	public function getPalestra($id_palestra) {
		$this->db->where('id', $id_palestra);
		$this->db->from($this->tabella_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function getNumRowsAllPalestre() {
		$this->db->from($this->tabella_palestre);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function getLogo($id_palestra) {
		$this->db->select('immagine_logo');
		$this->db->where('id', $id_palestra);
		$this->db->from($this->tabella_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0]->immagine_logo;
		} else {
			return NULL;
		}
		
	}
	
	public function searchPalestra($words, $giorni_preavviso = null) {
		
		$parole_array = explode(' ', $words);
		if( count($parole_array) > 0 ) {
			foreach( $parole_array as $word) {
				$this->db->like('nome', $word);
			}
		}
		
		$now = time();
		if( $giorni_preavviso != null ) {
			if( $giorni_preavviso >= 0 ) {
				$secondi_preavviso = $giorni_preavviso*86400;
				$soglia = $secondi_preavviso+$now;
				$this->db->where('attiva_al <', $soglia);
			} else if( $giorni_preavviso == -1 ) {
				$this->db->where('attiva_al <', $now);
			}
		}
		
		$this->db->group_by('id');
		
		$this->db->from($this->tabella_palestre);
		$query = $this->db->get();
		
		//if( $query->num_rows() > 0 ) {
			return $query->result();
		/*} else {
			return NULL;
		}*/
	}
	
	public function getAllPalestreScadute($num_voci = null, $start = null) {
		//ottiene tutte le palestre non più attive
		$now = time();
		
		$this->db->limit($num_voci, $start);
		$this->db->where('attiva_al <', $now);
		$this->db->from($this->tabella_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllPalestreInScadenza($giorni_preavviso, $num_voci = null, $start = null) {
		//ottiene tutte le palestre in scadenza
		$now = time();
		$secondi_preavviso = $giorni_preavviso*86400;
		$soglia = $secondi_preavviso+$now;
		
		$this->db->limit($num_voci, $start);
		$this->db->where('attiva_al <', $soglia);
		$this->db->where('attiva_al >', $now);
		$this->db->from($this->tabella_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function checkScadenzaPalestra($id_palestra, $giorni_preavviso) {
		//controlla che attiva_al relativa alla scadenza della palestra non abbia superato ad oggi la soglia per il preavviso
		//ritorna true se la scadenza si avvicina
		$this->db->select('attiva_al');
		$this->db->where('id', $id_palestra);
		$this->db->from($this->tabella_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			$result = $query->result();
			$attiva_al = $result[0]['attiva_al'];
			$now = time();
			$secondi_preavviso = $giorni_preavviso*86400;
			$soglia = $secondi_preavviso+$now;
			if( $soglia > $attiva_al ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false; // errore la palestra non esiste
		}
	}
	
	public function checkValiditaPalestra($id_palestra) {
		//controlla che la palestra non sia scaduta
		//ritorna true se valida, false se scaduta
		$now = time();
		
		$this->db->select('attiva_al');
		$this->db->where('id', $id_palestra);
		$this->db->from($this->tabella_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			$result = $query->result();
			$attiva_al = $result[0]['attiva_al'];
			if( $attiva_al > $now ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false; // errore la palestra non esiste
		}
	}
	
	
	
	
	/* GESTIONE UBICAZIONI */
	
	private $tabella_ubicazioni_palestre = 'ubicazioni_palestre';
	
	public function insertUbicazione($dati_ubicazione) {
		return $this->db->insert($this->tabella_ubicazioni_palestre, $dati_ubicazione);
	}
	
	public function deleteUbicazione($id_ubicazione) {
		$this->db->where('id', $id_ubicazione);
		return $this->db->delete($this->tabella_ubicazioni_palestre);
	}
	
	public function deleteAllUbicazioniPalestra($id_palestra) {
		//elimina tutte le ubicazini create o associate alla palestra selezionata
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_ubicazioni_palestre);
	}
	
	public function updateUbicazione($id_ubicazione, $dati_ubicazione) {
		$this->db->where('id', $id_ubicazione);
		return $this->db->update($this->tabella_ubicazioni_palestre, $dati_ubicazione);		
	}
	
	public function getAllUbicazioniPalestra($id_palestra = '', $num_voci = null, $start = null) {
		//ottiene tutte le ubicazini delle palestra selezionata + quelle con id_palestra vuoto
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->order_by('posizione', 'ASC');
		$this->db->from($this->tabella_ubicazioni_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllUbicazioni($num_voci = null, $start = null) {
		//ottiene tutte le ubicazioni nel DB
		$this->db->order_by('posizione', 'ASC');
		$query = $this->db->get($this->tabella_ubicazioni_palestre, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
		
	}
	
	public function getUbicazione($id_ubicazione) {
		$this->db->where('id', $id_ubicazione);
		$this->db->from($this->tabella_ubicazioni_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function getNumRowsAllUbicazioniPalestra($id_palestra) {
		$this->db->where('id', $id_palestra);
		$this->db->from($this->tabella_ubicazioni_palestre);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function getNumRowsAllUbicazioni() {
		$this->db->from($this->tabella_ubicazioni_palestre);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	
	
	
	
	
	
	/* GESTIONE ATTIVITA' PALESTRE */
	
	private $tabella_attivita_palestre = 'attivita_palestre';
	
	public function insertAttivita($dati_attivita) {
		return $this->db->insert($this->tabella_attivita_palestre, $dati_attivita);
	}
	
	public function deleteAttivita($id_attivita) {
		$this->db->where('id', $id_attivita);
		return $this->db->delete($this->tabella_attivita_palestre);
	}
	
	public function deleteAllAttivitaPalestra($id_palestra) {
		//elimina tutte le attività create o associate alla palestra selezionata
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_attivita_palestre);
		
	}
	
	public function updateAttivita($id_attivita, $dati_attivita) {
		$this->db->where('id', $id_attivita);
		return $this->db->update($this->tabella_attivita_palestre, $dati_attivita);
	}
	
	public function getAllAttivitaPalestra($id_palestra = '', $num_voci = null, $start = null) {
		//ottiene tutte le attività delle palestra selezionata + quelle con id_palestra vuoto
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->or_where('id_palestra', '');
		$this->db->order_by('tipo_attivita', 'ASC');
		$this->db->from($this->tabella_attivita_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllAttivita($num_voci = null, $start = null) {
		//ottiene tutte le attività nel DB
		$this->db->order_by('tipo_attivita', 'ASC');
		$query = $this->db->get($this->tabella_attivita_palestre, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
		
	}
	
	public function getAttivita($id_attivita) {
		$this->db->where('id', $id_attivita);
		$this->db->from($this->tabella_attivita_palestre);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function getNumRowsAllAttivitaPalestra($id_palestra) {
		$this->db->where('id', $id_palestra);
		$this->db->from($this->tabella_attivita_palestre);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function getNumRowsAllAttivita() {
		$this->db->from($this->tabella_attivita_palestre);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	
	
	
	
	
	
	/* GESTIONE PERSONE DI RIFERIMENTO */
	
	private $tabella_persone_riferimento_palestra = 'persone_riferimento_palestra';
	
	public function insertPersonaRif($dati_persona_rif) {
		return $this->db->insert($this->tabella_persone_riferimento_palestra, $dati_persona_rif);
	}
	
	public function deletePersonaRif($id_persona_rif) {
		$this->db->where('id', $id_persona_rif);
		return $this->db->delete($this->tabella_persone_riferimento_palestra);
	}
	
	public function deleteAllPersoneRifPalestra($id_palestra) {
		//elimina tutte le persone di riferimento create o associate alla palestra selezionata
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_persone_riferimento_palestra);
	}
	
	public function updatePersonaRif($id_persona_rif, $dati_persona_rif) {
		$this->db->where('id', $id_persona_rif);
		return $this->db->update($this->tabella_persone_riferimento_palestra, $dati_persona_rif);
	}
	
	public function getAllPersoneRifPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutte le persone di riferimento delle palestra selezionata + quelle con id_palestra vuoto
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->from($this->tabella_persone_riferimento_palestra);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllPersoneRif($num_voci = null, $start = null) {
		//ottiene tutte le persone di riferimento nel DB
		
		$query = $this->db->get($this->tabella_persone_riferimento_palestra, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
		
	}
	
	public function getPersonaRif($id_persona_rif) {
		$this->db->where('id', $id_persona_rif);
		$this->db->from($this->tabella_persone_riferimento_palestra);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function getNumRowsAllPersoneRifPalestra($id_palestra) {
		$this->db->where('id', $id_palestra);
		$this->db->from($this->tabella_persone_riferimento_palestra);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function getNumRowsAllPersoneRif() {
		$this->db->from($this->tabella_persone_riferimento_palestra);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	
	
	
	
	
	
	/* GESTIONE RUOLI PERSONE RIFERIMENTO */
	
	private $tabella_ruoli_personale_palestra = 'ruoli_personale_palestra';
	
	public function insertRuoloPersonaRif($dati_ruolo_persona_rif) {
		return $this->db->insert($this->tabella_ruoli_personale_palestra, $dati_ruolo_persona_rif);
	}
	
	public function deleteRuoloPersonaRif($id_ruolo_persona_rif) {
		$this->db->where('id', $id_ruolo_persona_rif);
		return $this->db->delete($this->tabella_ruoli_personale_palestra);
	}
	
	public function deleteAllRuoliPersoneRifPalestra($id_palestra) {
		//elimina tutti i ruoli per le persone di riferimento create o associate alla palestra selezionata
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_ruoli_personale_palestra);
	}
	
	public function updateRuoloPersonaRif($id_ruolo_persona_rif, $dati_ruolo_persona_rif) {
		$this->db->where('id', $id_ruolo_persona_rif);
		return $this->db->update($this->tabella_ruoli_personale_palestra, $dati_ruolo_persona_rif);
	}
	
	public function getAllRuoliPersoneRifPalestra($id_palestra = '', $num_voci = null, $start = null) {
		//ottiene tutti i ruoli per le persone di riferimento delle palestra selezionata + quelle con id_palestra vuoto
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->or_where('id_palestra', '');
		$this->db->order_by('ruolo', 'ASC');
		$this->db->from($this->tabella_ruoli_personale_palestra);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}	
	}
	
	public function getAllRuoliPersoneRif($num_voci = null, $start = null) {
		//ottiene tutti i ruoli per le persone di riferimento nel DB
		$this->db->order_by('ruolo', 'ASC');
		$query = $this->db->get($this->tabella_ruoli_personale_palestra, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
		
	}
	
	public function getRuoloPersonaRif($id_ruolo_persona_rif) {
		$this->db->where('id', $id_ruolo_persona_rif);
		$this->db->from($this->tabella_ruoli_personale_palestra);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function getNumRowsAllRuoliPersoneRifPalestra($id_palestra) {
		$this->db->where('id', $id_palestra);
		$this->db->from($this->tabella_ruoli_personale_palestra);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function getNumRowsAllRuoliPersoneRif() {
		$this->db->from($this->tabella_ruoli_personale_palestra);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	
	
	
	
	
	
	
	
	/**** PARAMETRI PALESTRA ****/
	
	
	
	
	/* GESTIONE SOGLIE MISSED DESK !!!!!!!OLD!!!!!*/
	/* OLD!!!
	private $tabella_soglie_missed_desk = 'soglie_missed_desk';
	
	public function insertSogliaMissedDesk($dati_soglia_missed_desk) {
		return $this->db->insert($this->tabella_soglie_missed_desk, $dati_soglia_missed_desk);
	}
	
	public function deleteSogliaMissedDesk($id_soglia_missed_desk) {
		$this->db->where('id', $id_soglia_missed_desk);
		return $this->db->delete($this->tabella_soglie_missed_desk);
	}
	
	public function deleteSogliaMissedDeskByPalestra($id_palestra) {
		//elimina tutti i ruoli per le persone di riferimento create o associate alla palestra selezionata
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_soglie_missed_desk);
	}
	
	public function updateSogliaMissedDesk($id_soglia_missed_desk, $dati_soglia_missed_desk) {
		$this->db->where('id', $id_soglia_missed_desk);
		return $this->db->update($this->tabella_soglie_missed_desk, $dati_soglia_missed_desk);
	}
	
	public function getSogliaMissedDesk($id_soglia_missed_desk) {
		$this->db->where('id', $id_soglia_missed_desk);
		$this->db->from($this->tabella_soglie_missed_desk);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function getSogliaMissedDeskByPalestra($id_palestra) {
		$this->db->where('id_palestra', $id_palestra);
		$this->db->from($this->tabella_soglie_missed_desk);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	*/
	
	
	/* GESTIONE PARAMETRI PALESTRA */
	
	private $tabella_parametri_palestra = 'parametri_palestra';
	
	public function insertParametriPalestra($parametri_palestra) {
		return $this->db->insert($this->tabella_parametri_palestra, $parametri_palestra);
	}
	
	public function deleteParametriPalestra($id_parametri_palestra) {
		$this->db->where('id', $id_parametri_palestra);
		return $this->db->delete($this->tabella_parametri_palestra);
	}
	
	public function deleteParametriPalestraByPalestra($id_palestra) {
		//elimina tutti i ruoli per le persone di riferimento create o associate alla palestra selezionata
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_parametri_palestra);
	}
	
	public function updateParametriPalestra($id_parametri_palestra, $parametri_palestra) {
		$this->db->where('id', $id_parametri_palestra);
		return $this->db->update($this->tabella_parametri_palestra, $parametri_palestra);
	}
	
	public function getParametriPalestra($id_parametri_palestra) {
		$this->db->where('id', $id_parametri_palestra);
		$this->db->from($this->tabella_parametri_palestra);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function getParametriPalestraByPalestra($id_palestra) {
		$this->db->where('id_palestra', $id_palestra);
		$this->db->from($this->tabella_parametri_palestra);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	
	
	
}

?>