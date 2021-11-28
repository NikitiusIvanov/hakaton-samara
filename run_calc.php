<?php
//echo 1234;

// загрузка наборов данных из json
      $json_files=scandir("json/");
      //print_r($json_files);

      $pids=array(); // массив id пациентов
      $data=array(); // массив данных из json
      foreach ($json_files as $fn)
      {
	    if (!in_array($fn,array(".","..")))
	    {
		  //echo $fn;
		  $e=explode(".",$fn);
		  $pids[]=$e[0];
		  $data[$e[0]]=json_decode(file_get_contents("json/".$fn));
	    }
      }
      
      print_r($data);
      
      
      $keys_to_process=array();
      foreach($data as $k=>$d)
      {
	    if (isset($d->status))
	    {
		  if ($d->status=="Не посчитана") $keys_to_process[]=$k;
	    }
      }
      
      print_r($keys_to_process);
      
      foreach ($keys_to_process as $k)
      {
	  $fn="flag_to_process/$k";
	  $fn2="json/$k.json";
	  if (!file_exists($fn))
	  { 
		file_put_contents($fn,"tostart");
		
		$d=$data[$k];
		$d->status="Вычисления начаты";
		$aa=(array) $d;
		file_put_contents($fn2,json_encode($aa));
		chmod($fn2, 0777);
		//print_r($d);
	  }
	  
      }
      
      $output = shell_exec('/usr/bin/nohup php dispetcher.php >/dev/null 2>&1 &');
      echo $output;
      
?>