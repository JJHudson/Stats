<?php

	$mysqli = new mysqli("external-db.s175476.gridserver.com", "db175476", "Isabella1!", "db175476_stats");

	$teams = array(
		"Arsenal",
		"Aston Villa",
		"Burnley",
		"Chelsea",
		"Crystal Palace",
		"Everton",
		"Hull",
		"Leicester",
		"Liverpool",
		"Man City",
		"Man United",
		"Newcastle",
		"QPR",
		"Southampton",
		"Stoke",
		"Sunderland",
		"Swansea",
		"Tottenham",
		"West Brom",
		"West Ham"
	);

	$colors = array(
		"#EF0107",
		"#7A003C",
		"#53162F",
		"#034694",
		"#C4122E",
		"#274488",
		"#F5A12D",
		"#0053A0",
		"#D00027",
		"#5CBFEB",
		"#DA020E",
		"#231F20",
		"#005CAB",
		"#ED1A3B",
		"#E03A3E",
		"#EB172B",
		"#000000",
		"#001C58",
		"#091453",
		"#60223B",

	);

	$jsonData = array();

	for($i=0; $i<count($teams); $i++) {

		// PP = Possible Profit
		// AP = Actual Profit
		// WS = Wins
		// Q = Query
		// R = Result

		$PPQ = $mysqli->query("SELECT SUM(CASE WHEN home_team = '".$teams[$i]."' THEN bet365_home_win WHEN away_team = '".$teams[$i]."' THEN bet365_away_win END) AS winnings FROM results WHERE home_team = '".$teams[$i]."' OR away_team = '".$teams[$i]."'");
		$APQ = $mysqli->query("SELECT SUM(CASE WHEN result = 'H' THEN bet365_home_win WHEN result = 'A' THEN bet365_away_win ELSE 0 END) AS winnings FROM results WHERE (result = 'H' && home_team = '".$teams[$i]."') OR (result = 'A' && away_team = '".$teams[$i]."')");
		$WSQ = $mysqli->query("SELECT COUNT(*) AS wins FROM results WHERE (result = 'H' && home_team = '".$teams[$i]."') OR (result = 'A' && away_team = '".$teams[$i]."')");

		$PPR = $PPQ->fetch_assoc();
		$APR = $APQ->fetch_assoc();
		$WSR = $WSQ->fetch_assoc();

		$PP = $PPR['winnings'] - 38;
		$AP = $APR['winnings'] - 38;

		//echo $teams[$i].'<br /> Possible 	Profit: '.$PP.'<br /> Actual Profit: '.$AP.'<br /><br />';

		$temp_data = array('team'=>$teams[$i], 'possible profit'=>$PP, 'actual profit'=>$AP, 'color'=>$colors[$i]);

		//$jsonData[$teams[$i]] = $temp_data;
		array_push($jsonData,$temp_data);

	}

	//var_dump($jsonData)

	$fh = fopen("./js/data.json", 'w+') or die("Error opening output file");
	fwrite($fh, "var data = ");
	fwrite($fh, json_encode($jsonData,JSON_UNESCAPED_UNICODE));
	fclose($fh);

?>