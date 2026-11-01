// Buscador en vivo para la barra superior del encabezado
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('global-search-input');
    const searchDropdown = document.getElementById('search-dropdown');

    if (!searchInput || !searchDropdown) return;

    let debounceTimer;

    searchInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        const query = e.target.value.trim();

        if (query.length < 2) {
            searchDropdown.innerHTML = '';
            searchDropdown.classList.remove('active');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`/index.php?action=api_buscar&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    searchDropdown.innerHTML = '';
                    if (data.length === 0) {
                        searchDropdown.innerHTML = '<div style="padding: 0.85rem 1rem; color: #777; font-size: 0.85rem;">No se encontraron resultados para "' + escapeHtml(query) + '"</div>';
                        searchDropdown.classList.add('active');
                        return;
                    }

                    data.forEach(item => {
                        const a = document.createElement('a');
                        a.href = item.ruta;
                        a.className = 'search-item';
                        a.innerHTML = `
                            <div class="search-item-title">
                                <span>${item.icono || ''}</span>
                                <span>${escapeHtml(item.titulo)}</span>
                            </div>
                            <div class="search-item-desc">${escapeHtml(item.descripcion)}</div>
                        `;
                        searchDropdown.appendChild(a);
                    });

                    searchDropdown.classList.add('active');
                })
                .catch(err => {
                    console.error('Error al buscar:', err);
                });
        }, 250);
    });

    // Cerrar al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.classList.remove('active');
        }
    });

    // Cerrar con Escape
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            searchDropdown.classList.remove('active');
        }
    });

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
});
