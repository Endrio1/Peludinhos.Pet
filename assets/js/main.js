// assets/js/main.js
document.addEventListener('DOMContentLoaded', () => {
    const catalogoGatos = document.getElementById('catalogo-gatos');
    const modal = document.getElementById('modal-adocao');
    const closeButton = document.querySelector('.close-button');
    const formAdocao = document.getElementById('form-adocao');
    const modalNomeGato = document.getElementById('modal-nome-gato');
    const gatoIdInput = document.getElementById('gato_id');

    // Função para buscar e exibir os gatos
    async function carregarGatos() {
        try {
            const response = await fetch('api/gatos/listar.php');
            const gatos = await response.json();

            catalogoGatos.innerHTML = ''; // Limpa o catálogo
            gatos.forEach(gato => {
                if (gato.status === 'Disponível') {
                    const card = document.createElement('div');
                    card.className = 'gato-card';
                    card.innerHTML = `
                        <img src="${gato.foto_url}" alt="Foto de ${gato.nome}">
                        <div class="gato-info">
                            <h3>${gato.nome}</h3>
                            <div class="tags">
                                <span class="tag">${gato.idade}</span>
                                <span class="tag">${gato.sexo}</span>
                            </div>
                            <p>${gato.descricao}</p>
                            <button class="btn-adotar" data-id="${gato.id}" data-nome="${gato.nome}">Quero Adotar!</button>
                        </div>
                    `;
                    catalogoGatos.appendChild(card);
                }
            });
        } catch (error) {
            catalogoGatos.innerHTML = '<p>Não foi possível carregar os gatinhos. Tente novamente mais tarde.</p>';
            console.error('Erro ao buscar gatos:', error);
        }
    }

    // Abrir o modal
    catalogoGatos.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-adotar')) {
            const id = e.target.dataset.id;
            const nome = e.target.dataset.nome;
            modalNomeGato.textContent = nome;
            gatoIdInput.value = id;
            modal.style.display = 'block';
        }
    });

    // Fechar o modal
    closeButton.onclick = () => modal.style.display = 'none';
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };

    // Enviar formulário de adoção
    formAdocao.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formAdocao);
        
        try {
            const response = await fetch('api/pedidos/criar.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.success) {
                alert('Pedido de adoção enviado com sucesso! Entraremos em contato em breve.');
                modal.style.display = 'none';
                formAdocao.reset();
            } else {
                alert('Erro ao enviar pedido: ' + result.message);
            }
        } catch (error) {
            alert('Ocorreu um erro na comunicação com o servidor.');
            console.error('Erro no formulário:', error);
        }
    });

    carregarGatos();
});