<?php

require('db.php');
$db = new Database('localhost', 'root', '', 'parcialito');
$result = $db->query("SELECT id, nit, CONCAT_WS(' ',first_name, middle_name, first_last_name, middle_last_name) AS full_name, birth_date, phone, email FROM users;");
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        'id' => $row['id'],
        'nit' => $row['nit'],
        'full_name' => $row['full_name'],
        'birth_date' => $row['birth_date'],
        'phone' => $row['phone'],
        'email' => $row['email']
    );
}
$db->close();
$res = array('ok' => true, 'mensaje' => '', 'data' => $data);
echo json_encode($res);
