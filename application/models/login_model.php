<?php
    Class login_model extends CI_Model{
            
        function login($email, $pass){
            $query = $this->db->select('user_salt')->from('users')->where('user_email',$email)->get();
            if($query -> num_rows() == 1){
                $user_salt = $query->row()->user_salt;
            }else{
                return false;
            }


            $this ->db->select('user_id, user_email, user_first_name, user_last_name, user_theme, user_password, is_disabled'); 
            $this ->db->from('users');
            $this ->db->where('user_email = ' . "'" . $email . "'");
            $this ->db->where('user_password = ' . "'" .hash("sha512",hash("md5",$user_salt.$pass)). "'");
            $this ->db->limit(1);
            $query = $this -> db -> get();

            if($query -> num_rows() == 1){
                return $query->result();
            }else{
                return false;
            }
        }

        function checkEmail($email){
     		$this->db->where('user_email',$email);
     		$query=$this->db->get('users');
     		return ($query->num_rows()==1);
        }
        
        function changePassword($email,$pass){
            $user_salt = $this->db->select('user_salt')->from('users')->where('user_email',$email)->get()->row()->user_salt;

     		$this->db->where('user_email',$email);
    		$this->db->update('users', array(
                'user_password' => hash("sha512",hash("md5",$user_salt.$pass))
    		)); 
        }
    }
?>