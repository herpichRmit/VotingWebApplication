<?php

    require_once('./db_credentials.php');

    function db_connect() {
        $connection = oci_connect(DB_USER, DB_PASS, DB_CONN);
        return $connection;
    }

    function db_disconnect($connection) {
        if(isset($connection)) {
            oci_close($connection);
        }
    }

?>