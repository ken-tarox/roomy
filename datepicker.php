<div class="mt-5 row form-signin2">
    <h3>日付選択</h3>
  </div>
  <div class="mb-5 row form-signin2">
    <form action="" method="post" enctype="multipart/form-data" name="myform">
      <input type="text" name="date" id="datepicker" value="<?php print($date); ?>" readonly="readonly" />
      <script>
        $("#datepicker").datepicker({
          dateFormat: 'yy-mm-dd',
          datepicker:false,
          firstDay: 1, 
          monthNames: [ "1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月" ],
          minDate: '+1d',
          maxDate: "+3m",

          onSelect : function(dateText, inst) {
            document.myform.submit();
        }
      });
      </script>
    </form>
  </div>