(function () {
    'use strict';

    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-primary-nav]');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            const open = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            document.body.classList.toggle('is-nav-open', open);
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && nav.classList.contains('is-open')) {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('is-nav-open');
                toggle.focus();
            }
        });
    }

    document.querySelectorAll('[data-dismiss-alert]').forEach(function (button) {
        button.addEventListener('click', function () {
            button.closest('[role="alert"], [role="status"]').remove();
        });
    });

    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            const submit = form.querySelector('[type="submit"]');
            if (submit && !submit.disabled) {
                submit.classList.add('is-loading');
                submit.setAttribute('aria-busy', 'true');
            }
        });
    });

    const scrollButton = document.getElementById('scroll-to-top');
    if (scrollButton) {
        const update = function () {
            scrollButton.classList.toggle('is-visible', window.scrollY > 320);
        };
        window.addEventListener('scroll', update, {passive: true});
        scrollButton.addEventListener('click', function () {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
        update();
    }
}());
