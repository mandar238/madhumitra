<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hbaestimation extends CI_Controller {

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
			 	$this->load->model('hbaestimationdata');
			 	$this->load->model('userdata');
			}else{
				redirect('login', 'refresh');
			}
		$data['hbaestimationdata']=$this->hbaestimationdata->getRows();
		$data['title'] = "HBA1c Estimation List";
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/hbaestimationlist', $data);
		$this->load->view('backend/footer');
		$this->load->view('backend/hbaestimationjs.php');
	}

	public function getHBADataapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['selected_user_id'])){
			
			$this->load->model('hbaestimationdata');
			$hbaestimationdata = $this->hbaestimationdata->getData($postData);
			
			$result = array('status' => 'success', 'hbaestimationdata' => $hbaestimationdata);
			echo json_encode($result);
			exit();
		}else{
			$result = array('status' => 'error', 'message' => 'Login expire, logout and login again');
			echo json_encode($result);
			exit();
		}

	}


	public function addHbaestimation()
	{
		$user = $this->session->userdata;
			if(isset($user['USER_NAME'])){
			 	$this->load->model('hbaestimationdata');
			 	$this->load->model('userdata');
			}else{
				redirect('login', 'refresh');
			}
		$data['title'] = "Add HBA1c Estimation";
		$getdata['gethbaestimationdata']= array();
		$getdata['userData']=$this->userdata->getRows();
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/hbaestimationadd',$getdata);
		$this->load->view('backend/footer');

	}

	public function editHbaestimation($id) {
		$user = $this->session->userdata;
			if(isset($user['USER_NAME'])){
			 	$this->load->model('hbaestimationdata');
			 	$this->load->model('userdata');
			}else{
				redirect('login', 'refresh');
			}
	    $data= array('id'=>$id);  
	    $data1['title'] = "Update HBA1c Estimation";
	    $getdata['gethbaestimationdata']=$this->hbaestimationdata->getRow($data);
	    $getdata['userData']=$this->userdata->getRows();
	    $this->load->view('backend/header', $data1);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/hbaestimationedit', $getdata);
		$this->load->view('backend/footer');
	    
	}

	public function saveHbaestimation() {
		$user = $this->session->userdata;
			if(isset($user['USER_NAME'])){
			 	$this->load->model('hbaestimationdata');
			 	$this->load->model('userdata');
			}else{
				redirect('login', 'refresh');
			}
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('time', 'Time', 'trim|required');
		$this->form_validation->set_rules('hba_value', 'HBA1c value', 'trim|required|regex_match[/^0*[1-9]\d*$/]');
		

		if(isset($_POST['id']) && $_POST['id'] > 0){
			if ($this->form_validation->run() == FALSE)
	        {
				$getdata['gethbaestimationdata']= array(
					'id' => $_POST['id'],
				    'date'=>$_POST['date'],
				    'time'=>$_POST['time'],
				    'hba_value'=>$_POST['hba_value'],
				    'user_id'=>$this->session->userdata['selectedUser']
				);
				$data['title'] = "Update HBA Estimation";
				$getdata['userData']=$this->userdata->getRows();
				$this->load->view('backend/header',$data);
				$this->load->view('backend/sidebar');
				$this->load->view('backend/hbaestimationedit',$getdata);
				$this->load->view('backend/footer');
	            return false;
	        }
			$data=array(      
			    'date'=>date('Y-m-d',strtotime($_POST['date'])),
			    'time'=>$_POST['time'],
			    'hba_value'=>$_POST['hba_value'],
			    'user_id'=>$this->session->userdata['selectedUser'],
		      	'updated_by' => $this->session->userdata['USER_ID'],
		      	'updated_at' => date('Y-m-d H:i:s')
		    );
			$msg=$this->hbaestimationdata->update($data,$_POST['id']);
			if($msg =='success'){
				$this->session->set_flashdata('success', 'HBA1c updated successfully');
				redirect('hbaestimation');
			}else{
				$this->session->set_flashdata('error','Something is wrong, please try again');
				$id = $_POST['id'];
				redirect('hbaestimation/edithbaestimation/'.$id);
			}
		}else{
			if ($this->form_validation->run() == FALSE)
	        {
	        	$data['title'] = "Add HBA1c Estimation";
	        	$getdata['userData']=$this->userdata->getRows();
				$this->load->view('backend/header', $data);
				$this->load->view('backend/sidebar');
				$this->load->view('backend/hbaestimationadd',$getdata);
				$this->load->view('backend/footer');
	            return false;
	        }
			$data=array(      
			    'date'=>date('Y-m-d',strtotime($_POST['date'])),
			    'time'=>$_POST['time'],
			    'hba_value'=>$_POST['hba_value'],
			    'user_id'=>$this->session->userdata['selectedUser'],
		      	'created_by' => $this->session->userdata['USER_ID'],
		      	'created_at' => date('Y-m-d H:i:s'),
		    );
			$msg=$this->hbaestimationdata->insert($data);
			if($msg == 'success'){
				$this->session->set_flashdata('success', 'HBA1c updated successfully');
				redirect('hbaestimation');
			}else{
				$this->session->set_flashdata('error','Something is wrong, please try again');
			    redirect('hbaestimation/addhbaestimation');
			}
		}
	}


	public function saveHbaestimationapi() {
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		$this->load->model('hbaestimationdata');
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

			if(empty($postData['hba_value'])){
				$result = array('status' => 'error', 'message' => 'HBA1c value is empty');
				echo json_encode($result);
				exit();
			}

			if(!empty($postData['id']) && $postData['id'] > 0){
				
				$data=array(      
				    'date'=>date('Y-m-d',strtotime($postData['date'])),
				    'time'=>$postData['time'],
				    'hba_value'=>$postData['hba_value'],
				    'user_id'=>$postData['user_id'],
			      	'updated_by' => $postData['login_user_id'],
			      	'updated_at' => date('Y-m-d H:i:s')
			    );
				$msg=$this->hbaestimationdata->update($data,$postData['id']);
				if($msg =='success'){
					$result = array('status' => 'success', 'message' => 'HBA1c Estimation updated successfully');
					echo json_encode($result);
					exit();
				}else{
					$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
					echo json_encode($result);
					exit();
				}
			}else{
				$data=array(      
				    'date'=>date('Y-m-d',strtotime($postData['date'])),
				    'time'=>$postData['time'],
				    'hba_value'=>$postData['hba_value'],
				    'user_id'=>$postData['user_id'],
			      	'created_by' => $postData['login_user_id'],
			      	'created_at' => date('Y-m-d H:i:s'),
			    );
				$msg=$this->hbaestimationdata->insert($data);
				if($msg =='success'){
					$result = array('status' => 'success', 'message' => 'HBA1c Estimation added successfully');
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


	public function deleteHbaestimation() {
		$this->load->model('hbaestimationdata');
	  	$user_id= $this->session->userdata['selectedUser'];
	    $msg=$this->hbaestimationdata->deleteHbaestimation($user_id);
	    if($msg == 'success'){
	    	$this->session->set_flashdata('success', 'HBA1c deleted successfully');
	    	return 'success';
	    }else{
	    	$this->session->set_flashdata('Something is wrong, please try again', 'error');
	    	return 'error';
	    }
  	}


  	public function deleteHbaestimationapi() {
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		$this->load->model('hbaestimationdata');
		if(!empty($postData['user_id'])){
		  	$user_id= $postData['user_id'];
		    $msg=$this->hbaestimationdata->deleteHbaestimation($user_id);
		    if($msg == 'success'){
		    	$result = array('status' => 'error', 'message' => 'HBA1c Estimation deleted successfully');
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


  	public function getreportData() {
  		if(!empty($_POST)){
  			$this->load->model('hbaestimationdata');
  			$dates = $values = $results = array();
  			$result=$this->hbaestimationdata->getreportData($_POST);
  			for($i=0;$i<count($result);$i++)
			{
				$datestime = date('d-M',strtotime($result[$i]['date'])).' @ '.$result[$i]['time'];
				$results[$datestime][] = (int)$result[$i]['hba_value'];

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
