<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$db = Loader::db();
$form = Loader::helper('form');

$table = $db->execute("Select
                          Grouping.GroupID,
                          twsTeams.name As GroupName,
                          Count(Distinct twsLeagueTables.team) As TeamCount,
                          Sum(twsLeagueTables.played) As played,
                          Sum(twsLeagueTables.won) As won,
                          Sum(twsLeagueTables.drawn) As drawn,
                          Sum(twsLeagueTables.lost) As lost,
                          Sum(twsLeagueTables.goalsfor) As goalsfor,
                          Sum(twsLeagueTables.goalsagainst) As goalsagainst,
                          Sum(twsLeagueTables.points) As points,
                          Count(Distinct twsLeagueTables.season) As SeasonCount
                        From
                          (Select
                              If(twsTeams.groupID > 0, twsTeams.groupID, twsTeams.tID) As GroupID,
                              twsTeams.tID,
                              twsTeams.name As TeamName,
                              twsTeams.sortorder As TeamSort
                            From
                              twsTeams) Grouping Inner Join
                          twsTeams On Grouping.GroupID = twsTeams.tID Inner Join
                          twsLeagueTables On Grouping.tID = twsLeagueTables.team
                        Group By
                          Grouping.GroupID, twsTeams.name");

$toolurl = Loader::helper('concrete/urls')->getToolsURL('view_tables', 'tws_league_tables');
?>

<div>
    <table id="all-time-table">
        <thead> 
            <tr>
                <th title="Click to sort by team name.">Team Name</th>
                <th class="stat" title="Click to sort by games played.">P</th>
                <th class="stat" title="Click to sort by games won.">W</th>
                <th class="stat" title="Click to sort by games drawn.">D</th>
                <th class="stat" title="Click to sort by games lost.">L</th>
                <th class="stat" title="Click to sort by goals for.">F</th>
                <th class="stat" title="Click to sort by goals against.">A</th>
                <th class="stat" title="Click to sort by points.">Pts.</th>
                <th class="stat" title="Click to sort by seasons played.">Seasons</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($table as $row) {
                extract($row);
                //if ($TeamCount > 1) {
                //    $GroupName .= " ($TeamCount)";
                //}
                echo "<tr><td>$GroupName</td><td class=\"stat\">$played</td><td class=\"stat\">$won</td><td class=\"stat\">$drawn</td><td class=\"stat\">$lost</td><td class=\"stat\">$goalsfor</td><td class=\"stat\">$goalsagainst</td><td class=\"stat\">$points</td><td class=\"stat\">$SeasonCount</td></tr>";
            }
            ?>  
        </tbody>
    </table>
</div>