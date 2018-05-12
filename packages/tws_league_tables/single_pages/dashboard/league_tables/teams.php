<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$db = Loader::db();
$form = Loader::helper('form');
$ih = Loader::helper('concrete/interface');

$teamquery = $db->execute("SELECT twsTeams.*, t2.name as GroupName FROM twsTeams LEFT JOIN twsTeams t2 ON twsTeams.groupID = t2.tID ORDER BY sortorder ASC, name ASC");
$teams = array('0'=>"No Grouping");
?>
<style>
    #team-sort li {
        background-color: #f9f7f3; /* beige */
        margin: 5px;
        padding: 5px;
        font-weight: bold;
        width: 450px;
        list-style: none;
        cursor: move;
    }
    
    #team-sort a.del-button, #team-sort a.edit-button {
        display: inline;
        cursor: pointer;
        float: right;
        margin: 0 3px 2px 0;
        padding: 0 3px;
    }
    
    #team-sort a.del-button:hover, #team-sort a.edit-button:hover {
        text-decoration: underline;
    }
    
</style>

<h1><span><?php echo t('Teams')?></span></h1>
<div class="ccm-dashboard-inner ccm-ui">
    Drag and drop the blocks below to change the sorting order for teams.    <?php
        echo $ih->button_js("Add New", 'javascript: newTeam()');
    ?>
        <div class="ccm-spacer"></div>
    <ul id="team-sort">
    <?php
        foreach($teamquery as $t) {
            echo '<li id="'.$t['tID'].'" groupID="'.$t['groupID'].'"><span class="name">'.$t['name'].'</span>'.
                ($t['groupID'] ? '<br>&nbsp;&nbsp;&nbsp;-> '.$t['GroupName'] : '').
                '<a class="del-button">delete</a>
                <a class="edit-button">edit</a>
                
                </li>';
            $teams[$t['tID']] = $t['name'];
        }
    ?>
        <div class="ccm-spacer"></div>

</div>

<div id="edit-dialog" class="ccm-ui" style="display:none;">
    <form method="post" name="edit-team-form" action="<?php echo $this->action('edit_team') ?>">
    <?php echo $form->hidden('tID') ?>
        <table class="tiger-striped" method="post" border="0" cellspacing="1" cellpadding="0">
            <tr>
                <td align="right">
                    <?php  echo $form->label('name', t('Name:')); ?>
                </td>
                <td align="left">
                    <span class="optionField"><?php  echo $form->text('name', "", array('style' => 'width:300px', 'size' => '30')); ?></span>
                </td>
            <tr>
            <tr>
                <td align="right">
                    <?php  echo $form->label('sortteam', t('Team Grouping:')); ?>
                </td>
                <td align="left">
                    <span class="optionField"><?php  echo $form->select('sortteam', $teams, 0); ?></span>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
$(function() {
    $('#team-sort').sortable({
        update: function() { 
            order = $(this).sortable('toArray').toString();
            $.post("<?php echo $this->action('save_sort') ?>", {order: order})
       }
    });
    $('.del-button').click(function() {
        $item = $(this).parent('li');
        sID = $item.attr('id');
        name = $item.clone().children().remove().end().text().trim();
        if(confirm("Are you sure you want to delete team '"+name+"'?")) {
            $.post("<?php echo $this->action('delete_team') ?>", {id: sID}, function(output) {
                if(output=="OK") {
                    $item.slideUp(400).remove();
                } else {
                    alert (output);
                }
            })
        }
    });
    $('.edit-button').click(function() {
        $dialog = $('#edit-dialog');
        $item = $(this).parent('li');
        tID = $item.attr('id');
        gID = $item.attr('groupID');
        name = $item.find('.name').text();
        
        $dialog.find("input[name='tID']").val(tID);
        $dialog.find("input[name='name']").val(name);
        $dialog.find("select[name='sortteam']").val(gID);
        
        $dialog.dialog({
            title: 'Edit Team',
            width: '400px',
            modal: true,
            //height: '360px',
            buttons: {
                Cancel: function() {
                    $(this).dialog('close');
                },
                'Save': function() {
                    document.forms['edit-team-form'].submit();
                }
            }
        });
    });
});

function newTeam() {
        newTeam = prompt("Enter Team Name");
            $.post("<?php echo $this->action('new_team') ?>", {name: newTeam},  function(output) {
                if(output.length > 0) alert("Error: "+output);
                else window.location.reload();
            });
}
</script>