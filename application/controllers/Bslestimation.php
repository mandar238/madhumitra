<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bslestimation extends CI_Controller {

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
			 	$this->load->model('bslestimationdata');
			 	$this->load->model('userdata');
			}else{
				redirect('login', 'refresh');
			}
		$data['bslestimationdata']=$this->bslestimationdata->getRows();
		$data['title'] = "BSL Estimation List";
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/bslestimationlist', $data);
		$this->load->view('backend/footer');
		$this->load->view('backend/bslestimationjs.php');
	}


	public function getBSLDataapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['selected_user_id'])){
			
			$this->load->model('bslestimationdata');
			
			$bslestimationdata = $this->bslestimationdata->getData($postData);
			
			$result = array('status' => 'success', 'bslestimationdata' => $bslestimationdata);
			echo json_encode($result);
			exit();
		}else{
			$result = array('status' => 'error', 'message' => 'Login expire, logout and login again');
			echo json_encode($result);
			exit();
		}

	}


	public function addBslestimation()
	{
		$user = $this->session->userdata;
			if(isset($user['USER_NAME'])){
			 	$this->load->model('bslestimationdata');
			 	$this->load->model('userdata');
			}else{
				redirect('login', 'refresh');
			}
		$data['title'] = "Add BSL Estimation";
		$getdata['userData']=$this->userdata->getRows();
		$getdata['getbslestimationdata']= array();
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/bslestimationadd',$getdata);
		$this->load->view('backend/footer');

	}

	public function editBslestimation($id) {
		$user = $this->session->userdata;
			if(isset($user['USER_NAME'])){
			 	$this->load->model('bslestimationdata');
			 	$this->load->model('userdata');
			}else{
				redirect('login', 'refresh');
			}
	    $data= array('id'=>$id);  
	    $data1['title'] = "Update BSL Estimation";
	    $getdata['userData']=$this->userdata->getRows();
	    $getdata['getbslestimationdata']=$this->bslestimationdata->getRow($data);
	    $this->load->view('backend/header', $data1);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/bslestimationedit', $getdata);
		$this->load->view('backend/footer');
	    
	}

	public function saveBslestimation() {
		$user = $this->session->userdata;
			if(isset($user['USER_NAME'])){
			 	$this->load->model('bslestimationdata');
			 	$this->load->model('userdata');
			}else{
				redirect('login', 'refresh');
			}
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('time', 'Time', 'trim|required');
		$this->form_validation->set_rules('bsl_value', 'BSL value', 'trim|required|regex_match[/^0*[1-9]\d*$/]');
		
		if(isset($_POST['id']) && $_POST['id'] > 0){
			if ($this->form_validation->run() == FALSE)
	        {
				$getdata['getbslestimationdata']= array(
					'id' => $_POST['id'],
				    'date'=>$_POST['date'],
				    'time'=>$_POST['time'],
				    'bsl_value'=>$_POST['bsl_value'],
				    'user_id'=>$this->session->userdata['selectedUser']
				);
				$data['title'] = "Update BSL Estimation";
				$getdata['userData']=$this->userdata->getRows();
				$this->load->view('backend/header',$data);
				$this->load->view('backend/sidebar');
				$this->load->view('backend/bslestimationedit',$getdata);
				$this->load->view('backend/footer');
	            return false;
	        }
			$data=array(      
			    'date'=>date('Y-m-d',strtotime($_POST['date'])),
			    'user_id'=>$this->session->userdata['selectedUser'],
			    'time'=>$_POST['time'],
			    'bsl_value'=>$_POST['bsl_value'],
		      	'updated_by' => $this->session->userdata['USER_ID'],
		      	'updated_at' => date('Y-m-d H:i:s')
		    );
			$msg=$this->bslestimationdata->update($data,$_POST['id']);
			if($msg =='success'){
				$this->session->set_flashdata('success', 'BSL Estimation Updated successfully');
				redirect('bslestimation');
			}else{
				$id = $_POST['id'];
				$this->session->set_flashdata('Something is wrong, please try again', 'error');
				redirect('bslestimation/editBslestimation/'.$id);
			}
		}else{
			if ($this->form_validation->run() == FALSE)
	        {
	        	$data['title'] = "Add BSL Estimation";
	        	$getdata['userData']=$this->userdata->getRows();
				$this->load->view('backend/header', $data);
				$this->load->view('backend/sidebar');
				$this->load->view('backend/bslestimationadd',$getdata);
				$this->load->view('backend/footer');
	            return false;
	        }
			$data=array(      
		      	'user_id'=>$this->session->userdata['selectedUser'],
			    'date'=>date('Y-m-d',strtotime($_POST['date'])),
			    'time'=>$_POST['time'],
			    'bsl_value'=>$_POST['bsl_value'],
		      	'created_by' => $this->session->userdata['USER_ID'],
		      	'created_at' => date('Y-m-d H:i:s'),
		    );
			$msg=$this->bslestimationdata->insert($data);
			if($msg == 'success'){
				$this->session->set_flashdata('success', 'BSL Estimation Added successfully');
				redirect('bslestimation');
			}else{
				$this->session->set_flashdata('error', 'Something is wrong, please try again');
			    redirect('bslestimation/addBslestimation');
			}
		}
	}


	public function saveBslestimationapi() {
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		$this->load->model('bslestimationdata');
	 	$this->load->model('userdata');
		if(!empty($postData['login_user_id'])){
			if(empty($postData['user_id'])){
				$result = array('status' => 'error', 'message' => 'User is not selected');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['date'])){
				$result = array('status' => 'error', 'message' => 'Date is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['time'])){
				$result = array('status' => 'error', 'message' => 'Time is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['bsl_value'])){
				$result = array('status' => 'error', 'message' => 'BSL value is empty');
				echo json_encode($result);
				exit();
			}

			if(!empty($postData['id']) && $postData['id'] > 0){
				
				$data=array(      
				    'date'=>date('Y-m-d',strtotime($postData['date'])),
				    'user_id'=>$postData['user_id'],
				    'time'=>$postData['time'],
				    'bsl_value'=>$postData['bsl_value'],
			      	'updated_by' => $postData['login_user_id'],
			      	'updated_at' => date('Y-m-d H:i:s')
			    );
				$msg=$this->bslestimationdata->update($data,$postData['id']);
				if($msg =='success'){
					$result = array('status' => 'success', 'message' => 'BSL Estimation updated successfully');
					echo json_encode($result);
					exit();
				}else{
					$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
					echo json_encode($result);
					exit();
				}
			}else{
				
				$data=array(      
			      	'user_id'=>$postData['user_id'],
				    'date'=>date('Y-m-d',strtotime($postData['date'])),
				    'time'=>$postData['time'],
				    'bsl_value'=>$postData['bsl_value'],
			      	'created_by' => $postData['login_user_id'],
			      	'created_at' => date('Y-m-d H:i:s'),
			    );
				$msg=$this->bslestimationdata->insert($data);
				if($msg =='success'){
					$result = array('status' => 'success', 'message' => 'BSL Estimation added successfully');
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


	public function deleteBslestimationapi() {
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		$this->load->model('bslestimationdata');
		if(!empty($postData['user_id'])){
		  	$user_id= $postData['user_id'];
		    $msg=$this->bslestimationdata->deleteBslestimation($user_id);
		    if($msg == 'success'){
		    	$result = array('status' => 'error', 'message' => 'BSL Estimation deleted successfully');
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


  	public function deleteBslestimation() {
  		$this->load->model('bslestimationdata');
	  	$user_id= $this->session->userdata['selectedUser'];
	    $msg=$this->bslestimationdata->deleteBslestimation($user_id);
	    if($msg == 'success'){
	    	$this->session->set_flashdata('success', 'BSL Estimation Deleted successfully');
	    	return 'success';
	    }else{
	    	$this->session->set_flashdata('error','Something is wrong, please try again');
	    	return 'error';
	    }
  	}

  	public function getreportData() {

  		if(!empty($_POST)){
  			$this->load->model('bslestimationdata');
  			$dates = $values = $results = array();
  			$result=$this->bslestimationdata->getreportData($_POST);
  			for($i=0;$i<count($result);$i++)
			{
				$datestime = date('d-M',strtotime($result[$i]['date'])).' @ '.$result[$i]['time'];
				$results[$datestime][] = (int)$result[$i]['bsl_value'];

			}
			foreach ($results as $key => $value) {
				$dates[] = $key;
				$values[] = $value;
			}
			
			$resultArr = array('success' => true,'dates' => $dates, 'values' => $values);
			echo json_encode($resultArr);
  		}else{
  			$resultArr = array('success' => false);
  			echo json_encode($resultArr);
  		}
  	}
}
