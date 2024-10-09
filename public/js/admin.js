import { Uppy } from "https://releases.transloadit.com/uppy/v4.4.0/uppy.min.mjs";
import { Dashboard } from "https://releases.transloadit.com/uppy/v4.4.0/uppy.min.mjs";
import { Webcam } from "https://releases.transloadit.com/uppy/v4.4.0/uppy.min.mjs";
import { ImageEditor } from "https://releases.transloadit.com/uppy/v4.4.0/uppy.min.mjs";
import { XHRUpload } from "https://releases.transloadit.com/uppy/v4.4.0/uppy.min.mjs";

document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    /*=============================================== BUTTONS ===============================================*/
    // Delete button
    document.querySelectorAll('#delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.url;
            this.disabled = true;
            this.classList.add('disabled-button');

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.closest('.product-row').remove();
                        alert('Item deleted successfully');
                    } else {
                        console.error('Error:', data.message);
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert(`There has been a problem with your fetch operation: ${error.message}`);
                })
                .finally(() => {
                    this.disabled = false;
                    this.classList.remove('disabled-button');
                });
        });
    });

    // Return back
    const backButton = document.getElementById('backButton');
    if (backButton) {
        backButton.addEventListener('click', () => window.history.back());
    }

    // Selection of subcategories
    const category = document.getElementById('category_id');
    const subcategory = document.getElementById('subcategory_id');
    const subcategoryContainer = document.getElementById('subcategory-container');
    const addButton = document.getElementById('btn');

    const updateSubcategories = (categoryId) => {
        const subcategories = window.categoriesWithSubcategories[categoryId] || [];
        subcategory.innerHTML = '<option value="">Choose subcategory</option>';

        if (subcategories.length > 0) {
            subcategoryContainer.style.display = 'flex';
            subcategories.forEach(subcategoryItem => {
                subcategory.insertAdjacentHTML('beforeend', `<option value="${subcategoryItem.id}">${subcategoryItem.name}</option>`);
            });
        } else {
            subcategoryContainer.style.display = 'none';
        }

        addButton.disabled = false;
        addButton.classList.remove('opacity-50', 'cursor-not-allowed');
    };

    if (category) {
        category.addEventListener('change', function() {
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');
            updateSubcategories(this.value);
        });
    }

    /*=============================================== CATEGORY PAGE ===============================================*/

    const addSubcategoryButton = document.getElementById('add-subcategory');
    if (addSubcategoryButton) {
        addSubcategoryButton.addEventListener('click', function() {
            const subcategoryBox = document.getElementById('subcategory-box');
            const tempId = 'new-' + Math.random().toString(36).substr(2, 9);

            const newSubcategoryGroup = document.createElement('div');
            newSubcategoryGroup.classList.add('subcategory-group');
            newSubcategoryGroup.innerHTML = `
                <input type="text" name="subcategories[${tempId}]" class="form-input" placeholder="Subcategory">
                <button type="button" class="remove-button">-</button>
            `;

            subcategoryBox.appendChild(newSubcategoryGroup);
            newSubcategoryGroup.querySelector('.remove-button').addEventListener('click', function() {
                newSubcategoryGroup.remove();
            });
        });
    }

    /*=================================================== HEADER ===================================================*/
    // Menu switch
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            this.classList.toggle('open');
            navMenu.classList.toggle('open');
            document.body.classList.toggle('no-scroll');
        });
    }

    // Dropdown меню
    const dropdownItems = document.querySelectorAll('.dropdown__item');
    dropdownItems.forEach(item => {
        const toggle = item.querySelector('.dropdown-toggle');
        if (toggle) {
            toggle.addEventListener('click', function(event) {
                event.preventDefault();
                dropdownItems.forEach(i => i.classList.remove('active'));
                item.classList.toggle('active');
            });
        }
    });

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.header') && !event.target.closest('.dropdown__item')) {
            navMenu.classList.remove('open');
            dropdownItems.forEach(item => item.classList.remove('active'));
        }
    });

    /*=============================================== Uppy ===============================================*/
    let uppy = null;

    if (document.getElementById('uppy')) {
        uppy = new Uppy({
            autoProceed: false,
            restrictions: {
                maxFileSize: 50000000, // Максимальний розмір файлу 50MB
                maxNumberOfFiles: 10, // Max 10 files
                allowedFileTypes: ['image/*', 'video/*']
            }
        })
            .use(Dashboard, {
                inline: true,
                target: '#uppy',
                height: 340,
                showProgressDetails: false,
                proudlyDisplayPoweredByUppy: false
            })
            .use(Webcam, {
                target: Dashboard
            })
            .use(ImageEditor, {
                target: Dashboard
            })
            .use(XHRUpload, {
                endpoint: document.getElementById('product-form').getAttribute('data-url'), // URL для завантаження файлів
                fieldName: 'files[]',
                formData: true,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF токен
                }
            });
    }

    /*============================================ PRODUCT PAGE ============================================*/

    const productForm = document.getElementById('product-form');

    if (productForm) {
        productForm.addEventListener('submit', function(event) {
            event.preventDefault();
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');

            const formData = new FormData(this);

            if (document.getElementById('uppy')) {
                uppy.getFiles().forEach(file => {
                    formData.append('files[]', file.data, file.name); // Додаємо фото до FormData
                });
            }

            // Відправка даних на сервер
            axios.post(this.getAttribute('data-url'), formData, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // CSRF токен
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    alert(response.data.message);
                    window.location.reload();
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        displayValidationErrors(error.response.data.errors);
                    } else {
                        alert('Error occurred while adding the product.');
                        console.error('Error:', error);
                    }
                })
                .finally(() => {
                    addButton.disabled = false;
                    addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                });
        });
    }


    const displayValidationErrors = (errors) => {
        document.querySelectorAll('.error-msg').forEach(span => span.textContent = '');
        for (const key in errors) {
            const inputElement = document.querySelector(`[name="${key}"]`);
            if (inputElement) {
                const errorMsgElement = inputElement.parentElement.querySelector('.error-msg');
                if (errorMsgElement) {
                    errorMsgElement.textContent = errors[key].join(', ');
                } else {
                    inputElement.insertAdjacentHTML('afterend', `<span class="error-msg text-red-500 text-sm">${errors[key].join(', ')}</span>`);
                }
            } else {
                console.error(`Input element not found for ${key}`);
            }
        }
    };

    // Adding rows of color and quantity
    const addColorQuantityButton = document.getElementById('add-color-quantity');
    const colorQuantityContainer = document.getElementById('color-quantity-container');

    // Function for updating the mandatory fields
    function updateFieldRequirements() {
        const rows = document.querySelectorAll('.color-quantity-row');
        const inputs = document.querySelectorAll('input[name="colors[]"], input[name="quantities[]"]');
        const allRowsRemoved = rows.length === 1;

        inputs.forEach(input => {
            input.required = !allRowsRemoved;
        });
    }

    // Add a new line
    if (addColorQuantityButton) {
        addColorQuantityButton.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'color-quantity-row';
            newRow.innerHTML = `
            <input type="text" name="colors[]" class="form-input" placeholder="Color" required>
            <input type="number" name="quantities[]" class="form-input" min="0" placeholder="Quantity" required>
            <button type="button" class="remove-row-button">Remove</button>
        `;
            colorQuantityContainer.appendChild(newRow);

            // Додати слухач події для кнопки видалення нового рядка
            newRow.querySelector('.remove-row-button').addEventListener('click', function() {
                newRow.remove();
                updateFieldRequirements();
            });

            updateFieldRequirements();
        });

        // Додати слухач події для вже існуючих кнопок видалення
        document.querySelectorAll('.remove-row-button').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.color-quantity-row').remove();
                updateFieldRequirements();
            });
        });

        updateFieldRequirements();
    }

    /*============================================ UPDATE STATUS ORDER ============================================*/
    document.querySelectorAll('.order-status-select').forEach(select => {
        select.addEventListener('change', function() {
            const orderId = this.id.split('-').pop();
            const applyBtn = document.getElementById(`apply-btn-${orderId}`);

            applyBtn.style.display = 'inline-block';
            applyBtn.disabled = false;
            applyBtn.style.fill = 'green';
        });
    });

    document.querySelectorAll('.apply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.id.split('-').pop();
            const select = document.getElementById(`order-status-${orderId}`);
            const status = select.value;
            const url = this.dataset.url;

            this.disabled = true;
            this.style.fill = 'gray';

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order_status: status }) // We use order_status as expected on the server
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Order status updated successfully');
                        this.style.display = 'none';

                        const row = this.closest('tr');
                        if (row) {
                            row.remove();
                        }
                    } else {
                        console.error('Error:', data.message);
                        this.style.display = 'none';
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    this.style.display = 'none';
                    alert(`There has been a problem with your fetch operation: ${error.message}`);
                });
        });
    });
});
