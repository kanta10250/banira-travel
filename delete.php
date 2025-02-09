<?php
require_once 'db.php';

// GET パラメータから削除対象の ID を取得
$id = $_GET['id'] ?? null;
if (!$id) {
    die('IDが指定されていません。');
}

// 一覧画面へ戻るための都道府県ID
$prefecture_id = trim($_GET['prefecture_id'] ?? '');

// 削除処理を実行
if (deleteTravelData($id)) {
    // 削除成功の場合は一覧画面にリダイレクト
    header('Location: prefecture_list.php?prefecture_id=' . urlencode($prefecture_id));
    exit;
} else {
    die('削除に失敗しました。');
}
