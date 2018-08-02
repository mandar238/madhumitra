<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
		parent::__construct();

				$this->load->model('userdata');
		}

	public function index()
	{
		$data['title'] = "Login";
		$this->load->view('backend/login',$data);
		
	}

	public function checkLogin()
	{
		$userName= trim($this->input->post('mobile_no'));
		$password= trim($this->input->post('password'));

		$query = $this->userdata->processLogin($userName,$password);

		$query1 = $this->userdata->activeLogin($userName,$password);

		$this->form_validation->set_rules('password', 'Password', 'required|callback_validateUser[' . $query->num_rows() . ']|callback_activeUser[' . $query1->num_rows() . ']');

		$this->form_validation->set_rules('mobile_no', 'Mobile Number', 'required');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_message('required', 'Enter %s');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('backend/login');
		}else{
			if($query){
				$query = $query->result();
				$user = array(
				 'USER_ID' => $query[0]->id,
				 'USER_NAME' => $query[0]->username,
				 'EMAIL' => $query[0]->email_id,
				 'MOBILE' => $query[0]->mobile_no,
				 'USER_TYPE' => $query[0]->user_type_id,
				);
				$data=array(   
			      'last_login' => date('Y-m-d H:i:s'),
			    );
				
				$query3 = $this->userdata->lastLogin($data,$query[0]->id);
				$this->session->set_userdata($user);
				if($this->session->userdata['USER_TYPE'] == 1){
					redirect('dashboard/admin');
				}else if($this->session->userdata['USER_TYPE'] == 2){
					redirect('dashboard/');
				}
			}
		}

		
	}


	
	public function checkLoginapi()
	{
		$json = file_get_contents('php://input');
		$data = json_decode($json,TRUE);

		//print_r($data);exit;
		if(empty($data['mobile'])){
			$result = array('status' => 'error', 'message' => 'Mobile Number is empty');
			echo json_encode($result);
			exit();
		}
		if(empty($data['password'])){
			$result = array('status' => 'error', 'message' => 'Password is empty');
			echo json_encode($result);
			exit();
		}

		$query = $this->userdata->processLogin($data['mobile'],$data['password']);

		if($query->num_rows() == 0){
			$result = array('status' => 'error', 'message' => 'Invalid mobile number or Password');
			echo json_encode($result);
			exit();
		}else{
			$query1 = $this->userdata->activeLogin($data['mobile'],$data['password']);

			if($query1->num_rows() == 0){
				$result = array('status' => 'error', 'message' => 'Your account is not activated, Please contact your admin');
				echo json_encode($result);
				exit();
			}else{
				$result = array('status' => 'success', 'message' => 'Login successfully', 'Profile' => $query->row_array());
				echo json_encode($result);
				exit();
			}
		}

	}

	public function validateUser($userName,$recordCount){
		if ($recordCount != 0){
			return TRUE;
		}else{
			$this->form_validation->set_message('validateUser', 'Invalid Mobile number or Password');
			return FALSE;
		}
	 }

	 public function activeUser($userName,$recordCount){
		if ($recordCount != 0){
			return TRUE;
		}else{
			$this->form_validation->set_message('activeUser', 'Your account is not activated, Please contact your admin');
			return FALSE;
		}
	 }

	public function error_page()
	{
		$data['title'] = "404 Error Page";
		$this->load->view('backend/error404',$data);
	}

	public function forgotPassword()
	{
		$data['title'] = "Forgot Password";
		$this->load->view('backend/forgotPassword',$data);
	}

	public function getPasswordapi()
	{
		//cho 'here';exit;
		$json = file_get_contents('php://input');
		$data = json_decode($json,TRUE);
		if(!empty($data['email_id'])){
			$email_id= trim($data['email_id']);

			$query = $this->userdata->getPassword($email_id);
			
			if($query->num_rows() > 0){
					
				$result = $query->row_array();

				$request = '';
				$smsmsg = "Hi ".$result['username'].", Your password for Madhumitra App for username:  ".$result['mobile_no']." is ".base64_decode($result['password']);
		    	$param = ["sender" => "MADHUM", "route" => 4, "mobiles" => $result['mobile_no'], "authkey" => "221214AMjrpP3i8xP5b28a757", "country" => "91", "message" => $smsmsg];
                //Have to encode the url values 
                foreach ($param as $key => $val) {
                    $request .= $key . "=" . urlencode($val); //we have to urlencode the values 
                    $request .= "&"; //append the ampersand (&) sign after each parameter/value pair 
                }
                $request = substr($request, 0, strlen($request) - 1); //remove final (&) sign from the request 
                $url = "http://api.msg91.com/api/sendhttp.php?" . $request;
		    	$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => $url,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "GET",
				  CURLOPT_SSL_VERIFYHOST => 0,
				  CURLOPT_SSL_VERIFYPEER => 0,
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				$this->load->library('email');
				//SMTP & mail configuration
				$config = array(
				    'protocol'  => 'smtp',
				    'smtp_host' => 'ssl://smtp.gmail.com',
				    'smtp_port' => 465,
				    'smtp_user' => 'app.madhumitra@gmail.com',
				    'smtp_pass' => 'Madhu@123#',
				    'mailtype'  => 'html',
				    'charset'   => 'utf-8'
				);
				$this->email->initialize($config);
				$this->email->set_mailtype("html");
				$this->email->set_newline("\r\n");
				//Email content
				$email_body = "Hi ".$result['username'].", Your password for Madhumitra App for username:  ".$result['mobile_no']." is ".base64_decode($result['password']);

				$this->email->to($email_id);
				$this->email->from('app.madhumitra@gmail.com','Madhumitra App');
				$this->email->subject('Madhumitra App Password');
				$this->email->message($email_body);
				//Send email
				$this->email->send();
				
				$result = array('status' => 'success', 'message' => 'Password has been send to your mail id');
				echo json_encode($result);
				exit();
			}else{
				$result = array('status' => 'error', 'message' => 'Please enter registered email id');
				echo json_encode($result);
				exit();
			}
		}else{			
			$result = array('status' => 'error', 'message' => 'Please enter registered email id');
			echo json_encode($result);
			exit();
		}
	}



	public function getPassword()
	{
		if(!empty($this->input->post('email_id'))){
		$email_id= trim($this->input->post('email_id'));

		$query = $this->userdata->getPassword($email_id);
		$this->form_validation->set_rules('email_id', 'Email Id', 'required|callback_validateEmail[' . $query->num_rows() . ']');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_message('required', 'Enter %s');

		if ($this->form_validation->run() == FALSE) {
			
			$data['title'] = "Forgot Password";
			$this->load->view('backend/forgotPassword',$data);
		}else{
			if($query->num_rows() > 0){
				$result = $query->row_array();
				$request = '';
				$smsmsg = "Hi ".$result['username'].", Your password for Madhumitra App for username:  ".$result['mobile_no']." is ".base64_decode($result['password']);
		    	$param = ["sender" => "MADHUM", "route" => 4, "mobiles" => $result['mobile_no'], "authkey" => "221214AMjrpP3i8xP5b28a757", "country" => "91", "message" => $smsmsg];
                //Have to encode the url values 
                foreach ($param as $key => $val) {
                    $request .= $key . "=" . urlencode($val); //we have to urlencode the values 
                    $request .= "&"; //append the ampersand (&) sign after each parameter/value pair 
                }
                $request = substr($request, 0, strlen($request) - 1); //remove final (&) sign from the request 
                $url = "http://api.msg91.com/api/sendhttp.php?" . $request;
		    	$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => $url,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "GET",
				  CURLOPT_SSL_VERIFYHOST => 0,
				  CURLOPT_SSL_VERIFYPEER => 0,
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				$this->load->library('email');
				//SMTP & mail configuration
				$config = array(
				    'protocol'  => 'smtp',
				    'smtp_host' => 'ssl://smtp.gmail.com',
				    'smtp_port' => 465,
				    'smtp_user' => 'app.madhumitra@gmail.com',
				    'smtp_pass' => 'Madhu@123#',
				    'mailtype'  => 'html',
				    'charset'   => 'utf-8'
				);
				$this->email->initialize($config);
				$this->email->set_mailtype("html");
				$this->email->set_newline("\r\n");
				//Email content
				$email_body = "Hi ".$result['username'].", Your password for Madhumitra App for username:  ".$result['mobile_no']." is ".base64_decode($result['password']);

				$this->email->to($email_id);
				$this->email->from('app.madhumitra@gmail.com','Madhumitra App');
				$this->email->subject('Madhumitra App Password');
				$this->email->message($email_body);
				//Send email
				$this->email->send();
				echo "<script>
						alert('Password has been send to your mail id');
						window.location.href='index/';
						</script>";
				//$this->session->set_flashdata('success', 'Password has been send to your registered mail id');
				//redirect('login/forgotPassword');
				}else{
					$data['title'] = "Forgot Password";
					$this->load->view('backend/forgotPassword',$data);
				}
			}
		}else{
			$this->form_validation->set_rules('email_id', 'Email Id', 'required');
			if ($this->form_validation->run() == FALSE) {
			
				$data['title'] = "Forgot Password";
				$this->load->view('backend/forgotPassword',$data);
			}

		}
	}

	public function validateEmail($emailId,$recordCount){
		if ($recordCount != 0){
			return TRUE;
		}else{
			$this->form_validation->set_message('validateEmail', 'Invalid %s');
			return FALSE;
		}
	 }

	public function logout()
	{
	    $user_data = $this->session->all_userdata();
	        foreach ($user_data as $key => $value) {
	            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
	                $this->session->unset_userdata($key);
	            }
	        }
	    $this->session->sess_destroy();
	    redirect('login/');
	}

	
}
