<?php

class Model
{
  var $db;
  var $query;
  function __construct()
  {
    $this->query = '';
    $this->db = mysql_pconnect(DBHOST,DBUSER,DBPASS);
    mysql_query("SET NAMES 'utf8'");
    mysql_select_db(DBNAME,$this->db);
  }
  function fetch($sql)
  {
    @$this->query = mysql_unbuffered_query($sql,$this->db);
  }
  function getRow()
  {
    if ($row = mysql_fetch_array($this->query,MYSQL_ASSOC)) return $row;
    else return false;
  }
	function getData()
	{
		if ($data = $this->getRow()) return $data;
		else return false;
	}
  function packageData()
  {
    $package = array();
    while($data = $this->getRow()) {
      array_push($package, $data);
    }
    return $package;
  }
}
