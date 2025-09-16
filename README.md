# Peludinhos.Pet

!🐾[Peludinhos.Pet Logo](https://www.ufopa.edu.br/media/file/site/ufopa/imagens/2019/38b84d3ff194a8c533af5d2d294006b0_540x360.png) <!-- Substitua por um logo real do projeto -->

## 🐾 Bem-vindo ao Peludinhos.Pet! 🐾

Este é o projeto de adoção de animais de estimação desenvolvido como parte do projeto "Peludinhos" da Universidade Federal do Oeste do Pará (UFOPA). Nosso objetivo é conectar animais necessitados com lares amorosos, facilitando o processo de adoção e promovendo o bem-estar animal.

---

## ✨ Funcionalidades Principais

- **Listagem de Animais:** Navegue por uma galeria de animais disponíveis para adoção, com fotos e informações detalhadas.
- **Perfis Detalhados:** Cada animal possui um perfil completo com sua história, características, necessidades especiais e informações de contato para adoção.
- **Filtros de Busca:** Encontre o pet ideal utilizando filtros por espécie, raça, idade, porte e localização.
- **Processo de Adoção Simplificado:** Um fluxo intuitivo para interessados em adotar, com formulários e orientações claras.
- **Gerenciamento de Animais (para administradores/ONGs):** Ferramentas para adicionar, editar e remover animais da plataforma.

---

## 🚀 Como Começar (Para Usuários Finais)

1. **Acesse a Plataforma:** Visite [URL_DA_PLATAFORMA_AQUI] (em breve!).
2. **Navegue pelos Animais:** Explore os perfis dos animais disponíveis.
3. **Entre em Contato:** Se encontrar um amigo peludo que te encante, siga as instruções no perfil dele para iniciar o processo de adoção.

---

## 💻 Configuração do Ambiente de Desenvolvimento (Para Desenvolvedores)

Se você deseja contribuir com o projeto ou executá-lo localmente, siga os passos abaixo:

### Pré-requisitos

Certifique-se de ter os seguintes softwares instalados em sua máquina:

- **PHP:** Versão 7.4 ou superior.
- **Composer:** Gerenciador de dependências PHP.
- **MySQL/MariaDB:** Servidor de banco de dados.
- **Servidor Web:** Apache ou Nginx (XAMPP/LAMPP são recomendados para um ambiente local fácil).
- **Git:** Para clonar o repositório.

### Passos para Instalação

1. **Clone o Repositório:**
   ```bash
   git clone https://github.com/seu-usuario/Peludinhos.Pet.git # Substitua pelo link real do repositório
   cd Peludinhos.Pet
   ```

2. **Instale as Dependências do Composer:**
   ```bash
   composer install
   ```

3. **Configure o Banco de Dados:**
   - Crie um banco de dados MySQL/MariaDB para o projeto (ex: `peludinhos_pet`).
   - Copie o arquivo de exemplo de configuração de ambiente:
     ```bash
     cp .env.example .env
     ```
   - Edite o arquivo `.env` e configure as credenciais do seu banco de dados:
     ```dotenv
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=peludinhos_pet
     DB_USERNAME=seu_usuario_mysql
     DB_PASSWORD=sua_senha_mysql
     ```

4. **Execute as Migrações do Banco de Dados:**
   ```bash
   php artisan migrate
   ```

5. **Gere a Chave da Aplicação:**
   ```bash
   php artisan key:generate
   ```

6. **Configure o Servidor Web:**
   - Se estiver usando XAMPP/LAMPP, mova a pasta `Peludinhos.Pet` para `htdocs` (ou `www`).
   - Configure um Virtual Host para apontar para a pasta `public` do projeto (recomendado para URLs amigáveis).
   - Alternativamente, você pode usar o servidor de desenvolvimento embutido do PHP:
     ```bash
     php artisan serve
     ```
     (Acesse em `http://127.0.0.1:8000`)

7. **Acesse o Projeto:**
   Abra seu navegador e acesse a URL configurada para o projeto (ex: `http://localhost/Peludinhos.Pet/public` ou `http://peludinhos.test` se configurou um Virtual Host).

---

## 🤝 Contribuição

Adoramos contribuições! Se você tem ideias, encontrou um bug ou quer adicionar uma nova funcionalidade, por favor, abra uma `issue` ou envie um `pull request`. Consulte nosso [CONTRIBUTING.md](CONTRIBUTING.md) (se houver) para mais detalhes.

---

## 📄 Licença

Este projeto está licenciado sob a Licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## 📞 Contato

Para dúvidas ou informações adicionais, entre em contato com a equipe do projeto Peludinhos da UFOPA.

**Desenvolvido com ❤️ pela UFOPA.**
