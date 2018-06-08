<?php

class Blog extends Model
{
    public function qqq()
    {
        $sql = "select * from blog where 1";
        return $this->db->query($sql);

    }

    public function getById($id){
        $id = (int)$id;
        $sql = "select * from blog where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function save($data, $id = null)
    {
        if (!isset($data['name']) || !isset($data['massage'])) {
            return false;
        }

        $id = (int)$id;
        $name = $this->db->escape($data['name']);
        $massage = $this->db->escape($data['massage']);

        if (!$id) { // Add new record
            $sql = "
                insert into blog
                   set name = '{$name}',
                       massage = '{$massage}'
            ";
        } else { // Update existing record
            $sql = "
                update blog
                   set name = '{$name}',
                       massage = '{$massage}'
                   where id = {$id}
            ";
        }

        return $this->db->query($sql);

    }

    public function blog_delete($id)
    {
         $id = (int)$id;
         $sql = "delete from blog where id = {$id}";
         return $this->db->query($sql);

    }
	
}