/**
 * ระบบขอเปิดหมู่เรียนพิเศษ — Global JavaScript
 * HCI: Real-time Feedback, Error Prevention
 */

'use strict';

// ─── Bootstrap Form Validation ────────────────────────────────────────────────
(function () {
    const forms = document.querySelectorAll('form.needs-validation');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });

        // Real-time field-level feedback on blur
        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            field.addEventListener('blur', function () {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
        });
    });
})();

// ─── Character counter for reason textarea ────────────────────────────────────
(function () {
    const reason  = document.getElementById('reason');
    const counter = document.getElementById('reason-counter');
    if (!reason || !counter) return;

    const MIN_CHARS = 20;

    function update() {
        const len = reason.value.length;
        counter.textContent = len;
        counter.style.color = len < MIN_CHARS ? '#dc3545' : '#198754';
    }

    reason.addEventListener('input', update);
    update();
})();

// ─── Course code lookup (API: api/check_course.php) ───────────────────────────
(function () {
    const input = document.getElementById('course_code');
    const box   = document.getElementById('course-feedback');
    if (!input || !box) return;

    let timer = null;

    function reset() {
        box.textContent = '';
        box.className = 'form-text';
    }

    function lookup() {
        const code = input.value.trim();
        if (code === '') {
            reset();
            return;
        }

        fetch('api/check_course.php?code=' + encodeURIComponent(code), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (res) {
                return res.ok ? res.json() : Promise.reject(res.status);
            })
            .then(function (data) {
                if (!data.found) {
                    box.textContent = 'ไม่พบรหัสวิชานี้ในระบบ';
                    box.className = 'form-text text-danger';
                    return;
                }
                box.textContent = data.course_name_th + ' (' + data.credits + ')';
                box.className = 'form-text text-success';
            })
            .catch(reset);
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(lookup, 350);
    });

    if (input.value.trim() !== '') lookup();
})();

// ─── Auto-dismiss flash messages ──────────────────────────────────────────────
(function () {
    document.querySelectorAll('.alert-dismissible[data-auto-dismiss]').forEach(function (el) {
        const delay = parseInt(el.dataset.autoDismiss, 10) || 4000;
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            bsAlert.close();
        }, delay);
    });
})();
