function acceptCookies(){
  localStorage.setItem("cookies","accepted");
  document.getElementById("cookie-banner").style.display="none";
}

function rejectCookies(){
  localStorage.setItem("cookies","rejected");
  document.getElementById("cookie-banner").style.display="none";
}

window.addEventListener("load", () => {
  if(localStorage.getItem("cookies")){
    const banner = document.getElementById("cookie-banner");
    if(banner) banner.style.display="none";
  }
});