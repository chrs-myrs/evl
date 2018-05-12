function showTwitterAuthPIN() {
  $('#ccm-twitter-oauth-pin-label').show();
  $('#ccm-twitter-oauth-pin').show();
  $('#ccm-twitter-oauth-pin').enable();
}

$(function(){
  $("a.add-timeline-component").live('click', function(){
    createComponentRow();
    return false;
  });
  $("a.setting-remove").live('click',function(){
    $(this).parent().remove();
  });    
});

function createComponentRow() {
  $('#tweetcrete-component-row-template div.user-or-hash-wrapper').clone().attr('class', 'user-or-hash-wrapper').appendTo($('#append-components-anchor'));
}

