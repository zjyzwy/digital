<?php
$con=new mysqli("localhost","root","990926zwy","digital") or die("flower连接失败");
$con->query("SET NAMES utf8");

$email=$_POST["email"];
$str="select * from users where email='".$email."'";
$rs=$con->query($str);     //面向对象
if($rs->fetch_row()){   //面向对象
    echo "您输入的email已存在！";
    
}else{
    echo "恭喜您，email可以使用！";
}
?>