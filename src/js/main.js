import './header.js';
$(document).ready(function() {

    const $eye = $(".vision");
    const $body = $(".body");

    $eye.on("click", function() {
        $body.toggleClass("poor-eyesight");
    });

});

