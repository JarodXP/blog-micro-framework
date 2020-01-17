$("#adminToggleBtn").click(function(e) {
    e.preventDefault();
    $("#sidebar-nav").toggleClass("hidden");
    $("#page-content-wrapper").toggleClass("col-md-10 offset-md-2 hidden");
});

$("#frontToggleBtn").click(function(e) {
    e.preventDefault();
    $("#front-sidebar").toggleClass("hidden");
    $("#frontToggleBtn").toggleClass("hidden");
});
