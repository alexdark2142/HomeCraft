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

    let category = document.getElementById('category_id')

    if (category) {
        document.getElementById('category_id').addEventListener('change', function() {
            const categoryId = this.value;
            const subcategoryContainer = document.getElementById('subcategory-container');

            // Заблокувати кнопку
            const addButton = document.getElementById('btn');
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');

            if (categoryId) {
                axios.get(`/api/subcategories/${categoryId}`).then(response => {
                    const subcategories = response.data;
                    const subcategorySelect = document.getElementById('subcategory_id');
                    subcategorySelect.innerHTML = '<option value="">Choose subcategory</option>';
                    if (subcategories.length > 0) {
                        subcategoryContainer.style.display = 'flex';
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

            const addButton = document.getElementById('btn');
            const url = this.getAttribute('data-url');
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');

            const formData = new FormData(this);
            // Очистити попередні повідомлення про помилки
            document.querySelectorAll('.error-msg').forEach(function(span) {
                span.textContent = '';
            });

            axios.post(url, formData, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                addButton.disabled = false;
                addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                alert(response.data.message);
                window.location.reload();
            }).catch(error => {
                addButton.disabled = false;
                addButton.classList.remove('opacity-50', 'cursor-not-allowed');

                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    displayValidationErrors(errors);
                } else {
                    alert('Error occurred while adding the product.');
                    console.error('Error:', error);
                }
            });
        });

        function displayValidationErrors(errors) {
            // Очистити попередні повідомлення про помилки
            document.querySelectorAll('.error-msg').forEach(function(span) {
                span.textContent = '';
            });

            for (const key in errors) {
                if (errors.hasOwnProperty(key)) {
                    const errorMessages = errors[key];
                    const inputElement = document.querySelector(`[name="${key}"]`);
                    if (inputElement) {
                        const errorMsgElement = inputElement.parentElement.querySelector('.error-msg');
                        if (errorMsgElement) {
                            errorMsgElement.textContent = errorMessages.join(', ');
                        } else {
                            const newErrorMsgElement = document.createElement('span');
                            newErrorMsgElement.className = 'error-msg text-red-500 text-sm';
                            newErrorMsgElement.textContent = errorMessages.join(', ');
                            inputElement.parentElement.appendChild(newErrorMsgElement);
                        }
                    } else {
                        console.error(`Input element not found for ${key}`);
                    }
                }
            }
        }

    }
});
