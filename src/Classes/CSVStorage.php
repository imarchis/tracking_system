<?php
namespace Tracking;

class CSVStorage implements StorageTypeInterface
{
    public $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function setup()
    {
        if(!file_exists($this->file) || !is_readable($this->file)) {
            $csv = fopen($this->file, 'w');
            fputcsv($csv, ['id', 'tracking_code', 'delivery_date']);
            fclose($csv);
        }
    }

    public function insert($data)
    {
        if (!isset($data['id'])) {
            array_unshift($data, $this->countRecords() + 1);
        }
        $csv = fopen($this->file, 'a');
        fputcsv($csv, $data);
        fclose($csv);
    }

    public function allRecords()
    {
        if(!file_exists($this->file) || !is_readable($this->file)) {
            return FALSE;
        }
        $header = NULL;
        $data = array();
        if (($handle = fopen($this->file, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle)) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    if (!empty($row)) {
                        $data[] = array_combine($header, $row);
                    }
                }
            }
            fclose($handle);
        }
        return $data;
    }

    public function countRecords()
    {
        if (!file_exists($this->file) || !is_readable($this->file)) {
            return FALSE;
        }
        return count(file($this->file, FILE_SKIP_EMPTY_LINES)) - 1;
    }

    public function findOneBy($key, $value)
    {
        if (!file_exists($this->file) || !is_readable($this->file)) {
            return FALSE;
        }
        $header = NULL;
        $result = FALSE;
        if (($handle = fopen($this->file, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle)) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    if (!empty($row)) {
                        $data = array_combine($header, $row);
                        if ($data[$key] == $value) {
                            $result = $data;
                            break;
                        }
                    }
                }
            }
            fclose($handle);
        }
        return $result;
    }
}