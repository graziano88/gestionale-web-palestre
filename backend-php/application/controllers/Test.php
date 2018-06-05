<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct() {
		parent::__construct();
		//$this->load->model('user', '', TRUE);
	}
	
	function index() {
		/*
		$this->load->model('contatti');
		
		echo $this->contatti->VERIFICA."<br>";
		
		echo $this->contatti->getTipoContattoConSocio(3);
		*/
		
		//$this->pdf();
		/*
		$this->load->model('contatti');
		$this->load->model('personale');
		$this->load->model('RinnoviIscrizioni', 'rinnovi_iscrizioni');
		$this->load->model('socio');
		var_dump($this->checkExistUtente('947ca4eb-2595-4f8a-8f6d-88859c13b6e2'));
		var_dump($this->checkExistUtente('24ca1c60-ae03-4c8a-8afc-84bb18c85d03'));
		*/
		$this->load->model("budget");
		$id_palestra = 'c88b98ae-8425-4ca5-891c-f6624c323aef';
		$collaborazioni = $this->budget->getAllVociBudgetPrincipaliPalestraByTipo($id_palestra, $this->budget->COLLABORAZIONI);
		if( count($collaborazioni) > 0 ) {
			foreach( $collaborazioni as $voce_collaborazione ) {

				$elenco_sotto_voci_collaborazione = $this->budget->getAllSottoVociBudgetPalestra($id_palestra, $voce_collaborazione->id);

				$voce_collaborazione->lock = false;
				if( $this->checkLockVoce($voce_collaborazione->id) ) {
					$voce_collaborazione->lock = true;
				}
				if( count($elenco_sotto_voci_collaborazione) > 0 ) {
					$voce_collaborazione->lock = true;
					foreach( $elenco_sotto_voci_collaborazione as $sotto_voce_collaborazione) {
						$sotto_voce_collaborazione->lock = false;
						if( $this->checkLockVoce($sotto_voce_collaborazione->id) ) {
							$sotto_voce_collaborazione->lock = true;
						}
					}
				}
				$voce_collaborazione->sotto_gruppi = $elenco_sotto_voci_collaborazione;
			}
		}
		
		/*
		$this->load->model('user');
		
		print_r($this->user->controlloAutenticazione());
		*/
		
		
		
		
		/*
		$this->load->model('FatturePalestra');
		$this->load->model('utility');
		$anno = 2018;
		$id_palestra = 'c88b98ae-8425-4ca5-891c-f6624c323aef';
		echo $this->utility->getCurrentYear();//checkAnnoRicevutePalestra($id_palestra, $anno);
		//echo 
		//var_dump($this->personale->getAllUtenti());
		*/
	}
	
	function checkExistUtente($id_utente) {
		return ( 
			$this->contatti->checkExistConsulenteInColloqui($id_utente) || 
			$this->contatti->checkExistConsulenteInTelefonate($id_utente) || 
			$this->contatti->checkExistConsulenteInContatti($id_utente) || 
			$this->personale->checkExistConsulenteAsCoordinatore($id_utente) || 
			$this->rinnovi_iscrizioni->checkExistConsulenteInRinnoviIscrizioni($id_utente) || 
			$this->socio->checkExistConsulenteInSoci($id_utente) ? true : false
		);
	}
	
	function pdf() {
    $this->load->helper('pdf_helper');
		/*
			---- ---- ---- ----
			your code here
			---- ---- ---- ----
		*/
		$dati_view1['citta_palestra'] = "Torino";
		$data['content'] = $this->load->view('test_modulo_work_out', $dati_view1, true);//$this->load->view('test2'); //"<html><head><style>h1 { color:red; }</style></head><boby><h1>Titolo del corpo</h1> Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla</body></html>";
		//var_dump($data['content']);
		$dati_view2['nome_palestra'] = 'Palestra 1';
		$data['content2'] = $this->load->view('test_modulo_condizioni_generali', $dati_view2, true);
		$data['title'] = "Titolo";
		$this->load->view('test', $data);
	}
	
	function getLoggedInArray($cookie_info) {
		
		$cookie_value_array = explode(';', $cookie_info);
		var_dump($cookie_value_array);
		$logged_in_array = array();
		$logged_in_array['user_password_time'] = $cookie_value_array[0];
		$logged_in_array['time'] = $cookie_value_array[1];
		$logged_in_array['id_utente'] = $cookie_value_array[2];
		$logged_in_array['ruolo'] = $cookie_value_array[3];
		$logged_in_array['id_palestra'] = $cookie_value_array[4];
		$logged_in_array['username'] = $cookie_value_array[5];
		
		return $logged_in_array;
	}

	function form() {
		
		/*
		if( $this->user->controlloAutenticazione() ) {
			redirect('home', 'refresh');
		} else {
			$data['title'] = 'Login Page';
			$this->load->helper(array('form'));
			$this->load->view('header', $data);
			$this->load->view('login_view');
			$this->load->view('footer');
		}*/
		$this->load->view('test');
		
	}
	
	function do_upload() {
		//$post_result['immagine_logo']
		$config = array(
			'file_name' => 'Pippo',
			'upload_path' => "./loghi_uploads/",
			'allowed_types' => "gif|jpg|png|jpeg|pdf",
			'overwrite' => TRUE,
			'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
		);
		$this->load->library('upload', $config);
		if( $this->upload->do_upload('prova') )
		{
			
			$data = array('upload_data' => $this->upload->data());
			var_dump($this->upload->data());
			//$this->load->view('upload_success',$data);
		}
		else
		{
			$error = array('error' => $this->upload->display_errors());
			echo $this->upload->display_errors();
			//$this->load->view('file_view', $error);
		}

	}
	
	private function checkLockVoce($id_voce) {
		return false;
	}

}

?>