<?php
$arUrlRewrite=array (
  0 => 
  array (
    'CONDITION' => '#^/news/([^\\\\/]+)/([^\\\\/]+)/(\\$|\\\\?.*)#',
    'RULE' => 'SECTION_CODE=$1&ELEMENT_CODE=$2',
    'ID' => '',
    'PATH' => '/news/detail.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/news/([0-9a-zA-Z-]+)/.*#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/news/section.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/news3/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/news3/index.php',
    'SORT' => 100,
  ),
);
