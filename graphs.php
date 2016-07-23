<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
  <title></title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="js/data.json"></script>
  <script src="js/chart.js"></script>
  <script>
  
  $(function(){

  //   var ctx = document.getElementById("profit").getContext("2d");
  //   var teams = [];
  //   var pp = [];
  //   var ap = [];

  //   for(var i=0; i<20; i++) {
  //       teams.push(data[i]['team']);
  //       pp.push(data[i]['possible profit']);
  //       ap.push(data[i]['actual profit']);
  //   }

  //   var stats = {
  //     labels: teams,
  //     datasets: [
  //     {
  //         label: "My Second dataset",
  //         fillColor: "rgba(151,187,205,0.2)",
  //         strokeColor: "rgba(151,187,205,1)",
  //         pointColor: "rgba(151,187,205,1)",
  //         pointStrokeColor: "#fff",
  //         pointHighlightFill: "#fff",
  //         pointHighlightStroke: "rgba(151,187,205,1)",
  //         data: ap
  //     }
  //     ]
  // };

  // var profitChart = new Chart(ctx).Radar(stats, {
  //   scaleBeginAtZero : false,
  //   responsive: true
  // });
  
    var ctx = document.getElementById("profit").getContext("2d");
    var teams = [];

    for(var i=0; i<20; i++) {
        var tempData = {};
        tempData['value'] = data[i]['actual profit'];
        tempData['color'] = data[i]['color'];
        //tempData['highlight'] = '#f0f0f0';
        tempData['label'] = data[i]['team'];
        teams.push(tempData);
    }
  
    var profitChart = new Chart(ctx).PolarArea(teams, {
        responsive: true,
        scaleBeginAtZero: false,
        scaleLabel: "£<%=value%>",
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%>£<%= value %>",
    });

});

</script>
</head>
<body>

  <canvas id="profit"></canvas>

</body>
</html>