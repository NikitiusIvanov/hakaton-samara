<?php

//$a=array();

//61258
 $preset_files=scandir("preset/");
      //print_r($json_files);

      $preset_name=array(); // массив id пациентов
      $preset_data=array(); // массив данных из json
      $i=0;
      foreach ($preset_files as $fn)
      {    
	    if (!in_array($fn,array(".","..")))
	    {
		  $i=$i+1;
		  //echo $fn;
		  //$e=explode(".",$fn);
		  $json="{";
		  
		  $preset_name[]=$i;
		  $preset_data=file("preset/".$fn);
		  
		  $fn2="preset/$fn.json";
		  
		  print_r($preset_data);
		  $a2=array();
		  foreach($preset_data as $pd)
		  {
		      $e=explode(",",$pd);
		      $e1=$e[0];
		      $e2=$e[1];
		      $s='"'.str_replace(array("\n", "\r"), '', $e1).'":'.str_replace(array("\n", "\r"), '', $e2).'';
		      $a2[]=$s;
		  }
		  $json="{".implode(",",$a2)."}";
		  file_put_contents($fn2,$json);
	    }
      }
/*
for($i=11; $i<=21; $i++)
{
	$a=array("patientid"=>$i,"date"=>date("d-m-Y H:i:s"),"p1"=>rand(1,100),"p2"=>rand(1,100),"p3"=>rand(1,100),"p1"=>rand(1,100));
	print_r($a);
	$fn="json/$i.json";
	//file_put_contents($fn,json_encode($a));
}*/

//echo $json

?>