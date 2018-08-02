<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Userdata extends CI_Model{
    function __construct() {
        $this->userTbl = 'users';
        
    }
    /*
     * get rows from the users table
     */
    function getRows(){
        $this->db->select('*');
        $this->db->from($this->userTbl);
        $this->db->where('created_by',$this->session->userdata['USER_ID']);
        $this->db->where('user_status',1);
        $this->db->or_where('id',$this->session->userdata['USER_ID']);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getUserList($data){
        $this->db->select('users.*,cities.name as city_name');
        $this->db->join('cities', 'users.city_id = cities.id','left');
        $this->db->from($this->userTbl);
        $this->db->where('users.created_by',$data['login_user_id']);
        $this->db->where('users.user_status',1);
        $this->db->or_where('users.id',$data['login_user_id']);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function getallRows(){
        $this->db->select('users.*,cities.name as city_name');
        $this->db->join('cities', 'users.city_id = cities.id','left');
        $this->db->where('users.user_type_id != ',1,FALSE);
        $this->db->order_by('users.created_at DESC');
        $this->db->from($this->userTbl);

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    function checkusercnt()
    {
        $user_id = $this->session->userdata['USER_ID'];

        $this->db->select('*');
        $this->db->where('created_by',$user_id);
        $this->db->from($this->userTbl);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
        
    }

    function checkusercntapi($data)
    {
        $user_id = $data['login_user_id'];

        $this->db->select('*');
        $this->db->where('created_by',$user_id);
        $this->db->from($this->userTbl);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
        
    }

    function checkUserExists($data)
    {
        
        $this->db->select('*');
        $this->db->where('email_id',$data['email_id']);
        $this->db->or_where('mobile_no',$data['mobile_no']);
        $this->db->from($this->userTbl);
        
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
        
    }

    function gettotalUser(){
        $this->db->select('*');
        $this->db->from($this->userTbl);
        $this->db->where('user_type_id != ',1,FALSE);
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    function getactiveUser(){
        $this->db->select('*');
        $this->db->from($this->userTbl);
        $this->db->where('user_type_id != ',1,FALSE);
        $this->db->where('user_status',1);
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    function getinactiveUser(){
        $this->db->select('*');
        $this->db->from($this->userTbl);
        $this->db->where('user_status',0);
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    function processLogin($userName,$password){
        $password = base64_encode($password);
        $this->db->select('*');
        $this->db->from($this->userTbl);
        $this->db->where('mobile_no',$userName);
        $this->db->where('password',$password);
        $query = $this->db->get();

        //$result = $query->row_array();
        return $query;
     }


    function activeLogin($userName,$password){
        $password = base64_encode($password);
        $this->db->select('*');
        $this->db->from($this->userTbl);
        $this->db->where('mobile_no',$userName);
        $this->db->where('password',$password);
        $this->db->where('user_status',1);
        $query = $this->db->get();
        
        return $query;
     }


     function getPassword($email_id)
     {
        $this->db->select('*');
        $this->db->from($this->userTbl);
        $this->db->where('email_id',$email_id);
        $query = $this->db->get();
        return $query;
     }

    public function saveNewPass($new_pass,$username)
    {
        $data = array(
               'password' => $new_pass
            );
        $this->db->where('mobile_no', $username);
        $this->db->update('users', $data);
        return true;
    }


    function getRow($params = array()){
        $this->db->select('*');
        $this->db->from($this->userTbl);
        $this->db->where('id',$params['id']);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    
    public function insert($data = array()) {
        $this->db->from($this->userTbl);
        $query = $this->db->get();
        $result = $query->row_array();
        
        $insert = $this->db->insert($this->userTbl, $data);
        
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
       $this->db->update($this->userTbl, $data, array('id' => $id));   

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


    public function lastLogin($data = array(),$id) {
       
        //insert user data to users table
       $this->db->update($this->userTbl, $data, array('id' => $id));   

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

    
    public function updateStatus($data = array(),$id)
    {
        $this->db->update($this->userTbl, $data, array('id' => $id));
        return 'success';
    }

    public function deleteUser($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->userTbl);

        $this->db->where('user_id', $id);
        $this->db->delete('bsl_estimation');

        $this->db->where('user_id', $id);
        $this->db->delete('doctors');

        $this->db->where('user_id', $id);
        $this->db->delete('hba_estimation');

        $this->db->where('user_id', $id);
        $this->db->delete('meal');

        $this->db->where('user_id', $id);
        $this->db->delete('prescription');

        $this->db->where('user_id', $id);
        $this->db->delete('user_activities');

        $this->db->where('user_id', $id);
        $this->db->delete('user_exercise');

        return 'success';
    }

    public function updatePassword($data = array(),$id)
    {
        $this->db->update($this->userTbl, $data, array('id' => $id));
        return 'success';
    }

    


    public function checkOldPassword($userName,$oldPassword)
    {
        $password = base64_encode($password);
        $this->db->select('*');
        $this->db->from($this->userTbl);
        $this->db->where('username',$userName);
        $this->db->where('password',$password);
        $query = $this->db->get();
        //$result = $query->row_array();
        return $query;
    }


    public function getreportData($data)
    {
        // /print_r($data);exit;
        $sql = "SELECT users.*, cities.name as city_name FROM users LEFT JOIN cities ON users.city_id=cities.id where users.is_deleted = 0 AND users.user_type_id !=1 AND $data ORDER By users.created_at DESC";
        //echo $sql;exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
        
    }
}