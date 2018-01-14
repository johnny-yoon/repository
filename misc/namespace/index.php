<?php
use App\MyLib1;
header('Content-type: text/plain');
require_once('mylib1.php');

echo MyLib1\MYCONST . "\n";

?>