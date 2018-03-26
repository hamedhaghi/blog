$("#name1").on('keyup',function(e){
    $("#slug").val(slugify($(this).val()));
});