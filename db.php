<?php
// db.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'tra-nkou');

/**
 * DBに接続する
 *
 * @return mysqli
 */
function getDBConnection() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_errno) {
        die('MySQLの接続に失敗しました。：' . $mysqli->connect_error);
    }
    return $mysqli;
}

/**
 * 旅行情報の入力データを新規登録する
 *
 * @param array $data
 * @return bool
 */
function createTravelData(array $data) {
    $mysqli = getDBConnection();

    // テーブル名にハイフンが含まれているのでバッククオートで囲む
    $query = 'INSERT INTO `travel-trip-nkou` (prefecture_id, title, description, url) VALUES (?, ?, ?, ?)';
    $statement = $mysqli->prepare($query);
    if (!$statement) {
        die('SQL準備エラー：' . $mysqli->error);
    }

    // 全て文字列としてバインド
    $statement->bind_param('ssss', $data['prefecture_id'], $data['title'], $data['description'], $data['url']);

    $response = $statement->execute();
    if ($response === false) {
        echo 'エラーメッセージ：' . $mysqli->error . "\n";
    }

    $statement->close();
    $mysqli->close();

    return $response;
}

/**
 * DBから指定した都道府県の旅行情報データをすべて取得する
 *
 * @param string $prefecture_id
 * @return array
 */
function findTravelData($prefecture_id) {
    $mysqli = getDBConnection();

    $query = 'SELECT * FROM `travel-trip-nkou` WHERE prefecture_id = ?';
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        die('SQL準備エラー：' . $mysqli->error);
    }
    $stmt->bind_param('s', $prefecture_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $mysqli->close();

    return $data;
}
