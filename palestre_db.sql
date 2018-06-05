-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2017 at 03:23 PM
-- Server version: 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `palestre`
--

-- --------------------------------------------------------

--
-- Table structure for table `abbonamenti_socio`
--

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


-- --------------------------------------------------------

--
-- Table structure for table `attivita_palestre`
--

CREATE TABLE `attivita_palestre` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `tipo_attivita` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attivita_palestre`
--

INSERT INTO `attivita_palestre` (`id`, `id_palestra`, `tipo_attivita`) VALUES
('4b4d685e-6937-4472-b6e1-494603a47b6f', '', 'Boxe'),
('2cf69c63-3faa-11e7-bdc4-448a5bcdae1a', '', 'Fitness più piscina'),
('2cf68df2-3faa-11e7-bdc4-448a5bcdae1a', '', 'Piscina'),
('2cf67e44-3faa-11e7-bdc4-448a5bcdae1a', '', 'Centro fitness');

-- --------------------------------------------------------

--
-- Table structure for table `bonus_socio`
--

CREATE TABLE `bonus_socio` (
  `id` varchar(36) NOT NULL,
  `id_socio` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `numero_giorni_bonus` int(11) NOT NULL,
  `id_tipo_abbonamento` varchar(36) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `colloqui_verifica`
--

CREATE TABLE `colloqui_verifica` (
  `id` varchar(36) NOT NULL,
  `id_socio` varchar(36) NOT NULL,
  `data_e_ora` int(11) NOT NULL,
  `id_consulente` varchar(36) NOT NULL,
  `descrizione` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `condizioni_contratto`
--

CREATE TABLE `condizioni_contratto` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `documento` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contatti`
--

