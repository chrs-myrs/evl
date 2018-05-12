<div class="marquee">
     <nobr><?php echo $content?></nobr>
</div>

<script type="text/javascript">
    $("div.marquee").marquee('pointer').mouseover(function () {
        $(this).trigger('stop');
    }).mouseout(function () {
        $(this).trigger('start');
    }).mousemove(function (event) {
        if ($(this).data('drag') == true) {
            this.scrollLeft = $(this).data('scrollX') + ($(this).data('x') - event.clientX);
        }
	event.cancelBubble = true;
	if (event.stopPropagation) event.stopPropagation();
    }).mousedown(function (event) {
        $(this).data('drag', true).data('x', event.clientX).data('scrollX', this.scrollLeft);
        	event.cancelBubble = true;
	if (event.stopPropagation) event.stopPropagation();
    }).mouseup(function () {
        $(this).data('drag', false);
        	event.cancelBubble = true;
	if (event.stopPropagation) event.stopPropagation();
    });
</script>

