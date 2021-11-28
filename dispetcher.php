<?php
        $files=scandir("flag_to_process/");
      //print_r($json_files);

      //$preset_name=array(); // массив id пациентов
      //$preset_data=array(); // массив данных из json
      foreach ($files as $fn)
      {
	    if (!in_array($fn,array(".","..")))
	    {
		  //echo $fn;
		  //$e=explode(".",$fn);
		  //$preset_name[]=$e[0];
		 // $preset_data[$e[0]]=file_get_contents("preset/".$fn);
		 echo $fn." ";
		 $cmd="python3 job.py ".$fn;
		 $output=shell_exec($cmd);
		 echo $output." ";
	    }
      }
      
       $files2=scandir("flag_finished/");
      foreach ($files2 as $fn)
      {
	    if (!in_array($fn,array(".","..")))
	    {
		    $fn2="result/".$fn.".json";
		    $res_text=file_get_contents($fn2);
		    $res_text_a=json_decode($res_text);
		    //print_r($res_text_a);
		    if (file_exists($fn2))
		    {
				// всё отработало нормально
				$fn3="json/".$fn.".json";
				$a=json_decode(file_get_contents($fn3));
				//$a->status
				$txt="Расчёт осуществлён.<br><br>";
				$txt.="Оценка ВС: ".round($res_text_a->res_value,2)."<br><br>";
				$txt.="Оценка значимости параметров: <br>";
				$param_relevance=(array)($res_text_a->param_relevance[0]);
				arsort($param_relevance);
				foreach($param_relevance as $k=>$pr)
				{
				      $txt.="$k : $pr <br>";
				}
				//print_r($param_relevance);
				//echo $txt;
				$a->status=$txt;
				//echo json_encode((array)$a);
				file_put_contents($fn3,json_encode((array)$a));
				unlink("flag_finished/".$fn);
				
		    }
	   }
	}
      
      
?>