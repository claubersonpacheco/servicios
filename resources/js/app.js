import 'preline';

function initPrelineComponents() {
  if (window.HSStaticMethods && typeof window.HSStaticMethods.autoInit === 'function') {
    window.HSStaticMethods.autoInit();
  }
}

function queuePrelineInit() {
  requestAnimationFrame(() => {
    initPrelineComponents();
  });
}

document.addEventListener('DOMContentLoaded', queuePrelineInit);
window.addEventListener('load', queuePrelineInit);

document.addEventListener('livewire:init', queuePrelineInit);
document.addEventListener('livewire:navigated', queuePrelineInit);
document.addEventListener('livewire:updated', queuePrelineInit);
