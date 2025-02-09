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

// GET パラメータで編集するレコードのIDを取得
$id = $_GET['id'] ?? null;
if (!$id) {
    die('IDが指定されていません。');
}

// 都道府県ID（一覧画面から渡されたもの）
$prefecture_id = trim($_GET['prefecture_id'] ?? '');

// 指定したIDのレコードを取得
$record = getTravelDataById($id);
if (!$record) {
    die('指定された旅行情報が見つかりません。');
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから送信された値を取得
    $prefecture_id = trim($_POST['prefecture_id'] ?? '');
    $title         = trim($_POST['title'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $url           = trim($_POST['url'] ?? '');

    // すべての項目が入力されているかチェック
    if ($prefecture_id !== '' && $title !== '' && $description !== '' && $url !== '') {
        $data = [
            'prefecture_id' => $prefecture_id,
            'title'         => $title,
            'description'   => $description,
            'url'           => $url,
        ];
        if (updateTravelData($id, $data)) {
            $message = '更新が保存されました。';
            // 最新データを再取得
            $record = getTravelDataById($id);
        } else {
            $message = '更新の保存に失敗しました。';
        }
    } else {
        $message = 'すべての項目を入力してください。';
    }
}

$currentPrefectureName = $prefectureNames[$record['prefecture_id']] ?? $record['prefecture_id'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>旅行情報編集</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="favicon.png" type="image/png" />
</head>
<body>
  <div class="container my-4">
      <h1 class="mb-4">旅行情報編集</h1>
      
      <?php if ($message): ?>
          <div class="alert alert-info"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
      
      <form action="" method="POST">
          <div class="mb-3">
              <label class="form-label">都道府県</label>
              <!-- 都道府県はセレクトボックスで選択 -->
              <select name="prefecture_id" class="form-select" required>
                  <?php foreach ($prefectureNames as $key => $name): ?>
                      <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" <?= $record['prefecture_id'] == $key ? 'selected' : '' ?>>
                          <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>
          <div class="mb-3">
              <label for="title" class="form-label">場所</label>
              <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($record['title'], ENT_QUOTES, 'UTF-8') ?>" required>
          </div>
          <div class="mb-3">
              <label for="description" class="form-label">内容</label>
              <textarea name="description" id="description" rows="4" class="form-control" required><?= htmlspecialchars($record['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>
          <div class="mb-3">
              <label for="url" class="form-label">URL</label>
              <input type="url" name="url" id="url" class="form-control" value="<?= htmlspecialchars($record['url'], ENT_QUOTES, 'UTF-8') ?>" required>
          </div>
          <div class="text-center">
              <button type="submit" class="btn btn-primary">更新</button>
              <a href="prefecture_list.php?prefecture_id=<?= urlencode($prefecture_id) ?>" class="btn btn-secondary">戻る</a>
          </div>
      </form>
  </div>
  <!-- Bootstrap JS CDN (オプション) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
