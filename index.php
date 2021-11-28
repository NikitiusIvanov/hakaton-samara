<html>
<head>
    <style>
	.div2 {width:80%; min-width: 400px; margin: 0 auto;}
	.preset_btn {margin-left: 5px;}
	.mybtn {
		color: #333 !important;
		border: 1px solid #979797;
		background: linear-gradient(to bottom, white 0%, #dcdcdc 100%);
		box-sizing: border-box;
		display: inline-block;
		min-width: 1.5em;
		padding: 0.5em 1em;
		margin-left: 2px;
		text-align: center;
		text-decoration: none !important;
		cursor: pointer;
		border-radius: 2px;
	}
	.dataTables_filter input { width: 200px }
    </style>
</head>
<body>

<div class="maindiv">
<div class="div2">
<h2>Интеллектуальная система определения опасных состояний здоровья</h2>


<?php
    // загрузка списка предикторов
    $parids=array();
    $pnames=array();
    $row = 1;
    if (($handle = fopen("params.csv", "r")) !== FALSE) {
		  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		      $num = count($data);
		      //echo "<p> $num полей в строке $row: <br /></p>\n";
		      $row++;
		      for ($c=0; $c < $num; $c++) {
			  //echo $data[$c] . "<br />\n";
			  if ($c==0) $parids[]=$data[$c];
			  if ($c==1) $pnames[]=$data[$c];
		      }
		  }
		  fclose($handle);
    }
    
      // загрузка пресетов
       $preset_files=scandir("preset/");
      //print_r($json_files);

      $preset_name=array(); // массив id пациентов
      $preset_data=array(); // массив данных из json
      foreach ($preset_files as $fn)
      {
	    if (!in_array($fn,array(".","..")))
	    {
		  //echo $fn;
		  $e=explode(".",$fn);
		  $preset_name[]=$e[0];
		  $preset_data[$e[0]]=file_get_contents("preset/".$fn);
	    }
      }
      echo "<script>var preset_pars=[];";
      foreach ($preset_data as $k=>$pd) echo "preset_pars[$k]=$pd; ";
      echo "</script>";
      
      /*print_r($preset_name);
      print_r($preset_data);
      exit;*/
    
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
      
      $next_id=max($pids)+1;
      $now_date=date("d-m-Y H:i:s");
    
    echo "<table id='table_edit' class='cell-border compact stripe'><thead><tr>";
    foreach ($pnames as $pn)
    {
	if ($pn!="Оценка вероятности летального исхода") echo "<td><b>$pn</b></td>";
    }
    echo "</tr></thead><tbody><tr>";
    foreach ($parids as $p)
    {
	$val="";
       if ($p=="patientid") $val=$next_id;
       if ($p=="date") $val=$now_date;
       if ($p!="status") echo "<td><input id='new_$p' value='$val' style='width: 100%;'></td>";
    }
    echo "</tr></tbody>";
    echo "<table>";

?>
<br>
<button id="button_add" class="mybtn" style="margin-right: 56px;">Добавить новую запись</button>

<?php
    foreach($preset_name as $pn)
    {
	  $n="Пресет ".$pn;
          echo "<button id='button_preset_$pn' class='preset_btn  mybtn'>$n</button>";
    }
    
?>
<button id="button_delete" class="mybtn" style="margin-right: 56px; margin-left: 56px;">Удалить выбранную запись</button>
<button id="button_start" style='margin-left: 5px;'  class="mybtn">Запустить расчёт оценок</button>
<button id="button_update" class="mybtn" style="margin-left: 56px;">Обновить страницу</button>
<br><br>
<table id='table_id' class='cell-border compact stripe'>

<?php


//print_r($pids);
//print_r($pnames);
$pars=array_combine($parids,$pnames);
//print_r($parids);

echo "<thead><tr>";
foreach ($pars as $p) echo "<th>".$p."</th>";
echo "</tr></thead>";

//echo "</table>";




echo "<tbody>";
 
foreach ($data as $d)
{ 
        echo "<tr>";
	foreach ($parids as $p)	 
	{
	        $val="";
	        if (in_array($p,array_keys((array)$d)))
	        {
		    $val=$d->$p;
		}
	        //echo "<td><input id='".$d->patientid."_".$p."' value='$val'></td>";
	        echo "<td>$val</td>";
	  }
	  echo "</tr>";
 }
 echo "</tbody></table>";
 
 //print_r( array_keys((array) $data[1]));
 
// print_r($parids);
 //exit;

//echo 123;

?>


