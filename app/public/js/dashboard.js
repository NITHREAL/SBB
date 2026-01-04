/******/ (() => { // webpackBootstrap
/*!***********************************!*\
  !*** ./resources/js/dashboard.js ***!
  \***********************************/
document.addEventListener("DOMContentLoaded", function (event) {
  if (window.location.pathname !== '/admin/login') {
    messagesCounter();
  }
});
setInterval(function () {
  if (window.location.pathname !== '/admin/login') {
    messagesCounter();
  }
}, 10000);
var messagesCounter = function messagesCounter() {
  var elem = document.querySelector('.support-menu');
  if (!elem) return;
  var counter = document.getElementById('support-menu-counter');
  if (!counter) {
    counter = document.createElement('span');
    counter.setAttribute("id", "support-menu-counter");
    elem.append(counter);
  }
  var ajax = new XMLHttpRequest();
  ajax.onreadystatechange = function () {
    if (ajax.readyState === XMLHttpRequest.DONE) {
      if (ajax.status === 200) {
        var data = JSON.parse(ajax.responseText);
        if (data.count > 0) {
          counter.textContent = data.count;
          counter.style.display = 'inline';
        } else {
          counter.style.display = 'none';
        }
      } else {
        counter.textContent = 'неизвестно';
        counter.style.display = 'inline';
      }
    }
  };
  ajax.open("GET", "/admin/support/messages/unread-count", true);
  ajax.send();
};
function setupNotificationRecipientType() {
  var notificationSelect = document.querySelector('select[name="notification_recipient_type"]');
  var audienceRelation = document.querySelector('[id="audience-relation"]');
  var userRelation = document.querySelector('[id="user-relation"]');
  var usersRelation = document.querySelector('[id="users-relation"]');
  var updateVisibility = function updateVisibility() {
    var recipientType = notificationSelect.value;
    if (audienceRelation) audienceRelation.closest('.form-group').style.display = recipientType === 'audience' ? 'block' : 'none';
    if (userRelation) userRelation.closest('.form-group').style.display = recipientType === 'personalized' ? 'block' : 'none';
    if (usersRelation) usersRelation.closest('.form-group').style.display = recipientType === 'custom' ? 'block' : 'none';
  };
  if (notificationSelect) {
    updateVisibility();
    notificationSelect.addEventListener('change', updateVisibility);
  }
}
document.addEventListener('DOMContentLoaded', function (event) {
  if (window.location.pathname === '/admin/mass-notifications') {
    setupNotificationRecipientType();
  }
});
document.addEventListener('turbo:load', function (event) {
  if (window.location.pathname === '/admin/mass-notifications') {
    setupNotificationRecipientType();
  }
});
/******/ })()
;