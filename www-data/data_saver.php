<?php
include("../params.php");
ini_set('error_reporting', E_WARNING);
function cacheIsVaild($author,$count,$array)
{
	$uid=explode("=", $author);
	for($k=0; $k<$count; $k++)
	{
		if($array[$k][0] == $uid[1])
			return $k;
	}
	return -1;
}
$id=0;
$url="http://opencaching.pl/okapi/services/caches/search/all?consumer_key=".$_SCRIPT_PARAMS['OKAPI_KEY']."&status=Temporarily%20unavailable&name=".$_SCRIPT_PARAMS["PROJECT_REGEXP"];
$data = json_decode(file_get_contents($url));
foreach ($data->results as $value) 
{    
		$wyniki[$id][0]=$value;
		$wyniki[$id][1]=1;
		$id++;
}
$url="http://opencaching.pl/okapi/services/caches/search/all?consumer_key=".$_SCRIPT_PARAMS['OKAPI_KEY']."&status=Available&name=".$_SCRIPT_PARAMS["PROJECT_REGEXP"];
$data = json_decode(file_get_contents($url));
foreach ($data->results as $value) 
{    
		$wyniki[$id][0]=$value;
		$wyniki[$id][1]=0;
		$id++;
}
$url="http://opencaching.pl/okapi/services/caches/search/all?consumer_key=".$_SCRIPT_PARAMS['OKAPI_KEY']."&status=Archived&name=".$_SCRIPT_PARAMS["PROJECT_REGEXP"];
$data = json_decode(file_get_contents($url));
foreach ($data->results as $value) 
{    
		$wyniki[$id][0]=$value;
		$wyniki[$id][1]=2;
		$id++;
}
foreach ($wyniki as $value)
{    
		$caches=$caches.$value[0]."|";
}
$usersFile = fopen("../keszyno_members.dat", "r"); 
$i=0;
while(!feof($usersFile)){
    $line = fgets($usersFile);
    $afterExplode = explode(";", $line);
    $scores[$i][0] = $afterExplode[0];
    $scores[$i][1] = $afterExplode[1];
    $scores[$i][2] = $afterExplode[2];
    $scores[$i][3] = $afterExplode[3];
    $scores[$i][4] = 0;
    $scores[$i][5] = 0;
    $scores[$i][6] = 0;
    $i++;
}
$geocachersInGame=$i;
fclose($usersFile);
$fields="type|founds|notfounds|recommendations|name|date_hidden|url|owner";
$url="http://opencaching.pl/okapi/services/caches/geocaches?consumer_key=".$_SCRIPT_PARAMS['OKAPI_KEY']."&fields=".$fields."&cache_codes=".$caches;
$cachesCount=$id;
$data = json_decode(file_get_contents($url),true);
for($id=0;$id<$cachesCount;$id++)
{
		if($data[$wyniki[$id][0]]["type"]=="Traditional")
			$wyniki[$id][2]=0;
		else if($data[$wyniki[$id][0]]["type"]=="Quiz")
			$wyniki[$id][2]=1;
		else if($data[$wyniki[$id][0]]["type"]=="Multi")
			$wyniki[$id][2]=2;
		else if($data[$wyniki[$id][0]]["type"]=="Other")
			$wyniki[$id][2]=3;
		$wyniki[$id][3]=$data[$wyniki[$id][0]]["founds"];
		$wyniki[$id][4]=$data[$wyniki[$id][0]]["notfounds"];
		$wyniki[$id][5]=$data[$wyniki[$id][0]]["recommendations"];
		$wyniki[$id][6]=$data[$wyniki[$id][0]]["name"];
		$wyniki[$id][7]=$data[$wyniki[$id][0]]["url"];
		$wyniki[$id][8]=$data[$wyniki[$id][0]]["owner"]["profile_url"];
		$wyniki[$id][9]=$data[$wyniki[$id][0]]["owner"]["username"];
		$wyniki[$id][10]=cacheIsVaild($data[$wyniki[$id][0]]["owner"]["profile_url"],$geocachersInGame,$scores);
        	$scores[$wyniki[$id][10]][4]++;
		$scores[$wyniki[$id][10]][5]=$scores[$wyniki[$id][10]][5]+$wyniki[$id][3];
        	$scores[$wyniki[$id][10]][6]=$scores[$wyniki[$id][10]][6]+$wyniki[$id][5];
}
$serializedData = serialize($wyniki);
file_put_contents('../wyniki.dat', $serializedData);
$serializedData = serialize($scores);
file_put_contents('../scores.dat', $serializedData);
echo ":)"; 
?>
