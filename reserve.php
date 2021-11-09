
  <!-- <div id="">
    <h3>予約状況</h3>
  </div>
  <div class="slider">

  <table >
    <thead>
      <tr>
        <th ><?php print($datewoy); ?></th>
        <?php
        for ($num = 9; $num <= 20; $num++){
          echo('<th colspan="4">'.$num.'</th>') ;
        }
        ?>
      </tr>
    </thead>

  <?php
  
  //まず配列変数にデータを格納します。
  foreach($statement as $index => $state){
      // $room[$index] = substr($state['item'],4);
      // echo '$room['.$index.']='.$room[$index].'<br><br>';
      $strday[$index] = $state['date']."09:00:00";
      $enduy[$index] = $state['starttime'];
      //echo '$enduy='.$enduy.'<br><br>';
      $endy[$index] = $state['endtime'];
      $specdate[$index] = $state['date'];
      
      // $strbusi = new DateTime($state['date']."09:00:00");
      // $strres = new DateTime($state['starttime']);
      // $endres = new DateTime($state['endtime']);

      $strbusi[$index] = new DateTime($state['date']."09:00:00");
      $strres[$index] = new DateTime($state['starttime']);
      $endres[$index] = new DateTime($state['endtime']);
        
      // $tbstr = 5;
      // $tbend = 12;

      $strpt[$index] = $strres[$index]->diff($strbusi[$index]);
      $tbstr[$index] = ($strpt[$index]->h*60+$strpt[$index]->i)/15;
      //echo '$tbstr['.$index.']='.$tbstr[$index].'<br><br>';

      $endpt[$index] = $endres[$index]->diff($strres[$index]);
      $tbend[$index] = ($endpt[$index]->h*60+$endpt[$index]->i)/15+$tbstr[$index];
      //echo '$tbend['.$index.']='.$tbend[$index].'<br><br>';
      // print_r($tbend);
    }

      // echo('</br>');
      // echo $tbend;
      // echo $index. "：".  "\n";
      // // echo('</br>');
      // $_SESSION["startpoint"] = $tbstr;
      // $_SESSION["endpoint"] = $tbend;
      
      // var_dump($_SESSION["startpoint"]);
      // var_dump($_SESSION["endpoint"]);
      // echo('</br>');


      //1行ずつ処理していきます。
  for($roomnum = 1; $roomnum <= 3; $roomnum++){
    echo '<tr>';
    echo '<th>ROOM'.$roomnum.'</th>';
    echo '<div class=""> ';
    //1セルずつ処理していきます。
    for ($i = 1; $i <=48 ; $i++)
    {
    $flag=false;
    //全レコードをチェックします。
    for ($ind = 0; $ind <= $count-1 ; $ind++)
    {
    //ルーム番号が同じ時だけ処理します。
    if($room[$ind]==$roomnum && $specdate[$ind]==$date){
    //いずれからのレコードの時間幅の間におさまるときだけフラグをtrueに変えます。
    if($i >= $tbstr[$ind] && $i <= $tbend[$ind]){
      $flag=true;
  }
  }
  }
  //フラグがtrueのときのみ色のついたセルを描画します。
  if($flag){
  echo "<td $bg class=$i></td>";
  // echo "<td bgcolor=#C0C0C0 class= $i></td>";
  $bg = 'bgcolor=#C0C0C0';
  } else {
  echo "<td  class=$i></td>";
  $bg = '';
  }
  }
    
  } 
    //   for ($ind = 0; $ind <= $count-1 ; $ind++)
    //   {
    //     for ($i = 1; $i <=48 ; $i++)
    //   {
      
    //     if($index == 1)
    //     {

    //       if($i >= $_SESSION["startpoint"] && $i <= $_SESSION["endpoint"]){
    //         echo "<td $bg class=$i></td>";
    //         // echo "<td bgcolor=#C0C0C0 class= $i></td>";
    //         $bg = 'bgcolor=#C0C0C0';
    //       }
    //       else
    //       {
    //         echo "<td  class=$i></td>";
    //         $bg = '';
    //       }
    //     }
    //   // }
    //     }
    //   }

    // };  

      
      ?>

    
</table>
</div> -->
