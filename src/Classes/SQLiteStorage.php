<?php
namespace Tracking;

use SQLite3;

class SQLiteStorage  extends SQLite3 implements StorageTypeInterface
{
    public function __construct($file)
    {
        parent::__construct($file);
    }

    public function __destruct(){
        $this->close();
    }

    public function setup()
    {
        $sql = "CREATE TABLE IF NOT EXISTS  orders (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              tracking_code CHAR(255) NOT NULL,
              delivery_date CHAR(50)
              );";
        $this->exec($sql);
    }

    public function insert($data)
    {
        if (!empty($data)) {
            $code = $data['tracking_code'];
            $delivery = $data['delivery_date'];
            $found = $this->findOneBy('tracking_code', $code);
            if ($found == null) {
                $sql = "INSERT INTO orders (tracking_code, delivery_date) VALUES ('$code', '$delivery');";
                $this->exec($sql);
            }
        }
    }

    public function allRecords()
    {
        $sql = "SELECT * FROM orders;";
        $data = null;
        $ret = $this->query($sql);
        if ($ret) {
            while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function countRecords()
    {
        $sql = "SELECT COUNT(*) AS total FROM orders;";
        $data = null;
        $ret = $this->query($sql);
        $total = $ret->fetchArray(SQLITE3_ASSOC)['total'];
        return $total;
    }

    public function findOneBy($key, $value)
    {
        $sql = "SELECT * FROM orders WHERE ". $key. "='". $value. "';";
        $data = null;
        $ret = $this->query($sql);
        if ($ret) {
            while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                $data=$row;
            }
        }
        return $data;
    }
}