<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
  <title></title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="js/chart.js"></script>
  <script>
  
var getData = function(){

    var teamData,
        result,
        bet365Data = {},
        profitChart = null,
        teams = [],
        bet365 = [],
        bet365Total = 0;
    

    var options = {
        animation: false,
        bezierCurve : false,
        pointHitDetectionRadius : 10,
        responsive: true,
        scaleOverride: true,
        scaleSteps: 23,
        scaleStepWidth: 2,
        scaleStartValue: -12,
        scaleIntegersOnly: true,
        scaleLabel: "£<%=value%>",
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%>£<%= value %>",
    }

    function init(team) {
        bindUiEvents();
        fetchData(team);
    }

    function bindUiEvents() {

        $(document).on('change','.teams',changeTeam);

    }

    function fetchData(team) {
        
        $.ajax({
            url: "./js/teamData/1516/"+team+".json",
            dataType: 'json'
        }).done(function(data) {
            teamData = data;
            handleData();
        });

    }

    function handleData() {

        teams = [];
        bet365 = [];
        bet365Total = 0;

        for(var i=0; i<teamData.length; i++) {
            result = teamData[i]['opponent']+" ("+teamData[i]['result']+" "+teamData[i]['home_goals']+"-"+teamData[i]['away_goals']+")";
            teams.push(result);

            bet365Total = bet365Total + parseFloat(teamData[i]['bet365'], 10) - 1;
            bet365.push(bet365Total.toFixed(2));
        }

        bet365Data = {
            label: "Bet 365",
            fillColor: "transparent",
            strokeColor: "#027b5b",
            pointColor: "#027b5b",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "#027b5b",
            data: bet365
        };

        changeBrand();
    }

    function changeBrand() {

        var brand = $(this).attr('data-brand');
        var ctx = document.getElementById("profit").getContext("2d");

        if(profitChart !== null){
            profitChart.destroy();
        }

        switch(brand) {
            default:
                profitChart = new Chart(ctx).Line({labels: teams, datasets: [bet365Data]}, options);
        }

    }

    function changeTeam() {

        var team = this.value;
        fetchData(team);

    }

    return {
        init: init
    };

}();

    $(function(){
        var team = getData.init('crystalpalace');
    });

</script>
<style type="text/css">
.button {
    cursor: pointer;
    height: 25px;
    width: 100px; 
    font: 14px arial, sans-serif;
    position: absolute;
    top: 10px;
}
.bet365 {
    left: 60px;
}
.teams{
    height: 25px;
    position: absolute;
    top: 10px;
    left: 180px;
}
.graph_wrapper {
    margin: 20px auto;
    max-width: 1200px;
    position: relative;
    width: 90%;
}
</style>
</head>
<body>

<div class="graph_wrapper">
    <div data-brand="bet365" class="button bet365"><img src="./img/bet365.png" alt="bet365" width="100" /></div>
    <select class="teams">
        <option value="crystalpalace">Crystal Palace</option>
        <option value="stoke">Stoke</option>
        <option value="swansea">Swansea</option>
        <option value="westbrom">West Brom</option>
        <option value="westham">West Ham</option>
    </select>
    <canvas width="960" height="600" id="profit"></canvas>
</div>

</body>
</html>