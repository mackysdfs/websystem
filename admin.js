// Function to handle approval or rejection
async function updateRequestStatus(id, action) {
    try {
        // Show loading state
        const row = document.querySelector(`[data-id="${id}"]`);
        if (!row) {
            throw new Error('Request row not found');
        }
        row.style.opacity = '0.5';

        const response = await fetch('api/handle_request.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id, action })
        });

        const data = await response.json();

        if (response.ok) {
            // Success animation
            row.style.transition = 'all 0.3s ease-out';
            row.style.transform = 'translateX(100%)';
            setTimeout(() => row.remove(), 300);
            
            // Show success message
            showNotification(`Request ${action}ed successfully!`, 'success');
        } else {
            throw new Error(data.error || `Failed to ${action} the request`);
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message, 'error');
        
        // Reset row state
        const row = document.querySelector(`[data-id="${id}"]`);
        if (row) {
            row.style.opacity = '1';
        }
    }
}

// Helper function to show notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add notification styles
const style = document.createElement('style');
style.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 4px;
        color: white;
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
    }
    
    .notification.success { background-color: #4caf50; }
    .notification.error { background-color: #f44336; }
    .notification.info { background-color: #2196f3; }
    
    .notification.fade-out {
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

// Approve button handler
function approveRequest(id) {
    updateRequestStatus(id, 'approve');
}

// Reject button handler
function rejectRequest(id) {
    updateRequestStatus(id, 'reject');
}
