var messages = function() {
    this.hideErrors = function(obj) {
        obj.find('span').removeClass('error').hide('blind').empty();
        obj.find('input').removeClass('error');
    };

    this.showErrors = function(id, error) {
        var obj = $('#'+id);
        if(!obj.hasClass('error')){
            obj.addClass('error');
        }
        obj.parent().find('span').addClass('error').attr('style', 'display:none').html(error).show('blind');
    };
}

//tags input
$(document).ready(
    function(){
        $('#tags').tagsInput();

        $('.ebook').hide();
    }
);