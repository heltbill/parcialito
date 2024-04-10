<?php
class Database
{
    private $conn;

    public function __construct($host, $user, $pass, $db)
    {
        $this->conn = new mysqli($host, $user, $pass, $db);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Error de preparación: " . $this->conn->error);
        }

        if (!empty($params)) {
            $types = '';
            $values = [];

            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 'b';
                }

                $values[] = $param;
            }
            $stmt->bind_param($types, ...$values);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    public function queryInsert($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die("Error de preparación: " . $this->conn->error);
            }
            
            if (str_contains($sql, 'INSERT')) {
                $msg = 'guardado';
            } elseif (str_contains($sql, 'DELETE')) {
                $msg = 'eliminado';
            } elseif (str_contains($sql, 'UPDATE')) {
                $msg = 'actualizado';
            }

            if (!empty($params)) {
                $types = '';
                $values = [];

                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } elseif (is_string($param)) {
                        $types .= 's';
                    } else {
                        $types .= 'b';
                    }

                    $values[] = $param;
                }
                $stmt->bind_param($types, ...$values);
            }

            $query = $stmt->execute();

            if ($query) {
                return array('ok' => true, 'mensaje' => 'Se ha ' . $msg . ' correctamente', 'tipo' => 'success');
            } else {
                return array('ok' => false, 'mensaje' => 'Algo salió mal', 'tipo' => 'danger');
            }
        } catch (\Throwable $th) {
            if (str_contains($th, 'Duplicate entry')) {
                return array('ok' => false, 'mensaje' => 'Está intentando guardar datos duplicados', 'tipo' => 'warning', 'stack' => $th->getMessage());
            }
            return array('ok' => false, 'mensaje' => 'Algo salió mal', 'tipo' => 'danger');
        }
    }

    public function close()
    {
        $this->conn->close();
    }
}
