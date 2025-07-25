<?php
require_once 'db.php';

// 都道府県の名称一覧
$prefectureNames = [
    '1'  => '北海道',
    '2'  => '青森県',
    '3'  => '岩手県',
    '4'  => '宮城県',
    '5'  => '秋田県',
    '6'  => '山形県',
    '7'  => '福島県',
    '8'  => '茨城県',
    '9'  => '栃木県',
    '10' => '群馬県',
    '11' => '埼玉県',
    '12' => '千葉県',
    '13' => '東京都',
    '14' => '神奈川県',
    '15' => '新潟県',
    '16' => '富山県',
    '17' => '石川県',
    '18' => '福井県',
    '19' => '山梨県',
    '20' => '長野県',
    '21' => '岐阜県',
    '22' => '静岡県',
    '23' => '愛知県',
    '24' => '三重県',
    '25' => '滋賀県',
    '26' => '京都府',
    '27' => '大阪府',
    '28' => '兵庫県',
    '29' => '奈良県',
    '30' => '和歌山県',
    '31' => '鳥取県',
    '32' => '島根県',
    '33' => '岡山県',
    '34' => '広島県',
    '35' => '山口県',
    '36' => '徳島県',
    '37' => '香川県',
    '38' => '愛媛県',
    '39' => '高知県',
    '40' => '福岡県',
    '41' => '佐賀県',
    '42' => '長崎県',
    '43' => '熊本県',
    '44' => '大分県',
    '45' => '宮崎県',
    '46' => '鹿児島県',
    '47' => '沖縄県',
];

//　デフォルトで東京都 (ID:13) を利用
$prefecture_id = trim($_GET['prefecture_id'] ?? '13');
$prefectureName = $prefectureNames[$prefecture_id] ?? $prefecture_id;

// 指定した都道府県のデータを取得
$data = findTravelData($prefecture_id);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>旅行メモ一覧</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- style.css の読み込み -->
  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="./image/favicon.png" type="image/png">
</head>
<body>
  <div class="container my-4">
      <h1 class="mb-4">旅行データ一覧 (<?= htmlspecialchars($prefectureName, ENT_QUOTES, 'UTF-8') ?>)</h1>
      <?php if (!empty($data)): ?>
          <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>都道府県</th>
                        <th>場所</th>
                        <th>内容</th>
                        <th>URL</th>
                        <th>登録日</th>
                        <th>更新日</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($prefectureNames[$row['prefecture_id']] ?? $row['prefecture_id'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= nl2br(htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8')) ?></td>
                            <td>
                                <?php if ($row['url']): ?>
                                  <a href="<?= htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">リンク</a>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(date('Y年m月d日', strtotime($row['created_at'])), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(date('Y年m月d日', strtotime($row['updated_at'])), ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <!-- 編集ページへ -->
                                <a href="edit.php?id=<?= urlencode($row['id']) ?>&prefecture_id=<?= urlencode($prefecture_id) ?>" class="btn btn-sm btn-primary mb-1">編集</a>
                                <!-- 削除：クリック時に confirm() で確認後、delete.php へ GET リクエスト -->
                                <a href="delete.php?id=<?= urlencode($row['id']) ?>&prefecture_id=<?= urlencode($prefecture_id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('本当に削除してよろしいですか？');">削除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
          </div>
      <?php else: ?>
          <div class="alert alert-warning">データは存在しません。</div>
      <?php endif; ?>
      <div class="text-center mt-4">
        <a href="index.php?prefecture_id=<?= urlencode($prefecture_id) ?>" class="btn btn-secondary">データ入力に戻る</a>
      </div>
  </div>
  <!-- Bootstrap JS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
