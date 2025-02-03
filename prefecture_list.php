<?php
// prefecture_list.php
require_once 'db.php';

// 都道府県IDと名称の対応表
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

// GET パラメータから都道府県IDを取得
$prefecture_id = trim($_GET['prefecture_id'] ?? '');
if ($prefecture_id === '') {
    die("都道府県IDが指定されていません。");
}
$prefectureName = $prefectureNames[$prefecture_id] ?? $prefecture_id;

// 指定した都道府県IDのデータのみ取得
$data = findTravelData($prefecture_id);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>入力された旅行データ一覧 (<?= htmlspecialchars($prefectureName, ENT_QUOTES, 'UTF-8') ?>)</title>
</head>
<body>
    <h1>入力された旅行データ一覧 (<?= htmlspecialchars($prefectureName, ENT_QUOTES, 'UTF-8') ?>)</h1>
    <?php if (!empty($data)): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>都道府県</th>
                <th>タイトル</th>
                <th>説明</th>
                <th>登録日時</th>
                <th>更新日時</th>
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <!-- 数字の都道府県IDではなく、対応する名称を表示 -->
                    <td><?= htmlspecialchars($prefectureNames[$row['prefecture_id']] ?? $row['prefecture_id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['updated_at'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>データは存在しません。</p>
    <?php endif; ?>
    <p>
        <a href="prefecture_input.php?prefecture_id=<?= urlencode($prefecture_id) ?>">データ入力に戻る</a>
    </p>
    <p>
        <a href="index.php">Homeに戻る</a>
    </p>
</body>
</html>
