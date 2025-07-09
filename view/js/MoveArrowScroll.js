
const arrow = document.getElementById('scroll-arrow');
const container = document.getElementById('menu-dashboard');
let scrollDown = true; 

/**
 * Scrolls the given container element vertically by 100 pixels with smooth behavior.
 *
 * @param {HTMLElement} container - The container element to scroll.
 */
const arrowMove = (container) => {
     const currentScroll = container.scrollTop;
    /*arrow.style = currentScroll <= 100 ? 'visibility: hidden;' :'visibility: visible;';*/

    container.scrollBy({
      top: scrollDown ? 100 : -100,
      behavior: 'smooth'
    });

    scrollDown = !scrollDown; // Invertir dirección para el próximo click
}


arrow.addEventListener("click", () => arrowMove(container));