const Toast = {
  show: function(message, type = 'success', duration = 3500) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    
    const icons = {
      success: '<svg class="toast-icon" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>',
      error: '<svg class="toast-icon" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>',
      warning: '<svg class="toast-icon" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
      info: '<svg class="toast-icon" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };

    toast.innerHTML = `
      ${icons[type] || icons.info}
      <div class="toast-message">${message}</div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
      toast.style.animation = 'dynamicIslandOut 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards';
      setTimeout(() => toast.remove(), 300);
    }, duration);
  },

  success: function(message, duration) { this.show(message, 'success', duration); },
  error: function(message, duration) { this.show(message, 'error', duration); },
  warning: function(message, duration) { this.show(message, 'warning', duration); },
  info: function(message, duration) { this.show(message, 'info', duration); }
};

window.Toast = Toast;
