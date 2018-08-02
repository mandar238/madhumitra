<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prescription extends CI_Controller {

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
		 	$this->load->model('prescriptiondata');
		 	$this->load->model('userdata');
		 	$this->load->model('doctordata');
		}else{
			redirect('login', 'refresh');
		}
		$data['prescriptionData']=$this->prescriptiondata->getRows();
		$data['title'] = "Prescription List";
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/prescriptionlist', $data);
		$this->load->view('backend/footer');
		$this->load->view('backend/prescriptionjs.php');
	}


	public function getPrescriptionDataapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['selected_user_id'])){
			
			$this->load->model('prescriptiondata');
			$prescriptiondata = $this->prescriptiondata->getData($postData);
			
			$result = array('status' => 'success', 'prescriptiondata' => $prescriptiondata);
			echo json_encode($result);
			exit();
		}else{
			$result = array('status' => 'error', 'message' => 'Login expire, logout and login again');
			echo json_encode($result);
			exit();
		}

	}


	public function addPrescription()
	{
		$user = $this->session->userdata;
		if(isset($user['USER_NAME'])){
		 	$this->load->model('prescriptiondata');
		 	$this->load->model('userdata');
		 	$this->load->model('doctordata');
		}else{
			redirect('login', 'refresh');
		}
		$data['title'] = "Add Prescription";
		$data['userData']=$this->userdata->getRows();
		$data['doctorData']=$this->doctordata->getRows();
		$this->load->view('backend/header', $data);

		$this->load->view('backend/sidebar');

		$this->load->view('backend/prescriptionadd', $data);

		$this->load->view('backend/footer');
		$this->load->view('backend/prescriptionjs.php');


	}

	

	public function getTime() {
		$this->load->model('prescriptiondata');
		$data = $_POST['data'];
		$getdata=$this->prescriptiondata->getTime($data);
		echo json_encode($getdata['time']);
	}

	public function savePrescription() {
		$this->load->model('prescriptiondata');

		$userId = $this->session->userdata['selectedUser'];
		$doctorId = $_POST['data']['doctor_id'];
		$ddate = date('Y-m-d',strtotime($_POST['data']['ddate']));
		for($i=0;$i < count($_POST['dosedetails']);$i++){
			$DrugName = ucwords($_POST['dosedetails'][$i]['DrugName']);
			$Days = $_POST['dosedetails'][$i]['Days'];
			$Takeat = $_POST['dosedetails'][$i]['Takeat'];
			$data=array(
				'user_id' => $userId,
				'doctor_id' => $doctorId,
				'date' => $ddate,
				'drug_name' => $DrugName,
				'duration_days' => $Days,
				'dose_details' => $Takeat,
		  		'created_by' => $this->session->userdata['USER_ID'],
		      	'created_at' => date('Y-m-d H:i:s')
	  		);
		    $msg=$this->prescriptiondata->insert($data);
		    
		}
		if($msg == "success"){
	    	$this->session->set_flashdata('success', 'Prescription details added successfully');
    		return 'success';
	    }else{
	    	$this->session->set_flashdata('Something is wrong, please try again', 'error');
    		return 'error';
	    }

	}


	public function savePrescriptionapi() {
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		$this->load->model('prescriptiondata');
		
		if(!empty($postData['data']['login_user_id'])){
			$userId = $postData['data']['user_id'];
			$doctorId = $postData['data']['doctor_id'];
			$ddate = date('Y-m-d',strtotime($postData['data']['ddate']));
			for($i=0;$i < count($postData['dosedetails']);$i++){
				$DrugName = ucwords($postData['dosedetails'][$i]['DrugName']);
				$Days = $postData['dosedetails'][$i]['Days'];
				$Takeat = $postData['dosedetails'][$i]['Takeat'];
				$data=array(
					'user_id' => $userId,
					'doctor_id' => $doctorId,
					'date' => $ddate,
					'drug_name' => $DrugName,
					'duration_days' => $Days,
					'dose_details' => $Takeat,
			  		'created_by' => $postData['data']['login_user_id'],
			      	'created_at' => date('Y-m-d H:i:s')
		  		);
			    $msg=$this->prescriptiondata->insert($data);
			    
			}
			if($msg == "success"){
		    	$result = array('status' => 'success', 'message' => 'Prescription added successfully');
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


	public function updatePrescriptionapi() {
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
	 	$this->load->model('prescriptiondata');
		
		if(!empty($postData['login_user_id'])){
			if(empty($postData['user_id'])){
				$result = array('status' => 'error', 'message' => 'User is not selected');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['doctor_id'])){
				$result = array('status' => 'error', 'message' => 'Doctor is not selected');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['ddate'])){
				$result = array('status' => 'error', 'message' => 'Date is empty');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['drug_name'])){
				$result = array('status' => 'error', 'message' => 'Drug name is empty');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['duration'])){
				$result = array('status' => 'error', 'message' => 'Duration is empty');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['dosedetails'])){
				$result = array('status' => 'error', 'message' => 'Dose details is empty');
				echo json_encode($result);
				exit();
			}			

			if(!empty($postData['id']) && $postData['id'] > 0){
				
				$data=array(      
				    'user_id' => $postData['user_id'],
					'doctor_id' => $postData['doctor_id'],
					'date' => date('Y-m-d',strtotime($postData['ddate'])),
					'drug_name' => $postData['drug_name'],
					'duration_days' => $postData['duration'],
					'dose_details' => $postData['dosedetails'],
			      	'updated_by' => $postData['login_user_id'],
			      	'updated_at' => date('Y-m-d H:i:s')
			    );
				$msg=$this->prescriptiondata->update($data,$postData['id']);
				if($msg =='success'){
					$result = array('status' => 'success', 'message' => 'Prescription updated successfully');
					echo json_encode($result);
					exit();
				}else{
					$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
					echo json_encode($result);
					exit();
				}
			}else{
				$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
				echo json_encode($result);
				exit();
			}
		}else{
			$result = array('status' => 'error', 'message' => 'Please logout and login again');
			echo json_encode($result);
			exit();
		}
	}


	public function deletePrescription() {
		$this->load->model('prescriptiondata');
	  	$user_id= $this->session->userdata['selectedUser'];
	    $msg=$this->prescriptiondata->deletePrescription($user_id);
	    if($msg == 'success'){
	    	$this->session->set_flashdata('success', 'Prescription details deleted successfully');
	    	return 'success';
	    }else{
	    	$this->session->set_flashdata('Something is wrong, please try again', 'error');
	    	return 'error';
	    }
  	}


  	public function deletePrescriptionapi() {
	    $json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		$this->load->model('prescriptiondata');
		if(!empty($postData['ids'])){
		  	$ids= explode(',',$postData['ids']);
		    $msg=$this->prescriptiondata->deletesinglePrescription($ids);
		    if($msg == 'success'){
		    	$result = array('status' => 'success', 'message' => 'Prescriptions deleted successfully');
				echo json_encode($result);
				exit();
		    }else{
		    	$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
				echo json_encode($result);
				exit();
		    }
		}else{
			$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
			echo json_encode($result);
			exit();

		}

	}	


}
