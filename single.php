<?php
require('db.php');
$db = new Database('localhost', 'root', '', 'parcialito');

if (isset($_POST['id'])) {
    $id = trim($_POST['id']) !== '' ? trim($_POST['id']) : null;
    if (!empty($id)) {
        $result = $db->query("SELECT id, nit, first_name, middle_name, first_last_name, middle_last_name, birth_date, phone, email FROM users WHERE id = ?", [$id]);
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = array(
                'id' => $row['id'],
                'nit' => $row['nit'],
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'first_last_name' => $row['first_last_name'],
                'middle_last_name' => $row['middle_last_name'],
                'birth_date' => $row['birth_date'],
                'phone' => $row['phone'],
                'email' => $row['email']
            );
        }
        $db->close();
        $res = array('ok' => true, 'mensaje' => '', 'data' => $data);
        echo json_encode($res);
    } else {
        echo json_encode(array('ok' => false, 'mensaje' => 'Falta(n) parametro(s)', 'data' => ''));
    }
} else {
    echo json_encode(array('ok' => false, 'mensaje' => 'Falta(n) parametro(s)', 'data' => ''));
}
