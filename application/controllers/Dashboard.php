<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
		function __construct()
		{
			parent::__construct();

			
			$user = $this->session->userdata;

			if(isset($user['USER_NAME'])){
			 	$this->load->model('userdata');
			 	$this->load->model('bslestimationdata');
			 	$this->load->model('hbaestimationdata');
			 	$this->load->model('mealdata');
			 	$this->load->model('useractivitydata');
			 	$this->load->model('prescriptiondata');
			 	$this->load->model('userexercisedata');
			}else{
				redirect('login', 'refresh');
			}
		}
	public function index()
	{
		if($this->session->userdata['USER_TYPE'] == 1){
			 redirect('dashboard/admin');
		}else{
			$this->session->unset_userdata('selectedUser');
			$this->session->unset_userdata('selectedPatient');
		}
		$data['title'] = "Dashboard";
		$data['userData']=$this->userdata->getRows();
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/dashboard');
		$this->load->view('backend/footer');
	}

	


	public function admin()
	{
		if($this->session->userdata['USER_TYPE'] == 2){
			 redirect('dashboard');
		}
		$data['title'] = "Admin Dashboard";
		$data['totalUser']=$this->userdata->gettotalUser();
		$data['activeUser']=$this->userdata->getactiveUser();
		$data['inactiveUser']=$this->userdata->getinactiveUser();
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/dashboard1');
		$this->load->view('backend/footer');
	}

	public function error_page()
	{
		$data['title'] = "404 Error Page";
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/error404');
		$this->load->view('backend/footer');
	}

	public function selectedUser()
	{
		$this->load->model('userdata');
		$data= array('id'=>$_POST['user_id']);  
	    $getUserData=$this->userdata->getRow($data);

		$this->session->set_userdata(array('selectedUser' => $_POST['user_id']));
		$this->session->set_userdata(array('selectedUserWt' => $getUserData['weight']));
		$this->session->set_userdata(array('selectedPatient' => $_POST['selectedPatient']));
		$resultArr = array('success' => true);
		echo json_encode($resultArr);
	}

	public function deleteAll() {
	  	$user_id= $this->session->userdata['selectedUser'];
	    $msg=$this->bslestimationdata->deleteBslestimation($user_id);
	    $msg1=$this->hbaestimationdata->deleteHbaestimation($user_id);
	    $msg2=$this->mealdata->deleteMeal($user_id);
	    $msg3=$this->useractivitydata->deleteUseractivity($user_id);
	    $msg4=$this->userexercisedata->deleteUserexercise($user_id);
	    $msg5=$this->prescriptiondata->deletePrescription($user_id);

	    if($msg == 'success'){
	    	$this->session->set_flashdata('success', 'All records deleted successfully');
	    	return 'success';
	    }else{
	    	$this->session->set_flashdata('Something is wrong, please try again', 'error');
	    	return 'error';
	    }
  	}
}
