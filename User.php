<?php
require_once 'common.php';
class User
{
    private $connection, $table, $allRecords, $limit = 4;
    public function __construct($table) {
        $this->table = $table;
        $this->connection = getDbConnection();
        $this->setAllRecords();
    }

    public function setAllRecords() {
        $stmt = $this->connection->prepare("SELECT count(*) as numberOfRows FROM $this->table");
        $stmt->execute();
        $all = $stmt->fetch(PDO::FETCH_OBJ);
        $this->allRecords = $all->numberOfRows;
    }

    public function currentPage(){
        return intval($_GET['page'] ?? 1);
    }

   public function getUsers($orderBy = 'id', $order = 'asc') {

        $start = ($this->currentPage() -1) * $this->limit;

        $sql = "SELECT * FROM $this->table ORDER BY $orderBy $order limit ?, ?";

        $stm = $this->connection->prepare($sql);
        $stm->bindParam(1, $start, PDO::PARAM_INT);
        $stm->bindParam(2, $this->limit, PDO::PARAM_INT);
        $stm->execute();
        $users = $stm->fetchAll(PDO::FETCH_OBJ);

        $this->connection = null;
        return $users;
    }

    public function getPaginationNo() {
        return ceil($this->allRecords / $this->limit);
    }
}