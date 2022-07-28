<?php

class showroom extends View
{
  function __construct($url)
  {
    parent::__construct();

    @$total = $this->handle('countPost');
    if(@$_GET['sort'] == 'hot') $sort = 'view'; else $sort = 'id';
    $data = $this->handle('readShowroom','0,'.$this->config['showroom']['perpage'],$sort);
    foreach ($data as $value)
    {
      if($value['view'] < 1000)
        $boom = '';
      elseif(1000 <= $value['view'] && $value['view'] < 2000)
        $boom = '<span style="color:#535353">爆</span> ';
      elseif(2000 <= $value['view'] && $value['view'] < 5000)
        $boom = '<span style="color:red">爆</span> ';
      elseif(5000 <= $value['view'] && $value['view'] < 10000)
        $boom = '<span style="color:#0000FF">爆</span> ';
      elseif(10000 <= $value['view'] && $value['view'] < 30000)
        $boom = '<span style="color:#04F5FC">爆</span> ';
      elseif(30000 <= $value['view'] && $value['view'] < 60000)
        $boom = '<span style="color:#00E604">爆</span> ';
      elseif(60000 <= $value['view'] && $value['view'] < 100000)
        $boom = '<span style="color:yellow">爆</span> ';
      else
        $boom = '<span style="color:#EF00EE">爆</span> ';

      if (strposa($value['author'].$value['title'].$value['text'], $this->config['app']['avoidlist']) && $value['time'] < 1421432892)
        $image = 'http://placehold.it/800x420/000000/ffffff&text=Protected';
      else
        $image = $this->config['site']['path'].'i/'.$value['url'].'.png';

      @$list.= loadPartial('showroomItem',[
        '$path' => $this->config['site']['path'],
        '$url' => $value['url'],
        '$image' => $image,
        '$title' => $boom.replaceByArray($value['title'],$this->config['app']['avoid'])
      ]);
    }
    echo pageCreator($this->config,'default',[
      '$content' => viewParser('showroom.html',[
        '$path' => $this->config['site']['path'],
        '$total' => $total,
        '$list' => @$list,
        '$sort' => @$_GET['sort']
      ]),
      '$foot-custom' => loadPartial('showroom',[
        '$path' => $this->config['site']['path']
      ],'js')
    ]);
  }
}
class detail extends View
{
  function __construct($url)
  {
    parent::__construct();

    $data = $this->handle('readItem',$url[0]);
    if($data['id'] == null)
    {
      $url = $this->config['site']['path'];
      header("Location: $url");
    }
    else
    {
      //insert analytics
      if(!isset($_SESSION['showroom'.$data['id']]))
      {
        $ip = getUserIP();
        $_SESSION['showroom'.$data['id']] = $ip;
        $this->handle('viewUpdate',$data['id']);
      }

      //parent
      if($data['parent'] !== '') {
        $parent_url = $this->config['site']['path'].$data['parent'];
        $parent = '本篇為 <a href="'.$parent_url.'">'.$parent_url.'</a> 之回應';
      }
      else $parent = '';

      //comment
      $comments = $this->handle('readComment',$url[0]);
      foreach ($comments as $comment)
      {
        switch ($comment['comment_type']) {
          case 1: $type = '<i style="color: #C0C0C0;font-style:normal">推</i>'; break;
          case 2: $type = '噓'; break;
          default: $type = '→'; break;
        }
        @$list.= loadPartial('commentItem',[
          '$author' => replaceByArray($comment['author'],$this->config['app']['avoid']),
          '$text' => replaceByArray($comment['text'],$this->config['app']['avoid']),
          '$time' => gmdate("m/d H:i", $comment['time']),
          '$url' => $this->config['site']['path'].$comment['url'],
          '$type' => $type
        ]);
      }

      if (strposa($data['author'].$data['title'].$data['text'], $this->config['app']['avoidlist']) && $data['time'] < 1421432892)
        $image = 'http://placehold.it/800x420/000000/ffffff&text=Protected';
      else
        $image = $this->config['site']['path'].'i/'.$data['url'].'.png';

      echo pageCreator($this->config,'default',[
        '$content' => viewParser('detail.html',[
          '$path' => $this->config['site']['path'],
          '$url' => $data['url'],
          '$image' => $image,
          '$comment-list' => @$list,
          '$parent' => $parent
        ]),
        '$og' => viewParser('_og.html',[
            '$title' => replaceByArray($data['title'],$this->config['app']['avoid']),
            '$type' => $this->config['og']['type'],
            '$url' => $this->config['site']['path'].$data['url'],
            '$image' => $image,
            '$sitename' => $this->config['og']['sitename'],
            '$description' => '&#x4f5c;&#x8005; '.replaceByArray($data['author'],$this->config['app']['avoid'])
        ])
      ]);
    }
  }
}
