<p>Enter the marquee text below.</p>


<?php echo $form->label('content', 'Text');?>
<?php echo $form->text('content', $content, array('style' => 'width: 320px'));?>