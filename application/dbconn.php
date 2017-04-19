<?php
$dbname = "CP";
$servername = "localhost";
$username = "root";
$password = "root";

function setup_db() {
    global $servername, $username, $password;
    $dbc = @mysqli_connect($servername, $username, $password) or
            die( "Connect failed: ". mysqli_connect_error() );

    // Run sql script to create database and tables
    // Script should do nothing if the database already exists
    $commands = file_get_contents('../application/dbcreate.sql');   
    $dbc->multi_query($commands);
    $dbc->close();
}

function connect_to_db() {
    global $dbname, $servername, $username, $password;
    $dbc = @mysqli_connect($servername, $username, $password, $dbname) or
            die( "Connect failed: ". mysqli_connect_error() );
    return $dbc;
}


function disconnect_from_db($dbc, $result) {
    mysqli_free_result( $result );
    mysqli_close( $dbc );
}



function perform_query($dbc, $query) {
    $result = mysqli_query($dbc, $query) or 
              die( "bad query".mysqli_error( $dbc ) );
              
    return $result;
}

?>