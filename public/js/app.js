//////////Menu Navbar///////////
document.addEventListener('DOMContentLoaded', function () {
  const menuBtn = document.querySelector('.menu-btn');
  const navMenu = document.querySelector('.nav');

  menuBtn.addEventListener('click', function () {
    navMenu.classList.toggle('active');
  });

});

document.addEventListener('DOMContentLoaded', function() {
  const links = document.querySelectorAll('.nav-link_color');

  links.forEach(link => {
      // Add the 'clicked' class to a link if it matches the stored clicked link
      if (localStorage.getItem('clickedLink') === link.href) {
          link.classList.add('clicked');
      }
     
      link.addEventListener('click', function() {
          // Remove 'clicked' class from all links
          links.forEach(link => link.classList.remove('clicked'));
          
          // Add 'clicked' class to the clicked link
          this.classList.add('clicked');
          
          // Store the clicked link's URL in local storage
          localStorage.setItem('clickedLink', this.href);
      });
  });
});


document.addEventListener('DOMContentLoaded', function () {
  new Splide('#carousel1', {
    type: 'loop',
    perPage: 1,
    focus: 'center',
    pagination: false,
    arrows: true,
  }).mount();

  new Splide('#carousel2', {
    type: 'loop',
    perPage: 1,
    focus: 'center',
    pagination: false,
    arrows: true,
  }).mount();

  new Splide('#carousel3', {
    type: 'loop',
    perPage: 1,
    focus: 'center',
    pagination: false,
    arrows: true,
  }).mount();
});
//////////Menu Navbar End///////////

document.addEventListener('DOMContentLoaded', function () {
  $(".owl-carousel").owlCarousel({
    items: 5,
    loop: true,
    autoplay: false,
    dots: false,
    nav: false,
    responsive: {
      0: {
        items: 2
      },
      600: {
        items: 2
      },
      1000: {
        items: 5
      }
    }
  });
});

function showSearchButton(button) {

  const SelectorId = button === 'searchbutton1' ? 'search' : 'search2';
  const inputID = button === 'searchbutton1' ? 'input' : 'input2';

  document.querySelector('.'+inputID).classList.toggle('active');
  document.getElementById(SelectorId).classList.toggle('active');

}

function handleSearch(inputSelector, searchRoute) {
  
  const inputElement = document.querySelector(inputSelector);

  if (!inputElement) {
    console.error('Element not found:', inputSelector);
    return;
  }

  if (e.key === "Enter" || e.keyCode === 13) {
    
    const query = this.value.trim();

    if (query) {
      const searchUrl = searchRoute;
      window.location.href = `${searchUrl}?search=${encodeURIComponent(query)}`;
    }

  }
    
}

// Increment function
function increment(productId) {

  const input = document.getElementById(`quantity-input${productId}`);

  const mobinput = document.getElementById(`mob_quantity-input${productId}`);

  const numberDisplay = document.querySelector(`.number-display${productId}`);

  const CartMaxQuentity = parseInt(document.getElementById('maxquentity').value)

  let webcurrentValue = parseInt(input.value);

  let mobcurrentValue = parseInt(mobinput.value);

  // Check if it's below the max value
  if (webcurrentValue < CartMaxQuentity && mobcurrentValue < CartMaxQuentity) {
      input.value = webcurrentValue + 1;
      mobinput.value = mobcurrentValue + 1;
      numberDisplay.textContent = mobcurrentValue + 1;
  } else {
      showNotification('Maximum quantity reached', 'error');
      return;
  }

  updateCartDisplay(productId);
}

