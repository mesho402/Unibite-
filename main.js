const navLinks = document.getElementById("navLinks");
const homeSection = document.getElementById("homeSection");
const mainContent = document.getElementById("mainContent");
const productsContainer = document.getElementById("productsContainer");
const categories = document.querySelectorAll(".category");
const menuToggle = document.getElementById("menuToggle");

const isLoggedIn = localStorage.getItem("loggedIn");

if (isLoggedIn !== "true") {
  window.location.href = "getstarted.html";
} else {
  homeSection.style.display = "block";
}



let productsData = {};

fetch("js/get_products.php")
  .then(res => res.json())
  .then(data => {
    productsData = data;
    console.log("✅ Products loaded:", productsData);
  })
  .catch(err => console.error("❌ Error loading products:", err));

categories.forEach(cat => {
  cat.addEventListener("click", () => {
    const type = cat.dataset.cat;
    const products = productsData[type];

    if (products && products.length > 0) {
      mainContent.style.display = "none";
      productsContainer.style.display = "grid";

      productsContainer.innerHTML = products.map(p => `
        <div class="product-card" data-id="${p.id}">
          <img src="../${p.img}" alt="${p.name}">
          <h4>${p.img}</h4>
          <h4>play</h4>
          <h4>${p.name}</h4>
          <div class="rating">${'<img src="imgs/icons/star.png">'.repeat(p.rating || 5)}</div>
          <p>${p.desc || ''}</p>
          <div class="price">${p.price} $</div>
        </div>
      `).join("");
    } else {
      productsContainer.innerHTML = `<p style="text-align:center;">لا توجد منتجات في هذا القسم.</p>`;
    }
  });
});


// Categories click
categories.forEach(cat => {
  cat.addEventListener("click", () => {
    const type = cat.dataset.cat;
    const products = productsData[type];
    if(products) {
      mainContent.style.display = "none";
      productsContainer.style.display = "grid";
      productsContainer.innerHTML = products.map(p => `
        <div class="product-card" data-id="${p.id}">
          <img src="${p.img}" alt="${p.name}">
          <h4>${p.name}</h4>
          <div class="rating">${'<img src="imgs/icons/star.png">'.repeat(p.rating)}</div>
          <p>${p.desc}</p>
          <div class="price">${p.price}</div>
        </div>
      `).join("");
    }
  });
});

// Navbar links active state
const navLinksItems = document.querySelectorAll(".nav-links li a");

navLinksItems.forEach(link => {
  link.addEventListener("click", (e) => {
    e.preventDefault(); // عشان # ما تسوي reload

    // إزالة الكلاس من كل الروابط
    navLinksItems.forEach(l => l.classList.remove("active"));

    // إضافة الكلاس للرابط اللي انضغط عليه
    link.classList.add("active");

    // عرض homeSection لو Home انضغط
    if(link.textContent.trim() === "Home") {
      mainContent.style.display = "block";
      productsContainer.style.display = "none";
    } else {
      mainContent.style.display = "none";
      productsContainer.style.display = "none"; // ممكن تحط هنا أي سلوك ثاني للروابط الثانية
    }
  });
});

// Slider
let currentSlide = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

function setSlide(index) {
  currentSlide = index;
  document.querySelector(".slides-wrapper").style.transform = `translateX(-${index*100}%)`;
  dots.forEach(d => d.classList.remove("active"));
  dots[index].classList.add("active");
}

setInterval(() => {
  currentSlide = (currentSlide + 1) % slides.length;
  setSlide(currentSlide);
}, 4000);

const sidebar = document.getElementById("sidebar");
const profileIcon = document.getElementById("profileIcon");

profileIcon.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});

document.addEventListener("click", (e) => {
  if (!sidebar.contains(e.target) && !profileIcon.contains(e.target)) {
    sidebar.classList.remove("active");
  }
});

const notifBtn = document.querySelector('.icon img[alt="Notifications"]');
const notifSidebar = document.getElementById('notificationsSidebar');

notifBtn.addEventListener('click', () => {
  notifSidebar.classList.add('open');
});

document.addEventListener('click', (e) => {
  if (!notifSidebar.contains(e.target) && !notifBtn.contains(e.target)) {
    notifSidebar.classList.remove('open');
  }
});

// Sidebar lock icon
const lockIcon = document.querySelector('.icon img[alt="Lock"]');
const cartSidebar = document.getElementById('cartSidebar');

lockIcon.addEventListener('click', (e) => {
  e.stopPropagation(); // يمنع غلقها فور الضغط
  cartSidebar.classList.add('active');
});

document.addEventListener('click', (e) => {
  if (
    cartSidebar.classList.contains('active') &&
    !cartSidebar.contains(e.target) &&
    e.target !== lockIcon
  ) {
    cartSidebar.classList.remove('active');
  }
});



document.querySelector(".filter-icon").addEventListener("click", () => {
  window.location.href = "filter.html";
});


// === افتح product.php مع الـ ID عند الضغط على أي منتج ===
document.addEventListener('click', (e) => {
  const card = e.target.closest('.product-card');
  if (!card) return;

  const productId = card.dataset.id; // ناخد الـ id من data-id
  if (!productId) return;

  // نفتح صفحة المنتج مع الـ id في الـ URL
  window.location.href = `product.php?id=${productId}`;
});



// === افتح product.php مع بيانات المنتج عند الضغط على أي .product-card ===
document.addEventListener('click', (e) => {
  const card = e.target.closest('.product-card');
  if (!card) return; // مو كارت



  const params = new URLSearchParams({
    name, img, price, desc, rating
  });

  window.location.href = 'product.php?' + params.toString();
});
