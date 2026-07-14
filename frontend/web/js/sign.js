(function () {
    const modal = document.getElementById('ClientSign');
    const canvas = document.getElementById('sig-canvas');

    const sigText = document.getElementById('sig-dataUrl');
    const sigImage = document.getElementById('sig-image');
    const clearBtn = document.getElementById('sig-clearBtn');
    const submitBtn = document.getElementById('sig-submitBtn');

    if (!canvas) {
        return;
    }

    let ctx = canvas.getContext('2d');
    let drawing = false;
    let activePointerId = null;
    let devicePixelRatio = 1;
    let resizeTimer = null;

    /**
     * Настройки линии необходимо восстанавливать
     * после каждого изменения размера Canvas.
     */
    function configureContext() {
        ctx.strokeStyle = '#222222';
        ctx.lineWidth = 4;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
    }

    /**
     * Делает Canvas адаптивным и чётким на Retina-экранах.
     *
     * @param {boolean} preserveSignature Сохранять ли подпись при resize.
     */
    function resizeCanvas(preserveSignature = true) {
        const rect = canvas.getBoundingClientRect();
        const cssWidth = Math.floor(rect.width);
        const cssHeight = Math.floor(rect.height);

        /*
         * Пока Bootstrap-модалка скрыта,
         * её размеры могут быть равны нулю.
         */
        if (!cssWidth || !cssHeight) {
            return;
        }

        let snapshot = null;

        if (preserveSignature && canvas.width && canvas.height) {
            snapshot = document.createElement('canvas');
            snapshot.width = canvas.width;
            snapshot.height = canvas.height;

            snapshot
                .getContext('2d')
                .drawImage(canvas, 0, 0);
        }

        devicePixelRatio = Math.max(window.devicePixelRatio || 1, 1);

        /*
         * CSS-размер остаётся адаптивным,
         * физическое разрешение увеличивается для Retina.
         */
        canvas.width = Math.round(cssWidth * devicePixelRatio);
        canvas.height = Math.round(cssHeight * devicePixelRatio);

        ctx = canvas.getContext('2d');

        /*
         * После этого можно работать в обычных CSS-пикселях,
         * не умножая вручную каждую координату на DPR.
         */
        ctx.setTransform(
            devicePixelRatio,
            0,
            0,
            devicePixelRatio,
            0,
            0
        );

        configureContext();

        if (snapshot) {
            ctx.drawImage(
                snapshot,
                0,
                0,
                snapshot.width,
                snapshot.height,
                0,
                0,
                cssWidth,
                cssHeight
            );
        }
    }

    function getPointerPosition(event) {
        const rect = canvas.getBoundingClientRect();

        return {
            x: event.clientX - rect.left,
            y: event.clientY - rect.top
        };
    }

    function startDrawing(event) {
        /*
         * Для мыши разрешаем рисовать только левой кнопкой.
         * Палец и стилус проходят без этой проверки.
         */
        if (event.pointerType === 'mouse' && event.button !== 0) {
            return;
        }

        event.preventDefault();

        drawing = true;
        activePointerId = event.pointerId;

        canvas.setPointerCapture(event.pointerId);

        const position = getPointerPosition(event);

        ctx.beginPath();
        ctx.moveTo(position.x, position.y);
    }

    function draw(event) {
        if (!drawing || event.pointerId !== activePointerId) {
            return;
        }

        event.preventDefault();

        const position = getPointerPosition(event);

        ctx.lineTo(position.x, position.y);
        ctx.stroke();
    }

    function stopDrawing(event) {
        if (
            !drawing ||
            event.pointerId !== activePointerId
        ) {
            return;
        }

        event.preventDefault();

        drawing = false;
        activePointerId = null;

        ctx.closePath();

        if (canvas.hasPointerCapture(event.pointerId)) {
            canvas.releasePointerCapture(event.pointerId);
        }
    }

    function clearCanvas() {
        const rect = canvas.getBoundingClientRect();

        ctx.clearRect(
            0,
            0,
            rect.width,
            rect.height
        );

        sigText.value = '';

        sigImage.removeAttribute('src');
        sigImage.hidden = true;
    }

    canvas.addEventListener('pointerdown', startDrawing);
    canvas.addEventListener('pointermove', draw);
    canvas.addEventListener('pointerup', stopDrawing);
    canvas.addEventListener('pointercancel', stopDrawing);

    /*
     * Убираем контекстное меню при долгом нажатии.
     */
    canvas.addEventListener('contextmenu', function (event) {
        event.preventDefault();
    });

    clearBtn.addEventListener('click', function () {
        clearCanvas();
    });

    submitBtn.addEventListener('click', function () {
        const dataUrl = canvas.toDataURL('image/png');

        /*
         * Для textarea нужно использовать value,
         * а не innerHTML.
         */
        sigText.value = dataUrl;

        sigImage.src = dataUrl;
        sigImage.hidden = false;
    });

    /*
     * Canvas нужно инициализировать после открытия модалки,
     * потому что у скрытой Bootstrap-модалки ширина равна нулю.
     */
    $('#ClientSign').on('shown.bs.modal', function () {
        window.requestAnimationFrame(function () {
            resizeCanvas(false);
        });
    });

    /*
     * При смене ориентации или размера экрана
     * Canvas изменится, но подпись сохранится.
     */
    window.addEventListener('resize', function () {
        if (!modal.classList.contains('show')) {
            return;
        }

        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(function () {
            resizeCanvas(true);
        }, 150);
    });
})();