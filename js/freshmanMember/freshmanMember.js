$(document).mousemove(function(e){
    var amountMovedX = (e.pageX * -1 / 20);
    var amountMovedY = (e.pageY * -1 / 20);
    $('.member-bg').css({'left': amountMovedX + 'px','top':amountMovedY + 'px'});
});
