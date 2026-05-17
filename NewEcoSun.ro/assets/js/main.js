async function loadPartials() {

  const header = await fetch('partials/header.html');
  const headerData = await header.text();

  const footer = await fetch('partials/footer.html');
  const footerData = await footer.text();

  document.getElementById('header').innerHTML = headerData;
  document.getElementById('footer').innerHTML = footerData;

  // IMPORTANT: trebuie apelate DUPĂ ce header-ul e injectat
  initMobileMenu();
  initNavbarScroll();
}

/* =========================
   MOBILE MENU (mobileToggle + navbar)
========================= */

function initMobileMenu(){

  const mobileToggle = document.getElementById('mobileToggle');
  const navbar = document.getElementById('navbar');

  if(!mobileToggle || !navbar) return;

  // toggle open/close
  mobileToggle.addEventListener('click', () => {

    mobileToggle.classList.toggle('active');
    navbar.classList.toggle('active');
    document.body.classList.toggle('nav-open');

  });

  // close on link click
  navbar.querySelectorAll('a').forEach(a => {

    a.addEventListener('click', () => {

      mobileToggle.classList.remove('active');
      navbar.classList.remove('active');
      document.body.classList.remove('nav-open');

    });

  });

}

/* =========================
   SCROLL HEADER EFFECT
========================= */

function initNavbarScroll(){

  const header = document.querySelector('.main-header');

  if(!header) return;

  window.addEventListener('scroll', () => {

    if(window.scrollY > 40){
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }

  });

}

/* =========================
   REVEAL ANIMATION
========================= */

function initReveal(){

  const reveals = document.querySelectorAll('.reveal');

  const observer = new IntersectionObserver((entries) => {

    entries.forEach(entry => {

      if(entry.isIntersecting){
        entry.target.classList.add('active');
      }

    });

  }, {
    threshold: 0.15
  });

  reveals.forEach(el => observer.observe(el));

}

/* =========================
   INIT APP
========================= */

document.addEventListener('DOMContentLoaded', async () => {

  await loadPartials();

  initReveal();

});