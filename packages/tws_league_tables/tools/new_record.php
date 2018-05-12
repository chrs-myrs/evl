<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$db = Loader::db();
$form = Loader::helper('form');
$ih = Loader::helper('concrete/interface');
        
$seasonquery = $db->execute("SELECT sID, name FROM twsSeasons ORDER BY sortorder ASC, name DESC");
$divisionquery = $db->execute("SELECT dID, name FROM twsDivisions ORDER BY sortorder ASC, name DESC");
$teamquery = $db->execute("SELECT tID, name FROM twsTeams ORDER BY sortorder ASC, name ASC");

$seasons = $seasonquery->getAssoc();
$divisions = $divisionquery->getAssoc();
$teams = $teamquery->getAssoc();

?>
<form id="aform" method="POST">
<table class="entry-form" border="0" cellspacing="1" cellpadding="0">
    <tr>
        <td align="right">
            <?php  echo $form->label('season', t('Season:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->select('season', $seasons, 0); ?></span>
        </td>
    </tr>
    <tr>
        <td align="right">
            <?php  echo $form->label('division', t('Division:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->select('division', $divisions, 0); ?></span>
        </td>
    </tr>
    <tr>
        <td align="right">
            <?php  echo $form->label('team', t('Team:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->select('team', $teams, 0); ?></span>
        </td>
    </tr>
    <tr>
        <td align="right">
            <?php  echo $form->label('played', t('Played:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->text('played', "", array('style' => 'width:50px')); ?></span>
        </td>
    <tr>
        <td align="right">
            <?php  echo $form->label('won', t('Won:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->text('won', "", array('style' => 'width:50px')); ?></span>
        </td>
    </tr>
    <tr>
        <td align="right">
            <?php  echo $form->label('drawn', t('Drawn:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->text('drawn', "", array('style' => 'width:50px')); ?></span>
        </td>
    </tr>
    <tr>
        <td align="right">
            <?php  echo $form->label('lost', t('Lost:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->text('lost', "", array('style' => 'width:50px')); ?></span>
        </td>
    </tr>
    <tr>
        <td align="right">
            <?php  echo $form->label('goalsfor', t('Goals For:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->text('goalsfor', "", array('style' => 'width:50px')); ?></span>
        </td>
    </tr>
    <tr>
        <td align="right">
            <?php  echo $form->label('goalsagainst', t('Goals Against:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->text('goalsagainst', "", array('style' => 'width:50px')); ?></span>
        </td>
    </tr>
    <tr>
        <td align="right">
            <?php  echo $form->label('points', t('Points:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->text('points', "", array('style' => 'width:50px')); ?></span>
        </td>
    </tr>
</table>

<?php
    echo $form->submit("Save", "save");
    ?>
</form>