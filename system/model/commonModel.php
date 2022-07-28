<?php

class commonModel extends Model
{
  //read
  function commonSelect($table,$where=null,$order=null,$sort=null,$limit=null)
  {
    if($where!==null)
    {
      $where=explode('|',$where);
      $whereScript=' WHERE `'.$where[0].'` = \''.$where[1].'\' ';
    }
    if($order!==null) $orderScript=' ORDER BY `'.$order.'` '.$sort.' ';
    if($limit!==null)
    {
      $limit=explode('|',$limit);
      $limitScript=' LIMIT '.$limit[0].','.$limit[1].' ';
    }

    $sql = "SELECT * FROM `$table`".$whereScript.$orderScript.$limitScript;
    $this->fetch($sql);
  }

  //insert
  function commonInsert($table,$columns,$values)
  {
    $sql = "INSERT INTO `".$table."` (".$columns.") VALUES (".$values.")";
    $this->fetch($sql);
  }

  //delete
  function commonDelete($table,$id)
  {
    $sql = "DELETE FROM `$table` WHERE `id` = '$id'";
    $this->fetch($sql);
  }

  //search
  function commonSearch($table,$column,$keyword)
  {
    $sql = "SELECT * FROM `".$table."` WHERE `".$column."` LIKE '%".$keyword."%'";
    $this->fetch($sql);
  }

  //count
  function countTotal($table)
  {
    $sql = "SELECT count(*) as total FROM `$table`";
    $res=mysql_query($sql);
    $data=mysql_fetch_assoc($res);
    return $data[total];
  }

  //update
  function commonIncrease($table,$column,$id)
  {
    $sql = "UPDATE `".$table."` SET ".$column." = ".$column." + 1 WHERE `id` = $id";
    $this->fetch($sql);
  }
  function commonUpdate($table,$set,$where)
  {
    $sql = "UPDATE `".$table."` SET ".set." WHERE ".$where;
    $this->fetch($sql);
  }
}
