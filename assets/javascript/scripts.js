$(function() {
    $(".alert-success").each(function(index) {
        $(this).delay(5000).fadeTo(1000, 0).slideUp(300);
    });
    
    $(".alert>button").click(function() {
        $(this).parent().clearQueue().slideUp(300);
    });
});
