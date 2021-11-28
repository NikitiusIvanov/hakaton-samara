<?php
//$r=strip_tags($_REQUEST["json"]);
$id=strip_tags($_REQUEST["id"]);
//$parids=strip_tags($_REQUEST["parids"]);


//echo $parids;

//$a=array_combine(json_decode($parids),json_decode($r));

//print_r($a);
//print_r($a);
$fn="json/".$id.".json";
$fn2="flag_to_process/$id";
//$s=json_encode($a);
//foreach ($a as $k=>$v) $s.="$k,$v\n";
//file_put_contents($fn,$s);

unlink($fn);
if (file_exists($fn2)) unlink($fn2);

echo "Файл $fn удалён!";

?>