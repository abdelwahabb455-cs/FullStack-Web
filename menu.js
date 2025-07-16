// Toggle Product Lists on Category Click
function toggleProducts(category) {
    const allCategories = document.querySelectorAll('.product-list');
    allCategories.forEach(cat => {
        cat.style.display = 'none'; // Hide all categories
    });

    const selectedCategory = document.getElementById(category);
    if (selectedCategory) {
        selectedCategory.style.display = 'block'; // Show the selected category
    }
}
