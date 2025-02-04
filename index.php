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

// GET パラメータがなければ、デフォルトで東京都 (ID:13) を利用
$prefecture_id = trim($_GET['prefecture_id'] ?? '13');
$prefectureName = $prefectureNames[$prefecture_id] ?? $prefecture_id;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから送信された値を取得
    $prefecture_id = trim($_POST['prefecture_id'] ?? '');
    $title         = trim($_POST['title'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $url           = trim($_POST['url'] ?? '');

    // すべての項目（URL も含む）が入力されているかチェック
    if ($prefecture_id !== '' && $title !== '' && $description !== '' && $url !== '') {
        $data = [
            'prefecture_id' => $prefecture_id,
            'title'         => $title,
            'description'   => $description,
            'url'           => $url,
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
    <title>旅行メモ</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      /* フォームやマップのレイアウト調整 */
      #map {
          max-width: 500px;
          margin: 20px auto;
      }
      .svg-container {
          text-align: center;
      }
    </style>
</head>
<body>
    <div class="container my-4">
        <h1 class="mb-4">データ入力 (<?= htmlspecialchars($prefectureName, ENT_QUOTES, 'UTF-8') ?>)</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="mb-4">
            <!-- 都道府県IDは隠しフィールド -->
            <input type="hidden" name="prefecture_id" value="<?= htmlspecialchars($prefecture_id, ENT_QUOTES, 'UTF-8') ?>">
            <div class="mb-3">
                <label class="form-label">都道府県</label>
                <p class="form-control-plaintext"><?= htmlspecialchars($prefectureName, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">場所</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="場所を入力" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">内容</label>
                <textarea name="description" id="description" rows="4" class="form-control" placeholder="内容を入力" required></textarea>
            </div>
            <div class="mb-3">
                <label for="url" class="form-label">URL</label>
                <input type="url" name="url" id="url" class="form-control" placeholder="リンク先のURLを入力" required>
            </div>
            <button type="submit" class="btn btn-primary">送信</button>
        </form>
        <p>
            <a href="prefecture_list.php?prefecture_id=<?= urlencode($prefecture_id) ?>" class="btn btn-secondary">入力されたデータを確認する</a>
        </p>

        <!-- SVG マップ -->
        <div class="svg-container">
          <h2>日本地図</h2>
          <div id="map"></div>
        </div>
    </div>

    <!-- Bootstrap JS CDN (オプション) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      (async () => {
        const mapPath = "./map.svg";
        const container = document.getElementById('map');

        try {
          const res = await fetch(mapPath);
          if (!res.ok) {
            throw new Error(`SVG の読み込みに失敗しました: ${res.status}`);
          }
          const svgText = await res.text();
          container.innerHTML = svgText;

          const prefectures = container.querySelectorAll('.geolonia-svg-map .prefecture');
          prefectures.forEach(pref => {
            const titleEl = pref.querySelector('title');
            let japaneseName = "";
            if (titleEl) {
              const parts = titleEl.textContent.split('/');
              if (parts.length > 0) {
                japaneseName = parts[0].trim();
              }
            }

            // パス要素の中央にテキストを配置
            const pathEl = pref.querySelector('path');
            if (pathEl) {
              const bbox = pathEl.getBBox();
              const centerX = bbox.x + bbox.width / 2;
              const centerY = bbox.y + bbox.height / 2;

              const textEl = document.createElementNS("http://www.w3.org/2000/svg", "text");
              textEl.setAttribute("x", centerX);
              textEl.setAttribute("y", centerY);
              textEl.setAttribute("fill", "white");
              textEl.setAttribute("font-size", "12");
              textEl.setAttribute("text-anchor", "middle");
              textEl.setAttribute("dominant-baseline", "middle");
              textEl.textContent = japaneseName;
              pref.appendChild(textEl);
            }

            // クリック時に対応する都道府県の入力画面へ遷移
            pref.addEventListener('click', event => {
              const code = event.currentTarget.dataset.code;
              if (code) {
                location.href = `index.php?prefecture_id=${code}`;
              }
            });

            // マウスオーバー時の色変更
            pref.addEventListener('mouseover', event => {
              const path = event.currentTarget.querySelector('path');
              if (path) {
                path.style.fill = '#00ffee'; 
              }
            });
            pref.addEventListener('mouseleave', event => {
              const path = event.currentTarget.querySelector('path');
              if (path) {
                path.style.fill = '';
              }
            });
          });
        } catch (error) {
          console.error(error);
        }
      })();
    </script>
</body>
</html>
