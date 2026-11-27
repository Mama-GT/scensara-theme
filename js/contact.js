/* Scensara contact form: client validation + AJAX to admin-ajax.php (nonce protected). */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('scensara-contact-form');
    if (!form || typeof scensaraContact === 'undefined') return;

    var nameInput = document.getElementById('contact-name');
    var emailInput = document.getElementById('contact-email');
    var topicInput = document.getElementById('contact-topic');
    var messageInput = document.getElementById('contact-message');
    var statusBox = document.getElementById('contact-status');
    var button = form.querySelector('button[type="submit"]');

    function setError(el, msg) {
      var err = document.getElementById(el.id + '-error');
      if (err) err.textContent = msg;
    }
    function clearErrors() {
      [nameInput, emailInput, messageInput].forEach(function (el) { setError(el, ''); });
      statusBox.className = 'form-status';
      statusBox.textContent = '';
    }
    function validEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
    function fail(msg) { statusBox.className = 'form-status error'; statusBox.textContent = msg; }

    // Cached pages can carry a stale nonce, so fetch a fresh one on load.
    var nonce = scensaraContact.nonce;
    fetch(scensaraContact.ajaxUrl + '?action=scensara_contact_nonce', { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (j) { if (j && j.success && j.data && j.data.nonce) nonce = j.data.nonce; })
      .catch(function () {});

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      clearErrors();

      var bad = false;
      if (nameInput.value.trim().length < 2) { setError(nameInput, 'Please enter your full name.'); bad = true; }
      if (!validEmail(emailInput.value.trim())) { setError(emailInput, 'Please enter a valid email address.'); bad = true; }
      if (messageInput.value.trim().length < 10) { setError(messageInput, 'Please include a message of at least 10 characters.'); bad = true; }
      if (bad) { fail('Please review the highlighted fields above before sending.'); return; }

      var data = new FormData(form);
      data.append('action', 'scensara_contact');
      data.append('nonce', nonce);

      button.disabled = true;
      var label = button.textContent;
      button.textContent = 'Sending…';

      fetch(scensaraContact.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (j) {
          if (j && j.success) {
            statusBox.className = 'form-status success';
            statusBox.textContent = j.data.message;
            form.reset();
          } else {
            var d = (j && j.data) || {};
            if (d.errors) {
              if (d.errors.name) setError(nameInput, d.errors.name);
              if (d.errors.email) setError(emailInput, d.errors.email);
              if (d.errors.message) setError(messageInput, d.errors.message);
            }
            fail(d.message || scensaraContact.failure);
          }
        })
        .catch(function () { fail(scensaraContact.failure); })
        .then(function () { button.disabled = false; button.textContent = label; });
    });
  });
})();
