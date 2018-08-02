<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Settingsdata extends CI_Model{
    function __construct() {
        $this->settingTbl = 'settings';
        
    }
    /*
     * get rows from the users table
     */
    function getRow(){
        $this->db->select('*');
        $this->db->from($this->settingTbl);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['no_of_emp'];
    }


    public function update($data = array(),$id) {
       
        //insert user data to users table
       $this->db->update($this->settingTbl, $data, array('id' => $id));   

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
}