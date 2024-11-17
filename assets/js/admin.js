document.addEventListener('DOMContentLoaded', function() {
    // Make buttons sortable
    const buttonsList = document.getElementById('wpqc-buttons-list');
    let draggedItem = null;

    function initSortable() {
        const items = buttonsList.getElementsByClassName('wpqc-button-item');
        Array.from(items).forEach(item => {
            const handle = item.querySelector('.wpqc-button-header');
            
            handle.addEventListener('mousedown', function(e) {
                draggedItem = item;
                item.style.opacity = '0.5';
                
                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
                
                // Prevent text selection while dragging
                e.preventDefault();
            });
        });
    }

    function onMouseMove(e) {
        if (!draggedItem) return;
        
        const items = buttonsList.getElementsByClassName('wpqc-button-item');
        const draggedRect = draggedItem.getBoundingClientRect();
        
        Array.from(items).forEach(item => {
            if (item === draggedItem) return;
            
            const rect = item.getBoundingClientRect();
            if (e.clientY < rect.bottom && e.clientY > rect.top) {
                if (e.clientY < rect.top + rect.height / 2) {
                    buttonsList.insertBefore(draggedItem, item);
                } else {
                    buttonsList.insertBefore(draggedItem, item.nextSibling);
                }
            }
        });
    }

    function onMouseUp() {
        if (draggedItem) {
            draggedItem.style.opacity = '1';
            draggedItem = null;
        }
        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', onMouseUp);
    }

    // Add new button
    document.getElementById('wpqc-add-button').addEventListener('click', function() {
        const template = `
            <div class="wpqc-button-item" data-id="button-${Date.now()}">
                <div class="wpqc-button-header">
                    <span class="wpqc-button-move dashicons dashicons-move"></span>
                    <span class="wpqc-button-title">New Button</span>
                    <span class="wpqc-button-delete dashicons dashicons-trash"></span>
                </div>
                <div class="wpqc-button-content">
                    <div class="wpqc-field-row">
                        <label>Button Name</label>
                        <input type="text" class="wpqc-button-name" value="New Button">
                    </div>
                    <div class="wpqc-field-row">
                        <label>Button Type</label>
                        <select class="wpqc-button-type">
                            <option value="messenger">Messenger</option>
                            <option value="zalo">Zalo</option>
                            <option value="telegram">Telegram</option>
                            <option value="phone">Phone</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="viber">Viber</option>
                            <option value="line">Line</option>
                            <option value="discord">Discord</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="wpqc-field-row">
                        <label>URL</label>
                        <input type="url" class="wpqc-button-url" value="">
                    </div>
                    <div class="wpqc-field-row wpqc-custom-svg-field" style="display:none;">
                        <label>Custom SVG Icon</label>
                        <textarea class="wpqc-button-custom-svg" rows="6"
                            placeholder="Paste your SVG code here"></textarea>
                        <p class="description">Paste your SVG icon code. Make sure it's a valid SVG with viewBox="0 0 24 24".</p>
                    </div>
                    <input type="hidden" class="wpqc-button-id" value="button-${Date.now()}">
                </div>
            </div>
        `;

        buttonsList.insertAdjacentHTML('beforeend', template);
        initSortable();
        showNotification('success', 'New button added successfully');
    });

    // Event delegation for dynamic elements
    document.addEventListener('click', function(e) {
        // Delete button
        if (e.target.matches('.wpqc-button-delete')) {
            if (confirm('Are you sure you want to delete this button?')) {
                const item = e.target.closest('.wpqc-button-item');
                item.remove();
                showNotification('success', 'Button deleted successfully');
            }
        }
    });

    // Handle type change
    document.addEventListener('change', function(e) {
        if (e.target.matches('.wpqc-button-type')) {
            const item = e.target.closest('.wpqc-button-item');
            const customSvgField = item.querySelector('.wpqc-custom-svg-field');
            
            if (e.target.value === 'custom') {
                customSvgField.style.display = 'block';
            } else {
                customSvgField.style.display = 'none';
            }
        }
    });

    // Handle name change
    document.addEventListener('input', function(e) {
        if (e.target.matches('.wpqc-button-name')) {
            const item = e.target.closest('.wpqc-button-item');
            const title = item.querySelector('.wpqc-button-title');
            title.textContent = e.target.value || 'Untitled';
        }

        // Validate custom SVG
        if (e.target.matches('.wpqc-button-custom-svg')) {
            const svg = e.target.value.trim();
            if (svg && !svg.startsWith('<svg')) {
                showFieldError(e.target, 'Invalid SVG code. Must start with <svg> tag.');
            } else if (svg && !svg.includes('viewBox')) {
                showFieldError(e.target, 'SVG must include viewBox attribute.');
            } else {
                hideFieldError(e.target);
            }
        }
    });

    // Save settings
    document.getElementById('wpqc-settings-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const spinner = form.querySelector('.spinner');
        const submitButton = form.querySelector('button[type="submit"]');
        
        // Validate form
        let hasErrors = false;
        form.querySelectorAll('.wpqc-button-url').forEach(input => {
            if (input.value && !isValidUrl(input.value)) {
                showFieldError(input, 'Please enter a valid URL');
                hasErrors = true;
            }
        });

        if (hasErrors) {
            showNotification('error', 'Please correct the errors before saving');
            return;
        }

        // Collect data
        const buttons = [];
        form.querySelectorAll('.wpqc-button-item').forEach(item => {
            const buttonData = {
                id: item.querySelector('.wpqc-button-id').value,
                name: item.querySelector('.wpqc-button-name').value,
                type: item.querySelector('.wpqc-button-type').value,
                url: item.querySelector('.wpqc-button-url').value
            };

            const customSvg = item.querySelector('.wpqc-button-custom-svg');
            if (buttonData.type === 'custom' && customSvg) {
                buttonData.custom_svg = customSvg.value;
            }

            buttons.push(buttonData);
        });

        // Show loading state
        spinner.classList.add('is-active');
        submitButton.disabled = true;

        // Save via AJAX
        fetch(wpqc_admin.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'wpqc_save_buttons',
                nonce: wpqc_admin.nonce,
                buttons: JSON.stringify(buttons)
            })
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                showNotification('success', 'Settings saved successfully!');
            } else {
                showNotification('error', response.data || 'Error saving settings. Please try again.');
            }
        })
        .catch(error => {
            showNotification('error', 'Network error. Please try again.');
            console.error('Error:', error);
        })
        .finally(() => {
            spinner.classList.remove('is-active');
            submitButton.disabled = false;
        });
    });

    // Helper Functions
    function showNotification(type, message, duration = 3000) {
        let overlay = document.querySelector('.wpqc-notice-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'wpqc-notice-overlay';
            document.body.appendChild(overlay);
        }

        const notice = document.createElement('div');
        notice.className = `wpqc-notice ${type}`;
        notice.innerHTML = `
            <div class="wpqc-notice-message">${message}</div>
            <span class="wpqc-notice-close dashicons dashicons-no-alt"></span>
        `;

        overlay.appendChild(notice);

        const closeBtn = notice.querySelector('.wpqc-notice-close');
        closeBtn.addEventListener('click', () => removeNotice(notice));

        if (duration > 0) {
            setTimeout(() => removeNotice(notice), duration);
        }
    }

    function removeNotice(notice) {
        notice.style.animation = 'fadeOut 0.3s ease-in-out';
        setTimeout(() => {
            if (notice.parentNode) {
                notice.parentNode.removeChild(notice);
                const overlay = document.querySelector('.wpqc-notice-overlay');
                if (overlay && !overlay.hasChildNodes()) {
                    overlay.remove();
                }
            }
        }, 300);
    }

    function showFieldError(field, message) {
        let error = field.nextElementSibling;
        if (!error || !error.classList.contains('wpqc-field-error')) {
            error = document.createElement('div');
            error.className = 'wpqc-field-error';
            field.parentNode.insertBefore(error, field.nextSibling);
        }
        error.textContent = message;
        field.classList.add('invalid');
    }

    function hideFieldError(field) {
        const error = field.nextElementSibling;
        if (error && error.classList.contains('wpqc-field-error')) {
            error.remove();
        }
        field.classList.remove('invalid');
    }

    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

    // Initialize sortable functionality
    initSortable();
});