<?php
require './bootstrap.php';
require './ImageBbsApplication.php';

$categories =  ['料理', '景色', '動物', 'スポーツ', 'ゲーム', 'その他'];
$colors = [ 
    'red'    => '赤',
    'blue'   => '青',
    'yellow' => '黄色',
    'green'  => '緑',
    'pink'   => 'ピンク',
    'black'  => '黒',
];

$app = new ImageBbsApplication(true);
$app->run();

