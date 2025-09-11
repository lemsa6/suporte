// Loading simples para debug
console.log('Loading script carregado!');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado, configurando loading...');
    
    // Criar loading global
    const loadingHtml = `
        <div id="global-loading" class="fixed inset-0 bg-branco bg-opacity-0 flex items-center justify-center z-50 hidden transition-all duration-1000 ease-in-out">
            <div class="loading-content opacity-0 transition-all duration-1000 ease-in-out">
                <div class="loading-dots-container">
                    <div class="loading-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <p class="text-cinza-claro font-medium mt-4 text-center">Carregando...</p>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', loadingHtml);
    
    // Interceptar cliques
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href]');
        
        if (link && !link.hasAttribute('data-no-loading')) {
            console.log('Link clicado:', link.href);
            
            // Mostrar loading
            const loading = document.getElementById('global-loading');
            loading.classList.remove('hidden');
            setTimeout(() => {
                loading.classList.add('show');
            }, 10);
            
            // Navegar
            setTimeout(() => {
                window.location.href = link.href;
            }, 100);
        }
    });
    
    // Esconder loading quando carregar
    window.addEventListener('load', function() {
        console.log('Página carregada');
        const loading = document.getElementById('global-loading');
        if (loading) {
            loading.classList.remove('show');
            setTimeout(() => {
                loading.classList.add('hidden');
            }, 1000);
        }
    });
});
