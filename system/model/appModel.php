<?php

class appModel extends Model
{
  //create
  function insertContract($url,$author,$title,$text,$ip,$device,$time,$parent,$comment_type)
  {
    $sql = "INSERT INTO `showroom`
      (`id`, `url`, `author`, `title`, `text`, `ip`, `device`, `time`, `view`, `parent`, `comment_type`)
    VALUES
      (NULL, '$url', '$author', '$title', '$text', '$ip', '$device', '$time', '0', '$parent', '$comment_type')";
    $this->fetch($sql);
  }

  //read
  function readShowroom($limit='0,6',$sort='id')
  {
    $sql = "SELECT * FROM `showroom` WHERE `parent` = '' ORDER BY `$sort` DESC LIMIT $limit";
    $this->fetch($sql);
    return $this->packageData();
  }
  function listComment($limit='0,6',$sort='id')
  {
    $sql = "SELECT * FROM `showroom` WHERE `parent` != '' ORDER BY `$sort` DESC LIMIT $limit";
    $this->fetch($sql);
    return $this->packageData();
  }
  function readItem($url)
  {
    $sql = "SELECT * FROM `showroom` WHERE `url` = '$url'";
    $this->fetch($sql);
    return $this->getData();
  }
  function readComment($url)
  {
    $sql = "SELECT * FROM `showroom` WHERE `parent` = '$url' ORDER BY 'id'";
    $this->fetch($sql);
    return $this->packageData();
  }

  //update
  function viewUpdate($id)
  {
    $sql = "UPDATE `showroom` SET view = view + 1 WHERE `id` = $id";
    $this->fetch($sql);
  }

  //delete

  //search

  //count
  function countPost()
  {
    $sql = "SELECT count(*) as total FROM `showroom` WHERE `parent` = ''";
    $res=mysql_query($sql);
    $data=mysql_fetch_assoc($res);
    return $data[total];
  }
  function countComment()
  {
    $sql = "SELECT count(*) as total FROM `showroom` WHERE `parent` != ''";
    $res=mysql_query($sql);
    $data=mysql_fetch_assoc($res);
    return $data[total];
  }

}
