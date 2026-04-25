AOS.init({duration:800, once:true});
const lb = GLightbox({selector:'.glightbox'});
$('.news-slider').slick?.({autoplay:true, arrows:false, dots:true});
document.querySelectorAll('.theme-btn').forEach(btn=>btn.addEventListener('click',()=>{
  document.cookie = `theme=${btn.dataset.theme};path=/;max-age=31536000`;
  location.reload();
}));
