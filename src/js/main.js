import './header.js';
$(document).ready(function() {
    // not save img
    // $('img').on('contextmenu', function(e) {
    //     e.preventDefault();
    // });

    const $eye = $(".vision");
    const $body = $(".body");

    $eye.on("click", function() {
        $body.toggleClass("poor-eyesight");
    });

});