</div>
</div>
</body>
<link rel="stylesheet" type="text/css" href="jquery.dataTables.css">
<script src="jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="jquery.dataTables.js"></script>
<script>
    $(document).ready( function () {
        $('#table_id').dataTable( {
	  "iDisplayLength": 50,
	  "columnDefs": [
              { "type": "num", "targets": 0 }
          ],
	  language: {
	      url: 'table.ru.json'
	  },
	  "order": [[ 0, "desc" ]]
	} );
	
	  $('#table_edit').dataTable( {
	   "paging":   false,
	    "ordering": false,
	    "info":     false,
	     "searching": false,
	  } );
	
	$( "button.preset_btn" ).click(function(){
	    //table.row('.selected').remove().draw( false );
	      var btnid=this.id;
	      var btn_split=btnid.split('_')[2];
	      var pr_data=preset_pars[btn_split];
	      //console.log(pr_data);
	       var obj = jQuery.parseJSON(JSON.stringify(pr_data));
	       //console.log(obj);
	       
	       var k=Object.keys(obj);
	       
	       var edit_id=0;
	       k.forEach(function(item, i, arr) {
		//alert( i + ": " + item + " (массив:" + arr + ")" );
		//console.log(pr_data[item]);
		edit_id="new_"+item;
		//alert(edit_id);
		$("#"+edit_id).val(pr_data[item]);
	      });
	       //console.log();
	       //obj.forEach(element => console.log(element));
	       
	       //obj.forEach(function(entry) {
	//	  console.log(entry);
	  //     });
	        //obj.keys(pr_data).forEach(function (key){
		 //   console.log(a[key]);
		//});
	} );
	
	
	
        /*$('#table_id').DataTable(); */
        
        var table = $('#table_id').DataTable();
        var table0 = $('#table_edit').DataTable();
	
	$('#table_id tbody').on( 'click', 'tr', function () {
	
	    //console.log(table.$('tr.selected'));
	    
	    //console.log(table.rows( { selected: true } ));
	    var d=table.row( this ).data()[0] ;
	    //var rid=
	   // alert( 'Row index: '+table.row( this ).data() );
	  //  var d = table.row(':eq(0)').data();
	   console.log(d);
	
	    if ( $(this).hasClass('selected') ) {
		$(this).removeClass('selected');
	    }
	    else {
		table.$('tr.selected').removeClass('selected');
		$(this).addClass('selected');
	    }
	} );
    
	$('#button_delete').click( function () {
	    var d=table.row( '.selected' ).data()[0] ;
	    console.log(d);
	    
	    $.ajax({
			    url: "delete_json.php",
			    data: { 
				  "id": d
			    },
			    cache: false,
			    type: "POST",
			    success: function(response) {
				      //alert(response);
			    },
			    error: function(xhr) {

			    }
		   });
	    
	    table.row('.selected').remove().draw( false );
	    
	    
	} );
	
	 $( "#button_add" ).click(function(){ 
		  var table = $('#table_id').DataTable();
		  
		  var a_paste=[ 
			  <?php
			      $new_json="";
			      foreach($parids as $p) 
			      { 
				  if ($p!="status")
				  {
				    if ($p==$parids[0]) echo "$('#new_$p').val()"; else echo ",$('#new_$p').val()";
				    //$vv="$('#new_$p').val()";
				  //  $new_json.='"'.$p.'":"+'.$vv;
				  }
			       }
			       //$new_json="}";
			       echo ',"Не посчитана"';
			   ?>
		   ] ;
		  
		  var new_json= ""<?php echo $new_json; ?>;
		  
		  var rowNode = table.row.add(a_paste).draw().node();
		  var lastid=parseInt($('#new_patientid').val());
		  var newid=parseInt($('#new_patientid').val())+1;
		  $('#new_patientid').val(newid);
		  
		  console.log(a_paste);
		  
		  $.ajax({
			    url: "create_json.php",
			    data: { 
				"id": lastid, 
				"json": JSON.stringify(a_paste),
				"parids":JSON.stringify(parids)
			    },
			    cache: false,
			    type: "POST",
			    success: function(response) {
				      //alert(response);
			    },
			    error: function(xhr) {

			    }
		   });
		  
		  //$( rowNode ).css( 'color', 'red' )	.animate( { color: 'black' } );
		  
		 
	  });
	  
	   $( "#button_start" ).click(function(){ 
		     $.ajax({
			    url: "run_calc.php",
			    data: { 
			
			    },
			    cache: false,
			    type: "POST",
			    success: function(response) {
				      alert(response);
			    },
			    error: function(xhr) {

			    }
			});
			
			document.location.reload();
	   });
	
	   $( "#button_update" ).click(function(){ 
		    document.location.reload();
	   });
	
    } );
    
    var parids=['<?php echo implode("','",$parids); ?>'];
</script>

<?php
//print_r($parids);
?>


</html>