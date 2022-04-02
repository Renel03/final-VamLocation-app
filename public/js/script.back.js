$(document).ready(function() {
    $(document).on('click', '._dropdown-toggle', function(e){
        e.stopPropagation()
        e.preventDefault()
        var $o = $(this).parent();
        var $bool = $o.hasClass('active')
        $(document).click()
        if(!$bool) $o.addClass('active')
    });
    $(document).on('click', function(){
        $('._dropdown').removeClass('active')
    })
});
