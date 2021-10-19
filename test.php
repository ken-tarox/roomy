<?php
//接続は省略
$query='select * from check_info';

$i = $lines = "";

if ($result = mysqli_query($link, $query)) {

     $i=0;
     while ($row = mysqli_fetch_row($result)) {
       $name[$i] = $row[0];
       $birthday[$i] = $row[1];
       $age[$i] = $row[2];






if($name[$i] == "未確定" && $age[$i] == "30"){
$lines .= "<tr><td style='background-color:red;'>".$name[$i]."</td><td>".$birthday[$i]."</td><td>".$age[$i]."</td></tr>\n";


} elseif ($name[$i] == "未確定"){
$lines .= "<tr><td style='background-color:red;'>".$name[$i]."</td><td>".$birthday[$i]."</td><td style='background-color:red;'>".$age[$i]."</td></tr>\n";

} else {



if($name[$i] == "未確定"){
$lines .= "<tr><td style='background-color:red;'>".$name[$i]."</td><td>".$birthday[$i]."</td><td>".$age[$i]."</td></tr>\n";
//未確定ならセルの背景色を赤にする     

} elseif ($name[$i] == "未確定" && $age[$i] == "30"){
$lines .= "<tr><td style='background-color:red;'>".$name[$i]."</td><td>".$birthday[$i]."</td><td style='background-color:red;'>".$age[$i]."</td></tr>\n";
//名前が未確定、年齢が30なら二つのセルの背景色を赤にする     

} else {
        $lines .= "<tr><td>".$name[$i]."</td><td>".$birthday[$i]."</td><td>".$age[$i]."</td></tr>\n";
//そうでなければ普通に表示
     }
     $i++;
    }
     mysqli_free_result($result);
}
 mysqli_close($link);


 $fp=fopen('./test.html','r');

 while(!feof($fp)) {
    $html_line=fgets($fp);
    $lines=str_replace("<#LIST#>",$lines,$html_line2);

    echo $lines;
 }

 fclose($fp);
 exit();

?>

