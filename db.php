<?php

// Heroku 上で JawsDB MySQL の接続情報が設定されている場合はその情報を使用する
if (getenv('JAWSDB_URL')) {
    $url = getenv('JAWSDB_URL');
    $parsed_url = parse_url($url);
    define('DB_HOST', $parsed_url['host']);
    define('DB_USER', $parsed_url['user']);
    define('DB_PASSWORD', $parsed_url['pass']);
    define('DB_NAME', ltrim($parsed_url['path'], '/'));
    define('DB_PORT', isset($parsed_url['port']) ? $parsed_url['port'] : 3306);
} else {
    // ローカル環境の設定
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_NAME', 'tra-nkou');
    define('DB_PORT', 3306);
}

/**
 * DBに接続する
 *
 * @return mysqli
 */
function getDBConnection() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
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

/**
 * 指定したIDの旅行情報を取得する
 *
 * @param int $id
 * @return array|null
 */
function getTravelDataById($id) {
    $mysqli = getDBConnection();

    $query = 'SELECT * FROM `travel-trip-nkou` WHERE id = ?';
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        die('SQL準備エラー：' . $mysqli->error);
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    $mysqli->close();

    return $data;
}

/**
 * 旅行情報を更新する
 *
 * @param int   $id
 * @param array $data
 * @return bool
 */
function updateTravelData($id, array $data) {
    $mysqli = getDBConnection();

    $query = 'UPDATE `travel-trip-nkou` SET prefecture_id = ?, title = ?, description = ?, url = ?, updated_at = NOW() WHERE id = ?';
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        die('SQL準備エラー：' . $mysqli->error);
    }
    // prefecture_id, title, description, url は文字列、id は整数
    $stmt->bind_param('ssssi', $data['prefecture_id'], $data['title'], $data['description'], $data['url'], $id);
    $result = $stmt->execute();
    if ($result === false) {
        echo 'エラーメッセージ：' . $mysqli->error;
    }
    $stmt->close();
    $mysqli->close();

    return $result;
}

/**
 * 旅行情報を削除する
 *
 * @param int $id
 * @return bool
 */
function deleteTravelData($id) {
    $mysqli = getDBConnection();

    $query = 'DELETE FROM `travel-trip-nkou` WHERE id = ?';
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        die('SQL準備エラー：' . $mysqli->error);
    }
    $stmt->bind_param('i', $id);
    $result = $stmt->execute();
    if ($result === false) {
        echo 'エラーメッセージ：' . $mysqli->error;
    }
    $stmt->close();
    $mysqli->close();

    return $result;
}
