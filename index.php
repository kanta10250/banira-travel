<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SVGマップ</title>
  <style>
    .prefecture {
      cursor: pointer;
      transition: fill 0.3s;
    }
    .prefecture:hover path {
      fill: #ffcccb;
    }
  </style>
</head>
<body>
  <div id="map"></div>
  
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
        
        const prefectures = document.querySelectorAll('.geolonia-svg-map .prefecture');
        
        prefectures.forEach(pref => {
          const titleEl = pref.querySelector('title');
          let japaneseName = "";
          if (titleEl) {
            const parts = titleEl.textContent.split('/');
            if (parts.length > 0) {
              japaneseName = parts[0].trim();
            }
          }
          
          // 表示するため、まず最初の <path> 要素のバウンディングボックスを取得
          const pathEl = pref.querySelector('path');
          if (pathEl) {
            // getBBox() によりパスの領域を取得
            const bbox = pathEl.getBBox();
            const centerX = bbox.x + bbox.width / 2;
            const centerY = bbox.y + bbox.height / 2;
            
            // SVGの名前空間で <text> 要素を作成
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
          
          // クリック時の処理例（data-code 属性を利用してPHPの入力フォームへ遷移）
          pref.addEventListener('click', event => {
            const code = event.currentTarget.dataset.code;
            if (code) {
              // 都道府県IDをGETパラメータとして渡す
              location.href = `prefecture_input.php?prefecture_id=${code}`;
            }
          });
          
          // マウスオーバー時に色変更（JSで直接指定）
          pref.addEventListener('mouseover', event => {
            const path = event.currentTarget.querySelector('path');
            if (path) {
              path.style.fill = '#00ffee'; 
            }
          });
          
          // マウスアウト時に元の色に戻す
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