CREATE TABLE `contatti` (
  `id` varchar(36) NOT NULL,
  `id_socio` varchar(36) NOT NULL,
  `data_e_ora` int(11) NOT NULL,
  `id_consulente` varchar(36) NOT NULL,
  `motivo` varchar(250) NOT NULL,
  `esito` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fatture_palestra`
--

CREATE TABLE `fatture_palestra` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `anno` int(11) NOT NULL,
  `numero_totale` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `fonti_provenienza`
--

CREATE TABLE `fonti_provenienza` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `fonte` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fonti_provenienza`
--

INSERT INTO `fonti_provenienza` (`id`, `id_palestra`, `fonte`) VALUES
('f8b4615b-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Pubblicità televisiva'),
('f8b45484-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Pubblicità radiofonica'),
('f8b44860-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Telemarketing'),
('f8b4392a-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Passaparola');

-- --------------------------------------------------------

--
-- Table structure for table `free_pass`
--

CREATE TABLE `free_pass` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `id_desk` varchar(36) NOT NULL,
  `id_rinnovo_iscrizione` varchar(36) NOT NULL,
  `data_ora` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `motivazioni_frequenza`
--

CREATE TABLE `motivazioni_frequenza` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `motivazione` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `motivazioni_frequenza`
--

INSERT INTO `motivazioni_frequenza` (`id`, `id_palestra`, `motivazione`) VALUES
('b8df6256-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Culturismo'),
('b8df5651-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Cura dell\'aspetto fisico'),
('b8df4693-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Benessere');

-- --------------------------------------------------------

--
-- Table structure for table `pagamenti_socio`
--

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


-- --------------------------------------------------------

--
-- Table structure for table `palestre`
--

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


-- --------------------------------------------------------

--
-- Table structure for table `parametri_palestra`
--

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

--
-- Dumping data for table `parametri_palestra`
--

-- --------------------------------------------------------

--
-- Table structure for table `parametri_sistema`
--

CREATE TABLE `parametri_sistema` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `alert_scadenza_palestre` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parametri_sistema`
--

INSERT INTO `parametri_sistema` (`id`, `id_palestra`, `alert_scadenza_palestre`) VALUES
('d7bfc651-b8c8-11e7-85ba-448a5bcdae1a', '', 30);

-- --------------------------------------------------------

--
-- Table structure for table `persone_riferimento_palestra`
--

CREATE TABLE `persone_riferimento_palestra` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `id_ruolo_personale` varchar(36) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `cellulare` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `pesonale_palestre`
--

CREATE TABLE `pesonale_palestre` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(250) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `ruolo` int(11) NOT NULL,
  `coordinatore` int(11) NOT NULL DEFAULT '1',
  `id_coordinatore` varchar(36) NOT NULL,
  `data_nascita` int(11) NOT NULL,
  `sesso` int(11) NOT NULL,
  `indirizzo` varchar(150) NOT NULL,
  `citta` varchar(50) NOT NULL,
  `cap` varchar(5) NOT NULL,
  `provincia` varchar(2) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pesonale_palestre`
--

INSERT INTO `pesonale_palestre` (`id`, `id_palestra`, `username`, `password`, `cognome`, `nome`, `ruolo`, `coordinatore`, `id_coordinatore`, `data_nascita`, `sesso`, `indirizzo`, `citta`, `cap`, `provincia`, `email`) VALUES
('d3d4bd9d-4faf-11e7-942e-246e9631c0c0', '', 'SU-test', 'd8578edf8458ce06fbc5bb76a58c5ca4', 'Rossi', 'Mario', 0, 1, '', 568425600, 0, 'Via Fasulla 123', 'Torino', '10100', 'TO', 'sdfvasd@fsdfs.com');

-- --------------------------------------------------------

--
-- Table structure for table `professioni`
--

CREATE TABLE `professioni` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `professione` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `professioni`
--

INSERT INTO `professioni` (`id`, `id_palestra`, `professione`) VALUES
('86b9aaf0-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Imprenditore/Imprenditrice'),
('86b99d54-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Segretario/a'),
('86b990ff-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Impiegato/a'),
('86b9849e-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Operaio/a'),
('86b97630-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Libero/a professionista'),
('9488b8a5-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Medico'),
('9488c741-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Infermiere/a');

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--

CREATE TABLE `rate` (
  `id` varchar(36) NOT NULL,
  `id_abbonamento` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `id_socio` varchar(36) NOT NULL,
  `numero_sequenziale` int(11) NOT NULL,
  `valore_rata` varchar(36) NOT NULL,
  `tipo` int(11) NOT NULL,
  `per` int(11) NOT NULL,
  `data_scadenza` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `recapiti_telefonici_palestre`
--

CREATE TABLE `recapiti_telefonici_palestre` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `id_tipologia_numero` varchar(36) NOT NULL,
  `numero` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `recapiti_telefonici_personale`
--

CREATE TABLE `recapiti_telefonici_personale` (
  `id` varchar(36) NOT NULL,
  `id_utente` varchar(36) NOT NULL,
  `id_tipologia_numero` varchar(36) NOT NULL,
  `numero` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `recapiti_telefonici_soci`
--

CREATE TABLE `recapiti_telefonici_soci` (
  `id` varchar(36) NOT NULL,
  `id_socio` varchar(36) NOT NULL,
  `id_tipologia_numero` varchar(36) NOT NULL,
  `numero` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `rinnovi_e_iscrizioni`
--

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
  `id_socio_presentatore` varchar(36) NOT NULL,
  `id_tipo_abbonamento` varchar(36) NOT NULL,
  `missed` int(11) NOT NULL,
  `note` text NOT NULL,
  `id_motivazione` varchar(36) NOT NULL,
  `id_socio_registrato` varchar(36) NOT NULL DEFAULT '',
  `id_concatenazione` varchar(36) NOT NULL,
  `mostra` int(11) NOT NULL DEFAULT '1',
  `blocco_scadenza_freepass` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `ruoli_personale_palestra`
--

CREATE TABLE `ruoli_personale_palestra` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `ruolo` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ruoli_personale_palestra`
--

INSERT INTO `ruoli_personale_palestra` (`id`, `id_palestra`, `ruolo`) VALUES
('3d02542c-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Segreteria'),
('3d024539-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Commerciale'),
('3d0225b1-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Titolare'),
('3d023603-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Ufficio');


-- --------------------------------------------------------

--
-- Table structure for table `soci_palestra`
--

CREATE TABLE `soci_palestra` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `data_iscrizione` int(11) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `foto` varchar(250) DEFAULT NULL,
  `indirizzo` varchar(150) NOT NULL,
  `citta` varchar(100) NOT NULL,
  `cap` varchar(5) NOT NULL,
  `provincia` varchar(2) NOT NULL,
  `nato_a` varchar(100) NOT NULL,
  `data_nascita` int(11) NOT NULL,
  `sesso` int(11) NOT NULL,
  `id_professione` varchar(36) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_socio_presentatore` varchar(36) DEFAULT NULL,
  `id_consulente` varchar(36) DEFAULT NULL,
  `id_coordinatore` varchar(36) DEFAULT NULL,
  `id_fonte_provenienza` varchar(36) DEFAULT NULL,
  `id_motivazione` varchar(36) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `telefonate`
--

CREATE TABLE `telefonate` (
  `id` varchar(36) NOT NULL,
  `id_socio` varchar(36) NOT NULL,
  `data_e_ora` int(11) NOT NULL,
  `id_consulente` varchar(36) NOT NULL,
  `motivo` varchar(250) NOT NULL,
  `esito` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `tipologia_numero_telefonico`
--

CREATE TABLE `tipologia_numero_telefonico` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `etichetta` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipologia_numero_telefonico`
--

INSERT INTO `tipologia_numero_telefonico` (`id`, `id_palestra`, `etichetta`) VALUES
('253c6e90-4d17-11e7-995b-448a5bcdae1a', '', 'Fax'),
('14e21bf7-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Cellulare'),
('14e22af0-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Fisso');

-- --------------------------------------------------------

--
-- Table structure for table `tipologie_abbonamento`
--

CREATE TABLE `tipologie_abbonamento` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `durata` int(11) NOT NULL,
  `costo_base` float NOT NULL,
  `freepass` int(11) NOT NULL,
  `giorni_gratuiti_socio` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipologie_abbonamento`
--

INSERT INTO `tipologie_abbonamento` (`id`, `id_palestra`, `tipo`, `durata`, `costo_base`, `freepass`, `giorni_gratuiti_socio`) VALUES
('6132a90a-7b86-11e7-a4b2-448a5bcdae1a', '', 'Standard', 30, 50, 0, 0),
('59d66e0a-9949-11e7-89db-448a5bcdae1a', '', 'Free Pass Standard', 15, 0, 1, 30);

-- --------------------------------------------------------

--
-- Table structure for table `ubicazioni_palestre`
--

CREATE TABLE `ubicazioni_palestre` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) DEFAULT NULL,
  `posizione` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ubicazioni_palestre`
--

INSERT INTO `ubicazioni_palestre` (`id`, `id_palestra`, `posizione`) VALUES
('c70e83eb-3fa9-11e7-bdc4-448a5bcdae1a', '', 'Periferia'),
('d8f1b208-3fa8-11e7-bdc4-448a5bcdae1a', '', 'Centro'),
('ace86b64-3fa8-11e7-bdc4-448a5bcdae1a', '', 'Centro commerciale');

-- --------------------------------------------------------

--
-- Table structure for table `voci_collaborazioni`
--

CREATE TABLE `voci_collaborazioni` (
  `id` varchar(36) NOT NULL,
  `id_palestra` varchar(36) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abbonamenti_socio`
--
ALTER TABLE `abbonamenti_socio`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `attivita_palestre`
--
ALTER TABLE `attivita_palestre`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `bonus_socio`
--
ALTER TABLE `bonus_socio`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `colloqui_verifica`
--
ALTER TABLE `colloqui_verifica`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `condizioni_contratto`
--
ALTER TABLE `condizioni_contratto`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `contatti`
--
ALTER TABLE `contatti`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `fatture_palestra`
--
ALTER TABLE `fatture_palestra`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `fonti_provenienza`
--
ALTER TABLE `fonti_provenienza`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `free_pass`
--
ALTER TABLE `free_pass`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `motivazioni_frequenza`
--
ALTER TABLE `motivazioni_frequenza`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `pagamenti_socio`
--
ALTER TABLE `pagamenti_socio`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `palestre`
--
ALTER TABLE `palestre`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `parametri_palestra`
--
ALTER TABLE `parametri_palestra`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `parametri_sistema`
--
ALTER TABLE `parametri_sistema`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `persone_riferimento_palestra`
--
ALTER TABLE `persone_riferimento_palestra`
  ADD UNIQUE KEY `id_utente_pelestra` (`id`);

--
-- Indexes for table `pesonale_palestre`
--
ALTER TABLE `pesonale_palestre`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `professioni`
--
ALTER TABLE `professioni`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `rate`
--
ALTER TABLE `rate`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `recapiti_telefonici_palestre`
--
ALTER TABLE `recapiti_telefonici_palestre`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `recapiti_telefonici_personale`
--
ALTER TABLE `recapiti_telefonici_personale`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `recapiti_telefonici_soci`
--
ALTER TABLE `recapiti_telefonici_soci`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `rinnovi_e_iscrizioni`
--
ALTER TABLE `rinnovi_e_iscrizioni`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `ruoli_personale_palestra`
--
ALTER TABLE `ruoli_personale_palestra`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `soci_palestra`
--
ALTER TABLE `soci_palestra`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `telefonate`
--
ALTER TABLE `telefonate`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tipologia_numero_telefonico`
--
ALTER TABLE `tipologia_numero_telefonico`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tipologie_abbonamento`
--
ALTER TABLE `tipologie_abbonamento`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `ubicazioni_palestre`
--
ALTER TABLE `ubicazioni_palestre`
  ADD UNIQUE KEY `id` (`id`);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
