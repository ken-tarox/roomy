<div class="row form-signin2 mt-5">
  <h3>会員一覧</h3>
</div>

<div class="row table-responsive form-signin2 resev_st mb-5">
  <table class="table table-striped" style="white-space: nowrap">
    <thead>
        <tr>
          <th>名前</th>
          <th>Email</th>
          <th>登録日</th>
          <th></th>
        </tr>
    </thead>
    <tbody>
      <?php foreach($delmembers as $delmember): ?>
      <?php if($delmember['admin'] == false): ?>
      <tr>
        <th>
          <?php print(htmlspecialchars($delmember['name'], ENT_QUOTES)); ?>
        </th>
        <th>
          <?php print(htmlspecialchars($delmember['email'], ENT_QUOTES)); ?>
        </th>
        <th>
          <?php print(htmlspecialchars($delmember['created'], ENT_QUOTES)); ?>
        </th>
        <th> 
          <a href="deleteuser.php?id=<?php print(htmlspecialchars($delmember['id'])); ?>" class="btn btn-info btn-sm">削除</a>
        </th>
        <?php endif; ?>
        <?php endforeach; ?>
      </tr>
    </tbody>
  </table>
</div>