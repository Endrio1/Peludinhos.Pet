# Peludinhos UFOPA - Aplicação de Adoção de Gatos (PHP + MySQL)

Instruções rápidas:

1. Importe o SQL em `sql/schema.sql` no seu MySQL (por exemplo, usando phpMyAdmin ou `mysql` CLI).
2. Ajuste credenciais em `includes/config.php` (usuário/senha do MySQL).
3. Coloque o projeto numa pasta acessível pelo servidor web (por exemplo `/opt/lampp/htdocs/peludinhos`).
4. Acesse `http://localhost/peludinhos/`.

Configuração específica para XAMPP / permissões (uploads) no Linux
-----------------------------------------------------
Para que o upload de imagens funcione corretamente com XAMPP (lampp) é necessário garantir que o usuário do processo do Apache consiga escrever na pasta `uploads/`.

1. Identifique o usuário do Apache (ex.: `daemon`, `www-data`, `nobody`) — no XAMPP geralmente é `daemon`.

2. Execute (como usuário com `sudo`) os comandos abaixo (ajuste `daemon:daemon` se o seu Apache usar outro usuário/grupo):

```bash
sudo chown -R daemon:daemon /opt/lampp/htdocs/peludinhos/Peludinhos.Pet/uploads /opt/lampp/htdocs/peludinhos/Peludinhos.Pet/tmp
```
-------------------------
1. Acesse a área administrativa e crie/edite um gato, envie uma imagem (JPEG/PNG/WEBP). Se funcionar, o arquivo aparecerá em `uploads/cats/`.

-------------------------
- Implementar autenticação JWT e área admin (`admin/*`).
- Melhorar logs e ferramenta de manutenção (rotacionar/limpar `tmp/`).
