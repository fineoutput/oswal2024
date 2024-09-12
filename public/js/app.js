//////////Menu Navbar///////////
document.addEventListener('DOMContentLoaded', function () {
  const menuBtn = document.querySelector('.menu-btn');
  const navMenu = document.querySelector('.nav');

  menuBtn.addEventListener('click', function () {
    navMenu.classList.toggle('active');
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
  let currentValue = parseInt(input.value);

  // Check if it's below the max value
  if (currentValue < 5) {
      input.value = currentValue + 1;
  } else {
      alert("Maximum quantity reached.");
  }

  updateCartDisplay(productId);
}

// Decrement function
function decrement(productId) {
  const input = document.getElementById(`quantity-input${productId}`);
  let currentValue = parseInt(input.value);

  if (currentValue > 1) {
      input.value = currentValue - 1;
  } else {
      // Hide quantity section and show "Add to Cart" if the value reaches 0 or 1
      document.getElementById(`quantity-section${productId}`).style.display = 'none';
      document.getElementById(`add-to-cart-section${productId}`).style.display = 'block';
      input.value = 0;
  }

  updateCartDisplay(productId);
}

// Manage Cart (Show the quantity section and hide "Add to Cart")
function manageCart(productId) {

  document.getElementById(`add-to-cart-section${productId}`).style.display = 'none';
  document.getElementById(`quantity-section${productId}`).style.display = 'flex';

  // Increment by default when "Add to Cart" is clicked
  increment(productId);
}

// Function to handle updating the cart (like making API calls to update cart)
function updateCartDisplay(productId) {
  const quantity = document.getElementById(`quantity-input${productId}`).value;
  console.log(`Product ${productId} quantity updated to ${quantity}`);

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
// console.log("hello2");
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
// console.log("splider");






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

//////////////////Login modal///////////


/////Mobile Countet button product category//////////////

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

// ///Notify cart///
// $('#notifyButton').click(function() {
//   $.notify("Product added to cart!", "success");
// });
// ///Notify cart END///

// document.getElementById('myButton').addEventListener('click', function() {
//   // Fire SweetAlert on button click
//   Swal.fire({
//       title: "Thanks!",
//       text: "You order has been placed!",
//       icon: "success"
//   });
// });
// document.getElementById('failureButton').addEventListener('click', function() {
//   Swal.fire({
//       title: "Oops...",
//       text: "Something went wrong!",
//       icon: "error"
//   });
// });

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

/////Product category button
document.addEventListener('DOMContentLoaded', function () {
  
  document.querySelectorAll('.addButton').forEach(function (addButton) {
    let count = 0;
    const buttonText = addButton.querySelector('.buttonText');
    const controlButtons = addButton.querySelector('.controlButtons');
    const numberDisplay = addButton.querySelector('.number-display');
    const incrementButton = addButton.querySelector('.btn-increase');
    const decrementButton = addButton.querySelector('.btn-decrease');

    addButton.addEventListener('click', function () {
      if (count === 0) {
        buttonText.style.display = 'none';
        controlButtons.style.display = 'flex';
        count = 1;
        numberDisplay.textContent = count;
      }
    });

    incrementButton.addEventListener('click', function (event) {
      event.stopPropagation();
      changeNumber(1);
    });

    decrementButton.addEventListener('click', function (event) {
      event.stopPropagation();
      changeNumber(-1);
    });

    function changeNumber(amount) {
      count += amount;

      if (count > 0) {
        numberDisplay.textContent = count;
      } else {
        count = 0;
        numberDisplay.textContent = count;
        buttonText.style.display = 'block';
        controlButtons.style.display = 'none';
      }
    }
  });
});

// Gift card js
function setupSelectableSection(sectionId, listId, itemClass) {
  const sectionElement = document.getElementById(sectionId);
  const listElement = document.getElementById(listId);

  // Handle the click event to toggle the list display
  sectionElement.addEventListener('click', () => {
    listElement.style.display = listElement.style.display === 'block' ? 'none' : 'block';
  });

  // Handle item selection
  document.querySelectorAll(`.${itemClass}`).forEach(item => {
    item.addEventListener('click', function () {
      document.querySelectorAll(`.${itemClass}`).forEach(i => i.classList.remove('selected'));
      this.classList.add('selected');

      // Update the section with the selected item's text and image
      const selectedText = this.querySelector('p').innerText;
      const selectedImageSrc = this.querySelector('img').src;
      sectionElement.innerHTML = `
              <p>${selectedText}</p>
              <img src="${selectedImageSrc}" alt="Selected" style="width: 40px; margin-left: 10px;">
          `;

      // Hide the list
      listElement.style.display = 'none';
    });
  });
}

function setupPromoCodeSelection(promoClass, inputId, applyButtonId) {
  const promoInput = document.getElementById(inputId);

  // Handle promo option selection
  document.querySelectorAll(`.${promoClass}`).forEach(button => {
    button.addEventListener('click', function () {
      document.querySelectorAll(`.${promoClass}`).forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      promoInput.value = this.getAttribute('data-code');
    });
  });

  // Handle apply button click
  document.getElementById(applyButtonId).addEventListener('click', () => {
    const promoCode = promoInput.value;
    alert(promoCode ? `Promo code ${promoCode} applied!` : 'Please select a promo code.');
  });
}

// Initialize the sections
setupSelectableSection('giftCardSection', 'giftCardList', 'gift-card-item');
setupSelectableSection('promoCodeSection', 'promoCodeList', 'promo-code-item');
// Initialize promo code selection
setupPromoCodeSelection('promo-option', 'promoCodeInput', 'applyButton');


function renderproductview(url) {

  $.ajax({

    url: url,

    type: 'GET',

    dataType: 'json',

    success: function (response) {

      const category = response.categoryDetails;

      // Update category details

      $('#category-description').text(category.description);

      // $('.category_banner_img').css('background-image', 'url(' + category.banner_image + ')');

      $('#category_name').text(category.category_name);

      $('#product-list-container').html(response.products);

      bindPaginationLinks();

    },

    error: function (xhr) {

      console.error('An error occurred while loading the category details and products.');

    }

  });

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