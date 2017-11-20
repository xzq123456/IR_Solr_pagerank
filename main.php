<?php
// make sure browsers see this page as utf-8 encoded HTML
header('Content-Type: text/html; charset=utf-8');
$limit = 10;
$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
$results = false;
if ($query)
{
 // The Apache Solr Client library should be on the include path
 // which is usually most easily accomplished by placing in the
 // same directory as this script ( . or current directory is a default
 // php include path entry in the php.ini)
 require_once('Apache/Solr/Service.php');
 // create a new solr service instance - host, port, and corename
 // path (all defaults in this example)
 $solr = new Apache_Solr_Service('localhost', 8983, 'solr/myexample/');
 // if magic quotes is enabled then stripslashes will be needed
 if (get_magic_quotes_gpc() == 1)
 {
 $query = stripslashes($query);
 }
  $additionalparams=[];
    if(array_key_exists("algo", $_REQUEST) && $_REQUEST["algo"]=="pagerank") {
        $additionalparams['sort']="pageRankFile desc";
    }
 // in production code you'll always want to use a try /catch for any
 // possible exceptions emitted by searching (i.e. connection
 // problems or a query parsing error)
//$results = $solr->search($query, $start, $rows, $additionalParameters);
 try
 {
 $results = $solr->search($query, 0, $limit,$additionalparams);
 }
 catch (Exception $e)
 {
 // in production you'd probably log or email this error to an admin
 // and then show a special message to the user but for this example
 // we're going to show the full exception
 die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
 }
}
?>
<html>
 <head>
 <title>PHP Solr Client Example</title>
 </head>
 <body>
 <form accept-charset="utf-8" method="get">
 <label for="q">Search:</label>
 <input id="q" name="q" type="text" value="<?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8'); ?>"/>
</br>
<input type=radio name="algo" value="solr" <?php if($_REQUEST['algo'] == 'solr')  echo ' checked="checked"'?>>Solr lucene</input>
<input type=radio name="algo" value = "pagerank"<?php if($_REQUEST['algo'] == 'pagerank')  echo ' checked="checked"'?>>Page Rank</input>
</br>
</br> <input type="submit"/>

 </form>
<?php
// display results
if ($results)
{
 $total = (int) $results->response->numFound;
 $start = min(1, $total);
 $end = min($limit, $total);
?>
 <ol>
<?php
 // iterate result documents
   foreach ($results->response->docs as $doc)
 { 
	$id = $doc->id;
	$exploded = explode('/', $id);
	$id1 = end($exploded);
	$title = $doc->title;
	if(isset($doc->og_url)) {
	$url = urldecode($doc->og_url);
	}
	else
	{
	$url = 'NA';
	
	}
	$desc = $doc->description;
?>
 <li>
 <table style="width:100%;border: 1px solid black; text-align: left">
 <tr>
 <th>TITLE: </th>
 <td><a target="_blank" href="<?php echo $url?>"/><?php echo $title?></td></tr>
 <tr><th>URL: </th>
 <td><a target="_blank" href="<?php echo $url?>"/><?php echo $url?></td></tr>
 </tr><th>ID: </th>
 <td><?php echo $id?></td></tr>
 <tr><th>DESCRIPTION: </th>
 <td><?php echo $desc?></td>
 </tr>

 </table>
 </li>
<?php
 }
?>
 </ol>
<?php
}
?>
 </body>
</html>
