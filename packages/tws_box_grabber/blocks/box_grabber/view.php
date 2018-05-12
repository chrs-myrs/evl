<?php
defined('C5_EXECUTE') or die("Access Denied.");

echo '<div class="box-grabber">';

if($title) echo '<h2 class="page-title">'.$title.'</h2><hr>';

$purl = parse_url($url);
  $scheme   = isset($purl['scheme']) ? $purl['scheme'] . '://' : ''; 
  $host     = isset($purl['host']) ? $purl['host'] : ''; 
  $port     = isset($purl['port']) ? ':' . $purl['port'] : ''; 
  $user     = isset($purl['user']) ? $purl['user'] : ''; 
  $pass     = isset($purl['pass']) ? ':' . $purl['pass']  : ''; 
  $pass     = ($user || $pass) ? "$pass@" : '';
  
$baseurl = $scheme.$user.$pass.$host.$port;

Loader::library('phpQuery-onefile', 'tws_box_grabber');
Loader::library('url_to_absolute', 'tws_box_grabber');

global $docs;

if($docs == null) {
    $docs = array();
}

if(isset($docs[$url])) {
    //reuse the previously retrieved document
    $doc = $docs[$url];
}
else {
    $doc = phpQuery::newDocumentFileHTML($url, "UTF-8");
    $docs[$url] = $doc;
}

phpQuery::selectDocument($doc);
$data = pq($selector)->clone();
foreach($data['a'] as $a) {
    $ja = pq($a);
    $href = $ja->attr('href');
    $ja->attr('href', url_to_absolute($baseurl, $href));
    $ja->attr('target', '_blank');
}
        
echo $data;   
echo '</div>';
?>