<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 8/13/2015
 * Time: 7:15 PM
 */
require_once 'vendor/autoload.php';
libxml_use_internal_errors(true);
set_time_limit( 300 );
$time_start = microtime(true);
$recordCount = 0;
use \Curl\Curl;

$curl = new Curl();
$curl->get( 'http://www.bodybuilding.com/store/whey.html' );
$content = $curl->response;

htmlentities( $content );
$content = iconv( 'UTF-8', 'UTF-8//TRANSLIT', $content );
//$content = preg_replace('~\p{C}+~u', '', $content);
//echo $content;
//exit;
$dom = new DOMDocument();
$dom->loadHTML( $content );
$selector = new DOMXPath($dom);

$resultClass = $selector->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' " . COUNTCLASS . " ')]");
$link = $selector->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' " . PAGINATIONCLASS . " ')]");

$linkArray = getPageLink( $dom->saveXML( $link[0] ) );

if( isset( $linkArray['error'] ) ){
    echo $linkArray['error'] . '<br/>';
    exit;
}
//here is a comment
$count = ceil( $linkArray[3][1] / 50 );
$paramFirst = $linkArray['link'] . '?' . $linkArray[1][0] . '=';
$paramSecond = '&'. $linkArray[2][0] . '=50&' . $linkArray[3][0] . '=';

for( $i = 1; $i <= $count; $i++ ){
    $curl->get( BODYBUILDING . $paramFirst . $i . $paramSecond . $count );
    $dom->loadHTML( $curl->response );
    $tempSelector = new DOMXPath( $dom );
    $articleTag = $tempSelector->query( '/html/body//article' );
    foreach( $articleTag as $ar ){
        $details = parseContent( $dom->saveXML( $ar ), PRODUCTDETIALCLASS );
        print_r( $details );
        //echo $ar->nodeValue . '<br/><br/>';
        $recordCount++;
        exit;
    }

}

$curl->close();
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins<br/>';
echo 'Record count: ' . $recordCount;

function parseContent( $html, $tag ){
    $tempDom = new DOMDocument();
    $tempDom->loadHTML( $html );
    $xPath = new DOMXPath( $tempDom );
    //found a work around where I didn't need to use this yet
    //$token = $xPath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $tag ')]");

    switch( $tag ){
        case PRODUCTDETIALCLASS:
            $title = $xPath->query("//h3");
            $details['title'] = preg_replace('/[^0-9a-zA-Z]/', "", $title[0]->nodeValue ) . '<br/>';
            $detailList = $xPath->query("//ul/li");
            foreach( $detailList as $d ){
                $details[] = preg_replace('/[^0-9a-zA-Z.:%_\s]/', "", $d->nodeValue ) . '<br/>';
            }
            return $details;
            break;
    }



    /*
    $all = $xPath->query("//div");
    foreach( $all as $t ){
        $block = $tempDom->saveXML( $t );
        echo $block;
    }
    */
}

function getPageLink( $html ){
    $tempDom = new DOMDocument();
    $tempDom->loadHTML( $html );
    $xPath = new DOMXPath( $tempDom );
    $liTag = $xPath->query('//a');
    /*
    foreach( $liTag as $li ){
        echo $li->nodeValue;
    }*/
    $link = ( $liTag[0]->getAttribute( 'href' ) == '#' ) ? $liTag[1]->getAttribute( 'href' ) : 'error' ;

    if( $link == 'error' ){
        return array( 'error' => 'There was an error with the link' );
    } else {
        list( $link, $param ) = explode( '?', $link );
        $returnArray['link'] = $link;
        $param = str_replace( ':amp;', '&', $param );
        $paramTemp = explode( '&', $param );
        $count = 1;
        foreach( $paramTemp as $par ){
            list( $first, $second ) = explode( '=', $par );
            $returnArray[$count] = array( $first, $second );
            $count++;
        }
        return $returnArray;
    }
}