(function () {
    'use strict';

    function dismissAlert(alert) {
        if (!alert || alert.classList.contains('site-alert--leaving')) {
            return;
        }

        alert.classList.add('site-alert--leaving');
        window.setTimeout(function () {
            alert.remove();
        }, 220);
    }

    window.showAlert = function (message, type, duration) {
        var allowed = ['success', 'error', 'warning', 'info'];
        type = allowed.indexOf(type) >= 0 ? type : 'info';
        duration = Number(duration) || 6000;

        var container = document.querySelector('.site-alert-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'site-alert-container';
            container.setAttribute('aria-label', 'Notifications');
            document.body.appendChild(container);
        }

        var titles = {
            success: 'Success',
            error: 'Something went wrong',
            warning: 'Please check',
            info: 'Notice'
        };
        var alert = document.createElement('div');
        alert.className = 'site-alert site-alert--' + type;
        alert.setAttribute('role', type === 'error' ? 'alert' : 'status');
        alert.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');
        alert.innerHTML =
            '<div class="site-alert__icon" aria-hidden="true"></div>' +
            '<div class="site-alert__content"><strong></strong><span></span></div>' +
            '<button class="site-alert__close" type="button" aria-label="Dismiss notification">&times;</button>';
        alert.querySelector('strong').textContent = titles[type];
        alert.querySelector('span').textContent = String(message || '');
        container.appendChild(alert);

        window.setTimeout(function () {
            dismissAlert(alert);
        }, duration);
    };

    function initializeAlerts() {
        document.querySelectorAll('.site-alert').forEach(function (alert) {
            var duration = Number(alert.getAttribute('data-alert-duration')) || 6000;
            window.setTimeout(function () {
                dismissAlert(alert);
            }, duration);
        });

        document.addEventListener('click', function (event) {
            var closeButton = event.target.closest('.site-alert__close');
            if (closeButton) {
                dismissAlert(closeButton.closest('.site-alert'));
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAlerts);
    } else {
        initializeAlerts();
    }
})();