// Decrement function
function decrement(productId) {

  const input = document.getElementById(`quantity-input${productId}`);
  const mobinput = document.getElementById(`mob_quantity-input${productId}`);
  const numberDisplay = document.querySelector(`.number-display${productId}`);

  let webcurrentValue = parseInt(input.value);  
  let mobcurrentValue = parseInt(mobinput.value);

  if (webcurrentValue > 1 && mobcurrentValue > 1) {

      input.value = webcurrentValue - 1;
      mobinput.value = mobcurrentValue - 1;
      numberDisplay.textContent = mobcurrentValue - 1;
  } else {
      // Hide quantity section and show "Add to Cart" if the value reaches 0 or 1
      document.getElementById(`quantity-section${productId}`).style.display = 'none';

      document.getElementById(`mob_quantity-section${productId}`).style.display = 'none';

      document.getElementById(`add-to-cart-section${productId}`).style.display = 'block';

      document.getElementById(`mob_add-to-cart-section${productId}`).style.display = 'block';

      input.value = 0;
      mobinput.value = 0;
      numberDisplay.textContent = 0;
  }
  
  updateCartDisplay(productId);
}

// Manage Cart (Show the quantity section and hide "Add to Cart")
function manageCart(productId) {

  document.getElementById(`add-to-cart-section${productId}`).style.display = 'none';

  document.getElementById(`mob_add-to-cart-section${productId}`).style.display = 'none';

  document.getElementById(`quantity-section${productId}`).style.display = 'flex';

  document.getElementById(`mob_quantity-section${productId}`).style.display = 'flex';

  // Increment by default when "Add to Cart" is clicked
  increment(productId);
}

// Function to handle updating the cart (like making API calls to update cart)
function updateCartDisplay(productId) {
  const webquantity = document.getElementById(`quantity-input${productId}`).value;

  const mobquantity = document.getElementById(`mob_quantity-input${productId}`).value;

  console.log(`Product ${productId} quantity updated to ${webquantity}`);
  console.log(`Product ${productId} quantity updated to ${mobquantity}`);

  addToCart(productId)
}


// console.log("name");
const imgs = document.querySelectorAll('.details-img-select a');
const imgBtns = [...imgs];
let imgId = 1;

imgBtns.forEach((imgItem) => {
  imgItem.addEventListener('click', (event) => {
    event.preventDefault();
    imgId = imgItem.dataset.id;
    slideImage();
  });
});

function slideImage() {
  const displayWidth = document.querySelector('.details-img-showcase img:first-child').clientWidth;
  document.querySelector('.details-img-showcase').style.transform = `translateX(${-(imgId - 1) * displayWidth}px)`;
}

window.addEventListener('resize', slideImage);


/////////////////////////splide section//////////////////
document.addEventListener('DOMContentLoaded', function () {
  // Initialize first carousel
  new Splide('#splide1', {
    type: 'loop',
    perPage: 1,
    autoplay: true,
    pagination: true,
    arrows: false,
  }).mount();

  // Initialize second carousel with three images per slide
  new Splide('#splide2', {
    type: 'loop', // You can choose other types like 'fade' if needed
    perPage: 3, // Show 3 slides at once
    perMove: 1, // Move one slide at a time
    focus: 'center', // Center the current slide
    autoplay: true, // Auto-scroll the slides
    pagination: true, // Show pagination
    arrows: true, // Show arrows
    gap: '1rem', // Space between slides
    breakpoints: {
      768: {
        perPage: 1, // Adjust for smaller screens
      },
    },
  }).mount();
});

document.addEventListener('DOMContentLoaded', function () {
  new Splide('#product-splide', {
    // type: 'loop',  
    perPage: 4, // Display 4 products at once
    perMove: 4,
    focus: 'center',
    autoplay: false,
    pagination: true,
    arrows: false,
    gap: '3rem',
    breakpoints: {
      768: {
        perPage: 2, // Adjust for smaller screens
        perMove: 1,
      },
    },
  }).mount();
});

document.addEventListener('DOMContentLoaded', function () {
  new Splide('#product-splide_index', {
    // type: 'loop',  
    perPage: 4, // Display 4 products at once
    perMove: 4,
    focus: 'center',
    autoplay: true,
    pagination: true,
    arrows: false,
    gap: '3rem',
    breakpoints: {
      768: {
        perPage: 2, // Adjust for smaller screens
        perMove: 1,
      },
    },
  }).mount();
});

