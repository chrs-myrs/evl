<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$db = Loader::db();
$form = Loader::helper('form');
extract($_GET);

$tables = $db->execute("SELECT rID, twsSeasons.name as season, twsDivisions.name as division, twsTeams.name as team, played, drawn, lost, goalsfor, goalsagainst, points, isLive FROM twsLeagueTables JOIN twsSeasons on season=sID JOIN twsDivisions ON division=dID JOIN twsTeams on team=tID ORDER BY twsSeasons.sortorder, twsDivisions.sortOrder, twsTeams.sortOrder");

$seasonquery = $db->execute("SELECT sID, name FROM twsSeasons ORDER BY sortorder");
$seasons = array('Filter by Season');
foreach($seasonquery as $s) {
    $seasons[$s['sID']] = "Season ".$s['name'];
}

$divisionquery = $db->execute("SELECT dID, name FROM twsDivisions ORDER BY sortorder");
$divisions = array('Filter by Division');
foreach($divisionquery as $d) {
    $divisions[$d['dID']] = $d['name'];
}

$teamquery = $db->execute("Select
                              Grouping.GroupID,
                              twsTeams.name
                            From
                              (Select distinct
                                  If(twsTeams.groupID > 0, twsTeams.groupID, twsTeams.tID) As GroupID
                                From
                                  twsTeams) Grouping Inner Join
                              twsTeams On Grouping.GroupID = twsTeams.tID
                            Order By
                              twsTeams.sortorder");
$teams = array('Filter by Team');
foreach($teamquery as $t) {
    $teams[$t['GroupID']] = $t['name'];
}

$teamselect = ($teamfilter ? " JOIN (Select Distinct
                                          twsLeagueTables.season,
                                          twsLeagueTables.division
                                        From
                                          twsLeagueTables Inner Join
                                          (Select Distinct
                                              If(twsTeams.groupID > 0, twsTeams.groupID, twsTeams.tID) As GroupID,
                                              twsTeams.tID
                                            From
                                              twsTeams) Grouping 
                                On twsLeagueTables.team = Grouping.tID
                                Where
                                  Grouping.GroupID = $teamfilter) tt 
                                  ON twsLeagueTables.season = tt.season AND twsLeagueTables.division = tt.division" : '');

$filters = array();
if($seasonfilter) $filters[] = "twsLeagueTables.season=$seasonfilter";
if($divisionfilter) $filters[] = "twsLeagueTables.division=$divisionfilter";

if(sizeof($filters)) $wherestring = " WHERE ".implode (" AND ", $filters);

$tablequery = "SELECT rID, twsLeagueTables.season, twsLeagueTables.division, twsLeagueTables.team, twsSeasons.name as seasonname, twsDivisions.name as divisionname, twsTeams.name as teamname, played, won, drawn, lost, goalsfor, goalsagainst, points, isLive, groupID
                FROM twsLeagueTables$teamselect JOIN twsSeasons on twsLeagueTables.season=sID JOIN twsDivisions ON twsLeagueTables.division=dID JOIN twsTeams on twsLeagueTables.team=tID$wherestring
                ORDER BY twsSeasons.sortorder, twsSeasons.name DESC, twsDivisions.sortOrder, twsDivisions.name DESC, points DESC, (goalsfor - goalsagainst) DESC";
$tables = $db->execute($tablequery);

$toolurl = Loader::helper('concrete/urls')->getToolsURL('view_tables', 'tws_league_tables');
?>
<form id="filterboxes" name="filterboxes" method="get" style="margin: 0 auto;">

    <?php
echo $form->select("seasonfilter", $seasons, 0, array('class' => 'filterbox'));
echo $form->select("divisionfilter", $divisions, 0, array('class' => 'filterbox'));
echo $form->select("teamfilter", $teams, 0, array('class' => 'filterbox'));
echo $form->submit("filter", "Filter");
?>
</form>

<div id="league-tables">

    <?php
        $i = 1;
        foreach($tables as $row) {
            extract($row);
            if(!($season == $activeseason && $division == $activedivision)) {
                if($intable) echo '</table></div>'.($i & 1 ? '<div class="ccm-spacer"></div>' : '');
                echo '<div class="tablebox"><h2>Season '.$seasonname.' - '.$divisionname.'</h2><table>';
                echo "<tr><th style=\"min-width: 185px;\">Team</th><th class=\"stat\">P</th><th class=\"stat\">W</th><th class=\"stat\">D</th><th class=\"stat\">L</th><th class=\"stat\">F</th><th class=\"stat\">A</th><th class=\"stat\">Pts.</th></tr>";
                $intable = true;
                $i++;
            }
            $rowclass = ($team == $teamfilter || ($groupID>0 && $groupID == $teamfilter) ? "selected-team" : "");
            echo "<tr class=\"$rowclass\"><td>$teamname</td><td class=\"stat\">$played</td><td class=\"stat\">$won</td><td class=\"stat\">$drawn</td><td class=\"stat\">$lost</td><td class=\"stat\">$goalsfor</td><td class=\"stat\">$goalsagainst</td><td class=\"stat\">$points</td></td>";
            $activeseason = $season;
            $activedivision = $division;
        }
        if($intable) echo '</table></div>';
    ?>  
    
</div>