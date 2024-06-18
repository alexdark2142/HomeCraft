document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('#delete-product-btn');

    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const productId = button.closest('.product-row').dataset.productId;

            // Деактивація кнопки
            button.disabled = true;
            button.classList.add('disabled-button');

            fetch(`/admin/product/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        const productRow = document.querySelector(`.product-row[data-product-id="${productId}"]`);
                        productRow.remove();
                        alert('Product deleted successfully');
                    } else {
                        console.error('Error:', data.message);
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                    alert(`There has been a problem with your fetch operation: ${error.message}`);
                })
                .finally(() => {
                    // Активувати кнопку назад
                    button.disabled = false;
                    button.classList.remove('disabled-button');
                });
        });
    });

    let category = document.getElementById('category')

    if (category) {
        document.getElementById('category').addEventListener('change', function() {
            const categoryId = this.value;
            const subcategoryContainer = document.getElementById('subcategory-container');

            // Заблокувати кнопку
            const addButton = document.getElementById('btn');
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');

            if (categoryId) {
                axios.get(`/api/subcategories/${categoryId}`).then(response => {
                    const subcategories = response.data;
                    const subcategorySelect = document.getElementById('subcategory');
                    subcategorySelect.innerHTML = '<option value="">Choose subcategory</option>';
                    if (subcategories.length > 0) {
                        subcategoryContainer.style.display = 'block';
                        subcategories.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;
                            subcategorySelect.appendChild(option);
                        });

                        // Розблокувати кнопку після отримання відповіді
                        addButton.disabled = false;
                        addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        // Розблокувати кнопку після отримання відповіді
                        addButton.disabled = false;
                        addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        subcategoryContainer.style.display = 'none';
                    }
                });
            } else {
                // Розблокувати кнопку після отримання відповіді
                addButton.disabled = false;
                addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                subcategoryContainer.style.display = 'none';
            }
        });

        document.getElementById('product-form').addEventListener('submit', function(event) {
            event.preventDefault();

            // Заблокувати кнопку
            const addButton = document.getElementById('btn');
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');

            const formData = new FormData(this);

            axios.post('/admin/create-product', formData, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                // Розблокувати кнопку після отримання відповіді
                addButton.disabled = false;
                addButton.classList.remove('opacity-50', 'cursor-not-allowed');

                alert('Product added successfully!');
                this.reset();
            }).catch(error => {
                // Розблокувати кнопку у випадку помилки
                addButton.disabled = false;
                addButton.classList.remove('opacity-50', 'cursor-not-allowed');

                alert('Error occurred while adding the product.');
                console.error('Error:', error);
            });
        });
    }
});
