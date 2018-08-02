<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meal extends CI_Controller {

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
		 	$this->load->model('mealdata');
		 	$this->load->model('categorydata');
		 	$this->load->model('itemdata');
		 	$this->load->model('userdata');
		}else{
			redirect('login', 'refresh');
		}
		$data['mealdata']=$this->mealdata->getRows();
		$data['title'] = "Meal List";
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/meallist', $data);
		$this->load->view('backend/footer');
		$this->load->view('backend/mealjs.php');
	}

	public function getMealDataapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['selected_user_id'])){
			
			$this->load->model('mealdata');
			$mealdata = $this->mealdata->getData($postData);
			
			$result = array('status' => 'success', 'mealdata' => $mealdata);
			echo json_encode($result);
			exit();
		}else{
			$result = array('status' => 'error', 'message' => 'Login expire, logout and login again');
			echo json_encode($result);
			exit();
		}

	}


	public function addMeal()
	{
		$user = $this->session->userdata;
		if(isset($user['USER_NAME'])){
		 	$this->load->model('mealdata');
		 	$this->load->model('categorydata');
		 	$this->load->model('itemdata');
		 	$this->load->model('userdata');
		}else{
			redirect('login', 'refresh');
		}
		$data['title'] = "Add Meal";
		$getdata['getmealdata']= array();
		$getdata['userData']=$this->userdata->getRows();
		$getdata['categoryData']=$this->categorydata->getRows();
		$this->load->view('backend/header', $data);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/mealadd',$getdata);
		$this->load->view('backend/footer');
		$this->load->view('backend/mealjs.php');

	}

	public function editMeal($id) {
		$user = $this->session->userdata;
		if(isset($user['USER_NAME'])){
		 	$this->load->model('mealdata');
		 	$this->load->model('categorydata');
		 	$this->load->model('itemdata');
		 	$this->load->model('userdata');
		}else{
			redirect('login', 'refresh');
		}
	    $data= array('id'=>$id);  
	    $data1['title'] = "Update BSL Estimation";
	    $getdata['getmealdata']=$this->mealdata->getRow($data);
	    $this->load->view('backend/header', $data1);
		$this->load->view('backend/sidebar');
		$this->load->view('backend/mealedit', $getdata);
		$this->load->view('backend/footer');
		$this->load->view('backend/mealjs.php');
	}

	public function getCategorydataapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['login_user_id'])){
			$this->load->model('categorydata');
			$categoryData = $this->categorydata->getRows();
			$result = array('status' => 'success', 'categoryData' => $categoryData);
			echo json_encode($result);
			exit();
		}else{
			$result = array('status' => 'error', 'message' => 'Login expire, logout and login again');
			echo json_encode($result);
			exit();
		}
	}

	public function getItemsapi()
	{
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		if(isset($postData['login_user_id'])){
			$this->load->model('itemdata');
			$itemdata = $this->itemdata->getRows($postData);
			$result = array('status' => 'success', 'itemdata' => $itemdata);
			echo json_encode($result);
			exit();
		}else{
			$result = array('status' => 'error', 'message' => 'Login expire, logout and login again');
			echo json_encode($result);
			exit();
		}
	}

	public function getItems() {
	 	$this->load->model('itemdata');
		$category_id= $_POST['category_id'];
	  	$data=array(
	  		'category_id' => $category_id
  		);
	    $result=$this->itemdata->getRows($data);
	    echo json_encode($result);
	}

	public function getItemslabel() {
	 	$this->load->model('itemdata');
		$item_id= $_POST['item_id'];
	  	$data=array(
	  		'id' => $item_id
  		);
	    $result=$this->itemdata->getItemslabel($data);
	    echo json_encode($result);
	}

	public function saveMeal() {
		$user = $this->session->userdata;
		if(isset($user['USER_NAME'])){
		 	$this->load->model('mealdata');
		 	$this->load->model('categorydata');
		 	$this->load->model('itemdata');
		 	$this->load->model('userdata');
		}else{
			redirect('login', 'refresh');
		}
		$this->form_validation->set_rules('sdate', 'Date', 'trim|required');
		$this->form_validation->set_rules('category_id', 'Food Category', 'trim|required');
		$this->form_validation->set_rules('item_id', 'Food Item', 'trim|required');
		$this->form_validation->set_rules('quantity', 'Quantity', 'trim|required');
		$this->form_validation->set_rules('calories_taken', 'Calories taken', 'trim|required');
		

		if(isset($_POST['id']) && $_POST['id'] > 0){
			if ($this->form_validation->run() == FALSE)
	        {
				$getdata['getmealdata']= array(
					'id' => $_POST['id'],
				    'sdate'=>$_POST['sdate'],
				    'user_id'=>$this->session->userdata['selectedUser'],
				    'category_id'=>$_POST['category_id'],
				    'item_id'=>$_POST['item_id'],
				    'quantity'=>$_POST['quantity'],
				    'calories_taken'=>$_POST['calories_taken']
				);
				$data['title'] = "Update Meal";
				$getdata['getmealdata']= array();
				$getdata['userData']=$this->userdata->getRows();
				$getdata['categoryData']=$this->categorydata->getRows();
				$this->load->view('backend/header',$data);
				$this->load->view('backend/sidebar');
				$this->load->view('backend/mealedit',$getdata);
				$this->load->view('backend/footer');
				$this->load->view('backend/mealjs.php');
	            return false;
	        }
			$data=array(      
			    'sdate'=>date('Y-m-d',strtotime($_POST['sdate'])),
			    'user_id'=>$this->session->userdata['selectedUser'],
			    'category_id'=>$_POST['category_id'],
			    'item_id'=>$_POST['item_id'],
			    'quantity'=>$_POST['quantity'],
			    'calories_taken'=>$_POST['calories_taken'],
		      	'updated_by' => $this->session->userdata['USER_ID'],
		      	'updated_at' => date('Y-m-d H:i:s')
		    );
			$msg=$this->mealdata->update($data,$_POST['id']);
			if($msg =='success'){
				$this->session->set_flashdata('success', 'Meal Updated successfully');
				redirect('meal');
			}else{
				$id = $_POST['id'];
				$this->session->set_flashdata('Something is wrong, please try again', 'error');
				redirect('meal/editmeal/'.$id);
			}
		}else{
			if ($this->form_validation->run() == FALSE)
	        {
	        	$data['title'] = "Add Meal";
	        	$data['getmealdata']= array();
				$data['userData']=$this->userdata->getRows();
				$data['categoryData']=$this->categorydata->getRows();
				$this->load->view('backend/header', $data);
				$this->load->view('backend/sidebar');
				$this->load->view('backend/mealadd',$data);
				$this->load->view('backend/footer');
				$this->load->view('backend/mealjs.php');
	            return false;
	        }
			$data=array(      
			    'sdate'=>date('Y-m-d',strtotime($_POST['sdate'])),
			    'user_id'=>$this->session->userdata['selectedUser'],
			    'category_id'=>$_POST['category_id'],
			    'item_id'=>$_POST['item_id'],
			    'quantity'=>$_POST['quantity'],
			    'calories_taken'=>$_POST['calories_taken'],
		      	'created_by' => $this->session->userdata['USER_ID'],
		      	'created_at' => date('Y-m-d H:i:s')
		    );
			$msg=$this->mealdata->insert($data);
			if($msg == 'success'){
				$this->session->set_flashdata('success', 'Meal Added successfully');
				redirect('meal');
			}else{
				$this->session->set_flashdata('error', 'Something is wrong, please try again');
			    redirect('meal/addmeal');
			}
		}
	}


	public function saveMealapi() {
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
	 	$this->load->model('mealdata');
		
		if(!empty($postData['login_user_id'])){
			if(empty($postData['user_id'])){
				$result = array('status' => 'error', 'message' => 'User is not selected');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['sdate'])){
				$result = array('status' => 'error', 'message' => 'Date is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['category_id'])){
				$result = array('status' => 'error', 'message' => 'Category is empty');
				echo json_encode($result);
				exit();
			}

			if(empty($postData['item_id'])){
				$result = array('status' => 'error', 'message' => 'Item is empty');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['quantity'])){
				$result = array('status' => 'error', 'message' => 'Quantity is empty');
				echo json_encode($result);
				exit();
			}
			if(empty($postData['calories_taken'])){
				$result = array('status' => 'error', 'message' => 'Calories taken is empty');
				echo json_encode($result);
				exit();
			}

			if(!empty($postData['id']) && $postData['id'] > 0){
				
				$data=array(      
				    'sdate'=>date('Y-m-d',strtotime($postData['sdate'])),
				    'user_id'=>$postData['user_id'],
				    'category_id'=>$postData['category_id'],
				    'item_id'=>$postData['item_id'],
				    'quantity'=>$postData['quantity'],
				    'calories_taken'=>$postData['calories_taken'],
			      	'updated_by' => $postData['login_user_id'],
			      	'updated_at' => date('Y-m-d H:i:s')
			    );
				$msg=$this->mealdata->update($data,$postData['id']);
				if($msg =='success'){
					$result = array('status' => 'success', 'message' => 'Meal updated successfully');
					echo json_encode($result);
					exit();
				}else{
					$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
					echo json_encode($result);
					exit();
				}
			}else{
				
				$data=array(      
				    'sdate'=>date('Y-m-d',strtotime($postData['sdate'])),
				    'user_id'=>$postData['user_id'],
				    'category_id'=>$postData['category_id'],
				    'item_id'=>$postData['item_id'],
				    'quantity'=>$postData['quantity'],
				    'calories_taken'=>$postData['calories_taken'],
			      	'created_by' => $postData['login_user_id'],
			      	'created_at' => date('Y-m-d H:i:s')
			    );
				$msg=$this->mealdata->insert($data);
				if($msg == 'success'){
					$result = array('status' => 'success', 'message' => 'Meal added successfully');
					echo json_encode($result);
					exit();
				}else{
					$result = array('status' => 'error', 'message' => 'Something is wrong, please try again');
					echo json_encode($result);
					exit();
				}
			}
		}else{
			$result = array('status' => 'error', 'message' => 'User is not selected');
			echo json_encode($result);
			exit();
		}

	}


	public function deleteMeal() {
		$this->load->model('mealdata');
	  	$user_id= $this->session->userdata['selectedUser'];
	    $msg=$this->mealdata->deleteMeal($user_id);
	    if($msg == 'success'){
	    	$this->session->set_flashdata('success', 'Meal Deleted successfully');
	    	return 'success';
	    }else{
	    	$this->session->set_flashdata('error','Something is wrong, please try again');
	    	return 'error';
	    }
  	}


  	public function deleteMealapi() {
		$json = file_get_contents('php://input');
		$postData = json_decode($json,TRUE);
		$this->load->model('mealdata');
		if(!empty($postData['user_id'])){
		  	$user_id= $postData['user_id'];
		    $msg=$this->mealdata->deleteMeal($user_id);
		    if($msg == 'success'){
		    	$result = array('status' => 'error', 'message' => 'Meal deleted successfully');
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

}
