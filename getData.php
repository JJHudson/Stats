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

	for($i=0; $i<count($teams); $i++) {

		$jsonData = array();

		$team = preg_replace('/\s*/', '', $teams[$i]);
		$team = strtolower($team);

		//$oddsQuery = $mysqli->query('SELECT (CASE WHEN home_team = "'.$teams[$i].'" THEN away_team WHEN away_team = "'.$teams[$i].'" THEN home_team END) AS opponent, (CASE WHEN home_team = "'.$teams[$i].'" THEN bet365_home_win WHEN away_team = "'.$teams[$i].'" THEN bet365_away_win END) AS bet365, (CASE WHEN home_team = "'.$teams[$i].'" THEN ladbrokes_home_win WHEN away_team = "'.$teams[$i].'" THEN ladbrokes_away_win END) AS ladbrokes, (CASE WHEN home_team = "'.$teams[$i].'" THEN williamhill_home_win WHEN away_team = "'.$teams[$i].'" THEN williamhill_away_win END) AS williamhill FROM results WHERE home_team = "'.$teams[$i].'" OR away_team = "'.$teams[$i].'"');

		$oddsQuery = $mysqli->query('SELECT home_goals, away_goals, (CASE WHEN home_team = "'.$teams[$i].'" THEN away_team WHEN away_team = "'.$teams[$i].'" THEN home_team END) AS opponent, (CASE WHEN result = "H" && home_team = "'.$teams[$i].'" OR result = "A" && away_team = "'.$teams[$i].'" THEN "W" WHEN result = "A" && home_team = "'.$teams[$i].'" OR result = "H" && away_team = "'.$teams[$i].'" THEN "L" ELSE "D" END) as result, (CASE WHEN result = "H" && home_team = "'.$teams[$i].'" THEN bet365_home_win WHEN result = "A" && away_team = "'.$teams[$i].'" THEN bet365_away_win ELSE 0 END) as bet365, (CASE WHEN result = "H" && home_team = "'.$teams[$i].'" THEN ladbrokes_home_win WHEN result = "A" && away_team = "'.$teams[$i].'" THEN ladbrokes_away_win ELSE 0 END) as ladbrokes, (CASE WHEN result = "H" && home_team = "'.$teams[$i].'" THEN williamhill_home_win WHEN result = "A" && away_team = "'.$teams[$i].'" THEN williamhill_away_win ELSE 0 END) as williamhill FROM results WHERE home_team = "'.$teams[$i].'" OR away_team = "'.$teams[$i].'"');

		while($row = $oddsQuery->fetch_assoc()){
			$temp_data = array('opponent'=>$row['opponent'], 'result'=>$row['result'],'home_goals'=>$row['home_goals'],'away_goals'=>$row['away_goals'], 'bet365'=>$row['bet365'], 'ladbrokes'=>$row['ladbrokes'], 'williamhill'=>$row['williamhill']);
			array_push($jsonData,$temp_data);
		}

		//var_dump($jsonData);

		$fh = fopen("./js/teamData/".$team.".json", 'w') or die("can't open file");
		fwrite($fh, json_encode($jsonData,JSON_UNESCAPED_UNICODE));
		fclose($fh);

	}


?>