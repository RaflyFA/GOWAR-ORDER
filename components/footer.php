<style>
    /* Universal Footer CSS */
    .wrt-footer {
        background-color: #1e293b; /* slate-800 */
        color: #f1f5f9; /* slate-100 */
        padding-top: 2rem;
        padding-bottom: 2rem;
        margin-top: 3rem;
        width: 100%;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .wrt-footer-container {
        max-width: 56rem; /* 896px */
        margin-left: auto;
        margin-right: auto;
        padding-left: 1rem;
        padding-right: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    @media (min-width: 768px) {
        .wrt-footer-container {
            flex-direction: row;
        }
    }
    .wrt-footer-logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .wrt-footer-icon {
        background-color: #1a8f50;
        color: #ffffff;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
    }
    .wrt-footer-text {
        font-size: 1.25rem;
        font-weight: 700;
    }
    .wrt-footer-copyright {
        font-size: 0.875rem;
        color: #94a3b8; /* slate-400 */
        margin: 0;
    }
</style>
<footer class="wrt-footer">
    <div class="wrt-footer-container">
        <div class="wrt-footer-logo">
            <div class="wrt-footer-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-2.886-1.201-3.898C12.544 5.36 11.5 4.584 11.5 3.5c0-.837.299-1.554.8-2.03.116-.11.23-.217.338-.32C12.38 1 12 1 11.5 1 9.5 1 8 3.5 8 3.5S6.5 1 4.5 1C4 1 3.62 1 3.362 1.15c.108.103.222.21.338.32.5.476.8 1.193.8 2.03 0 1.084-1.044 1.86-1.299 3.102C2.5 7.614 2 8.999 2 11.5 2 14 4.686 16 8 16Z"/>
                </svg>
            </div>
            <span class="wrt-footer-text">Wartan.</span>
        </div>
        <p class="wrt-footer-copyright">&copy; <?php echo date('Y'); ?> Wartan Order System. Hak Cipta Dilindungi.</p>
    </div>
</footer>
