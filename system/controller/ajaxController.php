<?php

class ajaxShowroom extends View
{
  function __construct()
  {
    parent::__construct();

    if(isset($_POST['page']))
    {
      @$total = $this->handle('countPost');
      $page = ($_POST['page']+1) * $this->config['showroom']['perpage'];

      if($total >= $page)
      {
        if(($page + $this->config['showroom']['perpage']) > $total)
          $limit = $page.','.($total - $page + 1);
        else
          $limit = $page.','.$this->config['showroom']['perpage'];

        if(@$_POST['sort'] == 'hot') $sort = 'view'; else $sort = 'id';
        $data = $this->handle('readShowroom',$limit,$sort);
        foreach ($data as $value)
        {
          if (strposa($value['author'].$value['title'].$value['text'], $this->config['app']['avoidlist']) && $value['time'] < 1421432892)
            $image = 'http://placehold.it/800x420/000000/ffffff&text=Protected';
          else
            $image = $this->config['site']['path'].'i/'.$value['url'].'.png';

          @$list.= loadPartial('showroomItem',[
            '$path' => $this->config['site']['path'],
            '$url' => $value['url'],
            '$image' => $image,
            '$title' => replaceByArray($value['title'],$this->config['app']['avoid'])
          ]);
        }
        echo @$list;
      }
    }
  }
}
