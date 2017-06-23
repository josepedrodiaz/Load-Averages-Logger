
<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
function getData(){
  //get the DATA
  //Obtengo los datos de load averages 
  //para generar la grafica
  include("db_load_averages.php");
  
  $sql = "SELECT * FROM `elmisti_load_averages`.`load_averages`";

  if($_GET["initDate"] != ""){
    $sql .= " WHERE `elmisti_load_averages`.`load_averages`.`date` > '" . urldecode($_GET["initDate"]) . "'";
  }else{
    $sql .= " WHERE `elmisti_load_averages`.`load_averages`.`date` > '" . urlencode(date('Y-m-d H:i:s', time() - 43200)) . "'";
  }


  if(($_GET["initDate"] != "") && ($_GET["finalDate"] != "")){
    $sql .= " AND `elmisti_load_averages`.`load_averages`.`date` < '" . urldecode($_GET["finalDate"]) . "'";
  }

  //echo $sql;

  if ($resultado = $conn->query($sql)) {
  
      /* obtener el array de objetos */
      while ($fila = $resultado->fetch_row()) {
          printf ("['%s', %s, %s, %s],\n", $fila[0], $fila[1], $fila[2], $fila[3]);
      }

      /* liberar el conjunto de resultados */
      $resultado->close();
  }

  $conn->close();
}
?>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.4/themes/hot-sneaks/jquery-ui.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>

    <script type="text/javascript">
    // Shorthand for $( document ).ready()
      $(function() {
          $('#initDate').datetimepicker({dateFormat: 'yy-mm-dd'});
          $('#finalDate').datetimepicker({dateFormat: 'yy-mm-dd'});
      });
    </script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Date Time', 'Last Minute', 'Last 5 minutes', 'Last 15 minutes'],
          <?php
          getData();
          ?>
        ]);

        var options = {
          title: 'Load Averages - elmistihostels.com - dedicated232.inmotionhosting.com',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body style="text-align:center">
    <div id="curve_chart" style="width: 900px; height: 500px; margin: 0 auto 10px;"></div>
     <div id="datePickerBox">
      <form id="datePickerform" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Start: <input type="text" name="initDate" id="initDate" value="<?=urldecode($_GET["initDate"])?>" /><br />
        End: <input type="text" name="finalDate" id="finalDate" value="<?=urldecode($_GET["finalDate"])?>" /><br />
        <input type="submit" value="set" />
      </form>
    </div>
  </body>
</html>