document.addEventListener('DOMContentLoaded', function(){
  const carousel = document.getElementById('news-carousel');
  if (!carousel) return;
  const slides = carousel.querySelector('.slides');
  const slideCount = slides.children.length;
  let idx = 0;
  const interval = parseInt(carousel.dataset.interval || '5000', 10);

  function go(n){
    idx = (n + slideCount) % slideCount;
    slides.style.transform = `translateX(${ -idx * 100 }%)`;
  }

  const prev = carousel.querySelector('.prev');
  const next = carousel.querySelector('.next');
  prev.addEventListener('click', ()=> go(idx-1));
  next.addEventListener('click', ()=> go(idx+1));

  let timer = setInterval(()=> go(idx+1), interval);
  carousel.addEventListener('mouseenter', ()=> clearInterval(timer));
  carousel.addEventListener('mouseleave', ()=> timer = setInterval(()=> go(idx+1), interval));
});
