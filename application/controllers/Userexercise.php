<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userexercise extends CI_Controller {

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
		if(isset($user['USER_NAME'])){
		 	$this->load->model('userexercisedata');
		 	$this->load->model('userdata');
		 	$this->load->model('exercisedata');
		}else{
			redirect('login', 'refresh');
		}
		$data['userexerciseData']=$this->userexercisedata->getRows();
		$data['title'] = "User Exercise List";
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/userexerciselist', $data);
		$this->load->view('backend/footer');
		$this->load->view('backend/userexercisejs.php');
	}


	public function getUserexerciseDataapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['selected_user_id'])){
			
			$this->load->model('userexercisedata');
			$userexercisedata = $this->userexercisedata->getData($postData);
			
			$result = array('status' => 'success', 'userexercisedata' => $userexercisedata);
			echo json_encode($result);
			exit();
		}else{
			$result = array('status' => 'error', 'message' => 'Login expire, logout and login again');
			echo json_encode($result);
			exit();
		}

	}


	public function addUserexercise()
	{
		$user = $this->session->userdata;
		if(isset($user['USER_NAME'])){
		 	$this->load->model('userexercisedata');
		 	$this->load->model('userdata');
		 	$this->load->model('exercisedata');
		}else{
			redirect('login', 'refresh');
		}
		$data['title'] = "Add User Exercise";
		$getdata['getuserexerciseData']= array();
		$getdata['userData']=$this->userdata->getRows();
		$getdata['exerciseData']=$this->exercisedata->getRows();
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/userexerciseadd',$getdata);
		$this->load->view('backend/footer');
		$this->load->view('backend/userexercisejs.php');

	}

	public function editUserexercise($id) {
		$user = $this->session->userdata;
		if(isset($user['USER_NAME'])){
		 	$this->load->model('userexercisedata');
		 	$this->load->model('userdata');
		 	$this->load->model('exercisedata');
		}else{
			redirect('login', 'refresh');
		}
	    $data= array('id'=>$id);  
	    $data1['title'] = "Update User Exercise";
	    $getdata['getuserexerciseData']=$this->userexercisedata->getRow($data);
	    $getdata['userData']=$this->userdata->getRows();
	    $getdata['exerciseData']=$this->exercisedata->getRows();
	    $this->load->view('backend/header', $data1);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/userexerciseedit', $getdata);
		$this->load->view('backend/footer');
	    $this->load->view('backend/userexercisejs.php');
	}

	public function saveUserexercise() {
		$user = $this->session->userdata;
		if(isset($user['USER_NAME'])){
		 	$this->load->model('userexercisedata');
		 	$this->load->model('userdata');
		 	$this->load->model('exercisedata');
		}else{
			redirect('login', 'refresh');
		}
		$this->form_validation->set_rules('exercise_id', 'Exercise', 'trim|required');
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('duration', 'Duration', 'trim|required|regex_match[/^0*[1-9]\d*$/]');
		$this->form_validation->set_rules('calories_spent', 'Calories spent', 'required|regex_match[/^0*[1-9]\d*$/]');
		
		if(isset($_POST['id']) && $_POST['id'] > 0){
			if ($this->form_validation->run() == FALSE)
	        {
				$getdata['getuserexerciseData']= array(
					'id' => $_POST['id'],
					'exercise_id'=>$_POST['exercise_id'],
				    'date'=>$_POST['date'],
				    'duration'=>$_POST['duration'],
				    'calories_spent'=>$_POST['calories_spent'],
				    'user_id' =>$this->session->userdata['selectedUser']
				);
				$data['title'] = "Update User Exercise";
				$getdata['userData']=$this->userdata->getRows();
				$getdata['exerciseData']=$this->exercisedata->getRows();
				$this->load->view('backend/header',$data);
				$this->load->view('backend/sidebar');
				$this->load->view('backend/userexerciseedit',$getdata);
				$this->load->view('backend/footer');
				$this->load->view('backend/userexercisejs.php');
	            return false;
	        }
			$data=array(      
		      	'exercise_id'=>$_POST['exercise_id'],
			    'date'=>date('Y-m-d',strtotime($_POST['date'])),
			    'duration'=>$_POST['duration'],
			    'user_id' =>$this->session->userdata['selectedUser'],
			    'calories_spent'=>$_POST['calories_spent'],
		      	'updated_by' => $this->session->userdata['USER_ID'],
		      	'updated_at' => date('Y-m-d H:i:s')
		    );
			$msg=$this->userexercisedata->update($data,$_POST['id']);
			if($msg =='success'){
				$this->session->set_flashdata('success','User exercise updated successfully');
				redirect('userexercise');
			}else{
				$this->session->set_flashdata('error','Something is wrong, please try again');
				$id = $_POST['id'];
				redirect('userexercise/editUserexercise/'.$id);
			}
		}else{
			if ($this->form_validation->run() == FALSE)
	        {
	        	$data['title'] = "Add User Exercise";
	        	$getdata['userData']=$this->userdata->getRows();
	        	$getdata['exerciseData']=$this->exercisedata->getRows();
				$this->load->view('backend/header', $data);
				$this->load->view('backend/sidebar');
				$this->load->view('backend/userexerciseadd',$getdata);
				$this->load->view('backend/footer');
				$this->load->view('backend/userexercisejs.php');
	            return false;
	        }
			$data=array(      
		      	'exercise_id'=>$_POST['exercise_id'],
		      	'user_id' =>$this->session->userdata['selectedUser'],
			    'date'=>date('Y-m-d',strtotime($_POST['date'])),
			    'duration'=>$_POST['duration'],
			    'calories_spent'=>$_POST['calories_spent'],
		      	'created_by' => $this->session->userdata['USER_ID'],
		      	'created_at' => date('Y-m-d H:i:s')
		    );
			$msg=$this->userexercisedata->insert($data);
			if($msg == 'success'){
				$this->session->set_flashdata('success','User exercise added successfully');
				redirect('userexercise');
			}else{
				$this->session->set_flashdata('error','Something is wrong, please try again');
			    redirect('userexercise/addUserexercise');
			}
		}
	}

	public function saveUserexerciseapi() {
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
	 	$this->load->model('userexercisedata');
	 	$this->load->model('userdata');
	 	$this->load->model('exercisedata');
			
		if(!empty($postData['login_user_id'])){
			if(empty($postData['user_id'])){
				$result = array('status' => 'error', 'message' => 'User is not selected');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['exercise_id'])){
				$result = array('status' => 'error', 'message' => 'Exercise name is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['date'])){
				$result = array('status' => 'error', 'message' => 'Date is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['duration'])){
				$result = array('status' => 'error', 'message' => 'Duration is empty');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['calories_spent'])){
				$result = array('status' => 'error', 'message' => 'Calories spent is empty');
				echo json_encode($result);
				exit();
			}
			if(isset($postData['id']) && $postData['id'] > 0){
				$data=array(      
			      	'exercise_id'=>$postData['exercise_id'],
				    'date'=>date('Y-m-d',strtotime($postData['date'])),
				    'duration'=>$postData['duration'],
				    'calories_spent'=>$postData['calories_spent'],
			      	'updated_by' => $postData['login_user_id'],
			      	'updated_at' => date('Y-m-d H:i:s')
			    );
				$msg=$this->userexercisedata->update($data,$postData['id']);
				if($msg =='success'){
					$result = array('status' => 'success', 'message' => 'User exercise updated successfully');
					echo json_encode($result);
					exit();
				}else{
					$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
					echo json_encode($result);
					exit();
				}
			}else{
				$data=array(      
			      	'exercise_id'=>$postData['exercise_id'],
			      	'user_id' =>$postData['user_id'],
				    'date'=>date('Y-m-d',strtotime($postData['date'])),
				    'duration'=>$postData['duration'],
				    'calories_spent'=>$postData['calories_spent'],
			      	'created_by' => $postData['login_user_id'],
			      	'created_at' => date('Y-m-d H:i:s')
			    );
				$msg=$this->userexercisedata->insert($data);
				if($msg == 'success'){
					$result = array('status' => 'success', 'message' => 'User exercise added successfully');
					echo json_encode($result);
					exit();
				}else{
					$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
					echo json_encode($result);
					exit();
				}
			}
		}else{
			$result = array('status' => 'error', 'message' => 'Please logout and login again');
			echo json_encode($result);
			exit();
		}
	}


	public function deleteUserexercise() {
 		$this->load->model('userexercisedata');
		 
	  	$user_id= $this->session->userdata['selectedUser'];
	  	
	    $msg=$this->userexercisedata->deleteUserexercise($user_id);
	    if($msg == 'success'){
	    	$this->session->set_flashdata('success','User exercise deleted successfully');
	    	return 'success';
	    }else{
	    	$this->session->set_flashdata('error','Something is wrong, please try again');
	    	return 'error';
	    }
  	}

  	public function deleteUserexerciseapi() {
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		$this->load->model('userexercisedata');
		if(!empty($postData['user_id'])){
		  	$user_id= $postData['user_id'];
		    $msg=$this->userexercisedata->deleteUserexercise($user_id);
		    if($msg == 'success'){
		    	$result = array('status' => 'error', 'message' => 'User exercise deleted successfully');
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
  	}

  	public function getExercise() {
	 	$this->load->model('exercisedata');
		$exercise_id= $_POST['exercise_id'];
	  	$data=array(
	  		'id' => $exercise_id
  		);
	    $result=$this->exercisedata->getRow($data);
	    echo json_encode($result);
	}
}
