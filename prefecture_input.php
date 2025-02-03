<?php
// prefecture_input.php
require_once 'db.php';

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

$message = '';

// GET パラメータから都道府県IDを取得
$prefecture_id = trim($_GET['prefecture_id'] ?? '');
if ($prefecture_id === '') {
    die("都道府県IDが指定されていません。");
}
$prefectureName = $prefectureNames[$prefecture_id] ?? $prefecture_id;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから送信された値を取得
    $prefecture_id = trim($_POST['prefecture_id'] ?? '');
    $title         = trim($_POST['title'] ?? '');
    $description   = trim($_POST['description'] ?? '');

    // 入力チェック
    if ($prefecture_id !== '' && $title !== '' && $description !== '') {
        $data = [
            'prefecture_id' => $prefecture_id,
            'title'         => $title,
            'description'   => $description,
        ];
        if (createTravelData($data)) {
            $message = "入力が保存されました。";
        } else {
            $message = "入力の保存に失敗しました。";
        }
    } else {
        $message = "すべての項目を入力してください。";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>データ入力 (<?= htmlspecialchars($prefectureName, ENT_QUOTES, 'UTF-8') ?>)</title>
</head>
<body>
    <h1>データ入力 (<?= htmlspecialchars($prefectureName, ENT_QUOTES, 'UTF-8') ?>)</h1>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <!-- 都道府県IDは隠しフィールドにして固定化 -->
        <input type="hidden" name="prefecture_id" value="<?= htmlspecialchars($prefecture_id, ENT_QUOTES, 'UTF-8') ?>">
        <p>都道府県: <?= htmlspecialchars($prefectureName, ENT_QUOTES, 'UTF-8') ?></p>

        <label for="title">タイトル:</label><br>
        <input type="text" name="title" id="title" required><br><br>

        <label for="description">説明:</label><br>
        <textarea name="description" id="description" rows="4" cols="40" required></textarea><br><br>

        <button type="submit">送信</button>
    </form>
    <p>
        <a href="prefecture_list.php?prefecture_id=<?= urlencode($prefecture_id) ?>">入力されたデータを確認する</a>
    </p>
    <p>
        <a href="index.php">Homeに戻る</a>
    </p>
</body>
</html>
