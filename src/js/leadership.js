document.querySelectorAll('.leaders__block__btn').forEach(button => {
    button.addEventListener('click', function () {
        const block = this.closest('.leaders__block');
        const infoBlocks = block.querySelectorAll('.leaders__block__info');

        infoBlocks.forEach(info => {
            if (info.classList.contains('info-hidden')) {
                info.classList.remove('info-hidden');
                info.classList.add('info-show');
                this.querySelector('.leaders__block__btn-span').textContent = "Закрити";
                this.classList.add('open');
            } else {
                info.classList.remove('info-show');
                info.classList.add('info-hidden');
                this.querySelector('.leaders__block__btn-span').textContent = "Детальніше";
                this.classList.remove('open');
            }
        });
    });
});