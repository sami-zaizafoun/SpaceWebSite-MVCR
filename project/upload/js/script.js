window.onscroll = function() {
  scrollFunction()
};

window.onload = function () {
    document.getElementById("default").click();
};

/**
 * Displacy button after scrolling 20px
 */
function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}
  /**
   * Go back on top of page
   */
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

/**
 * Shuffle between admin tabs
 * @param  {event} evt
 * @param  {string}  tabFunction tabName
 */

function openTab(evt, tabFunction) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabFunction).style.display = "block";
  evt.currentTarget.className += " active";
}
