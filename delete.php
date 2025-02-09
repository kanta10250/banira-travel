<?php
require_once 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die('IDが指定されていません。');
}

$prefecture_id = trim($_GET['prefecture_id'] ?? '');

if (deleteTravelData($id)) {
    header('Location: prefecture_list.php?prefecture_id=' . urlencode($prefecture_id));
    exit;
} else {
    die('削除に失敗しました。');
}
