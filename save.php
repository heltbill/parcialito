<?php
require('db.php');
$db = new Database('localhost', 'root', '', 'parcialito');

if (isset($_POST['save'])) {
    $nit = trim($_POST['nit']) !== '' ? trim($_POST['nit']) : null;
    $first_name = trim($_POST['first_name']) !== '' ? trim($_POST['first_name']) : null;
    $middle_name = trim($_POST['middle_name']) !== '' ? trim($_POST['middle_name']) : null;
    $first_last_name = trim($_POST['first_last_name']) !== '' ? trim($_POST['first_last_name']) : null;
    $middle_last_name = trim($_POST['middle_last_name']) !== '' ? trim($_POST['middle_last_name']) : null;
    $birth_date = date('Y-m-d', strtotime($_POST['birth_date']));
    $phone = trim($_POST['phone']) !== '' ? trim($_POST['phone']) : null;
    $email = trim($_POST['email']) !== '' ? trim($_POST['email']) : null;

    $result = $db->queryInsert("INSERT INTO users(nit, first_name, middle_name, first_last_name, middle_last_name, birth_date, phone, email) VALUES(?, ?, ?, ?, ?, ?, ?, ?)", [$nit, $first_name, $middle_name, $first_last_name, $middle_last_name, $birth_date, $phone, $email]);
    $db->close();

    // $_SESSION['message'] = 'El usuario se ha guardado correctamente';
    // $_SESSION['message_type'] = 'success';

    // header('Location: index.php');

    echo json_encode($result);
} else {
    echo json_encode(array('ok' => false, 'mensaje' => 'Falta(n) parametro(s)', 'tipo' => 'warning'));
}
