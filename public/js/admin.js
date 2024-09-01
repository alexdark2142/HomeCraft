document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('#delete-btn');

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const url = button.dataset.url;

            // Деактивація кнопки
            button.disabled = true;
            button.classList.add('disabled-button');

            fetch(url, {
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
                        const productRow = button.closest('.product-row');
                        productRow.remove();
                        alert('Item deleted successfully');
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

    let category = document.getElementById('category_id');
    let subcategory = document.getElementById('subcategory_id');
    let subcategoryContainer = document.getElementById('subcategory-container');
    let addButton = document.getElementById('btn');

    // Отримання підкатегорій з даних, переданих з бекенду
    const categoriesWithSubcategories = window.categoriesWithSubcategories;

    if (category) {
        category.addEventListener('change', function() {
            const categoryId = this.value;
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');

            if (categoryId) {
                const subcategories = categoriesWithSubcategories[categoryId] || [];
                subcategory.innerHTML = '<option value="">Choose subcategory</option>';
                if (subcategories.length > 0) {
                    subcategoryContainer.style.display = 'flex';
                    subcategories.forEach(subcategoryItem => {
                        const option = document.createElement('option');
                        option.value = subcategoryItem.id;
                        option.textContent = subcategoryItem.name;
                        subcategory.appendChild(option);
                    });

                    addButton.disabled = false;
                    addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    addButton.disabled = false;
                    addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    subcategoryContainer.style.display = 'none';
                }
            } else {
                addButton.disabled = false;
                addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                subcategoryContainer.style.display = 'none';
            }
        });
    }

    // Логіка для сабміта форми
    if  (document.getElementById('product-form')) {
        document.getElementById('product-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const addButton = document.getElementById('btn');
            const url = this.getAttribute('data-url');
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');

            const formData = new FormData(this);
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
    }

    function displayValidationErrors(errors) {
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

    if (document.getElementById('add-subcategory')) {
        // Додавання підкатегорії
        document.getElementById('add-subcategory').addEventListener('click', function () {
            const subcategoryBox = document.getElementById('subcategory-box');
            const newSubcategoryGroup = document.createElement('div');
            newSubcategoryGroup.classList.add('subcategory-group');
            const tempId = 'new-' + Math.random().toString(36).substr(2, 9);
            newSubcategoryGroup.innerHTML = `
                    <input type="text" name="subcategories[${tempId}]" class="form-input" placeholder="Subcategory">
                    <button type="button" class="remove-button">-</button>
                `;
            subcategoryBox.appendChild(newSubcategoryGroup);

            newSubcategoryGroup.querySelector('.remove-button').addEventListener('click', function () {
                this.parentElement.remove();
            });
        });
    }

    if (document.getElementById('backButton')) {
        document.getElementById('backButton').addEventListener('click', function() {
            window.history.back();
        });
    }

    document.getElementById('nav-toggle').addEventListener('click', function() {
        this.classList.toggle('open');
        document.getElementById('nav-menu').classList.toggle('open');
        document.body.classList.toggle('no-scroll');
    });

    const navMenu = document.getElementById('nav-menu');
    const dropdownItems = document.querySelectorAll('.dropdown__item');

    dropdownItems.forEach(item => {
        const toggle = item.querySelector('.dropdown-toggle');

        toggle.addEventListener('click', function(event) {
            event.preventDefault();
            const dropdownMenu = item.querySelector('.dropdown__menu');

            // Закрити всі інші відкриті меню
            dropdownItems.forEach(i => {
                if (i !== item) {
                    i.classList.remove('active');
                }
            });

            // Перемикання класу для поточного меню
            item.classList.toggle('active');
        });
    });

    // Закриття меню при кліку поза межами меню
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.header') && !event.target.closest('.dropdown__item')) {
            navMenu.classList.remove('open');
            dropdownItems.forEach(item => {
                item.classList.remove('active');
            });
        }
    });
});
