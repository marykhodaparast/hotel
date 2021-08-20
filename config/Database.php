<?php
class Database
{
    protected $pdo;
    function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }
    public function getPdo()
    {
        return $this->pdo;
    }
    /* connect by pdo */
    function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        try {
            $pdo = new PDO("mysql:host=$servername;dbname=hotel;", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setPdo($pdo);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
    function getObject($condition, $table, $value, $fetchAll = false)
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM $table $condition ");
        $stmt->execute(['myval' => $value]);
        if ($fetchAll) {
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        } else {
            $result = $stmt->fetch(PDO::FETCH_OBJ);
        }
        if ($result) {
            return $result;
        } else {
            return false;
        }
        exit;
    }

    public function insert(array $values, $pdo, $table)
    {
        foreach ($values as $field => $v)
            $ins[] = ':' . $field;

        $ins = implode(',', $ins);
        $fields = implode(',', array_keys($values));
        $sql = "INSERT INTO $table ($fields) VALUES ($ins)";

        $sth = $pdo->prepare($sql);
        foreach ($values as $f => $v) {
            $sth->bindValue(':' . $f, $v);
        }
        $sth->execute();
        if ($sth) {
            return $sth;
        }
    }


    function insertOne($pdo, $table, $field, $value)
    {
        $stmt = $pdo->prepare("INSERT INTO $table ($field) VALUES (?)");
        $stmt->execute(["$value"]);
        if ($stmt) {
            return $stmt;
        } else {
            return false;
        }
    }
    function del($table, $condition = "", $paramArr = [])
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("UPDATE $table SET is_deleted = 1 $condition");
        $stmt->execute($paramArr);
        if (!$stmt->rowCount()) {
            return false;
        }
    }
    public function getvalues($table, $condition = "", $parametersArr = [], $fetchAll = false)
    {
        $pdo = $this->getPdo();
        $sql = "SELECT * FROM `$table` $condition";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($parametersArr);
        // if ($fetchAll) {
        //     $result = $stmt->fetchAll();
        // } else {
        //     $result = $stmt->fetch();
        // }
        if ($fetchAll) {
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        } else {
            $result = $stmt->fetch(PDO::FETCH_OBJ);
        }
        if ($result) {
            return $result;
            exit;
        } else {
            return false;
            exit;
        }
    }
    public function update($table, $id, $fields)
    {
        $pdo = $this->getPdo();
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = \"{$value}\"";
            if ($x < count($fields)) {
                $set .= ',';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if ($stmt) {
            return $stmt;
        }
    }
}
