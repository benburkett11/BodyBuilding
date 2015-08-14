<?PHP
/*
	Author: Ben Burkett
	file: testingcurl.js
	Created: 05/09/2015
	Updated:
	Version: 0.0.1
*/

//some preset variables for use in this file
$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; .NET CLR 1.1.4322)';
$url = "https://secure.rentecdirect.com/login/login.php";
$post_data = 'formpost=1&cb=rhoadsrentals&username=michael.rhoads%40rhoadsrentals.com&password=Madisonb0323&Login=Login';

//error log for debugging purposes, wont use unless error is thrown
$fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w');

//initialize the curl object
$ch = curl_init();

//set some need options post_data, url and browser we will be using
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_STDERR, $fp);
curl_setopt($ch, CURLOPT_POST, 1 );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1 );

//set the cookies to keep the session
curl_setopt($ch, CURLOPT_COOKIESESSION, 1 );
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

//log-in, then go to the next page we want
$output = curl_exec($ch);


//different pages we may want/need later on, keep for reference.
//$contentURL = 'https://secure.rentecdirect.com/owners/summary.php';
//$contentURL = 'https://secure.rentecdirect.com/owners/properties.php';
$contentURL = 'https://secure.rentecdirect.com/owners/ajax/get/property_search.php';
curl_setopt($ch, CURLOPT_URL, $contentURL);
$content = '<!DOCTYPE html>';
$content .= curl_exec($ch);
curl_close($ch);


$content = str_replace('&bull;', '', $content);
$content = str_replace('&nbsp;', '', $content);
$content = str_replace('&', '', $content);
//$content = preg_replace('#\s+#', ' ', $content);
//echo $content.'<br/>';

htmlentities($content);
$dom = new domDocument;
libxml_use_internal_errors(true);
$dom -> loadHTML( $content );
$dom->preserveWhiteSpace = false;

//grab the <pnick> tags
$pnickTag = $dom -> getElementsByTagName( 'pnick' );

//array to hold the data we want
$parseData = array();

foreach($pnickTag as $tag){
    if( strpos($tag -> nodeValue, 'Office') === 0 ){
        break;
    }
    $parseData[] = $tag -> nodeValue;
}
$count = 0;
$formattedHtml = '<div class="col-md-12">';
array_walk( $parseData, 'trim_val' );
foreach($parseData as $str){
    if( ($count % 4) == 0 ){
        $formattedHtml .= '</div><div class="col-md-12">';
    }
    $formattedHtml .= "<p>$str</p>";
    $count++;
}


function trim_val( &$value ){
    $value = trim( $value );
}

?>
    <html>
    <head>
        <title>cURL Playground</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <link rel="stylesheet" href="/css/bootstrap.css">
        <link rel="stylesheet" href="/css/bootstrap-theme.css">
    </head>
    <body>
    <br/><br/><br/>
    <div class="container">
        <?=$formattedHtml;?>
    </div>


    </body>
    </html>



<?PHP
/*------------------------------------------------
							Comment section
	
	0.0.1 -- 04/30/2015 -- BAB -- created file
	
	------------------------------------------------
*/
?>