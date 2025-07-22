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

    // すべての項目が入力されているかチェック
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>旅行先入力</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- 用意された style.css を読み込み -->
  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="./image/favicon.png" type="image/png">
</head>
<body>
  <div class="container my-4">
    <h1 class="mb-4">旅行先入力 (<?= htmlspecialchars($prefectureName, ENT_QUOTES, 'UTF-8') ?>)</h1>
    <?php if ($message): ?>
      <div class="alert alert-info text-center"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <div class="row">
      <!-- 入力フォーム -->
      <div class="col-12 col-md-6 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="card-title h4 text-center mb-3">旅行データ入力フォーム</h2>
            <form action="" method="POST">
              <!-- 都道府県IDは隠しフィールド -->
              <input type="hidden" name="prefecture_id" value="<?= htmlspecialchars($prefecture_id, ENT_QUOTES, 'UTF-8') ?>">
              <div class="mb-3">
                <label class="form-label fw-bold">都道府県</label>
                <p class="form-control-plaintext"><?= htmlspecialchars($prefectureName, ENT_QUOTES, 'UTF-8') ?></p>
              </div>
              <div class="mb-3">
                <label for="title" class="form-label fw-bold">場所</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="場所を入力" required>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label fw-bold">内容</label>
                <textarea name="description" id="description" rows="4" class="form-control" placeholder="内容を入力" required></textarea>
              </div>
              <div class="mb-3">
                <label for="url" class="form-label fw-bold">URL</label>
                <input type="url" name="url" id="url" class="form-control" placeholder="リンク先のURLを入力" required>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">送信</button>
              </div>
            </form>
            <div class="mt-3 text-center">
              <a href="prefecture_list.php?prefecture_id=<?= urlencode($prefecture_id) ?>" class="btn btn-secondary">入力されたデータを確認する</a>
            </div>
          </div>
        </div>
      </div>
      <!-- 地図部分 -->
      <div class="col-12 col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="card-title h4">日本地図</h2>
            <div class="svg-container">
              <div id="map"></div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- row -->
  </div><!-- container -->
  <!-- Bootstrap JS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (async () => {
      const mapPath = "./image/map.svg";  // SVGファイルのパス
      const container = document.getElementById('map');

      try {
        const res = await fetch(mapPath);
        if (!res.ok) {
          throw new Error(`SVG の読み込みに失敗しました: ${res.status}`);
        }
        const svgText = await res.text();
        container.innerHTML = svgText;

        // SVG の都道府県要素を取得
        const prefectures = container.querySelectorAll('.geolonia-svg-map .prefecture');
        const currentPrefectureId = "<?= htmlspecialchars($prefecture_id, ENT_QUOTES, 'UTF-8') ?>";

        prefectures.forEach(pref => {
          const code = pref.dataset.code;
          const titleEl = pref.querySelector('title');

          // タイトル文字列から日本語名を抽出
          let japaneseName = "";
          if (titleEl) {
            const parts = titleEl.textContent.split('/');
            if (parts.length > 0) {
              japaneseName = parts[0].trim();
            }
          }

          // マップ上に都道府県名のテキストを配置
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

          // 現在の都道府県ならハイライト
          if (code === currentPrefectureId) {
            pref.classList.add('selected');
          }

          // クリック時に対応する入力画面へ遷移
          pref.addEventListener('click', () => {
            if (code) {
              location.href = `index.php?prefecture_id=${code}`;
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

