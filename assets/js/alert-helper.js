/**
 * Sweet Alert Helper Functions
 * Simple helper functions to show alerts with consistent styling
 */

// Success Alert
function showSuccessAlert(title, message, callback = null) {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        confirmButtonColor: '#4B49AC'
    }).then((result) => {
        if (callback && typeof callback === 'function') {
            callback(result);
        }
    });
}

// Error Alert
function showErrorAlert(title, message, callback = null) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (callback && typeof callback === 'function') {
            callback(result);
        }
    });
}

// Warning Alert
function showWarningAlert(title, message, callback = null) {
    Swal.fire({
        icon: 'warning',
        title: title,
        text: message,
        confirmButtonColor: '#ffc107'
    }).then((result) => {
        if (callback && typeof callback === 'function') {
            callback(result);
        }
    });
}

// Confirmation Alert
function showConfirmAlert(title, message, confirmCallback, cancelCallback = null) {
    Swal.fire({
        icon: 'question',
        title: title,
        text: message,
        showCancelButton: true,
        confirmButtonColor: '#4B49AC',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batalkan'
    }).then((result) => {
        if (result.isConfirmed && confirmCallback && typeof confirmCallback === 'function') {
            confirmCallback();
        } else if (cancelCallback && typeof cancelCallback === 'function') {
            cancelCallback();
        }
    });
}

// Stock Warning Alert
function showStockAlert(title, items) {
    let itemsHtml = '';
    items.forEach(item => {
        itemsHtml += `<div class='stock-item'><strong>${item.name}</strong> ${item.message}</div>`;
    });
    
    Swal.fire({
        icon: 'error',
        title: title,
        html: itemsHtml,
        confirmButtonColor: '#d33',
        width: '500px',
        padding: '2em',
    });
}

// Auto Close Toast Alert
function showToast(message, icon = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    
    Toast.fire({
        icon: icon,
        title: message
    });
}
