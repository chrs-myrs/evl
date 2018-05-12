<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$db = Loader::db();
$form = Loader::helper('form');
$ih = Loader::helper('concrete/interface');
        
$divisionquery = $db->execute("SELECT * FROM twsDivisions ORDER BY sortorder ASC, name DESC");

?>
<style>
    #division-sort li {
        background-color: #f9f7f3; /* beige */
        margin: 5px;
        padding: 5px;
        font-weight: bold;
        width: 200px;
        list-style: none;
        cursor: move;
    }
    
    #division-sort .del-button {
        display: inline;
        cursor: pointer;
        float: right;
        margin: 0 3px 2px 0;
        padding: 0 3px;
    }
</style>

<h1><span><?php echo t('Divisions')?></span></h1>
<div class="ccm-dashboard-inner">
    Drag and drop the blocks below to change the sorting order for divisions.
    <ul id="division-sort">
    <?php
        foreach($divisionquery as $s) {
            echo '<li id="'.$s['dID'].'">'.$s['name'].'
                <a class="del-button">x</a>
                </li>';
        }
    ?>
        <div class="ccm-spacer"></div>
    <?php
        echo $ih->button_js("Add New", 'javascript: newDivision()');
    ?>
        <div class="ccm-spacer"></div>
</div>

<script>
$(function() {
    $('#division-sort').sortable({
        update: function() { 
            order = $(this).sortable('toArray').toString();
            $.post("<?php echo $this->action('save_sort') ?>", {order: order})
       }
    });
    $('.del-button').click(function() {
        $item = $(this).parent('li');
        sID = $item.attr('id');
        name = $item.clone().children().remove().end().text().trim();
        if(confirm("Are you sure you want to delete division '"+name+"'?")) {
            $.post("<?php echo $this->action('delete_division') ?>", {id: sID}, function(output) {
                if(output=="OK") {
                    $item.remove();
                } else {
                    alert (output);
                }
            })
        }
    })
});

function newDivision() {
        newDivision = prompt("Enter Division Name");
            $.post("<?php echo $this->action('new_division') ?>", {name: newDivision}, function(output) {
                if(output.length > 0) alert("Error: "+output);
                else window.location.reload();
            });
}
</script>