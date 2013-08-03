function showErrors(id, error) {
    var obj = $('#'+id);
    if(!obj.hasClass('error')){
        obj.addClass('error');
    }
    obj.parent().find('span').html(error);
}

//tags input
$(document).ready(
    function(){
        $('#tags').tagsInput();

        $('.ebook').hide();
    }
);