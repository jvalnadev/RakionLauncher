<?

$url = $_SERVER['QUERY_STRING'];

list($app, $ver) = explode("&", $url);
if(!is_numeric($app) || !is_numeric($ver)) {
	echo "Error";
	exit();
}
if($app != round($app) || $ver != round($ver)) {
	echo "Error";
	exit();
}
// DB
$config['db_host'] = '127.0.0.1';
$config['db_base'] = 'fetch';
$config['db_user'] = 'root';
$config['db_pass'] = '123456';

// mysql
mysql_connect($config['db_host'], $config['db_user'], $config['db_pass']);
mysql_select_db($config['db_base']);
$dbapp = @mysql_fetch_array(@mysql_query("SELECT * FROM `fetchapp` WHERE AppId = '$app' "));

if ($dbapp != "")
{
	if($dbapp['VerLimit'] == $ver)
	{
		exit();
	}

	if($dbapp['VerLimit'] > $ver)
	{
		echo "+".$dbapp['NoticeUrl']."\n";
		echo "=".$dbapp['FileUrl']."\n";
		$dbfile = mysql_fetch_array(mysql_query("SELECT count(*), sum(FileSize) FROM `fetchfile` WHERE FileVer > '$ver' AND AppId = '$app' "));
		echo "~".$dbfile['count(*)'].";".$dbfile['sum(FileSize)'].";".$dbapp['VerLimit']."\n";
		$dbfile2 = mysql_query("SELECT * FROM `fetchfile` WHERE FileVer > '$ver' AND AppId = '$app' ");
		while($data = mysql_fetch_array($dbfile2)){
		echo "".$data['Command'].";".$data['FileDir'].";".$data['FileIns'].";".$data['FileVer'].";".$data['FileSize']."\n";
		}
	} else 
	{
		echo "Error";
	}
}
@mysql_close();
?> 