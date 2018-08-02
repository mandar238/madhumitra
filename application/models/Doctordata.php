<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Doctordata extends CI_Model{
    function __construct() {
        $this->doctorTbl = 'doctors';
    }
    /*
     * get rows from the users table
     */
    function getRows(){
        $this->db->select('doctors.*,users.username');
        $this->db->join('users', 'doctors.user_id = users.id');
        $this->db->from($this->doctorTbl);
        $this->db->where('doctors.is_deleted',0);
        $this->db->where('doctors.user_id',$this->session->userdata['selectedUser']);
        
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getData($params = array()){
        $this->db->select('doctors.*,users.username');
        $this->db->join('users', 'doctors.user_id = users.id');
        $this->db->from($this->doctorTbl);
        $this->db->where('doctors.is_deleted',0);
        $this->db->where('doctors.user_id',$params['selected_user_id']);
        
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

   

    function getRow($params = array()){
        $this->db->select('*');
        $this->db->from($this->doctorTbl);
        $this->db->where('id',$params['id']);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    
    public function insert($data = array()) {
        $this->db->from($this->doctorTbl);
        $query = $this->db->get();
        $result = $query->row_array();
        
        $insert = $this->db->insert($this->doctorTbl, $data);
        
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
       $this->db->update($this->doctorTbl, $data, array('id' => $id));   

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

    
    public function deleteDoctor($data = array(),$id)
    {
        $this->db->update($this->doctorTbl, $data, array('id' => $id));
        return 'success';
    }
}