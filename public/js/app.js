//////////Menu Navbar///////////
document.addEventListener('DOMContentLoaded', function () {
  const menuBtn = document.querySelector('.menu-btn');
  const navMenu = document.querySelector('.nav');

  menuBtn.addEventListener('click', function () {
    navMenu.classList.toggle('active');
  });
});

$(".input").focus(function () {
  $("#search").addClass("move");
});
$(".input").focusout(function () {
  $("#search").removeClass("move");
  $(".input").val("");
});

$(".fa-search").click(function () {
  $(".input").toggleClass("active");
  $("#search").toggleClass("active");
});


$(".input2").focus(function () {
  $("#search2").addClass("move");
});
$(".input2").focusout(function () {
  $("#search2").removeClass("move");
  $(".input2").val("");
});

$("#naming").click(function () {
  // console.log("fuckit");

  $(".input2").toggleClass("active");
  $("#search2").toggleClass("active");
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

$(document).ready(function () {
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


// console.log("hello");

//////////////////////////////////////////////////////////RATING iNPUT///////////////////////////////////////////////

const ratingInputs = document.querySelectorAll('.rating input');

ratingInputs.forEach(input => {
  input.addEventListener('change', function () {
    // Remove active class from all labels
    document.querySelectorAll('.rating label').forEach(label => {
      label.classList.remove('active');
    });

    // Add active class to labels up to the selected input
    let selectedInput = this;
    while (selectedInput) {
      const label = document.querySelector(`label[for="${selectedInput.id}"]`);
      if (label) {
        label.classList.add('active');
      }
      selectedInput = selectedInput.previousElementSibling;
    }
  });
});

///////////////////////RATING iNPUT END//////////

///////////////////////Modal//////////
// console.log("name2");

//////////////Price range Slider///////////

//////////////Price range Slider End///////////

//////////Wishlist button functionlity///////////
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.wishlist_icons').forEach(iconContainer => {
    iconContainer.addEventListener('click', function (event) {
      // console.log("clicked");
      var regularHeart = this.querySelector('.hollow_icon');
      var solidHeart = this.querySelector('.colored_icon');
      if (regularHeart.style.display === 'none' || regularHeart.style.display === '') {
        regularHeart.style.display = 'inline';
        solidHeart.style.display = 'none';
      } else {
        regularHeart.style.display = 'none';
        solidHeart.style.display = 'inline';
      }
      event.preventDefault();
      event.stopPropagation();
    });
  });
});
//////////Wishlist button functionlity End///////////
// console.log("tillher");
//////////input increment/decremnet functionlity ///////////
document.addEventListener('DOMContentLoaded', function () {
  // Function to handle increment for all sections
  document.querySelectorAll('.btn-increment').forEach(button => {
    button.addEventListener('click', function () {
      const input = this.closest('.product_category_product_part').querySelector('.quantity-input');
      var currentValue = parseInt(input.value);
      input.value = currentValue + 1;
    });
  });

  // Function to handle decrement for all sections
  document.querySelectorAll('.btn-decrement').forEach(button => {
    button.addEventListener('click', function () {
      // console.log("decreaseit");
      const productPart = this.closest('.product_category_product_part');
      const input = productPart.querySelector('.quantity-input');
      const addToCartSection = productPart.querySelector('#add-to-cart-section');
      const quantitySection = productPart.querySelector('#quantity-section');
      var currentValue = parseInt(input.value);

      if (currentValue > 1) {
        input.value = currentValue - 1;
      } else {
        quantitySection.style.display = 'none';
        addToCartSection.style.display = 'block';
      }
    });
  });

  // Function to handle add to cart button click
  document.querySelectorAll('#add-to-cart-section button').forEach(button => {
    button.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent the default action (page reload)
      const productPart = this.closest('.product_category_product_part');
      const addToCartSection = productPart.querySelector('#add-to-cart-section');
      const quantitySection = productPart.querySelector('#quantity-section');

      addToCartSection.style.display = 'none';
      quantitySection.style.display = 'flex';
    });
  });
});


//////////input increment/decremnet functionlity End///////////
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
const toggleForm = () => {
  const container = document.querySelector('.bb-container');
  container.classList.toggle('bb-active');
};

const showModal = (event) => {
  event.preventDefault();
  document.getElementById('bb-modal').style.display = 'block';
};

const closeModal = () => {
  document.getElementById('bb-modal').style.display = 'none';
};

// Close the modal when clicking outside of it
window.onclick = function (event) {
  const modal = document.getElementById('bb-modal');
  if (event.target == modal) {
    modal.style.display = 'none';
  }
};

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
        controlButtons.style.background = 'red';
        controlButtons.style.color = '#fff';
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