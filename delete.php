<?php
require('db.php');
$db = new Database('localhost', 'root', '', 'parcialito');

if (isset($_POST['id'])) {
    $id = trim($_POST['id']) !== '' ? trim($_POST['id']) : null;
    if (!empty($id)) {
        $result = $db->queryInsert("DELETE FROM users WHERE id = ?", [$id]);
        $db->close();

        echo json_encode($result);
    } else {
        echo json_encode(array('ok' => false, 'mensaje' => 'Falta(n) parametro(s)', 'tipo' => 'warning'));
    }
} else {
    echo json_encode(array('ok' => false, 'mensaje' => 'Falta(n) parametro(s)', 'tipo' => 'warning'));
}
