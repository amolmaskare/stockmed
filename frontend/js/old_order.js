document.addEventListener('DOMContentLoaded', function() {
  const productsSection = document.getElementById('products-section');
  const searchInput = document.getElementById('search-input');
  const searchButton = document.getElementById('search-button');
  const paginationDiv = document.createElement('div');
  paginationDiv.className = 'pagination';
  productsSection.after(paginationDiv);

  // Path to your JSON file
  const jsonUrl = '/frontend/data/medicine_all_list.json';
  let allProducts = [];
  let filteredProducts = [];
  let currentPage = 1;
  const itemsPerPage = 1; // Number of items per page

  // Function to fetch an image using Google Custom Search API
  function fetchGoogleImage(query) {
      const GOOGLE_API_KEY = 'AIzaSyBtTy1ANEFX6VMevOGtdJLJyDkuFHZieqo';
      const GOOGLE_CX = 'f5db4f8ace70e4fa4';
      const googleUrl = `https://www.googleapis.com/customsearch/v1?key=${GOOGLE_API_KEY}&cx=${GOOGLE_CX}&q=${encodeURIComponent(query)}&searchType=image&num=1`;

      return fetch(googleUrl)
          .then(response => response.json())
          .then(data => {
              console.log(`Google data for "${query}":`, data); // Debug log
              if (data.items && data.items.length > 0) {
                  return data.items[0].link; // Return the first image's link
              } else {
                  return null;
              }
          })
          .catch(error => {
              console.error('Google Custom Search API error:', error);
              return null;
          });
  }

  // Function to update the JSON file with the new image URL
  function updateProductImageUrl(productId, imageUrl) {
      // Send a request to your backend to update the JSON file
      fetch('/update-product-url', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
          },
          body: JSON.stringify({
              id: productId,
              url: imageUrl,
          }),
      })
          .then(response => response.json())
          .then(data => {
              console.log('Product URL updated successfully:', data);
          })
          .catch(error => {
              console.error('Error updating product URL:', error);
          });
  }

  // Function to fetch medicines from JSON file
  function fetchMedicines(query = '') {
      fetch(jsonUrl)
          .then(response => response.json())
          .then(data => {
              allProducts = data;
              // Filter data based on search query
              filteredProducts = allProducts.filter(product =>
                  product.name.toLowerCase().includes(query.toLowerCase())
              );
              currentPage = 1; // Reset to the first page
              displayProducts(filteredProducts);
              updatePagination();
          })
          .catch(error => console.error('Error fetching data:', error));
  }

  // Function to display products with images from Google Custom Search API
  function displayProducts(products) {
      const startIndex = (currentPage - 1) * itemsPerPage;
      const endIndex = startIndex + itemsPerPage;
      const productsToShow = products.slice(startIndex, endIndex);

      productsSection.innerHTML = ''; // Clear previous results

      productsToShow.forEach(product => {
          const productCard = document.createElement('div');
          productCard.className = 'product-card';

          // Create an image element
          const imageElement = document.createElement('img');
          imageElement.alt = product.name;
          imageElement.className = 'product-image';

          // Use the stored image URL if available; otherwise, fetch from Google
          if (product.url && product.url.trim() !== "") {
              imageElement.src = product.url;
          } else {
              // Set a temporary placeholder image
              imageElement.src = 'https://via.placeholder.com/150'; // Default placeholder

              // Fetch image using Google Custom Search API
              fetchGoogleImage(product.name).then(imageUrl => {
                  if (imageUrl) {
                      imageElement.src = imageUrl;
                      // Update the product object with the fetched URL
                      product.url = imageUrl;

                      // Send the updated URL to the backend
                      updateProductImageUrl(product.id, imageUrl);

                      // Optional: Persist this updated URL by saving to localStorage
                      localStorage.setItem('cachedProducts', JSON.stringify(allProducts));
                  }
              });
          }
          productCard.appendChild(imageElement);

          // Create a container for product details
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

          productsSection.appendChild(productCard);
      });
  }

  // Function to update pagination buttons
  function updatePagination() {
      paginationDiv.innerHTML = ''; // Clear previous buttons

      const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
      const maxVisibleButtons = 20;
      let startPage = Math.max(1, currentPage - Math.floor(maxVisibleButtons / 2));
      let endPage = startPage + maxVisibleButtons - 1;

      if (endPage > totalPages) {
          endPage = totalPages;
          startPage = Math.max(1, endPage - maxVisibleButtons + 1);
      }

      // Left Slide Button
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

      // Page Numbers
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

      // Right Slide Button
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

  // Search button event listener
  searchButton.addEventListener('click', () => {
      const query = searchInput.value;
      fetchMedicines(query);
  });

  // On page load, try to load cached products (if using localStorage)
  const cachedData = localStorage.getItem('cachedProducts');
  if (cachedData) {
      allProducts = JSON.parse(cachedData);
      filteredProducts = allProducts;
      displayProducts(filteredProducts);
      updatePagination();
  } else {
      // Fetch all medicines on page load
      fetchMedicines();
  }
});