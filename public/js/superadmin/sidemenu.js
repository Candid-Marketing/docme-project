/******/ (() => { // webpackBootstrap
/*!*********************************************!*\
  !*** ./resources/js/superadmin/sidemenu.js ***!
  \*********************************************/
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }
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

//Add user

document.getElementById('addUserForm').addEventListener('submit', function (e) {
  // Clear previous errors
  document.querySelectorAll('.error').forEach(function (el) {
    return el.textContent = '';
  });
  var formData = new FormData(this);
  $.ajax({
    url: '/superadmin/add-user',
    // Correct URL for the POST request
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function success(data) {
      if (data.success) {
        e.preventDefault();
        document.getElementById('addUserForm').reset();
        var modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
        modal.hide();
      } else if (data.errors) {
        console.log(data);
        // Display validation errors in the modal
        for (var _i = 0, _Object$entries = Object.entries(data.errors); _i < _Object$entries.length; _i++) {
          var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
            field = _Object$entries$_i[0],
            messages = _Object$entries$_i[1];
          var errorElement = document.getElementById("".concat(field, "_error"));
          if (errorElement) {
            errorElement.textContent = messages[0]; // Display first error message for the field
          }
        }
      }
    },
    error: function error(xhr, status, _error) {
      // Handle any errors
      console.error('AJAX Error:', _error);
    }
  });
});
/******/ })()
;