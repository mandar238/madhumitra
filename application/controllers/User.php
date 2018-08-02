<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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

		}

	public function index()
	{
		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		}else{
			redirect('login', 'refresh');
		}
		if($this->session->userdata['USER_TYPE'] == 1){
			 redirect('user/userlist');
		}
		$data['title'] = "User List";
		$data['userData']=$this->userdata->getRows();
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/userlist', $data);
		$this->load->view('backend/footer');
		$this->load->view('backend/userjs.php');
	}

	public function userSelected()
	{
		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		}else{
			redirect('login', 'refresh');
		}
		
		$data['title'] = "Dashboard";
		$data['userData']=$this->userdata->getRows();
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/blank');
		$this->load->view('backend/footer');
	}
	public function userlist()
	{
		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		 	$this->load->model('settingsdata');
		}else{
			redirect('login', 'refresh');
		}
		$data['title'] = "User List";
		$data['userData']=$this->userdata->getallRows();
		$data['userLimit']=$this->settingsdata->getRow();
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/alluserlist', $data);
		$this->load->view('backend/footer');
		$this->load->view('backend/userjs.php');
	}

	public function updateuserLimit(){
		print_r($_POST['no_of_emp']);

		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		 	$this->load->model('settingsdata');
		}else{
			redirect('login', 'refresh');
		}

		$data=array(      
		      'no_of_emp' => $_POST['no_of_emp']
		    );
			$msg=$this->settingsdata->update($data,1);
			if($msg =='success'){
				$this->session->set_flashdata('success', 'User Limit updated successfully');
				redirect('user/userlist');
			}else{
				$this->session->set_flashdata('error','Something is wrong, please try again');
				
				redirect('user/userlist');
			}

	}


	public function addUser()
	{
		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		}else{
			redirect('login', 'refresh');
		}
		$data['title'] = "Add User";
		$getdata['getUserData']= array();
		$getdata['cityData']=$this->citydata->getRows();
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/useradd',$getdata);
		$this->load->view('backend/footer');
	}

	public function editUser($id) {
		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		}else{
			redirect('login', 'refresh');
		}
		$data1['title'] = "Update User";
	    $data= array('id'=>$id);  
	    $getdata['getUserData']=$this->userdata->getRow($data);
	    $getdata['cityData']=$this->citydata->getRows();
	    $this->load->view('backend/header', $data1);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/useredit', $getdata);
		$this->load->view('backend/footer');
	    
	}

	public function saveUser() {
		$this->load->model('userdata');
		 	$this->load->model('citydata');
		 	$this->load->model('settingsdata');
		$result = $this->userdata->checkusercnt();
		$userLimit=$this->settingsdata->getRow();
		
		
		if(isset($_POST['id'])){
			$data= array('id'=>$_POST['id']);
			$getdata['getUserData']=$this->userdata->getRow($data);
			$original_username = $getdata['getUserData']['username'];
			$original_email = $getdata['getUserData']['email_id'];
			$original_mobile = $getdata['getUserData']['mobile_no'];

			
			if($this->input->post('email_id') != $original_email) {
			   $email_unique = '|is_unique[users.email_id]';
			} else {
			   $email_unique = '';
			}
			if($this->input->post('mobile_no') != $original_mobile) {
			   $mobile_unique = '|is_unique[users.mobile_no]';
			} else {
			   $mobile_unique = '';
			}
		}else{
		    $email_unique = '|is_unique[users.email_id]';
		    $mobile_unique = '|is_unique[users.mobile_no]';
		}
		$this->form_validation->set_rules('username', 'Name', 'trim|required');
		$this->form_validation->set_rules('relation', 'Relation', 'required');
		$this->form_validation->set_rules('gender', 'Gender', 'required');
		$this->form_validation->set_rules('birthdate', 'Birthdate', 'required');
		$this->form_validation->set_rules('email_id', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('mobile_no', 'Mobile Number ', 'required|regex_match[/^[789]\d{9}$/]');
		$this->form_validation->set_rules('weight', 'Weight', 'required');
		$this->form_validation->set_rules('height', 'Height', 'required');
		$this->form_validation->set_rules('lifestyle', 'Lifstyle', 'required');
		$this->form_validation->set_rules('city_id', 'City', 'required');
		
		if(isset($_POST['user_status'])){
			$user_status = 1;
		}else{
			$user_status = 0;
		}
		$password = substr($_POST['mobile_no'],4,10);
		$password = base64_encode($password);
		

		if(isset($_POST['id']) && $_POST['id'] > 0){
			if ($this->form_validation->run() == FALSE)
	        {
	        	$data['title'] = "Update User";
				$getdata['getUserData']= array(
					'id' => $_POST['id'],
					'username'=>ucwords($_POST['username']),
				    'relation'=>$_POST['relation'],
				    'birthdate'=>date('Y-m-d',strtotime($_POST['birthdate'])),
				    'gender'=>$_POST['gender'],
				    'email_id'=>$_POST['email_id'],
				    'mobile_no'=>$_POST['mobile_no'],
				    'weight'=>$_POST['weight'],
				    'height'=>$_POST['height'],
				    'lifestyle'=>$_POST['lifestyle'],
				    'city_id'=>$_POST['city_id'],
				    'pincode'=>$_POST['pincode']
				);
				$getdata['cityData']=$this->citydata->getRows();
				$this->load->view('backend/header', $data);
				$this->load->view('backend/sidebar');
				$this->load->view('backend/useredit',$getdata);
				$this->load->view('backend/footer');
	            return false;
	        }
			$data=array(      
		      'username'=>ucwords($_POST['username']),
		      'person_name'=>ucwords($_POST['username']),
		      'relation'=>$_POST['relation'],
		      'birthdate'=>date('Y-m-d',strtotime($_POST['birthdate'])),
		      'gender'=>$_POST['gender'],
		      'email_id'=>$_POST['email_id'],
		      'mobile_no'=>$_POST['mobile_no'],
		      'weight'=>$_POST['weight'],
		      'height'=>$_POST['height'],
		      'lifestyle'=>$_POST['lifestyle'],
		      'city_id'=>$_POST['city_id'],
			  'pincode'=>$_POST['pincode'],
		      'updated_by' => $this->session->userdata['USER_ID'],
		      'updated_at' => date('Y-m-d H:i:s'),
		    );
			$msg=$this->userdata->update($data,$_POST['id']);
			$id = $_POST['id'];
			if($msg =='success'){
				$this->session->set_flashdata('success', 'User data updated successfully');
				redirect('user/editUser/'.$id);
			}else{
				$this->session->set_flashdata('error','Something is wrong, please try again');
				
				redirect('user/editUser/'.$id);
			}
		}else{
			if(count($result) >= $userLimit){
				$this->session->set_flashdata("error","You can`t add more than $userLimit users");
				redirect('user/addUser');
			}
			if ($this->form_validation->run() == FALSE)
	        {
	        	$data['title'] = "Add User";
	        	$getdata['cityData']=$this->citydata->getRows();
				$this->load->view('backend/header', $data);
				$this->load->view('backend/sidebar');
				$this->load->view('backend/useradd',$getdata);
				$this->load->view('backend/footer');
	            return false;
	        }
			$data=array(      
		      'username'=>ucfirst($_POST['username']),
		      'person_name'=>ucwords($_POST['username']),
		      'password'=> $password,
		      'relation'=>$_POST['relation'],
		      'birthdate'=>date('Y-m-d',strtotime($_POST['birthdate'])),
		      'gender'=>$_POST['gender'],
		      'email_id'=>$_POST['email_id'],
		      'mobile_no'=>$_POST['mobile_no'],
		      'weight'=>$_POST['weight'],
		      'height'=>$_POST['height'],
		      'lifestyle'=>$_POST['lifestyle'],
		      'city_id'=>$_POST['city_id'],
			  'pincode'=>$_POST['pincode'],
		      'user_status'=>$user_status,
		      'user_type_id' => 2,
		      'created_by' => $this->session->userdata['USER_ID'],
		      'created_at' => date('Y-m-d H:i:s'),
		    );
			$msg=$this->userdata->insert($data);
			if($msg == 'success'){
				$this->session->set_flashdata('success', 'New user added successfully');
				redirect('user');
			}else{
				$this->session->set_flashdata('error','Something is wrong, please try again');
			    redirect('user/addUser');
			}
		}
	}


	public function getCitydataapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['login_user_id'])){
			$this->load->model('citydata');
			$cityData = $this->citydata->getRows();
			$result = array('status' => 'success', 'cityData' => $cityData);
			echo json_encode($result);
			exit();
		}else{
			$result = array('status' => 'error', 'message' => 'Login expire, logout and login again');
			echo json_encode($result);
			exit();
		}
	}

	public function getUserlistapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['login_user_id'])){
			$this->load->model('userdata');
			$userData = $this->userdata->getUserList($postData);
			$result = array('status' => 'success', 'userData' => $userData);
			echo json_encode($result);
			exit();
		}else{
			$result = array('status' => 'error', 'message' => 'Login expire, logout and login again');
			echo json_encode($result);
			exit();
		}
	}


	public function getMasterdataapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['login_user_id'])){
			$this->load->model('activitydata');
			//$this->load->model('bslestimationdata');
			$this->load->model('categorydata');
			$this->load->model('citydata');
			//$this->load->model('doctordata');
			$this->load->model('exercisedata');
			//$this->load->model('hbaestimationdata');
			//$this->load->model('itemdata');
			//$this->load->model('mealdata');
			//$this->load->model('prescriptiondata');
			$this->load->model('schedulesettingdata');
			//$this->load->model('useractivitydata');
			$this->load->model('userdata');
			//$this->load->model('userexercisedata');

			$activityData = $this->activitydata->getRows();
			//$bslestimationdata = $this->bslestimationdata->getData($postData);
			//$doctorData = $this->doctordata->getData($postData);
			$exercisedata = $this->exercisedata->getRows();
			$categoryData = $this->categorydata->getRows();
			$cityData = $this->citydata->getRows();
			$schedulesettingdata = $this->schedulesettingdata->getRows();
			$userData = $this->userdata->getUserList($postData);

			$result = array('status' => 'success', 'activityData' => $activityData, 'exercisedata' => $exercisedata, 'categoryData' => $categoryData, 'cityData' => $cityData, 'settings' => $schedulesettingdata, 'userList' => $userData);
			echo json_encode($result);
			exit();

		}else{
			$result = array('status' => 'error', 'message' => 'Please logout and login again');
			echo json_encode($result);
			exit();
		}

	}


	public function getSelectedUserDataapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['selected_user_id'])){
			
			$this->load->model('bslestimationdata');
			$this->load->model('doctordata');
			$this->load->model('hbaestimationdata');
			//$this->load->model('itemdata');
			$this->load->model('mealdata');
			$this->load->model('prescriptiondata');
			$this->load->model('useractivitydata');
			$this->load->model('userexercisedata');

			$bslestimationdata = $this->bslestimationdata->getData($postData);
			$doctorData = $this->doctordata->getData($postData);
			$hbaestimationdata = $this->hbaestimationdata->getData($postData);
			$mealdata = $this->mealdata->getData($postData);
			$prescriptiondata = $this->prescriptiondata->getData($postData);
			$useractivitydata = $this->useractivitydata->getData($postData);
			$userexercisedata = $this->userexercisedata->getData($postData);  

			$result = array('status' => 'success', 'bslestimationdata' => $bslestimationdata, 'doctorData' => $doctorData, 'hbaestimationdata' => $hbaestimationdata, 'mealdata' => $mealdata, 'prescriptiondata'  => $prescriptiondata, 'useractivitydata' => $useractivitydata, 'userexercisedata' => $userexercisedata);
			echo json_encode($result);
			exit();
		}else{
			$result = array('status' => 'error', 'message' => 'Login expire, logout and login again');
			echo json_encode($result);
			exit();
		}

	}


	public function getOTP(){
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);

		if(empty($postData['email_id'])){
			$result = array('status' => 'error', 'message' => 'email id is empty');
			echo json_encode($result);
			exit();
		}

		if(empty($postData['mobile_no'])){
			$result = array('status' => 'error', 'message' => 'mobile no is empty');
			echo json_encode($result);
			exit();
		}


		$this->load->model('userdata');
		$this->load->model('settingsdata');

		
		$checkUserExists = $this->userdata->checkUserExists($postData);

		if(count($checkUserExists) > 0){
			$result = array('status' => 'error', 'message' => 'User already exists');
			echo json_encode($result);
			exit();
		}
		$otp_number = mt_rand(100000, 999999);
		$mobile_no = $postData['mobile_no'];
		$result =  array("status" => "success", "otp" => "$otp_number", "msg" => "OTP has been send to your mobile number");
    	echo json_encode($result);
		$request ="";
		$smsmsg = "Your OTP is $otp_number to make registration for Madhumitra App, it is valid for next 10 mins.Do not disclose OTP to anyone.";
    	$param = ["sender" => "MADHUM", "route" => 4, "mobiles" => $mobile_no, "authkey" => "221214AMjrpP3i8xP5b28a757", "country" => "91", "message" => $smsmsg];
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

		
	}


	public function addNewuserapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);

		if(!empty($postData['login_user_id'])){
			
			if(empty($postData['username'])){
				$result = array('status' => 'error', 'message' => 'Username is empty');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['relation'])){
				$result = array('status' => 'error', 'message' => 'relation is empty');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['birthdate'])){
				$result = array('status' => 'error', 'message' => 'birthdate is empty');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['gender'])){
				$result = array('status' => 'error', 'message' => 'gender is empty');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['email_id'])){
				$result = array('status' => 'error', 'message' => 'email id is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['mobile_no'])){
				$result = array('status' => 'error', 'message' => 'mobile no is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['weight'])){
				$result = array('status' => 'error', 'message' => 'weight is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['height'])){
				$result = array('status' => 'error', 'message' => 'height is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['lifestyle'])){
				$result = array('status' => 'error', 'message' => 'lifestyle is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['city_id'])){
				$result = array('status' => 'error', 'message' => 'city is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['pincode'])){
				$result = array('status' => 'error', 'message' => 'pincode is empty');
				echo json_encode($result);
				exit();
			}
			$this->load->model('userdata');
			$this->load->model('citydata'); 
			$this->load->model('settingsdata');
			

			$npassword = substr($postData['mobile_no'],4,10);
			$password = base64_encode($npassword);
			$mobile = $postData['mobile_no'];
			$emailid = $postData['email_id'];

			if(isset($postData['id']) && $postData['id'] > 0){
				$data=array(      
			      'username'=>ucwords($postData['username']),
			      'person_name'=>ucwords($postData['username']),
			      'relation'=>$postData['relation'],
			      'birthdate'=>date('Y-m-d',strtotime($postData['birthdate'])),
			      'gender'=>$postData['gender'],
			      'email_id'=>$postData['email_id'],
			      'mobile_no'=>$postData['mobile_no'],
			      'weight'=>$postData['weight'],
			      'height'=>$postData['height'],
			      'lifestyle'=>$postData['lifestyle'],
			      'city_id'=>$postData['city_id'],
				  'pincode'=>$postData['pincode'],
			      'updated_by' => $postData['login_user_id'],
			      'updated_at' => date('Y-m-d H:i:s')
			    );

			    $msg=$this->userdata->update($data,$postData['id']); 

				if($msg == 'success'){
					$result = array('status' => 'success', 'message' => 'User data updated successfully');
					echo json_encode($result);
					exit();
				}else{
					$result = array('status' => 'err6r', 'message' => 'Something is wrong, please try again');
					echo json_encode($result);
					exit();
				}
			}else{
				$result = $this->userdata->checkusercntapi($postData);
				$userLimit = $this->settingsdata->getRow();
				if(count($result) >= $userLimit){
		            $result = array('status' => 'error', 'message' => "You can`t add more than $userLimit user");
					echo json_encode($result);
					exit();
				}
				//$checkUserExists = $this->userdata->checkUserExists($postData);

				// if(count($checkUserExists) > 0){
				// 	$result = array('status' => 'error', 'message' => 'User already exists');
				// 	echo json_encode($result);
				// 	exit();
				// }
				$data=array(      
				      'username'=>ucwords($postData['username']),
				      'person_name'=>ucwords($postData['username']),
				      'password'=> $password,
				      'relation'=>$postData['relation'],
				      'birthdate'=>date('Y-m-d',strtotime($postData['birthdate'])),
				      'gender'=>$postData['gender'],
				      'email_id'=>$postData['email_id'],
				      'mobile_no'=>$postData['mobile_no'],
				      'weight'=>$postData['weight'],
				      'height'=>$postData['height'],
				      'lifestyle'=>$postData['lifestyle'],
				      'city_id'=>$postData['city_id'],
					  'pincode'=>$postData['pincode'],
					  'user_status'=>1,
					  'user_type_id' => 2,
					  'source' => 2,
				      'created_by' => $postData['login_user_id'],
				      'created_at' => date('Y-m-d H:i:s'),
				    );

				$msg=$this->userdata->insert($data);

				if($msg == 'success'){
					$result = array('status' => 'success', 'message' => 'New user added successfully');
					echo json_encode($result);

					// $this->load->library('email');

					// //SMTP & mail configuration
					// $config = array(
					//     'protocol'  => 'smtp',
					//     'smtp_host' => 'ssl://smtp.gmail.com',
					//     'smtp_port' => 465,
					//     'smtp_user' => 'app.madhumitra@gmail.com',
					//     'smtp_pass' => 'Madhu@123#',
					//     'mailtype'  => 'html',
					//     'charset'   => 'utf-8'
					// );
					// $this->email->initialize($config);
					// $this->email->set_mailtype("html");
					// $this->email->set_newline("\r\n");

					// //Email content
					// $htmlContent = "<h1>Login details for Madhumitra App. Download the app from http://tiny.cc/u6xzuy </h1>";
					// $htmlContent .= "<p>Username : $mobile<br> Password: $npassword</p>";

					// $this->email->to($emailid);
					// $this->email->from('app.madhumitra@gmail.com','Madhumitra App');
					// $this->email->subject('Login details for Madhumitra App');
					// $this->email->message($htmlContent);

					// //Send email
					// $this->email->send();

					exit();
				}else{
					$result = array('status' => 'err6r', 'message' => 'Something is wrong, please try again');
					echo json_encode($result);
					exit();
				}
			}

		}else{
				$result = array('status' => 'err6r', 'message' => 'Please logout and login again');
				echo json_encode($result);
				exit();
		}

	}


	public function Usersignupapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
			
		if(empty($postData['username'])){
			$result = array('status' => 'error', 'message' => 'Username is empty');
			echo json_encode($result);
			exit();
		}
		
		if(empty($postData['email_id'])){
			$result = array('status' => 'error', 'message' => 'email id is empty');
			echo json_encode($result);
			exit();
		}
		if(empty($postData['mobile_no'])){
			$result = array('status' => 'error', 'message' => 'mobile no is empty');
			echo json_encode($result);
			exit();
		}

		
		if(empty($postData['password'])){
			$result = array('status' => 'error', 'message' => 'password is empty');
			echo json_encode($result);
			exit();
		}

		$this->load->model('userdata');

		$npassword = $postData['password'];
		$password = base64_encode($npassword);
		$mobile = $postData['mobile_no'];
		$emailid = $postData['email_id'];
		
		
		$checkUserExists = $this->userdata->checkUserExists($postData);

		if(count($checkUserExists) > 0){
			$result = array('status' => 'error', 'message' => 'User already exists');
			echo json_encode($result);
			exit();
		}
		$pname = ucwords($postData['username']);

		$data=array(      
		      'username'=>ucwords($postData['username']),
		      'person_name'=>ucwords($postData['username']),
		      'password'=> $password,
		      'email_id'=>$postData['email_id'],
		      'mobile_no'=>$postData['mobile_no'],
			  'user_status'=>1,
			  'user_type_id' => 2,
			  'source' => 2,
		      'created_by' => 0,
		      'created_at' => date('Y-m-d H:i:s'),
		    );

		$msg=$this->userdata->insert($data);

		if($msg == 'success'){
			$result = array('status' => 'success', 'message' => 'Thank you for registration');
			echo json_encode($result);
			$request = '';
			$smsmsg = "Hello $pname, Thank You for registration with Madhumitra App. Login details are Username: $mobile  and  Password=$npassword  - From Madhumitra";
		    	$param = ["sender" => "MADHUM", "route" => 4, "mobiles" => $mobile, "authkey" => "221214AMjrpP3i8xP5b28a757", "country" => "91", "message" => $smsmsg];
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
			$htmlContent = "<h1>Thank you for registration with Madhumitra App.</h1>";
			$htmlContent .= "<p>Login details for app is as below</p>";
			$htmlContent .= "<p>Username : $mobile<br> Password: $npassword</p>";
			$htmlContent .= "<p>From Madhumitra</p>";

			$this->email->to($emailid);
			$this->email->from('app.madhumitra@gmail.com','Madhumitra App');
			$this->email->subject('Thank you for registration.Login details for Madhumitra App');
			$this->email->message($htmlContent);
			//Send email
			$this->email->send();
			exit();
		}

	}


	public function updateStatus() {
		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		}else{
			redirect('login', 'refresh');
		}
	  	$id= $_POST['id'];
	  	$status = $_POST['status'];
	  	$data=array( 
	  		'user_status' => $status,
	  		'updated_by' => $this->session->userdata['USER_ID'],
	        'updated_at' => date('Y-m-d H:i:s')
  		);
	    $msg=$this->userdata->updateStatus($data,$id);
	    if($msg == 'success'){
	    	$result = array('msg' => 'success');
	    }else{
	    	$result = array('msg' => 'error');
	    }
	    echo json_encode($result);
  	}

  	public function deleteUser() {
		$user = $this->session->userdata;
		$this->load->model('userdata');
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		}else{
			redirect('login', 'refresh');
		}
	  	$id= $_POST['id'];
	  	
	    $msg=$this->userdata->deleteUser($id);
	    if($msg == 'success'){
	    	$result = array('msg' => 'success');
	    }else{
	    	$result = array('msg' => 'error');
	    }
	    echo json_encode($result);
  	}

  	public function deleteUserapi()
  	{
  		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		$this->load->model('userdata');
		if(isset($postData['id'])){
			$id= $postData['id'];
	    	$msg=$this->userdata->deleteUser($id);
	    	if($msg == 'success'){
		    	$result = array('status' => 'success', 'message' => 'USer deleted successfully');
				echo json_encode($result);
				exit();
			}else{
				$result = array('status' => 'error', 'message' => 'User not deleted, please try again');
				echo json_encode($result);
				exit();
			}
		}else{
			$result = array('status' => 'error', 'message' => 'User not deleted, please try again');
			echo json_encode($result);
			exit();
		}
  	}

  	public function updatePassword() {
  		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		}else{
			redirect('login', 'refresh');
		}
  		$password=base64_encode($_POST['newPassword']);
  		$newPassword =$_POST['newPassword'];
  		$mobile =$_POST['pmobile_no'];
  		$id=$_POST['puser_id'];
  		$email_id = $_POST['emailid'];
	  	$data=array( 
	  		'password' => $password,
	  		'updated_by' => $this->session->userdata['USER_ID'],
	        'updated_at' => date('Y-m-d H:i:s')
  		);
	  	
	    $msg=$this->userdata->updatePassword($data,$id);
	    if($msg == 'success'){
    		$result = array('status' => 'success','msg' => "Password Changed Successfully" );
	    	echo json_encode($result);

	    	$request = '';
			$smsmsg = "Hi, Your password for Madhumitra App has been changed for username:  ".$mobile." & your new password is ".$newPassword;
	    	$param = ["sender" => "MADHUM", "route" => 4, "mobiles" => $mobile, "authkey" => "221214AMjrpP3i8xP5b28a757", "country" => "91", "message" => $smsmsg];
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
			$htmlContent = "<h1>Password changed as per your request.</h1>";
			$htmlContent .= "<p>As per your request your password has been changed by admin.<br>Your new Password for Madhumitra App is $newPassword</p><br> From Madhumitra";

			$this->email->to($email_id);
			$this->email->from('app.madhumitra@gmail.com','Madhumitra App');
			$this->email->subject('New Password for Madhumitra App');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
	    }else{
	    	$result = array('status' => 'error');
	    	echo json_encode($result);
	    }
	    
  	}

  	

  	public function userSettings()
	{
		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		}else{
			redirect('login', 'refresh');
		}
		$data['title'] = "Change Settings";
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/usersettings');
		$this->load->view('backend/footer');
	}


	public function changeSettings()
	{
		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		}else{
			redirect('login', 'refresh');
		}
		$mobile_no = trim($this->session->userdata['MOBILE']);
		$userName = trim($this->input->post('username'));
		$oldPassword = trim($this->input->post('oldPassword'));
		$newPassword = base64_encode($this->input->post('newPassword'));

		$query = $this->userdata->processLogin($mobile_no,$oldPassword);
		$this->form_validation->set_rules('oldPassword', 'Old Password', 'required|callback_oldpassword_check[' . $query->num_rows() . ']');
		$this->form_validation->set_rules('newPassword', 'New Password', 'required');
		$this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required|matches[newPassword]');
		if ($this->form_validation->run() == FALSE)
        {
        	$data['title'] = "Change Settings";
			$this->load->view('backend/header', $data);
			$this->load->view('backend/sidebar');
			$this->load->view('backend/usersettings');
			$this->load->view('backend/footer');
            return false;
        }else{
        	$result = $this->userdata->saveNewPass($newPassword,$mobile_no);
        	if($result == TRUE){
        		$this->session->set_flashdata('success', 'Password changed successfully');
        		redirect('dashboard');
        	}else{
        		$this->session->set_flashdata('error', 'Something is wrong, please try again');
        		redirect('user/userSettings');
        	}

        }
	}


	public function changePasswordapi()
	{
		$json = file_get_contents('php://input');
		$data = json_decode($json,TRUE);
		$this->load->model('userdata');
		if(empty($data['mobile_no'])){
			$result = array('status' => 'error', 'message' => 'Mobile Number is empty');
			echo json_encode($result);
			exit();
		}
		if(empty($data['oldPassword'])){
			$result = array('status' => 'error', 'message' => 'Old Password is empty');
			echo json_encode($result);
			exit();
		}
		if(empty($data['newPassword'])){
			$result = array('status' => 'error', 'message' => 'New Password is empty');
			echo json_encode($result);
			exit();
		}
		$mobile_no = trim($data['mobile_no']);
		$oldPassword = trim($data['oldPassword']);
		$newPassword = base64_encode($data['newPassword']);
		$query = $this->userdata->processLogin($mobile_no,$oldPassword);
		if($query->num_rows() > 0){
        	$result = $this->userdata->saveNewPass($newPassword,$mobile_no);
        	if($result == TRUE){
        		$result = array('status' => 'success', 'message' => 'Password changed successfully');
				echo json_encode($result);
				exit();
        	}else{
        		$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
				echo json_encode($result);
				exit();
        	}

        }else{
        	$result = array('status' => 'error', 'message' => 'Mobile number or Old Password is wrong');
			echo json_encode($result);
			exit();
        }
	}

	public function oldpassword_check($old_password,$recordCount){
	  if ($recordCount != 0){
			return TRUE;
		}else{
			$this->form_validation->set_message('oldpassword_check', 'Old password not match');
			return FALSE;
		}
	}

	public function userPDF()
  	{
  		$user = $this->session->userdata;
		if(isset($user['USER_NAME']) || isset($user['Appkey'])){
		 	$this->load->model('userdata');
		 	$this->load->model('citydata');
		}else{
			redirect('login', 'refresh');
		}
  		//load mPDF library
		$this->load->library('m_pdf');
		//load mPDF library
 	

    	$userData=$this->userdata->getRows();
    	$client_logo = base_url().'assets/img/logo.png';
		 //now pass the data //
		
		//this the the PDF filename that user will get to download
		$pdfFilePath = "Users-Report".date('d-m-Y').".pdf";
 
		$html="";
		//actually, you can pass mPDF parameter on this load() function
		$pdf = $this->m_pdf->load();

	     if(!empty($userData))
	     {
	    
	        $count = 1;
	        foreach ($userData as $key => $value) {
	    		if($value['user_status'] == 1){
	    			$user_status = 'Active';

	    		}else{
	    			$user_status = 'Not Active';
	    		}
	            $html.="<tr>
	                <td style='border: 1px solid #ccc; border-collapse: collapse;text-align:center;'>$count</td>
	                <td style='border: 1px solid #ccc; border-collapse: collapse;text-align:center;'>
	                    ".ucfirst($value['username'])."
	                </td>
	                
	                <td style='border: 1px solid #ccc; border-collapse: collapse;text-align:center;'>
	                    ".$value['email_id']."
	                </td>
	                <td style='border: 1px solid #ccc; border-collapse: collapse;text-align:center;'>
	                    ".$value['mobile_no']."
	                </td>
	                <td style='border: 1px solid #ccc; border-collapse: collapse;text-align:center;'>
	                    ".date('d-m-Y h:i A',strtotime($value['created_at']))."
	                </td>
	                <td style='border: 1px solid #ccc; border-collapse: collapse;text-align:center;'>
	                    ".$user_status."
	                </td>
	            </tr>";

	                  $count++;
	                  }
	               }
                                            
		//generate the PDF!
		$pdf->WriteHTML(
			'<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>User Report List</title>
			
			<style>
				.table-bord,.table-bord th,.table-bord td{
					border:1px solid #ddd;
					border-collapse: collapse;
					padding: 3px;
				}
				
				.bord-div{
				        *border-top: 1px solid #000;
				        *border-left: 1px solid #000;
				        *border-right: 1px solid #000;
				}
			</style>
			</head>
			<body>
                <table width="700" class="bord-div"  align="center" cellpadding="5" cellspacing="0" border="0" id="backgroundTable" st-sortable="preheader" style="padding: 0px;">
                    <tbody>
                        <tr>
                            <td width="100%">
                            	<table width="700"  align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" >
                                            <tbody>
                                                <tr>
                                                    <td width="100%" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="700">
                                                        <table width="700" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidthinner">
                                                            <tbody>
                                                                <!-- title -->
                                                                <tr>
                                                                    <td style="border-bottom: 1px solid #000;width: 120px;" align="center">
                                                                       <img src="' . $client_logo . '" style="width:auto;max-width:70px;max-height:70px;"/>
                                                                    </td>
                                                                    <td  style="font-family: Helvetica,arial,sans-serif;font-size: 19px;color:rgb(0, 0, 0);text-align: center;line-height: 15px;font-weight: 700;border-bottom: 1px solid #000; " st-title="fulltext-title">
                                                                            <h4 style="margin-top: 10px;">Madhumitra</h4>
                                                                    </td>
                                                                   <td style="border-bottom: 1px solid #000;" align="center">
                                                                       <strong style="font-size:10px">Contact No: XXXXXXXXXX</strong> <br><br>
                                                                       <span style="font-size:10px">Website&nbsp;:&nbsp;http://www.madhumitra.co.in </span>
                                                                    </td>
                                                                </tr>
                                                                <tr><td>&nbsp;</td></tr>
                                                                <tr>
                                                            <td align="center">
                                                                    
                                                            </td>
                                                            <td  style="font-family: Helvetica,arial,sans-serif;font-size: 16px;color:rgb(0, 0, 0);text-align: center;line-height: 15px;font-weight: 700; " st-title="fulltext-title">
                                                                    <h4 style="margin-top: 10px;">All User List</h4>
                                                            </td>
                                                            <td align="center">
                                                            </td>
                                                        </tr>
                                                                <!-- end of title -->
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="100%" height="10"> &nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                
                                <table width="700"  align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                                    <tbody>
                                        <tr style="height: 35px;background: rgba(236, 236, 236, 0.49)";>
                                            <td width="700">
                                                <table width="700" align="center" cellspacing="0" cellpadding="4" border="0" class="devicewidthinner" style="padding: 0 6px;font-size: 14px;">
                                                       	<thead>
	                                                       	<tr bgcolor="#D3D3D3">
		                                                       	<th style="border: 1px solid #ccc; border-collapse: collapse;text-align:center;">Sr.No.</th>
		                                                       	<th style="border: 1px solid #ccc; border-collapse: collapse;text-align:center;">Name</th>
		                                                       	<th style="border: 1px solid #ccc; border-collapse: collapse;text-align:center;">Email</th>
		                                                       	<th style="border: 1px solid #ccc; border-collapse: collapse;text-align:center;">Mobile Number</th>
		                                                       	<th style="border: 1px solid #ccc; border-collapse: collapse;text-align:center;">Registered On</th>
		                                                       	<th style="border: 1px solid #ccc; border-collapse: collapse;text-align:center;">Status</th>
	                                                       	</tr>
                                                       	</thead>
                                                        <tbody>'.
                                                        $html.'
                                                        </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>                                            
                            </td>
                        </tr>
                    </tbody>
                </table>
        </body>
        </html>');
		$pdf->Output($pdfFilePath, "D");
		//offer it to user via browser download! (Th
  	}


  	public function deleteAll() {
  		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);

		if(!empty($postData['login_user_id'])){
			if(!empty($postData['user_id'])){
			 	$this->load->model('bslestimationdata');
			 	$this->load->model('hbaestimationdata');
			 	$this->load->model('mealdata');
			 	$this->load->model('useractivitydata');
			 	$this->load->model('prescriptiondata');
			 	$this->load->model('userexercisedata');
			  	$user_id= $postData['user_id'];

			    $msg=$this->bslestimationdata->deleteBslestimation($user_id);
			    $msg1=$this->hbaestimationdata->deleteHbaestimation($user_id);
			    $msg2=$this->mealdata->deleteMeal($user_id);
			    $msg3=$this->useractivitydata->deleteUseractivity($user_id);
			    $msg4=$this->userexercisedata->deleteUserexercise($user_id);
			    $msg5=$this->prescriptiondata->deletePrescription($user_id);

			    if($msg == 'success'){
			    	$result = array('status' => 'error', 'message' => 'All records deleted successfully');
					echo json_encode($result);
					exit();
			    }else{
			    	$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
					echo json_encode($result);
					exit();
			    }
			}else{
				$result = array('status' => 'error', 'message' => 'User is not selected');
				echo json_encode($result);
				exit();
			}
		}else{
			$result = array('status' => 'error', 'message' => 'Login Expire, Please logout and login again');
			echo json_encode($result);
			exit();
		}
  	}
}
