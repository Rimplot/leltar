$(function() {
    $("#success-alert").delay(5000).fadeTo(1000, 0).slideUp(300);
    
    $("#success-alert>button").click(function() {
        $("#success-alert").clearQueue().slideUp(300);
    });
});
