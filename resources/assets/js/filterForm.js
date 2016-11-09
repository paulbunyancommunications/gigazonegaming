
// Do toForm action. There should be a clickable element with an id that matches a link or form to submit
$('.toForm').click(function(e){
    var button = $(this);
    var buttonId = button.attr('id');
    var buttonIdParts = buttonId.split('-');
    var actionable = $('#' + buttonIdParts[0] + '-' + buttonIdParts[1] + '-' + buttonIdParts[2] + '-form-' + buttonIdParts[3]);
    if (actionable.length === 1 && actionable.is('a')) {
        return document.location.replace(actionable.attr('href'));
    } else if (actionable.length === 1 && actionable.is('form')) {
        return actionable.submit();
    }
})