document.addEventListener('DOMContentLoaded', function () {
  new Splide('#splide3', {
    type: 'loop', // You can choose other types like 'fade' if needed
    perPage: 3, // Show 3 slides at once
    perMove: 1, // Move one slide at a time
    focus: 'center', // Center the current slide
    autoplay: true, // Auto-scroll the slides
    pagination: true, // Show pagination
    arrows: true, // Show arrows
    gap: '1rem', // Space between slides
    breakpoints: {
      768: {
        perPage: 1, // Adjust for smaller screens
      },
    },
  }).mount();
});

////////////////////Crousel code////////////
document.addEventListener('DOMContentLoaded', function () {

  new Splide('#product-splide_name', {
    type: 'loop',
    perPage: 3,
    perMove: 1,
    gap: '1rem',
    breakpoints: {
      600: {
        perPage: 1,
      },
      1024: {
        perPage: 1,
      },
    },
  }).mount();
});

/////Sticky buttons category//////////////
document.addEventListener("DOMContentLoaded", function () {
  const button = document.getElementById("fixedButton");
  const statBtns = document.querySelector(".stat-btns");
  let lastScrollTop = 0;

  window.addEventListener("scroll", function () {
    if (window.innerWidth <= 768) {
      let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      if (scrollTop > lastScrollTop) {
        // Scrolling down
        button.classList.add("sticky-button");
        button.classList.remove("hidden-button");
      } else {
        // Scrolling up
        button.classList.remove("sticky-button");
        button.classList.add("hidden-button");
      }
      lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    } else {
      button.classList.remove("sticky-button");
      button.classList.remove("hidden-button");
    }
  });

  window.addEventListener("resize", function () {
    if (window.innerWidth > 768) {
      button.classList.remove("sticky-button");
      button.classList.remove("hidden-button");
    }
  });
});


///////////////////Instagram Slide///////////
document.addEventListener('DOMContentLoaded', function () {
  new Splide('#insta_slide', {
    type: 'loop',
    perPage: 3,
    gap: '1rem',
    focus: 'center',
    pagination: false,
    arrows: true,
    breakpoints: {
      768: {
        perPage: 1,
      },
    },
  }).mount();
});

// Gift card selection setup
// function setupSelectableSection(sectionId, listId, itemClass) {

//   const sectionElement = document.getElementById(sectionId);
//   const listElement = document.getElementById(listId);

//   sectionElement.addEventListener('click', () => {
//     // Toggle the list visibility
//     listElement.style.display = listElement.style.display === 'block' ? 'none' : 'block';
//   });

//   // Handle gift card item selection
//   document.querySelectorAll(`.${itemClass}`).forEach(item => {
    
//     item.addEventListener('click', function () {
//       // Deselect all items and select the clicked one
//       document.querySelectorAll(`.${itemClass}`).forEach(i => i.classList.remove('selected'));
//       this.classList.add('selected');

//       // Update the selected gift card information in the section
//       const selectedText = this.querySelector('p').innerText;
//       const selectedImageSrc = this.querySelector('img').src;
//       sectionElement.innerHTML = `
//         <p>${selectedText}</p>
//         <img src="${selectedImageSrc}" alt="Selected" style="width: 40px; margin-left: 10px;">
//         <button id="removeSelected" style="margin-left: 10px;">Remove</button>
//       `;

//       // Hide the list after selection
//       listElement.style.display = 'none';

//       // Add event listener for the remove button
//       document.getElementById('removeSelected').addEventListener('click', function () {
//         // Clear selection
//         document.querySelectorAll(`.${itemClass}`).forEach(i => i.classList.remove('selected'));
//         sectionElement.innerHTML = '<p>Select a product</p>'; // Reset the section content

//         // Show the list again if needed
//         listElement.style.display = 'block';
//       });
//     });
//   });
// }


