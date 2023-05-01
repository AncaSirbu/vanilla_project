<?php
require_once 'common.php';
class Export
{
    private $connection, $table, $allRecords;

    public function __construct($table)
    {
        $this->connection = getDbConnection();
        $this->table = $table;
        $this->setAllRecords();
    }

    public function setAllRecords() {
        $stm = $this->connection->prepare('SELECT * FROM users');
        $stm->execute();
        $this->allRecords = $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public function exportAllRecords()
    {
        if ($this->allRecords) {
            $delimiter = ',';
            $fileName = 'export-users_'. date('Y-m-d') . '.csv';
            $f = fopen('php://memory', 'w');
            $fields = ['ID', 'NAME', 'EMAIL', 'IMAGE'];
            fputcsv($f, $fields, $delimiter);

            foreach ($this->allRecords as $user) {
                $data = [$user->id, $user->name, $user->email, $user->image];
                fputcsv($f, $data, $delimiter);
            }
            // Move back to beginning of file
            fseek($f, 0);
            // Set headers to download file rather than displayed
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Encoding: UTF-8');
            header('Content-Disposition: attachment; filename="' . $fileName . '";');

            //output all remaining data on a file pointer
            fpassthru($f);
        }
    }
}