<script>
    (function () {
        var dark = false;
        try {
            dark = localStorage.getItem('theme') === 'dark';
        } catch (e) {}
        document.documentElement.classList.toggle('dark', dark);
        document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
        function markFontsLoaded() {
            document.documentElement.classList.add('fonts-loaded');
        }
        if (document.fonts && typeof document.fonts.check === 'function' && document.fonts.check('24px "Material Symbols Outlined"')) {
            markFontsLoaded();
        } else if (document.fonts && typeof document.fonts.ready !== 'undefined') {
            document.fonts.ready.then(markFontsLoaded);
            setTimeout(markFontsLoaded, 3000);
        } else {
            markFontsLoaded();
        }
    })();
</script>
