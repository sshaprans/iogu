import './main';



$(document).ready(function() {

    const $modal = $("#modal");
    const $modalBtn = $(".btn-js");
    const $closeBtn = $(".modal-close").first();
    const $overlay = $(".overlay");

    function preventScroll() {
        const scrollTop = $(window).scrollTop();
        $("body")
            .css({
                position: "fixed",
                top: `-${scrollTop}px`
            })
            .data("scroll-position", scrollTop);
    }

    function restoreScroll() {
        const scrollPosition = $("body").data("scroll-position") || 0;
        $("body").css({
            position: "",
            top: ""
        });
        $(window).scrollTop(scrollPosition);
    }

    $modalBtn.on("click", function(e) {
        e.preventDefault();
        $overlay.addClass("open");
        $modal.addClass("open modal-an");
        preventScroll();
    });

    $closeBtn.on("click", function(e) {
        e.preventDefault();
        $overlay.removeClass("open");
        $modal.removeClass("open");
        restoreScroll();
    });

    $(window).on("click", function(e) {
        if (e.target === $overlay[0]) {
            $overlay.removeClass("open");
            $modal.removeClass("open");
            restoreScroll();
        }
    });

    // form submit to telegram
    $("#form--modal").on("submit", async function(e) {
        e.preventDefault();

        const name = $("#name").val();
        const phone = $("#phone").val();

        const botToken = '7280900385:AAGGn9OzV3-g8fm9mHZzOw3KOyurTFZXZA4';
        const chatId = '476091403';
        const message = `📩 Новий запит з форми:\n\n👤 Ім'я: ${name}\n📞 Телефон: ${phone}`;

        try {
            const response = await fetch(`https://api.telegram.org/bot${botToken}/sendMessage`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ chat_id: chatId, text: message, parse_mode: 'HTML' }),
            });

            if (response.ok) {
                $("#response-message").show();
                $("#form--modal")[0].reset();
            } else {
                alert('Спробуйте ще раз.');
            }
        } catch (error) {
            alert('Помилка з’єднання з сервером.');
        }
    });
});




