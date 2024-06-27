document.addEventListener('DOMContentLoaded', function() {
    function getCurrentBreakpoint() {
        const width = window.innerWidth;

        if (width < 576) {
            return 'xs';
        } else if (width >= 576 && width < 768) {
            return 'sm';
        } else if (width >= 768 && width < 992) {
            return 'md';
        } else if (width >= 992 && width < 1200) {
            return 'lg';
        } else if (width >= 1200 && width < 1400) {
            return 'xl';
        } else {
            return 'xxl';
        }
    }

    function togglePadding() {
        const mainElement = document.getElementById('primary');
        const currentBreakpoint = getCurrentBreakpoint();
        const breakpoint = 992; // lg breakpoint

        console.log('Current Breakpoint:', currentBreakpoint);

        if (window.innerWidth < breakpoint) {
            mainElement.classList.remove('px-3');
        } else {
            mainElement.classList.add('px-3');
        }
    }

    // Execute a função ao carregar a página e ao redimensionar a janela
    togglePadding();
    window.addEventListener('resize', togglePadding);
});
