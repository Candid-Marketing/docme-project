/******/ (() => { // webpackBootstrap
/*!*******************************!*\
  !*** ./resources/js/login.js ***!
  \*******************************/
var container = document.querySelector('.container');
var registerBtn = document.querySelector('.register-btn');
var loginBtn = document.querySelector('.login-btn');
registerBtn.addEventListener('click', function () {
  container.classList.add('active');
});
loginBtn.addEventListener('click', function () {
  container.classList.remove('active');
});
/******/ })()
;