// Promo code selection setup
document.addEventListener('DOMContentLoaded', function() {
  // Promo code selection setup
  const promoOptions = document.getElementById('promoOptions');
  const toggleButtonpromo = document.getElementById('toggleButtonpromo');

  // Toggle promo code options visibility
  toggleButtonpromo.addEventListener('click', function() {
      promoOptions.style.display = promoOptions.style.display === 'block' ? 'none' : 'block';
      toggleButtonpromo.innerText = promoOptions.style.display === 'block' ? 'Hide Promo Codes' : 'Show Promo Codes';
  });

  // Handle promo code option selection
  document.querySelectorAll('.promo-option').forEach(function(button) {
      button.addEventListener('click', function() {
          // Remove 'active' class from all buttons and add it to the clicked one
          document.querySelectorAll('.promo-option').forEach(function(btn) {
              btn.classList.remove('active');
          });
          this.classList.add('active');

          // Get the selected promo code and apply it
          const promoCode = this.getAttribute('data-code');
          applyPromocode(promoCode);  // Call the applyPromocode function with the selected code
      });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  setupSelectableSection('giftCardSection', 'giftCardList', 'gift-card-item');
  setupPromoCodeSelection('promo-option', 'applyButton'); // This line might be redundant and could be removed
});


let currentRequest = null;

let cache = {};

function renderproductview(url) {

  if (currentRequest) {

    currentRequest.abort();

  }

  $('#product-list-container').html('<div class="spinner">Loading...</div>');

  if (cache[url]) {

    updateUI(cache[url]);

    return; 

  }

  currentRequest = $.ajax({

    url: url,

    type: 'GET',

    dataType: 'json',

    success: function (response) {

      cache[url] = response;

      updateUI(response);

    },

    error: function (xhr) {

      console.error('An error occurred while loading the category details and products.');

    }

  });

}

function updateUI(response) {

  const category = response.categoryDetails;

  // Update category details

  $('#category-description').text(category.description);

  // $('.category_banner_img').css('background-image', 'url(' + category.banner_image + ')');

  $('#category_name').text(category.category_name);

  $('#product-list-container').html(response.products);

  bindPaginationLinks();

}

function bindPaginationLinks() {
  $('.pagination-links a').on('click', function (e) {
    e.preventDefault();

    var url = $(this).attr('href');
    if (url) {
      loadProducts(url);
    }
  });
}

function loadProducts(url) {
  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'json',
    success: function (response) {
      $('#product-list-container').html(response.products);

      // Rebind the pagination links
      bindPaginationLinks();
    },
    error: function (xhr) {
      console.error('An error occurred while loading the products.');
    }
  });
}

bindPaginationLinks();

function renderProduct(containerId , url , selectedId) {

  const type_id = $(`select[name="${selectedId}"]`).val();

  const webproduct = $(`#web_product_${containerId}`);

  const Mobileproduct = $(`#mob_product_${containerId}`);

  if(type_id != 'type'){

    $.ajax({
  
      url: url,
  
      type: 'GET',
  
      dataType: 'json',
  
      data : {type_id: type_id,product_id:containerId},
  
      success: function (response) {
  
        webproduct.empty();
  
        Mobileproduct.empty();
  
        webproduct.html(response.webproduct);
        
        Mobileproduct.html(response.mobproduct);
  
      },
  
      error: function (xhr) {
  
        console.error('An error occurred while loading the category details and products.');
  
      }
  
    });
  }

}

//SHOW MORE DETAILS PRODUCT DETAIL PAGE
document.addEventListener('DOMContentLoaded', function() {
  const toggleButton = document.querySelector('.accordion-toggle');
  const collapsibleContent = document.querySelector('.collapsible-content');

  toggleButton.addEventListener('click', function() {
      if (collapsibleContent.style.display === 'block') {
          collapsibleContent.style.display = 'none';
          toggleButton.textContent = 'Show More';
      } else {
          collapsibleContent.style.display = 'block';
          toggleButton.textContent = 'Show Less';
      }
  });
});