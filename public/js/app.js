// Crime Monitor JavaScript

document.addEventListener('DOMContentLoaded', function() {
    loadCrimeStats();
    loadRecentCrimes();
});

// Load crime statistics
async function loadCrimeStats() {
    try {
        const response = await fetch('api/stats.php');
        const stats = await response.json();

        // Animate numbers with counting effect
        animateNumber('total-crimes', stats.total || 0);
        animateNumber('reported-crimes', getStatusCount(stats.by_status, 'reported') || 0);
        animateNumber('investigating-crimes', getStatusCount(stats.by_status, 'investigating') || 0);
        animateNumber('resolved-crimes', getStatusCount(stats.by_status, 'resolved') || 0);
    } catch (error) {
        console.error('Error loading crime stats:', error);
        showError('Failed to load crime statistics');
    }
}

// Load recent crimes
async function loadRecentCrimes() {
    try {
        const response = await fetch('api/crimes.php?limit=10');
        const crimes = await response.json();

        const tbody = document.getElementById('crimes-table-body');
        tbody.innerHTML = '';

        if (crimes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">No crime incidents found</td></tr>';
            return;
        }

        crimes.forEach(crime => {
            const row = createCrimeRow(crime);
            tbody.appendChild(row);
        });

        // Trigger staggered animations for table rows
        setTimeout(animateTableRows, 100);
    } catch (error) {
        console.error('Error loading recent crimes:', error);
        document.getElementById('crimes-table-body').innerHTML =
            '<tr><td colspan="6" class="text-center text-danger">Failed to load crime data</td></tr>';
    }
}

// Create crime table row
function createCrimeRow(crime) {
    const row = document.createElement('tr');
    row.className = 'fade-in';

    const statusBadge = getStatusBadge(crime.status);
    const severityBadge = getSeverityBadge(crime.severity);

    row.innerHTML = `
        <td>${formatDate(crime.incident_date)}</td>
        <td>${escapeHtml(crime.title)}</td>
        <td>${escapeHtml(crime.category_name || 'Unknown')}</td>
        <td>${escapeHtml(crime.location || 'N/A')}</td>
        <td>${statusBadge}</td>
        <td>${severityBadge}</td>
    `;

    return row;
}

// Helper functions
function getStatusCount(statusArray, status) {
    const item = statusArray.find(s => s.status === status);
    return item ? item.count : 0;
}

function getStatusBadge(status) {
    const classes = {
        'reported': 'badge-warning',
        'investigating': 'badge-info',
        'resolved': 'badge-success'
    };

    return `<span class="badge ${classes[status] || 'badge-secondary'}">${capitalize(status)}</span>`;
}

function getSeverityBadge(severity) {
    const classes = {
        'low': 'badge-success',
        'medium': 'badge-warning',
        'high': 'badge-danger'
    };

    return `<span class="badge ${classes[severity] || 'badge-secondary'}">${capitalize(severity)}</span>`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showError(message) {
    // Simple error display - could be enhanced with toast notifications
    console.error(message);
    // For now, just log to console. In production, show user-friendly error
}

// Animate number counting effect
function animateNumber(elementId, targetValue, duration = 1000) {
    const element = document.getElementById(elementId);
    const startValue = parseInt(element.textContent) || 0;
    const difference = targetValue - startValue;
    const startTime = performance.now();

    function updateNumber(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function for smooth animation
        const easeOutCubic = 1 - Math.pow(1 - progress, 3);
        const currentValue = Math.round(startValue + (difference * easeOutCubic));

        element.textContent = currentValue;

        if (progress < 1) {
            requestAnimationFrame(updateNumber);
        }
    }

    requestAnimationFrame(updateNumber);
}

// Add staggered animations to table rows
function animateTableRows() {
    const rows = document.querySelectorAll('#crimes-table-body tr');
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        row.style.transition = 'all 0.6s ease-out';

        setTimeout(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 100); // Stagger by 100ms
    });
}

// Initialize page animations
function initializeAnimations() {
    // Add loading class to body initially
    document.body.classList.add('loading');

    // Remove loading class after page loads
    setTimeout(() => {
        document.body.classList.remove('loading');
    }, 500);

    // Animate stats cards on load
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease-out';

        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150); // Stagger cards
    });
}

// Refresh data every 5 minutes
setInterval(() => {
    loadCrimeStats();
    loadRecentCrimes();
}, 300000);

// Initialize animations when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    loadCrimeStats();
    loadRecentCrimes();
    initializeAnimations();
});
