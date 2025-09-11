/******/ (() => { // webpackBootstrap
/*!****************************************!*\
  !*** ./resources/js/admin/sidemenu.js ***!
  \****************************************/
document.addEventListener("DOMContentLoaded", function () {
  // Your existing JavaScript code

  // Menu Hover effect
  var list = document.querySelectorAll(".navigation li");
  function activeLink() {
    list.forEach(function (item) {
      item.classList.remove("hovered");
    });
    this.classList.add("hovered");
  }
  list.forEach(function (item) {
    return item.addEventListener("mouseover", activeLink);
  });

  // Menu Toggle functionality
  var toggle = document.querySelector(".toggle");
  var navigation = document.querySelector(".navigation");
  var main = document.querySelector(".main");
  toggle.onclick = function () {
    navigation.classList.toggle("active");
    main.classList.toggle("active");
  };
});
/******/ })()
;