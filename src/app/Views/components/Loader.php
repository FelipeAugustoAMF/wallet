<div id="pageLoader" class="hidden"></div>

<script>
    window.showLoader = () => document.getElementById('pageLoader')?.classList.remove('hidden');
    window.hideLoader = () => document.getElementById('pageLoader')?.classList.add('hidden');
</script>

<style>
    #pageLoader {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 48px;
        height: 48px;
        border: 5px solid #fff;
        border-bottom-color: transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 9999;
    }

    @keyframes spin {
        to {
            transform: translate(-50%, -50%) rotate(360deg)
        }
    }

    .hidden {
        display: none !important;
    }
</style>