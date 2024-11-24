<?php
class db {
    protected $host="localhost";
    protected $user="root";
    protected $password="";
    protected $dbname="HallBooking";
    public $con=null;
    public $status;
    function __construct(){
        $this->con= mysqli_connect($this->host, $this->user, $this->password, $this->dbname, 3306);
        if($this->con->connect_error) $this->status=false;
        else $this->status=true;
    }
    function exec_update($query){
        $this->con->query($query);
    }
    function prepare_statement($query){
        return $this->con->prepare($query);
    }
    function exec_query($query){        
        $rs= $this->con->query($query);
        if($rs) return $rs->fetch_all(MYSQLI_ASSOC);
        return [];
    }
    function close(){
        $this->con->close();
    }
}
?>
