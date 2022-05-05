<?php

/**
 * Use this file to output reports required for the SQL Query Design test.
 * An example is provided below. You can use the `asTable` method to pass your query result to,
 * to output it as a styled HTML table.
 */

$database = 'nba2019';
require_once('vendor/autoload.php');
require_once('include/utils.php');

/*
 * Example Query
 * -------------
 * Retrieve all team codes & names
 */
echo '<h1>Example Query</h1>';
$teamSql = "SELECT * FROM team";
$teamResult = query($teamSql);
// dd($teamResult);
echo asTable($teamResult);

/*
 * Report 1
 * --------
 * Produce a query that reports on the best 3pt shooters in the database that are older than 30 years old. Only
 * retrieve data for players who have shot 3-pointers at greater accuracy than 35%.
 *
 * Retrieve
 *  - Player name
 *  - Full team name
 *  - Age
 *  - Player number
 *  - Position
 *  - 3-pointers made %
 *  - Number of 3-pointers made
 *
 * Rank the data by the players with the best % accuracy first.
 */
echo '<h1>Report 1 - Best 3pt Shooters</h1>';
// write your query here
$bestShootersQuery = "
    SELECT
        player.name,
        team.code,
        pt.age,
        player.number,
        player.pos,
        CONCAT(ROUND((pt.3pt / pt.3pt_attempted) * 100, 0), '%') AS 'percentage',
        pt.3pt
    FROM
        player_totals AS pt
    INNER JOIN roster AS player
        ON pt.player_id = player.id
    INNER JOIN team
        ON player.team_code = team.code
    WHERE
        pt.age > 30
    AND
        (ROUND((pt.3pt / pt.3pt_attempted) * 100, 0) > 35)
    ORDER BY percentage ASC
";

$bestShootersResult = query($bestShootersQuery);
echo asTable($bestShootersResult);

/*
 * Report 2
 * --------
 * Produce a query that reports on the best 3pt shooting teams. Retrieve all teams in the database and list:
 *  - Team name
 *  - 3-pointer accuracy (as 2 decimal place percentage - e.g. 33.53%) for the team as a whole,
 *  - Total 3-pointers made by the team
 *  - # of contributing players - players that scored at least 1 x 3-pointer
 *  - of attempting player - players that attempted at least 1 x 3-point shot
 *  - total # of 3-point attempts made by players who failed to make a single 3-point shot.
 *
 * You should be able to retrieve all data in a single query, without subqueries.
 * Put the most accurate 3pt teams first.
 */
echo '<h1>Report 2 - Best 3pt Shooting Teams</h1>';
// write your query here
$bestTeamShootersQuery = "
    SELECT
        team.name,
        ROUND((SUM(pt.3pt) / SUM(pt.3pt_attempted)) * 100, 2) AS '3 points accuracy',
        SUM(pt.3pt) AS 'total 3 points',
        SUM(IF(pt.3pt > 1, 1, 0)) AS 'total numbers of contributing players',
        SUM(IF(pt.3pt_attempted > 1, 1, 0)) AS 'total numbers of attempting player',
        IF(pt.3pt < 1, pt.3pt_attempted, 0) AS 'total numbers of 3-point attempts'
    FROM
        player_totals AS pt
    INNER JOIN roster AS player
        ON pt.player_id = player.id
    INNER JOIN team
        ON player.team_code = team.code
    GROUP BY
        team.code
    ORDER BY
        (ROUND((SUM(pt.3pt) / SUM(pt.3pt_attempted)) * 100, 2)) DESC
";

$bestTeamShootersResult = query($bestTeamShootersQuery);
echo asTable($bestTeamShootersResult);
?>
