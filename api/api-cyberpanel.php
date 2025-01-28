<?php

if(isset($_GET['aksi'])){
  $list = getallheaders();
  $file = fopen("header-cyberpanel.json","w+");
  fwrite($file, json_encode($list));
  fclose($file);

  echo json_encode(["result" => "okeh"]);
}