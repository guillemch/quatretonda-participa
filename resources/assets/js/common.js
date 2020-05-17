import SmoothScroll from 'smooth-scroll';
const scroll = new SmoothScroll('a[data-scroll]', { header: '.navbar' });

// Toggle Bootstrap menu without jQuery
let toggler = document.getElementsByClassName('navbar-toggler')[0],
    collapse = document.getElementsByClassName('navbar-collapse')[0];

function toggleMenu () {
  collapse.classList.toggle('collapse');
  collapse.classList.toggle('in');
}

function closeMenusOnResize () {
  if (document.body.clientWidth >= 768) {
    collapse.classList.add('collapse');
    collapse.classList.remove('in');
  }
}

window.addEventListener('resize', closeMenusOnResize, false);
toggler.addEventListener('click', toggleMenu, false);
