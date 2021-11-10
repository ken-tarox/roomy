<div class="row form-signin2 mt-3">
  <h3>日付選択</h3>
</div>
<div class="row form-signin2">
  <form action="" method="post" enctype="multipart/form-data" name="myform">
    <input type="text" name="date" id="datepicker" value="<?php print($date); ?>" readonly="readonly" />

    <script>
      $("#datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        datepicker:false,
        // firstDay: 1, 
        monthNames: [ "1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月" ],
        minDate: '',
        maxDate: "+3m",

        onSelect : function(dateText, inst) {
          document.myform.submit();
      }
    });
    </script>

  </form>
</div>

<div class="row form-signin2 mt-3">
  <h3>予約一覧</h3>
</div>

<div class="row table-responsive form-signin2 resev_st">
  <table class="table table-striped" style="white-space: nowrap">
    <thead>
        <tr>
          <th>部屋名</th>
          <th>日付</th>
          <th>開始時刻</th>
          <th>終了時刻</th>
          <th></th>
        </tr>
    </thead>
    <tbody>
      <?php foreach($reservations as $reservation): ?>
      <?php if($date == $reservation['date'] ): ?>
        <tr>
          <th>
            <?php print(htmlspecialchars($reservation['item'], ENT_QUOTES)); ?>
          </th>
          <th>
            <?php
            $timestamp4 = strtotime($reservation['date']); 
            $w = date("w", $timestamp4);
            $week_name = array("日", "月", "火", "水", "木", "金", "土");
            $datTim = date("Y年m月d日", $timestamp4). "($week_name[$w])\n";
        
            print(htmlspecialchars($datTim, ENT_QUOTES));
            ?>
          </th>
          <th>
            <?php 
            $timestamp2 = strtotime($reservation['starttime']); 
            $stTim = date("
            H時i分", $timestamp2);
            print(htmlspecialchars($stTim, ENT_QUOTES)); 
            ?>
          </th>
          <th>
            <?php 
            $timestamp3 = strtotime($reservation['endtime']); 
            $enTim = date("
            H時i分", $timestamp3);
            print(htmlspecialchars($enTim, ENT_QUOTES)); 
            ?>
          </th>
          <th> 
            <a href="delete.php?id=<?php print(htmlspecialchars($reservation['id'])); ?>" class="btn btn-info btn-sm">削除</a>
          </th>
          <?php endif; ?>
          <?php endforeach; ?>
        </tr>
    </tbody>
  </table>
</div>