# Mapeamento do checklist de prova de funcionamento

| Seção | Item | Onde está |
|-------|------|-----------|
| 01 Ambiente | Inicia e conecta ao banco | `INFRA/conexao.php`, `INFRA/bootstrap.php` |
| 01 | Tabelas criadas e relacionadas | `database/schema.sql`, `DOC/modelo_dados.md` |
| 01 | Estrutura e arquivos principais | `README.md` (tabela de pastas) |
| 02 Login | Válidas entram, inválidas com mensagem | `PUBLIC/login.php` |
| 02 | Senhas como hash | `password_hash` em `INFRA/helpers.php`; `password_verify` no login |
| 02 | Sessão e perfil | `$_SESSION['administrador']` em `PUBLIC/login.php` |
| 02 | Comum não acessa área admin, nem pela URL | `exigir_admin()` em `PUBLIC/usuarios*.php` |
| 02 | Sair encerra e bloqueia | `PUBLIC/logout.php` + `exigir_login()` em toda página |
| 03 Usuários | CRUD (inclui busca por nome/e-mail) | `PUBLIC/usuarios.php`, `usuarios_editar.php`, `usuarios_apagar.php` |
| 03 | Validação de campos | `validar_usuario()` |
| 03 | Sem dados sensíveis | A senha/hash nunca é selecionada para a tela |
| 04 Trens | CRUD com validação | `PUBLIC/trens*.php`, `validar_trem()` |
| 04 | Usuário responsável | Campo `id_usuario` (FK) |
| 05 Rotas | CRUD com validação; exibe o trem (o estado fica só nas rotas) | `PUBLIC/rotas*.php`, coluna "Trem" |
| 06 Sensores | CRUD com validação; exibe a rota | `PUBLIC/sensores*.php`, coluna "Rota" |
| 06 | Valor x limite de disparo | `status_sensor()` (Normal / Disparado / Sem limite) |
| 07 Integração | Cadeia usuário → trem → rota → sensor | FKs em `database/schema.sql` |
| 07 | Persistência | Todas as telas gravam no MySQL |
| 07 | Exclusão com dependentes | `apagar_registro()` trata o erro 1451 |
| 08 Segurança | Consultas preparadas | `prepare()` + `bind_param()` em todas as queries |
| 08 | Validações e permissões no backend | `validar_*()`, `exigir_login()`, `exigir_admin()`, `exigir_csrf()` |
| 08 | Mensagens claras | `mensagem_erro_banco()` |
| 09 Organização | GitHub, commits, README | `README.md` (commits e Projects: feitos no GitHub) |
| 09 | Pesquisa com fontes | `DOC/pesquisas/apis.md` |

## Roteiro sugerido para a demonstração

1. Mostrar `database/schema.sql` e explicar a cadeia de tabelas.
2. Login com senha errada (mensagem) e depois com o administrador.
3. Criar um usuário comum e entrar com ele; abrir `usuarios.php` pela URL e mostrar o bloqueio.
4. Como administrador: criar trem, rota e sensor (nessa ordem) e mostrar os relacionamentos nas tabelas.
5. Editar e apagar um registro; tentar apagar um trem que tem rota e mostrar a mensagem.
6. Sair e tentar abrir uma página restrita pela URL.
7. Explicar front (`PUBLIC/`, `assets/`), back (`INFRA/`) e banco (`database/`), decisões técnicas e limitações.

## Limitações conhecidas

- Não há recuperação de senha nem limite de tentativas de login.
- Estados limitados a DF, RJ e SC (usados apenas nas rotas).
- O limite de disparo apenas sinaliza o status; não envia alertas.
