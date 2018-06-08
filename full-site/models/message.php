<?php

class Message extends Model {

    public function save($data, $id = null){
        if ( !isset($data['name']) || !isset($data['email']) || !isset($data['message']) ){
            return false;
        }

        $id = (int)$id;
        $name = $this->db->escape($data['name']);
        $email = $this->db->escape($data['email']);
        $message = $this->db->escape($data['message']);

        if ( !$id ){ // Add new record
            $sql = "
                insert into messages
                   set name = '{$name}',
                       email = '{$email}',
                       message = '{$message}'
            ";
        } else { // Update existing record
            $sql = "
                update messages
                   set name = '{$name}',
                       email = '{$email}',
                       message = '{$message}'
                   where id = {$id}
            ";
        }

        return $this->db->query($sql);

    }

    public function getList(){
        $sql = "select * from `recall` where 1";
        return $this->db->query($sql);
    }

    public function info($info){
        $message = $info['message'];
        $id = $info['id'];
        if ( !$id ){ // Add new record
            $sql = "
                insert into contact_info
                   set 
                       text = '{$message}'
            ";
        } else { // Update existing record
            $sql = "
                update contact_info
                   set
                       text = '{$message}'
                   where id = {$id}
            ";
        }
        return $this->db->query($sql);
    }

    public function getInfo(){
        $sql = "select text,id from `contact_info`";
        $result = $this->db->query($sql);
        if($result[0]) {
            return $result[0];
        }
    }

}