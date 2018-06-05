<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

- Tabella pagamenti_socio

CREATE TABLE `pagamenti_socio` (
  `id` varchar(36) NOT NULL,
  `id_rata` varchar(36) NOT NULL,
  `id_socio` varchar(36) NOT NULL,
  `id_abbonamento` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `data_ora` int(11) NOT NULL,
  `id_desk` varchar(36) NOT NULL,
  `importo_pagato` float NOT NULL,
  `numero_ricevuta` varchar(36) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/

class Pagamenti extends CI_Model {
	
	
	
	
	
	
	
	
	/* GESTIONE Pagamenti Socio */
	
	private $tabella_pagamenti = 'pagamenti_socio';
	
	public function insertPagamento($dati_pagamento) {
		return $this->db->insert($this->tabella_pagamenti, $dati_pagamento);
	}
	
	public function deletePagamento($id_pagamento) {
		$this->db->where('id', $id_pagamento);
		return $this->db->delete($this->tabella_pagamenti);
	}
	
	public function deleteAllPagamentiSocio($id_socio) {
		//elimina tutti i pagamenti del socio selezionato
		$this->db->where('id_socio', $id_socio);
		return $this->db->delete($this->tabella_pagamenti);
	}
	
	public function deleteAllPagamentiPalestra($id_palestra) {
		//elimina tutti i pagamenti presenti nella palestra
		$this->db->where('id_palestra', $id_palestra);
		return $this->db->delete($this->tabella_pagamenti);
	}
	
	public function updatePagamento($id_pagamento, $dati_pagamento) {
		$this->db->where('id', $id_pagamento);
		return $this->db->update($this->tabella_pagamenti, $dati_pagamento);
	}
	
	public function getAllPagamentiRata($id_rata, $num_voci = null, $start = null) {
		//ottiene tutti i pagamenti del socio selezionato
		$this->db->limit($num_voci, $start);
		$this->db->where('id_rata', $id_rata);
		$this->db->order_by('data_ora', 'DESC');
		$this->db->from($this->tabella_pagamenti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllPagamentiSocio($id_socio, $num_voci = null, $start = null) {
		//ottiene tutti i pagamenti del socio selezionato
		$this->db->limit($num_voci, $start);
		$this->db->where('id_socio', $id_socio);
		$this->db->order_by('data_ora', 'DESC');
		$this->db->from($this->tabella_pagamenti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllPagamentiAbbonamento($id_abbonamento, $num_voci = null, $start = null) {
		//ottiene tutti i pagamenti del socio selezionato
		$this->db->limit($num_voci, $start);
		$this->db->where('id_abbonamento', $id_abbonamento);
		$this->db->order_by('data_ora', 'DESC');
		$this->db->from($this->tabella_pagamenti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllPagamentiPalestra($id_palestra, $num_voci = null, $start = null) {
		//ottiene tutti i pagamenti della palestra selezionata
		$this->db->limit($num_voci, $start);
		$this->db->where('id_palestra', $id_palestra);
		$this->db->order_by('data_ora', 'DESC');
		$this->db->from($this->tabella_pagamenti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllPagamenti($num_voci = null, $start = null) {
		//ottiene tutti i pagamenti presenti nel DB
		$this->db->order_by('data_ora', 'DESC');
		$this->db->order_by('id_palestra', 'ASC');
		$query = $this->db->get($this->tabella_pagamenti, $num_voci, $start);
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getAllPagamentiByDesk($id_desk, $num_voci = null, $start = null) {
		$this->db->limit($num_voci, $start);
		$this->db->where('id_desk', $id_desk);
		$this->db->order_by('data_ora', 'DESC');
		$this->db->from($this->tabella_pagamenti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else {
			return NULL;
		}
	}
	
	public function getPagamento($id_pagamento) {
		//ottine le info del pagamento solezionato
		$this->db->where('id', $id_pagamento);
		$this->db->from($this->tabella_pagamenti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			return $query->result()[0];
		} else {
			return NULL;
		}
	}
	
	/*
	public function getDataProssimoPagamento($id_pagamento) {
		//ottiene la data del prossimo pagamento
		$this->db->select('data_prossimo_pagamento');
		$this->db->where('id', $id_pagamento);
		$this->db->from($this->tabella_pagamenti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			$result = $query->result();
			return $result[0]['data_prossimo_pagamento'];
		} else {
			return NULL;
		}
	}
	*/
	
	public function getSumPagamentiAbbonamento($id_abbonamento) {
		$this->db->select_sum('importo_pagato');
		$this->db->where('id_abbonamento', $id_abbonamento);
		$this->db->from($this->tabella_pagamenti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			
			$result = $query->result()[0]->importo_pagato;
			return $result;
		} else {
			return 0;
		}
	}
	
	public function getSumPagamentiRata($id_rata) {
		$this->db->select_sum('importo_pagato');
		$this->db->where('id_rata', $id_rata);
		$this->db->from($this->tabella_pagamenti);
		$query = $this->db->get();
		
		if( $query->num_rows() > 0 ) {
			
			$result = $query->result()[0]->importo_pagato;
			if( $result != null )
				return $result;
			else
				return 0;
		} else {
			return 0;
		}
	}
	
	public function getNumberPagamentiRata($id_rata) {
		$this->db->where('id_rata', $id_rata);
		$this->db->from($this->tabella_pagamenti);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
}
?>