<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {
	
	//private $user_table = 'pesonale_palestre';
	private $round_crypt = 10;
	const TIME_EXPIRE = 28800; //8 ORE		//3600; //1 ORA
	
	public function controlloCredenziali($username, $password) {
		
		$password = md5($password);
		
		$this->db->select('id, ruolo, id_palestra');
		$this->db->from('pesonale_palestre');//($this->user_table);
		$this->db->where('username', $username);
		$this->db->where('password',  $password);
		
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) {
			// password and user are correct
			return $query->result();
			//return $this->generateCredentials($username, $password, time()); //true: success, false: failed
		} else {
			return false;
		}
	}
	
	public function controlloAutenticazione() {
		$this->load->helper('cookie');
		//$cookie = get_cookie('logged_in');
		if($this->session->userdata('logged_in')) {
			$session_data = $this->session->userdata('logged_in');
			
			$data['username'] = $session_data['username'];
			$username = $session_data['username'];
			$password_plus_user_plus_time_hash = $session_data['user_password_time'];
			$time_create = $session_data['time'];
			
			if( $this->user->checkCredentials($username, $password_plus_user_plus_time_hash, $time_create) ) {
				return true;
			} else {
				return false;
			}
		} else {
			//If no session, redirect to login page
			return false;
		}
	}
	
	public function checkCredentials($username, $password_plus_user_plus_time_hash, $time_create) {
		$time_now = time();
		$absolute_time = $time_now-$time_create;
		if( $absolute_time < self::TIME_EXPIRE ) {
			
			$this->db->select('password');
			$this->db->from('pesonale_palestre');//($this->user_table);
			$this->db->where('username', $username);
			
			$query = $this->db->get();
			
			if ($query->num_rows() == 1) {
			// password and user are correct
				$result = $query->result();
				$password = $result[0]->password;
				$password_system_plus_user_system_plus_time_create = $password.$username.$time_create;
			
				if(crypt($password_system_plus_user_system_plus_time_create, $password_plus_user_plus_time_hash) == $password_plus_user_plus_time_hash) {
					return true;
				} else {
					//$this->resetSession();
					//session_destroy();
					return false;
				}
			} else {
				return false;
			}
		} else {
			//$this->resetSession();
			//session_destroy();
			return false;	// id &/or code wrong
		}
		
	}
	
	/* GENERATE PASSWORD HASH */
	public function generateCredentials($user, $password, $time) {
		// CREATE SESSION WITH user_plus_time_hash, password_plus_time_hash, time
		$time_create = time();
		
		$password_plus_user_plus_time = $password.$user.$time_create;
		$total_hash = $this->better_crypt($password_plus_user_plus_time, $this->round_crypt);
		
		$random_fake_hash = $this->better_crypt($this->randomString(25), $this->round_crypt);
		
		return array('user_password_time' => $total_hash, 'time' => $time_create);
	}
	
	
	
	/* PRIVATE FUNCTIONS */
	
	/* CRYPT PASSWORD */
	private function better_crypt($input, $rounds = 7) {
		$salt = "";
		$salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
		for($i=0; $i < 22; $i++) {
			$salt .= $salt_chars[array_rand($salt_chars)];
		}
		return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
	}
	
	private function randomString($random_string_length) {
		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$string = '';
		 for ($i = 0; $i < $random_string_length; $i++) {
			  $string .= $characters[rand(0, strlen($characters) - 1)];
		 }
		 
		 return $string;
		//return substr(uniqid(rand(), true), 0, 8);
	}
}

?>