<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella rinnovi_e_iscrizioni

CREATE TABLE `rinnovi_e_iscrizioni` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `tipo` int(11) NOT NULL,
  `data_e_ora` int(11) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cellulare` varchar(25) NOT NULL,
  `telefono` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_coordinatore` varchar(36) NOT NULL,
  `id_consulente` varchar(36) NOT NULL,
  `come_back` int(11) NOT NULL,
  `free_pass` int(11) NOT NULL,
  `id_tipo_abbonamento` varchar(36) NOT NULL,
  `missed` int(11) NOT NULL,
  `note` text NOT NULL,
  `id_motivazione` varchar(36) NOT NULL,
  `id_socio_registrato` varchar(36) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


- Tabella free_pass

CREATE TABLE `free_pass` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `id_desk` varchar(36) NOT NULL,
  `id_rinnovo_iscrizione` varchar(36) NOT NULL,
  `data_ora` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class RinnoviIscrizioni extends CI_Model {
	
	
	
	
	
	
	
	
	/* GESTIONE RINNOVI E ISCRIZIONI */
	
	private $tabella_rinnovi_e_iscrizioni = 'rinnovi_e_iscrizioni';
	
	public function insertRinnovoIscrizione($dati_rinnovo_iscrizione) {
		return $this->db->insert($this->tabella_rinnovi_e_iscrizioni, $dati_rinnovo_iscrizione);
	}
	
	public function deleteRinnovoIscrizione($id_rinnovo_iscrizione) {
		$this->db->where('id', $id_rinnovo_iscrizione);
		return $this->db->delete($this->tabella_rinnovi_e_iscrizioni);
	}
	
	public function deleteAllRinnoviIscrizioniPalestra($id_palestra) {
		//elimina tutti i colloqui verifica presenti nella palestra
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_rinnovi_e_iscrizioni);
	}
	
	public function updateRinnovoIscrizione($id_rinnovo_iscrizione, $dati_rinnovo_iscrizione) {
		$this->db->where('id', $id_rinnovo_iscrizione);
		return $this->db->update($this->tabella_rinnovi_e_iscrizioni, $dati_rinnovo_iscrizione);
	}
	
	public function getAllRinnoviIscrizioniPalestra($id_palestra, $num_voci = -1, $start = -1) {
		//ottiene tutti i colloqui verifica della palestra selezionata
		/*
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->where('mostra', 1);
		$this->db->order_by('data_e_ora', 'DESC');
		$this->db->from($this->tabella_rinnovi_e_iscrizioni);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		*/
		$sql = "SELECT ri.* FROM (SELECT id_concatenazione, MAX(data_e_ora) as MaxTime FROM ".$this->tabella_rinnovi_e_iscrizioni." WHERE missed=1 AND mostra=1 AND id_palestra='".$id_palestra."' ";
		
		$sql .= "GROUP BY id_concatenazione) r INNER JOIN ".$this->tabella_rinnovi_e_iscrizioni." AS ri ON r.id_concatenazione = ri.id_concatenazione AND ri.data_e_ora = r.MaxTime ";
		
		$sql .= "ORDER BY ri.data_e_ora DESC";
		
		$query = $this->db->query($sql);
		/*
		$this->db->where('mostra', 1);
		$this->db->order_by('data_e_ora', 'DESC');
		$query = $this->db->get($this->tabella_rinnovi_e_iscrizioni, $num_voci, $start);
		*/
		if( $query->num_rows() > 0 ) {
			$array_query = $query->result();
			
			if( $start >= 0 ) {
				$results = array();
				$h = 0;
				for( $i=0; $i<count($array_query); $i++ ) {
					if( $i >= $start ) {
						$results[$h] = $array_query[$i];
						$h++;
					}
					if( $h >= $num_voci ) {
						break;
					}
				}
				return $results;
			} else {
				return $array_query;
			}
		} else {
			return NULL;
		}
	}
	
	/* inutile */
	public function getAllRinnoviIscrizioni($num_voci = -1, $start = -1) {
		//ottiene tutti i colloqui verifica presenti nel DB
		$sql = "SELECT ri.* FROM (SELECT id_concatenazione, MAX(data_e_ora) as MaxTime FROM ".$this->tabella_rinnovi_e_iscrizioni." WHERE missed=1 AND mostra=1 AND id_consulente='".$id_desk."'  ";
		
		$sql .= "GROUP BY id_concatenazione) r INNER JOIN ".$this->tabella_rinnovi_e_iscrizioni." AS ri ON r.id_concatenazione = ri.id_concatenazione AND ri.data_e_ora = r.MaxTime ";
		
		$sql .= "ORDER BY ri.data_e_ora DESC";
		
		$query = $this->db->query($sql);
		/*
		$this->db->where('mostra', 1);
		$this->db->order_by('data_e_ora', 'DESC');
		$query = $this->db->get($this->tabella_rinnovi_e_iscrizioni, $num_voci, $start);
		*/
		if( $query->num_rows() > 0 ) {
			$array_query = $query->result();
			
			if( $start >= 0 ) {
				$results = array();
				$h = 0;
				for( $i=0; $i<count($array_query); $i++ ) {
					if( $i >= $start ) {
						$results[$h] = $array_query[$i];
						$h++;
					}
					if( $h >= $num_voci ) {
						break;
					}
				}
				return $results;
			} else {
				return $array_query;
			}
			
			//return $query->result();
		} else {
			return NULL;
		}
	}
	
	/* non dovrebbe essere più usato */
	public function getAllRinnoviIscrizioniByConsulente($id_consulente, $num_voci = null, $start = null) {
		$this->db->limit($num_voci, $start);
		$this->db->where('id_consulente', $id_consulente);
		$this->db->where('mostra', 1);
		$this->db->order_by('data_e_ora', 'DESC');
		$this->db->from($this->tabella_rinnovi_e_iscrizioni);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getRinnovoIscrizione($id_rinnovo_iscrizione) {
		//ottine le info del colloquio verifica solezionato
		$this->db->where('id', $id_rinnovo_iscrizione);
		$this->db->from($this->tabella_rinnovi_e_iscrizioni);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	public function searchInMissed($words, $id_palestra) {
		$sql = "SELECT ri.* FROM (SELECT id_concatenazione, MAX(data_e_ora) as MaxTime FROM rinnovi_e_iscrizioni WHERE (missed=1 OR free_pass=1) AND id_palestra='".$id_palestra."' AND mostra=1 ";
		
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
		$sql .= " GROUP BY id_concatenazione) r INNER JOIN rinnovi_e_iscrizioni AS ri ON r.id_concatenazione = ri.id_concatenazione AND ri.data_e_ora = r.MaxTime ORDER BY ri.data_e_ora DESC";
		
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	public function searchInRegistrati($words, $id_palestra) {
		$sql = "SELECT ri.* FROM (SELECT id_concatenazione, MAX(data_e_ora) as MaxTime FROM rinnovi_e_iscrizioni WHERE id_socio_registrato<>'' AND id_palestra='".$id_palestra."' ";
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
		$sql .= " GROUP BY id_concatenazione) r INNER JOIN rinnovi_e_iscrizioni AS ri ON r.id_concatenazione = ri.id_concatenazione AND ri.data_e_ora = r.MaxTime";
		
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	public function searchRinnovoIscrizione($words, $id_palestra, $id_desk = '') {
		$sql = "select * from ".$this->tabella_rinnovi_e_iscrizioni." where id_palestra='".$id_palestra."' AND mostra=1 ";
		if( $id_desk != '' ) {
			$sql .= " AND id_consulente='".$id_desk."' ";
		}
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
	
	
	public function getRinnoviIscrizioniMissed($id_palestra, $id_consulente = '', $num_voci = null, $start = null) {
		//echo "<script>alert('".$id_palestra."')</script>";
		$sql = "SELECT ri.* FROM (SELECT id_concatenazione, MAX(data_e_ora) as MaxTime FROM ".$this->tabella_rinnovi_e_iscrizioni." WHERE missed=1 AND mostra=1 AND id_palestra='".$id_palestra."'  ";
		$this->db->limit($num_voci, $start);
		if( $id_consulente != '' ) {
			$sql .= "AND id_consulente='".$id_consulente."' ";
			//$this->db->where('id_consulente', $id_consulente);
		}
		//$this->db->where('missed', 1);
		//$this->db->where('mostra', 1);
		$sql .= "GROUP BY id_concatenazione) r INNER JOIN ".$this->tabella_rinnovi_e_iscrizioni." AS ri ON r.id_concatenazione = ri.id_concatenazione AND ri.data_e_ora = r.MaxTime ";
		
		$sql .= "ORDER BY ri.data_e_ora ASC";
		//$this->db->order_by('data_e_ora', 'ASC');
		
		$query = $this->db->query($sql);
		//$this->db->from($this->tabella_rinnovi_e_iscrizioni);
		//$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	public function getNumberRinnoviIscrizioniMissed($id_palestra, $id_consulente = '') {
		return count($this->getRinnoviIscrizioniMissed($id_palestra, $id_consulente));
	}
	
	public function getRinnoviIscrizioniMissedDesk($id_palestra, $id_desk, $scadenza, $num_voci = -1, $start = -1) {
		//echo "<script>alert('".$id_palestra."')</script>";
		//$this->db->limit($num_voci, $start);
		$sql = "SELECT ri.* FROM (SELECT id_concatenazione, MAX(data_e_ora) as MaxTime FROM ".$this->tabella_rinnovi_e_iscrizioni." WHERE missed=1 AND mostra=1 AND id_palestra='".$id_palestra."' AND id_consulente='".$id_desk."'  ";
		
		$sql .= "GROUP BY id_concatenazione) r INNER JOIN ".$this->tabella_rinnovi_e_iscrizioni." AS ri ON r.id_concatenazione = ri.id_concatenazione AND ri.data_e_ora = r.MaxTime ";
		
		$sql .= "ORDER BY ri.data_e_ora ASC";
		
		$query = $this->db->query($sql);
		/*
		$this->db->where('id_consulente', $id_desk);
		$this->db->where('missed', 1);
		$this->db->where('mostra', 1);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_rinnovi_e_iscrizioni);
		$query = $this->db->get();
		*/
		if( $query->num_rows() > 0 ) {
			$now = time();
			$results = $query->result();
			
			$limite_scadenza = $scadenza*86400;
			$missed_alert = array();
			
			$j = 0;
			if( $start >= 0 ) {
				$h = 0;
				for($i=0; $i<count($results); $i++) {
					$soglia_limite = $results[$i]->data_e_ora+$limite_scadenza;

					if( $now < $soglia_limite ) {
						
						if( $h >= $start && $j < $num_voci ) {
							$missed_alert[$j] = $results[$i];
							$j++;
						}
						$h++;
					}
				}
			} else {
				for($i=0; $i<count($results); $i++) {
					$soglia_limite = $results[$i]->data_e_ora+$limite_scadenza;

					if( $now < $soglia_limite ) {
						$missed_alert[$j] = $results[$i];
						$j++;
					}
				}
			}
			//echo "<script>alert(".count($missed_alert).");</script>";
			return $missed_alert;
		} else {
			return NULL;
		}
	}
	public function getNumberRinnoviIscrizioniMissedDesk($id_palestra, $id_desk, $scadenza, $num_voci = -1, $start = -1) {
		return count($this->getRinnoviIscrizioniMissedDesk($id_palestra, $id_desk, $scadenza, $num_voci, $start));
	}
	
	public function getRinnoviIscrizioniFreePassExpired($id_palestra, $id_consulente = '', $num_voci = null, $start = null) {
		$now = time();
		$this->load->model('abbonamenti');
		$this->db->limit($num_voci, $start);
		if( $id_consulente != '' ) {
			$this->db->where('id_consulente', $id_consulente);
		}
		$this->db->where('free_pass', 1);
		$this->db->where('mostra', 1);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_rinnovi_e_iscrizioni);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			
			$results = $query->result();
			
			$free_pass_expired = array();			
			$j = 0;
			for($i=0; $i<count($results); $i++) {
				$id_tipo_abbonamento = $results[$i]->id_tipo_abbonamento;
				$durata_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($id_tipo_abbonamento)->durata;
				$durata_abbonamento *= 86400;
				$fine_validita = $results[$i]->data_e_ora + $durata_abbonamento;
				if( $fine_validita < $now ) {
					$free_pass_expired[$j] = $results[$i];
					$j++;
				}
			}
			return $free_pass_expired;
			
		} else {
			return NULL;
		}
	}
	public function getNumberRinnoviIscrizioniFreePassExpired($id_palestra, $id_consulente = '') {
		return count($this->getRinnoviIscrizioniFreePassExpired($id_consulente));
	}
	
	public function getRinnoviIscrizioniFreePassExpiredByBloccoScadenza($id_palestra, $id_consulente = '', $num_voci = null, $start = null) {
		$now = time();
		$this->load->model('abbonamenti');
		$this->db->limit($num_voci, $start);
		if( $id_consulente != '' ) {
			$this->db->where('id_consulente', $id_consulente);
		}
		$this->db->where('free_pass', 1);
		$this->db->where('mostra', 1);
		$this->db->where('blocco_scadenza_freepass', 0);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_rinnovi_e_iscrizioni);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			
			$results = $query->result();
			
			$free_pass_expired = array();			
			$j = 0;
			for($i=0; $i<count($results); $i++) {
				$id_tipo_abbonamento = $results[$i]->id_tipo_abbonamento;
				$durata_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($id_tipo_abbonamento)->durata;
				$durata_abbonamento *= 86400;
				$fine_validita = $results[$i]->data_e_ora + $durata_abbonamento;
				if( $fine_validita < $now ) {
					$free_pass_expired[$j] = $results[$i];
					$j++;
				}
			}
			return $free_pass_expired;
			
		} else {
			return NULL;
		}
	}
	
	public function getRinnoviIscrizioniFreePassWillExpired($id_palestra, $giorni_rimanenti, $id_consulente = '', $num_voci = null, $start = null) {
		$now = time();
		$this->load->model('abbonamenti');
		$this->db->limit($num_voci, $start);
		if( $id_consulente != '' ) {
			$this->db->where('id_consulente', $id_consulente);
		}
		$this->db->where('free_pass', 1);
		$this->db->where('mostra', 1);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_rinnovi_e_iscrizioni);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			
			$results = $query->result();
			
			$giorni_rimanenti *= 86400;
			$free_pass_will_expired = array();			
			$j = 0;
			for($i=0; $i<count($results); $i++) {
				$id_tipo_abbonamento = $results[$i]->id_tipo_abbonamento;
				$durata_abbonamento = $this->abbonamenti->getTipologiaAbbonamento($id_tipo_abbonamento)->durata;
				$durata_abbonamento *= 86400;
				$data_scadenza = $results[$i]->data_e_ora + $durata_abbonamento;
				$data_alert = $data_scadenza - $giorni_rimanenti;
				
				if( $data_alert < $now && $data_scadenza >= $now ) {
					$free_pass_will_expired[$j] = $results[$i];
					$j++;
				}
			}
			return $free_pass_will_expired;
			
		} else {
			return NULL;
		}
	}
	public function getNumberRinnoviIscrizioniFreePassWillExpired($id_palestra, $giorni_rimanenti, $id_consulente = '') {
		return count($this->getRinnoviIscrizioniFreePassWillExpired($id_palestra, $giorni_rimanenti, $id_consulente));
	}
	
	public function getRinnoviIscrizioniMissedAlertDesk($id_palestra, $id_desk, $scadenza_alert_giorni, $limite_scadenza_alert_giorni, $num_voci = null, $start = null) {
		$now = time();
		$this->db->limit($num_voci, $start);
		$this->db->where('id_consulente', $id_desk);
		$this->db->where('missed', 1);
		$this->db->where('mostra', 1);
		$this->db->order_by('data_e_ora', 'ASC');
		$this->db->from($this->tabella_rinnovi_e_iscrizioni);
		$query = $this->db->get();
		if( $query->num_rows() > 0 ) {
			$results = $query->result();
			$scadenza_alert_secondi = $scadenza_alert_giorni*86400;
			$limite_scadenza_alert_secondi = $limite_scadenza_alert_giorni*86400;
			
			$missed_alert = array();
			
			$j = 0;
			for($i=0; $i<count($results); $i++) {
				$soglia_alert = $results[$i]->data_e_ora+$scadenza_alert_secondi;
				$soglia_limite = $results[$i]->data_e_ora+$limite_scadenza_alert_secondi;
				
				if( $now >= $soglia_alert && $now < $soglia_limite ) {
					$missed_alert[$j] = $results[$i];
					$j++;
				}
			}
			
			return $missed_alert;
		} else {
			return null;
		}
		
	}
	public function getNumberRinnoviIscrizioniMissedAlertDesk($id_palestra, $id_desk, $scadenza_alert_giorni, $limite_scadenza_alert_giorni) {
		return count($this->getRinnoviIscrizioniMissedAlertDesk($id_palestra, $id_desk, $scadenza_alert_giorni, $limite_scadenza_alert_giorni));
	}
	
	
	
	public function removeRiferimentiSocio($id_socio) {
		$this->db->where('id_socio_registrato', $id_socio);
		$dati_update['id_socio_registrato'] = '';
		return $this->db->update($this->tabella_rinnovi_e_iscrizioni, $dati_update);
	}
	
	public function updateAllRinnoviIscrizioniByConcatenazione($id_concatenazione, $dati_rinnovo_iscrizione) {
		$this->db->where('id_concatenazione', $id_concatenazione);
		return $this->db->update($this->tabella_rinnovi_e_iscrizioni, $dati_rinnovo_iscrizione);
	}
	
	public function checkExistConsulenteInRinnoviIscrizioni($id_consulente) {
		$this->db->where('id_consulente', $id_consulente);
		$this->db->or_where('id_coordinatore', $id_consulente);
		$this->db->from($this->tabella_rinnovi_e_iscrizioni);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	//public function getAllRinnoviIscrizioniFreePass
	
	
	
	/* GESTIONE FREE PASS */
	private $tabella_free_pass = 'free_pass';
	
	public function insertFreePass($dati_free_pass) {
		return $this->db->insert($this->tabella_free_pass, $dati_free_pass);
	}
	
	public function deleteFreePass($id_free_pass) {
		$this->db->where('id', $id_free_pass);
		return $this->db->delete($this->tabella_free_pass);
	}
	
	public function deleteAllFreePassPalestra($id_palestra) {
		//elimina tutti i colloqui verifica presenti nella palestra
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_free_pass);
	}
	
	public function deleteFreePassByRinnovoIscrizione($id_rinnovo_iscrizione) {
		$this->db->where('id_rinnovo_iscrizione', $id_rinnovo_iscrizione);
		return $this->db->delete($this->tabella_free_pass);
	}
	
	public function getNumberOfFreePass() {
		$this->db->select('count(*)');
		$this->db->from($this->tabella_free_pass);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['count(*)'];
	}
	
	public function getNumberOfFreePassPalestra($id_palestra) {
		$this->db->select('count(*)');
		$this->db->from($this->tabella_free_pass);
		$this->db->where('id_palestra', $id_palestra);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['count(*)'];
	}
	
	public function getNumberOfFreePassDesk($id_desk) {
		$this->db->select('count(*)');
		$this->db->from($this->tabella_free_pass);
		$this->db->where('id_desk', $id_desk);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['count(*)'];
	}
	
}
?>