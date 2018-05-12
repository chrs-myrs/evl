<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$db = Loader::db();
$ih = Loader::helper('concrete/interface'); 

if(isset($_POST['Save'])) {
    $db->execute("INSERT INTO twsLeagueTables (season, division, team, played, won, drawn, lost, goalsfor, goalsagainst, points) VALUES (?,?,?,?,?,?,?,?,?,?)", array($_POST['season'],$_POST['division'],$_POST['team'],$_POST['played'],$_POST['won'],$_POST['drawn'],$_POST['lost'],$_POST['goalsfor'],$_POST['goalsagainst'],$_POST['points']));
}

$tables = $db->execute("SELECT rID, twsSeasons.name as season, twsDivisions.name as division, twsTeams.name as team, played, won, drawn, lost, goalsfor, goalsagainst, points, isLive FROM twsLeagueTables JOIN twsSeasons on season=sID JOIN twsDivisions ON division=dID JOIN twsTeams on team=tID ORDER BY twsSeasons.sortorder, twsDivisions.sortOrder, twsTeams.sortOrder");

?>

<h1><span><?php echo t('Historic League Tables')?></span></h1>
<div class="ccm-dashboard-inner ccm-ui">
<?php echo $ih->button_js("Add New Record", "javascript: addNew()", 'left', array('style'=>'margin-bottom: 10px;')); ?>
<table class="zebra-striped" border="0" cellspacing="1" cellpadding="0" style="margin-top: 20px;">
<tr><th>Season</th><th>Division</th><th>Team</th><th>P</th><th>W</th><th>D</th><th>L</th><th>F</th><th>A</th><th>Pts.</th><th></th></tr>
    <?php
        foreach($tables as $row) {
            extract($row);
            echo "<tr id=\"row$rID\"><td>$season</td><td>$division</td><td>$team</td><td>$played</td><td>$won</td><td>$drawn</td><td>$lost</td><td>$goalsfor</td><td>$goalsagainst</td><td>$points</td></td><td><a href=\"javascript: void()\" onclick=\"deleteRecord($rID)\">delete</a>";
        }
    
    ?>
</table>
    
</div>

<script>
    var TWS_LEAGUE_DIALOG_URL = '<?php  echo Loader::helper('concrete/urls')->getToolsURL('new_record', 'tws_league_tables'); ?>';
    
    function deleteRecord(id) {
        if(confirm("Are you sure you want to delete this record?")) {
            $.post("<?php echo $this->action('delete_record') ?>", { id: id }, function(output) {
                if(output=="OK") {
                    $("#row"+id).slideUp(400).remove();
                } else {
                    alert(output);
                }
            });
        }
    }
    
    function addNew() {
        $.fn.dialog.open({
          title: 'Add new record',
          href: TWS_LEAGUE_DIALOG_URL,
          width: '300px',
          modal: true,
          height: '400px',
          buttons: {
                'Save': function() {
                    saveRecord();
                    $(this).dialog('close');
                },
                Cancel: function() {
                    $(this).dialog('close');
                }
            }
          //onClose: window.location.reload()
          })
    }
    
</script>