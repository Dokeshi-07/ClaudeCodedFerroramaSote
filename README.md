# Ferrorama

Sistema web em PHP + MySQL para cadastro de **usuários, trens, rotas e sensores**.
Cadeia de dados: **usuário → trem → rota → sensor**.

## Requisitos

- PHP 8.0 ou superior (extensão `mysqli`)
- MySQL ou MariaDB

## Como executar

1. Crie o banco e as tabelas:
   `mysql -u root -p < database/schema.sql`
2. Ajuste os dados de acesso em `INFRA/conexao.php`
   (ou use as variáveis de ambiente `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `DB_PORT`).
3. Crie o primeiro administrador:
   `php SCRIPTS/criar_admin.php <usuario> <email> <senha>`
4. Na raiz do projeto, inicie o servidor:
   `php -S localhost:8000`
5. Acesse `http://localhost:8000` (o `index.php` leva ao sistema).

As imagens das cidades (`joinville_arco.jpg`, `RJ_Cristo.jpg`, `igreja-brasilia.jpg`)
devem ficar em `assets/img/`. Se não existirem, a página aparece sem imagem.

## Estrutura

| Pasta | Conteúdo |
|-------|----------|
| `PUBLIC/` | Telas do sistema (login, trens, rotas, sensores, usuários) |
| `INFRA/` | Conexão, sessão, permissões, validações e partes de tela reutilizadas |
| `database/` | Script SQL das tabelas |
| `SCRIPTS/` | Utilitários de terminal (criar administrador) |
| `assets/` | CSS, JavaScript e imagens |
| `DOC/` | Modelo de dados, mapeamento do checklist e pesquisas |

## Perfis de acesso

- **Comum:** trens, rotas e sensores.
- **Administrador:** tudo isso + gestão de usuários.

## Segurança

- Senhas guardadas com `password_hash`
- Consultas preparadas (SQL Injection)
- Permissões e validações no backend
- Proteção CSRF em todos os formulários
- Mensagens de erro claras, sem expor dados técnicos ou sensíveis

## Licença

MIT (veja `LICENSE`).
