// admin/dashboard.js
document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('pedidos-table-body'); // Certifique-se que seu <tbody> tenha este ID
    const token = localStorage.getItem('jwt_token');

    // Função para buscar e renderizar os pedidos na tabela
    async function fetchPedidos() {
        try {
            const response = await fetch('../api/pedidos/listar.php', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const pedidos = await response.json();

            tableBody.innerHTML = ''; // Limpa a tabela antes de preencher

            if (pedidos.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Nenhum pedido de adoção encontrado.</td></tr>';
                return;
            }

            pedidos.forEach(pedido => {
                const row = document.createElement('tr');

                // Formata a data para o padrão brasileiro
                const dataPedido = new Date(pedido.data_pedido).toLocaleDateString('pt-BR');

                row.innerHTML = `
                    <td>${pedido.gato_nome}</td>
                    <td>${pedido.nome_interessado}</td>
                    <td>${pedido.email_interessado}<br>${pedido.telefone_interessado}</td>
                    <td>${dataPedido}</td>
                    <td>${pedido.status}</td>
                    <td class="action-buttons">
                        ${pedido.status === 'Pendente' ? `
                            <button class="btn btn-success btn-approve" data-id="${pedido.id}" data-gato-id="${pedido.gato_id}">Aprovar</button>
                            <button class="btn btn-danger btn-reject" data-id="${pedido.id}" data-gato-id="${pedido.gato_id}">Rejeitar</button>
                        ` : `<span>Processado</span>`
                        }
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Erro ao buscar pedidos:', error);
            tableBody.innerHTML = '<tr><td colspan="6">Erro ao carregar os dados. Tente novamente.</td></tr>';
        }
    }

    // Função para atualizar o status de um pedido
    async function updateStatus(pedidoId, gatoId, newStatus) {
        const actionText = newStatus === 'Aprovado' ? 'aprovar' : 'rejeitar';
        if (!confirm(`Tem certeza que deseja ${actionText} este pedido?`)) {
            return;
        }

        try {
            const response = await fetch('../api/pedidos/atualizar_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ id: pedidoId, gato_id: gatoId, status: newStatus })
            });

            const result = await response.json();

            if (result.success) {
                alert('Pedido atualizado com sucesso!');
                fetchPedidos(); // Recarrega a tabela para mostrar o status atualizado
            } else {
                alert('Erro ao atualizar o pedido: ' . result.message);
            }
        } catch (error) {
            console.error('Erro na requisição de atualização:', error);
            alert('Ocorreu um erro de comunicação com o servidor.');
        }
    }

    // Adiciona o listener de eventos na tabela para os botões (event delegation)
    tableBody.addEventListener('click', (e) => {
        const target = e.target;
        const pedidoId = target.dataset.id;
        const gatoId = target.dataset.gatoId;

        if (target.classList.contains('btn-approve')) {
            updateStatus(pedidoId, gatoId, 'Aprovado');
        }

        if (target.classList.contains('btn-reject')) {
            updateStatus(pedidoId, gatoId, 'Rejeitado');
        }
    });

    // Carrega os pedidos quando a página é aberta
    fetchPedidos();
});