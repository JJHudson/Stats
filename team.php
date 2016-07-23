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
        ladbrokesData = {},
        williamhillData = {},
        profitChart = null,
        teams = [],
        bet365 = [],
        bet365Total = 0,
        ladbrokes = [],
        ladbrokesTotal = 0,
        williamhill = [],
        williamhillTotal = 0;
    

    var options = {
        animation: false,
        bezierCurve : false,
        pointHitDetectionRadius : 10,
        responsive: true,
        scaleOverride: true,
        scaleSteps: 23,
        scaleStepWidth: 2,
        scaleStartValue: -19,
        scaleIntegersOnly: true,
        scaleLabel: "£<%=value%>",
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%>£<%= value %>",
    }

    function init(team) {
        bindUiEvents();
        fetchData(team);
    }

    function bindUiEvents() {

        $(".button").on('click', changeBrand);
        $(document).on('change','.teams',changeTeam);

    }

    function fetchData(team) {
        
        $.ajax({
            url: "./js/teamData/"+team+".json",
            dataType: 'json'
        }).done(function(data) {
            teamData = data;
            handleData();
        });

    }

    function handleData() {

        teams = [];
        bet365 = [];
        ladbrokes = [];
        williamhill = [];
        bet365Total = 0;
        ladbrokesTotal = 0;
        williamhillTotal = 0;

        for(var i=0; i<38; i++) {
            result = teamData[i]['opponent']+" ("+teamData[i]['result']+" "+teamData[i]['home_goals']+"-"+teamData[i]['away_goals']+")";
            teams.push(result);

            bet365Total = bet365Total + parseFloat(teamData[i]['bet365'], 10) - 1;
            bet365.push(bet365Total.toFixed(2));

            ladbrokesTotal = ladbrokesTotal + parseFloat(teamData[i]['ladbrokes'], 10) - 1;
            ladbrokes.push(ladbrokesTotal.toFixed(2));

            williamhillTotal = williamhillTotal + parseFloat(teamData[i]['williamhill'], 10) - 1;
            williamhill.push(williamhillTotal.toFixed(2));
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

        ladbrokesData = {
            label: "Ladbrokes",
            fillColor: "transparent",
            strokeColor: "#fe0a02",
            pointColor: "#fe0a02",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "#fe0a02",
            data: ladbrokes
        };

        williamhillData = {
            label: "William Hill",
            fillColor: "transparent",
            strokeColor: "#020167",
            pointColor: "#020167",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "#020167",
            data: williamhill
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
            case 'ladbrokes':
                profitChart = new Chart(ctx).Line({labels: teams, datasets: [ladbrokesData]}, options);
                break;
            case 'williamhill':
                profitChart = new Chart(ctx).Line({labels: teams, datasets: [williamhillData]}, options);
                break;
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
        var team = getData.init('arsenal');
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
.ladbrokes {
    left: 170px;
}
.williamhill {
    left: 280px;
}
.teams{
    height: 25px;
    position: absolute;
    top: 10px;
    left: 390px;
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
    <div data-brand="ladbrokes" class="button ladbrokes"><img src="./img/ladbrokes.png" alt="ladbrokes" width="100" /></div>
    <div data-brand="williamhill" class="button williamhill"><img src="./img/williamhill.png" alt="williamhill" width="100" /></div>
    <select class="teams">
        <option value="arsenal">Arsenal</option>
        <option value="astonvilla">Aston Villa</option>
        <option value="burnley">Burnely</option>
        <option value="chelsea">Chelsea</option>
        <option value="crystalpalace">Crystal Palace</option>
        <option value="everton">Everton</option>
        <option value="hull">Hull</option>
        <option value="leicester">Leicester</option>
        <option value="liverpool">Liverpool</option>
        <option value="mancity">Manchester City</option>
        <option value="manunited">Manchester United</option>
        <option value="newcastle">Newcastle</option>
        <option value="qpr">QPR</option>
        <option value="southampton">Southampton</option>
        <option value="stoke">Stoke</option>
        <option value="sunderland">Sunderland</option>
        <option value="swansea">Swansea</option>
        <option value="tottenham">Tottenham</option>
        <option value="westbrom">West Brom</option>
        <option value="westham">West Ham</option>
    </select>
    <canvas width="960" height="600" id="profit"></canvas>
</div>

</body>
</html>