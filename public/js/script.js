$(document).ready(function() {
    $(document).on('click', '.tab-search-item', function(){
        var $o = $($(this).attr('data-id'))
        $('.tab,.tab-search-item').removeClass('active')
        $(this).addClass('active')
        $o.addClass('active')
    });
    $(document).on('click', '.tab-hiw-item', function(){
        var $o = $($(this).attr('data-id'))
        $('.hiw,.tab-hiw-item').removeClass('active')
        $(this).addClass('active')
        $o.addClass('active')
    });
    $(document).on('click', '._dropdown-toggle', function(e){
        e.stopPropagation()
        e.preventDefault()
        var $o = $(this).parent();
        var $bool = $o.hasClass('active')
        $(document).click()
        if(!$bool) $o.addClass('active')
    });
    $(document).on('click', '.show-password', function(){
        var $o = $($(this).attr('data-id'));
        if($o.attr('type') == 'text')
            $o.attr('type', 'password')
        else
            $o.attr('type', 'text')
    });
    $(document).on("click",'.notice',function(){
        $(this).addClass('hidden-notice');
    });
    $(document).on('click', function(){
        $('._dropdown').removeClass('active')
    })
    $(document).on('click', '.faq-open', function(e){
        e.preventDefault();
        e.stopPropagation();
        var $o = $(this);
        var $m = $('.faq-modal');
        $m.find('.faq-head').html($o.attr('data-head'));
        $m.find('.faq-body').html($o.attr('data-body'));
        $m.find('.faq-foot').html('Mis à jour: ' + getDateFormat($o.attr('data-updated-at')));
        $m.addClass('active');
    });
    $(document).on('click', function(){
        var $m = $('.faq-modal');
        $m.find('.faq-head').html("");
        $m.find('.faq-body').html("");
        $m.find('.faq-foot').html("");
        $m.removeClass('active');
    });
    $(document).on('click', '.faq-container', function(e){
        e.stopPropagation();
    });

    function getDateFormat(date){
        var day = (new Date(date)).getDay()
        var dateTemp = [ (String(date)).split('T')[0].split('-'), (String(date)).split('T')[1].split(':') ]
        var days = ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.']
        var months = ['jav.', 'fév.', 'mar.', 'avr.', 'mai', 'juin', 'juil.', 'aoû.', 'sept.', 'oct.', 'nov.', 'déc.']
        return days[day] + " " + dateTemp[0][2] + " " + months[parseInt(dateTemp[0][1]) - 1] + " " + dateTemp[0][0] + " à " + dateTemp[1][0] + "h" + dateTemp[1][1]
    }
});
