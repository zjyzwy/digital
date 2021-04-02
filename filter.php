<?php
$con=new mysqli("localhost","root","990926zwy","digital") or die("flower连接失败");
$con->query("SET NAMES utf8");

$cate_id = $_POST["cate_id"];
$str="select * from goods where cate_id='".$cate_id."'";
$rs=$con->query($str);
dump($rs);
?>