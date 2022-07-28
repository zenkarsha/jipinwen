<?php

class index extends View
{
  function __construct($url)
  {
    parent::__construct();

    echo pageCreator($this->config,'default',[
      '$content' => viewParser('index.html',[
        '$path' => $this->config['site']['path'],
        '$title' => $this->config['site']['title'],
        '$description' => $this->config['site']['description'],
        '$default-text' => ''
      ])
    ]);
  }
}
class index214 extends View
{
  function __construct($url)
  {
    parent::__construct();

    foreach ($this->config['facebook']['fanpage'] as $value)
      @$fanpage .= loadPartial('fanpageBox',['$url' => $value]);

    echo pageCreator($this->config,'default',[
      '$content' => viewParser('index.html',[
        '$path' => $this->config['site']['path'],
        '$title' => '割闌尾'.$this->config['site']['title'],
        '$description' => $this->config['site']['description'],
        '$fanpage' => $fanpage,
        '$default-text' => '如果割闌尾投票率過半
我就這樣這樣、那樣那樣'
      ]),
      '$og' => viewParser('_og.html',[
          '$title' => '割闌尾'.$this->config['og']['title'],
          '$type' => $this->config['og']['type'],
          '$url' => $this->config['site']['path'].'214',
          '$image' => $this->config['og']['image'],
          '$sitename' => '割闌尾'.$this->config['og']['sitename'],
          '$description' => $this->config['og']['description'],
      ])
    ]);
  }
}
