// admin/gatos.js
document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('gatos-table-body');
    const modal = document.getElementById('gato-modal');
    const modalTitle = document.getElementById('modal-title');
    const closeButton = document.querySelector('.close-button');
    const addCatBtn = document.getElementById('add-cat-btn');
    const gatoForm = document.getElementById('gato-form');
    const gatoIdInput = document.getElementById('gato_id');

    const API_URL = '../api/gatos/';
    const token = localStorage.getItem('jwt_token');

    // Função para buscar e exibir todos os gatos
    async function fetchGatos() {
        try {
            const response = await fetch(API_URL + 'listar_todos.php', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const gatos = await response.json();

            tableBody.innerHTML = '';
            gatos.forEach(gato => {
                const row = `
                    <tr>
                        <td><img src="../${gato.foto_url}" alt="${gato.nome}" width="60" style="border-radius: 5px;"></td>
                        <td>${gato.nome}</td>
                        <td>${gato.idade}</td>
                        <td>${gato.sexo}</td>
                        <td>${gato.status}</td>
                        <td class="action-buttons">
                            <button class="btn btn-secondary btn-edit" data-id="${gato.id}">Editar</button>
                            <button class="btn btn-danger btn-delete" data-id="${gato.id}" data-nome="${gato.nome}">Excluir</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } catch (error) {
            console.error('Erro ao buscar gatos:', error);
            tableBody.innerHTML = '<tr><td colspan="6">Erro ao carregar os dados.</td></tr>';
        }
    }

    // Abrir o modal para adicionar um novo gato
    addCatBtn.addEventListener('click', () => {
        gatoForm.reset();
        gatoIdInput.value = '';
        modalTitle.textContent = 'Adicionar Novo Gato';
        modal.style.display = 'block';
    });

    // Fechar o modal
    closeButton.onclick = () => modal.style.display = 'none';
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };

    // Salvar (Adicionar ou Editar)
    gatoForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(gatoForm);
        
        try {
            const response = await fetch(API_URL + 'cadastrar.php', {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}` },
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                modal.style.display = 'none';
                fetchGatos(); // Atualiza a tabela
            } else {
                alert('Erro: ' + result.message);
            }
        } catch (error) {
            console.error('Erro ao salvar gato:', error);
            alert('Ocorreu um erro de comunicação com o servidor.');
        }
    });

    // Lógica para os botões de Editar e Excluir
    tableBody.addEventListener('click', async (e) => {
        // Botão de Excluir
        if (e.target.classList.contains('btn-delete')) {
            const id = e.target.dataset.id;
            const nome = e.target.dataset.nome;
            if (confirm(`Tem certeza que deseja excluir o gato "${nome}"?`)) {
                try {
                    const response = await fetch(API_URL + 'deletar.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify({ id: id })
                    });
                    const result = await response.json();
                    if (result.success) {
                        fetchGatos(); // Atualiza a tabela
                    } else {
                        alert('Erro ao excluir: ' + result.message);
                    }
                } catch (error) {
                    console.error('Erro ao excluir:', error);
                }
            }
        }

        // Botão de Editar
        if (e.target.classList.contains('btn-edit')) {
            const id = e.target.dataset.id;
            // Busca os dados completos do gato para preencher o formulário
            const response = await fetch(`${API_URL}listar_todos.php?id=${id}`, {
                 headers: { 'Authorization': `Bearer ${token}` }
            });
            const gato = await response.json();

            // Preenche o formulário
            gatoIdInput.value = gato.id;
            document.getElementById('nome').value = gato.nome;
            document.getElementById('idade').value = gato.idade;
            document.getElementById('sexo').value = gato.sexo;
            document.getElementById('descricao').value = gato.descricao;
            document.getElementById('status').value = gato.status;

            modalTitle.textContent = `Editar Gato: ${gato.nome}`;
            modal.style.display = 'block';
        }
    });

    // Carrega os dados iniciais
    fetchGatos();
});