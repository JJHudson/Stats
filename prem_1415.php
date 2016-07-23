<?php

	$mysqli = new mysqli("external-db.s175476.gridserver.com", "db175476", "Ddljh4621710!", "db175476_stats");

	$teams = array(
		"Chelsea",
		"Man City",
		"Arsenal",
		"Man United",
		"Tottenham",
		"Liverpool",
		"Southampton",
		"Swansea",
		"Stoke",
		"Crystal Palace",
		"Everton",
		"West Ham",
		"West Brom",
		"Leicester",
		"Newcastle",
		"Sunderland",
		"Aston Villa",
		"Birmingham",
		"Hull", 
		"Burnley",
		"QPR"
	);

	/*
		"Blackburn",
		"Blackpool",
		"Bolton",
		"Bournemouth",
		"Cardiff",
		"Charlton",
		"Derby",
		"Fulham",
		"Leeds",
		"Middlesbrough",
		"Norwich",
		"Portsmouth",
		"Reading",
		"Sheffield United",
		"Watford",
		"Wigan",
		"Wolves"
	*/

	$year = 'prem_1415';

	for($i=0; $i<count($teams); $i++) {

		$jsonData = array();

		$oddsQuery = $mysqli->query('SELECT AVG((CASE WHEN HomeTeam = "'.$teams[$i].'" THEN B365H WHEN AwayTeam = "'.$teams[$i].'" THEN B365A END)) as avg_odds_all, MAX((CASE WHEN HomeTeam = "'.$teams[$i].'" THEN B365H WHEN AwayTeam = "'.$teams[$i].'" THEN B365A END)) as max_odds_all, MIN((CASE WHEN HomeTeam = "'.$teams[$i].'" THEN B365H WHEN AwayTeam = "'.$teams[$i].'" THEN B365A END)) as min_odds_all, AVG((CASE WHEN HomeTeam = "'.$teams[$i].'" && FTR = "H" THEN B365H WHEN AwayTeam = "'.$teams[$i].'" && FTR = "A" THEN B365A END)) as avg_odds_winning, MAX((CASE WHEN HomeTeam = "'.$teams[$i].'" && FTR = "H" THEN B365H WHEN AwayTeam = "'.$teams[$i].'" && FTR = "A" THEN B365A END)) as max_odds_winning, MIN((CASE WHEN HomeTeam = "'.$teams[$i].'" && FTR = "H" THEN B365H WHEN AwayTeam = "'.$teams[$i].'" && FTR = "A" THEN B365A END)) as min_odds_winning, SUM((CASE WHEN HomeTeam = "'.$teams[$i].'" && FTR = "H" THEN B365H - 1 WHEN AwayTeam = "'.$teams[$i].'" && FTR = "A" THEN B365A - 1 ELSE -1 END)) as winnings FROM '.$year.' WHERE HomeTeam = "'.$teams[$i].'" OR AwayTeam = "'.$teams[$i].'"');

		while($row = $oddsQuery->fetch_assoc()){
			
			$temp_data = array('team'=>$teams[$i],'avg_odds_all'=>$row['avg_odds_all'], 'max_odds_all'=>$row['max_odds_all'],'min_odds_all'=>$row['min_odds_all'],'avg_odds_winning'=>$row['avg_odds_winning'], 'max_odds_winning'=>$row['max_odds_winning'], 'min_odds_winning'=>$row['min_odds_winning'], 'winnings'=>$row['winnings']);

			print '<strong>'.$teams[$i].'</strong><br />';
			print '<strong>Average Odds: </strong>' . number_format((float)$row['avg_odds_all'], 2, '.', '') . '<br />';
			print '<strong>Average Odds (only wins): </strong>' . number_format((float)$row['avg_odds_winning'], 2, '.', '') . '<br />';
			print '<strong>Profit: </strong>' . number_format((float)$row['winnings'], 2, '.', '') . '<br />';
			print '<br /><br />';

			//array_push($jsonData,$temp_data);
		}

		//var_dump($jsonData);

		// $fh = fopen("./js/teamData/1516/".$team.".json", 'w') or die("can't open file");
		// fwrite($fh, json_encode($jsonData,JSON_UNESCAPED_UNICODE));
		// fclose($fh);

	}


?>