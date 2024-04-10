<?php
require('db.php');
$db = new Database('localhost', 'root', '', 'parcialito');

if (isset($_POST['edit']) && isset($_POST['id'])) {
    $id = trim($_POST['id']) !== '' ? trim($_POST['id']) : null;
    if (!empty($id)) {
        $nit = trim($_POST['nit']) !== '' ? trim($_POST['nit']) : null;
        $first_name = trim($_POST['first_name']) !== '' ? trim($_POST['first_name']) : null;
        $middle_name = trim($_POST['middle_name']) !== '' ? trim($_POST['middle_name']) : null;
        $first_last_name = trim($_POST['first_last_name']) !== '' ? trim($_POST['first_last_name']) : null;
        $middle_last_name = trim($_POST['middle_last_name']) !== '' ? trim($_POST['middle_last_name']) : null;
        $birth_date = date('Y-m-d', strtotime($_POST['birth_date']));
        $phone = trim($_POST['phone']) !== '' ? trim($_POST['phone']) : null;
        $email = trim($_POST['email']) !== '' ? trim($_POST['email']) : null;

        $result = $db->queryInsert("UPDATE users SET nit = ?, first_name = ?, middle_name = ?, first_last_name = ?, middle_last_name = ?, birth_date = ?, phone = ?, email = ? WHERE id = ?", [$nit, $first_name, $middle_name, $first_last_name, $middle_last_name, $birth_date, $phone, $email, $id]);
        $db->close();
        echo json_encode($result);
    } else {
        echo json_encode(array('ok' => false, 'mensaje' => 'Falta(n) parametro(s)', 'tipo' => 'warning'));
    }
} else {
    echo json_encode(array('ok' => false, 'mensaje' => 'Falta(n) parametro(s)', 'tipo' => 'warning'));
}
