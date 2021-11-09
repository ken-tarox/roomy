<?php

$format = 'Y-m-d';
$now = new DateTime('+1 day');
$datey = $now->format('Y-m-d');

if(empty($_POST)){
  $date = $datey;
}else{
  $date = $_POST['date'];
}

$statement = $db->prepare('SELECT r.* FROM members m, reservations r WHERE m.id=r.member_id');
$statement->execute();

$record = $db->prepare('SELECT count(*) FROM reservations');
  $record->execute();
  $count = $record->fetchColumn();

  foreach($statement as $index => $state){
  
    $room[$index] = substr($state['item'],4);
    $strday[$index] = $state['date']."09:00:00";
    $enduy[$index] = $state['starttime'];
    $endy[$index] = $state['endtime'];
    $spdate[$index] = $state['date'];
    
    $strbusi[$index] = new DateTime($state['date']."09:00:00");
    $strres[$index] = new DateTime($state['starttime']);
    $endres[$index] = new DateTime($state['endtime']);
    
    $strpt[$index] = $strres[$index]->diff($strbusi[$index]);
    $tbstr[$index] = ($strpt[$index]->h*60+$strpt[$index]->i)/15;

    $endpt[$index] = $endres[$index]->diff($strres[$index]);
    $tbend[$index] = ($endpt[$index]->h*60+$endpt[$index]->i)/15+$tbstr[$index];

    }
  
  for($roomnum = 1; $roomnum <= 3; $roomnum++){
    echo '<tr >';
    echo '<th scope="row">ROOM'.$roomnum.'</th>';
    echo '<div class=""> ';

    for ($i = 1; $i <=48 ; $i++)
    {
    $flag=false;

    for ($ind = 0; $ind <= $count-1 ; $ind++)
    {

    if($room[$ind]==$roomnum && $spdate[$ind]==$date){

    if($i >= $tbstr[$ind] && $i <= $tbend[$ind]){
      $flag=true;
  }
  }
  }
  if($flag){
  echo "<td $bg class=$i></td>";
  $bg = 'bgcolor=#7febbf
  ';
  } else {
  echo "<td  class=$i></td>";
  $bg = '';
  }
  }
  } 
?>