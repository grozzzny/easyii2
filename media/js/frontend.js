$(function(){
    $('.easyii2cms-edit').each(function(i, element){
        var $this = $(element);
        $this.append('<a href="'+$this.data('edit')+'" class="easyii2cms-goedit" style="width: '+$this.width()+'px; height: '+$this.height()+'px;" target="_blank"></a>');
    });
    $('#easyii2-navbar input').switcher({copy: {en: {yes: '', no: ''}}}).on('change', function(){
        var checkbox = $(this);
        checkbox.switcher('setDisabled', true);
        location.href = checkbox.attr('data-link') + '/' + (checkbox.is(':checked') ? 1 : 0);
    });;
});