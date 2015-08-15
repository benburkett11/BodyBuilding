<?php

require_once __DIR__ . '/database_config.php';

function pdoSelect( $sql, $vars='' ) {
    try {
        $conn = new PDO( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        if( empty( $vars ) )	$stmt = $conn->query( $sql );
        else{
            $stmt = $conn->prepare( $sql );
            $stmt->execute( $vars );
        }
        return $stmt->fetchAll( PDO::FETCH_ASSOC );
    } catch( PDOException $e ) {
        echo 'ERROR: ' . $e->getMessage();
        return false;
    }
}


function pdoInsert( $sql, $vars ) {
    global $siteMode;

    try {
        $conn = new PDO( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $stmt = $conn->prepare( $sql );
        if( $stmt->execute( $vars ) ) return $conn->lastInsertId();
        else return false;
    } catch( PDOException $e ) {
        echo 'ERROR: ' . $e->getMessage();
        return false;
    }
}

function pdoUpdate( $sql, $vars ){
    global $siteMode; //$dbHost, $dbName, $dbUser, $dbPass;

    try {
        $conn = new PDO( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $stmt = $conn->prepare( $sql );
        return $stmt->execute( $vars );
    } catch( PDOException $e ) {
        echo 'ERROR: ' . $e->getMessage();
        return false;
    }
}


function pdoDelete( $sql, $vars ) {
    global $siteMode; //$dbHost, $dbName, $dbUser, $dbPass;

    try {
        $conn = new PDO( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS );
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $stmt = $conn->prepare( $sql );
        return $stmt->execute( $vars );
    } catch( PDOException $e ) {
        echo 'ERROR: ' . $e->getMessage();
        return false;
    }
}

?>