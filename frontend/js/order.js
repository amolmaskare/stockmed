document.addEventListener('DOMContentLoaded', function () {
  const productsSection = document.getElementById('products-section');
  const searchInput = document.getElementById('search-input');
  const searchButton = document.getElementById('search-button');
  const paginationDiv = document.createElement('div');
  paginationDiv.className = 'pagination';
  productsSection.after(paginationDiv);

  // const cartCount = document.createElement('div');
  const cartCount = document.getElementById('cart-count');
  cartCount.id = 'cart-count';
  cartCount.textContent = '0';
  document.body.appendChild(cartCount);

  const jsonUrl = '/StockMed/frontend/data/medicine_all_list.json';
  let allProducts = [];
  let filteredProducts = [];
  let currentPage = 1;
  const itemsPerPage = 2000;
  let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
cartCount.textContent = cartItems.reduce((total, item) => total + item.quantity, 0);


  const GOOGLE_API_KEY = CONFIG.GOOGLE_API_KEY;
  const GOOGLE_CX = CONFIG.GOOGLE_CX;

  function isUserLoggedIn() {
    return localStorage.getItem('isLoggedIn') === 'true';
  }

  function showLoginModal() {
    const modal = document.getElementById('login-modal');
    modal.style.display = 'block';

    document.querySelector('.close-modal').addEventListener('click', () => {
      modal.style.display = 'none';
    });

    document.getElementById('login-button').addEventListener('click', () => {
      window.location.href = 'login.html';
    });

    document.getElementById('signup-button').addEventListener('click', () => {
      window.location.href = 'signup.html';
    });
  }

  function checkUrlAvailability(productId) {
    return fetch('checkurl.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: productId }),
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.message === 'url exists') {
            return { shouldAddUrl: false, url: data.url }; // URL exists, return it
        } else if (data.message === 'add url') {
            return { shouldAddUrl: true }; // URL needs to be added
        } else {
            throw new Error(data.error || 'Unknown error');
        }
    })
    .catch((error) => {
        console.error('Error checking URL:', error);
        return { shouldAddUrl: false }; // Default to not adding URL on error
    });
}

  function fetchGoogleImage(query) {
    const googleUrl = `https://www.googleapis.com/customsearch/v1?key=${GOOGLE_API_KEY}&cx=${GOOGLE_CX}&q=${encodeURIComponent(
      query
    )}&searchType=image&num=1`;

    return fetch(googleUrl)
      .then((response) => response.json())
      .then((data) => {
        if (data.items && data.items.length > 0) {
          return data.items[0].link;
        } else {
          return null;
        }
      })
      .catch((error) => {
        console.error('Google Custom Search API error:', error);
        return null;
      });
  }

  function updateProductImageUrl(productId, imageUrl) {
    fetch('updateProductUrl.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        id: productId,
        url: imageUrl,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log('Product URL update response:', data);
      })
      .catch((error) => {
        console.error('Error updating product URL:', error);
      });
  }

  async function processProduct(productId, query) {
    const { shouldAddUrl, url } = await checkUrlAvailability(productId);

    if (shouldAddUrl) {
        console.log('Fetching Google image...');
        const imageUrl = await fetchGoogleImage(query);
        if (imageUrl) {
            updateProductImageUrl(productId, imageUrl);
            return imageUrl; // Return the newly fetched URL
        }
    } else if (url) {
        console.log('URL already exists:', url);
        return url; // Return the existing URL
    }

    return null; // No URL available
}

  function fetchMedicines(query = '') {
    fetch(jsonUrl)
      .then((response) => response.json())
      .then((data) => {
        allProducts = data;
        filteredProducts = allProducts.filter((product) =>
          product.name.toLowerCase().includes(query.toLowerCase())
        );
        currentPage = 1;
        displayProducts(filteredProducts);
        updatePagination();
      })
      .catch((error) => console.error('Error fetching data:', error));
  }

  function displayProducts(products) {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const productsToShow = products.slice(startIndex, endIndex);

    productsSection.innerHTML = '';

    productsToShow.forEach((product) => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card';

        const imageElement = document.createElement('img');
        imageElement.alt = product.name;
        imageElement.className = 'product-image';

        // Set a placeholder image initially
        imageElement.src = 'https://via.placeholder.com/150';

        // Check URL availability and update the image source
        processProduct(product.id, product.name).then((url) => {
            if (url) {
                imageElement.src = url; // Update the image source with the fetched or existing URL
            }
        });

        productCard.appendChild(imageElement);

        const detailsDiv = document.createElement('div');
        detailsDiv.className = 'product-details';
        detailsDiv.innerHTML = `
            <h3>${product.name}</h3>
            <p><strong>Price:</strong> ₹${product['price(\u20b9)']}</p>
            <p><strong>Manufacturer:</strong> ${product.manufacturer_name}</p>
            <p><strong>Type:</strong> ${product.type}</p>
            <p><strong>Pack Size:</strong> ${product.pack_size_label}</p>
            <p><strong>Composition 1:</strong> ${product.short_composition1}</p>
            <p><strong>Composition 2:</strong> ${product.short_composition2}</p>
        `;
        productCard.appendChild(detailsDiv);

        const addButton = document.createElement('button');
        addButton.textContent = 'Add to Cart';
        addButton.className = 'add-button';
        addButton.addEventListener('click',async () => {
          if (!isUserLoggedIn()) {
              showLoginModal();
              return;
          }
          let url = await processProduct(product.id, product.url);
          const existingItem = cartItems.find((item) => item.id === product.id);
          if (existingItem) {
              existingItem.quantity += 1;
          } else {
              cartItems.push({ ...product, quantity: 1, url: url });
          }
      
          // Update localStorage and cart count
          localStorage.setItem('cartItems', JSON.stringify(cartItems));
          cartCount.textContent = cartItems.reduce((total, item) => total + item.quantity, 0);
      });
      
        productCard.appendChild(addButton);

        productsSection.appendChild(productCard);
    });
}

  function updatePagination() {
    paginationDiv.innerHTML = '';

    const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
    const maxVisibleButtons = 20;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisibleButtons / 2));
    let endPage = startPage + maxVisibleButtons - 1;

    if (endPage > totalPages) {
      endPage = totalPages;
      startPage = Math.max(1, endPage - maxVisibleButtons + 1);
    }

    if (startPage > 1) {
      const leftSlideButton = document.createElement('button');
      leftSlideButton.textContent = '<<';
      leftSlideButton.addEventListener('click', () => {
        currentPage = Math.max(1, currentPage - maxVisibleButtons);
        displayProducts(filteredProducts);
        updatePagination();
      });
      paginationDiv.appendChild(leftSlideButton);
    }

    for (let i = startPage; i <= endPage; i++) {
      const pageButton = document.createElement('button');
      pageButton.textContent = i;
      pageButton.disabled = i === currentPage;
      pageButton.addEventListener('click', () => {
        currentPage = i;
        displayProducts(filteredProducts);
        updatePagination();
      });
      paginationDiv.appendChild(pageButton);
    }

    if (endPage < totalPages) {
      const rightSlideButton = document.createElement('button');
      rightSlideButton.textContent = '>>';
      rightSlideButton.addEventListener('click', () => {
        currentPage = Math.min(totalPages, currentPage + maxVisibleButtons);
        displayProducts(filteredProducts);
        updatePagination();
      });
      paginationDiv.appendChild(rightSlideButton);
    }
  }

  searchButton.addEventListener('click', () => {
    const query = searchInput.value;
    fetchMedicines(query);
  });

  cartCount.addEventListener('click', () => {
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
    window.location.href = 'cart.html';
});


  const cachedData = localStorage.getItem('cachedProducts');
  if (cachedData) {
    allProducts = JSON.parse(cachedData);
    filteredProducts = allProducts;
    displayProducts(filteredProducts);
    updatePagination();
  } else {
    fetchMedicines();
  }
});