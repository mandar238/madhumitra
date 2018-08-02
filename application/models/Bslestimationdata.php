<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bslestimationdata extends CI_Model{
    function __construct() {
        $this->bslestimationTbl = 'bsl_estimation';
    }
    /*
     * get rows from the users table
     */
    function getRows(){
        $this->db->select('bsl_estimation.*,users.username');
        $this->db->join('users', 'bsl_estimation.user_id = users.id');
        $this->db->from($this->bslestimationTbl);
        $this->db->where('bsl_estimation.is_deleted',0);
        $this->db->where('bsl_estimation.user_id',$this->session->userdata['selectedUser']);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getData($params = array()){
        $this->db->select('bsl_estimation.*,users.username');
        $this->db->join('users', 'bsl_estimation.user_id = users.id');
        $this->db->from($this->bslestimationTbl);
        $this->db->where('bsl_estimation.is_deleted',0);
        $this->db->where('bsl_estimation.user_id',$params['selected_user_id']);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

   

    function getRow($params = array()){
        $this->db->select('*');
        $this->db->from($this->bslestimationTbl);
        $this->db->where('id',$params['id']);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    function getreportData($params = array()) {
        $user_id = $params['user_id'];
        $fromDate = date('Y-m-d',strtotime($params['fromDate']));
        $toDate = date('Y-m-d',strtotime($params['toDate']));
        $this->db->select('*');
        $this->db->from($this->bslestimationTbl);
        $this->db->where('user_id',$user_id);
        $this->db->where('date >=',$fromDate);
        $this->db->where('date <=',$toDate);        
        $this->db->where('is_deleted',0);
        $this->db->order_by('date');
        $this->db->order_by('time');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    
    public function insert($data = array()) {
        $this->db->from($this->bslestimationTbl);
        $query = $this->db->get();
        $result = $query->row_array();
        
        $insert = $this->db->insert($this->bslestimationTbl, $data);
        
        //return the status
        if($insert){
            
             return 'success';
            //return $this->db->insert_id();;
        }else{
            return 'error';
        }
    }
    public function update($data = array(),$id) {
       
        //insert user data to users table
       $this->db->update($this->bslestimationTbl, $data, array('id' => $id));   

       if ($this->db->trans_status() === FALSE)
        {


           // echo 'error';die;
            $this->db->trans_rollback();
           // $this->set_error('update_unsuccessful');
            return 'error';
        } 
        $this->db->trans_commit();

      
        //$this->set_message('update_successful');
        return 'success';

        //echo '<pre>',print_r($update);
        //return the status
       
    }

     public function deleteBslestimation($user_id)
    {
        $this->db-> where('user_id', $user_id);
        $this->db-> delete($this->bslestimationTbl);
        return 'success';
    }

}