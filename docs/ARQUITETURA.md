# Arquitetura aprovada

**Data de aprovação:** 15 de novembro de 2026  
**Etapa:** 3 — Definição da arquitetura e decisões técnicas

Esta arquitetura aprovada prioriza segurança, simplicidade e facilidade de compreensão no contexto escolar.

## Princípios

- PHP tradicional, sem framework Back-end, ORM ou arquitetura complexa.
- HTML5, CSS3, JavaScript tradicional e Bootstrap no Front-end.
- MySQL ou MariaDB acessado exclusivamente pela extensão `mysqli`.
- Duas tabelas principais: `usuarios` e `cientistas`.
- Funções PHP pequenas, reutilizáveis e agrupadas por responsabilidade.
- Regras de negócio, validação e autorização aplicadas no Back-end.

## Organização de diretórios

```text
snct_women_science/
├── app/
│   ├── config/
│   │   ├── bootstrap.php
│   │   └── conexao.php
│   ├── componentes/
│   │   ├── cabecalho.php
│   │   └── rodape.php
│   └── funcoes/
│       ├── comuns.php
│       ├── autenticacao.php
│       ├── csrf.php
│       ├── flash.php
│       ├── validacao.php
│       ├── upload.php
│       ├── usuarios.php
│       └── cientistas.php
├── database/
│   ├── schema.sql
│   └── criar_administrador.php
├── docs/
├── public/
│   ├── index.php
│   ├── catalogo.php
│   ├── cientista.php
│   ├── imagem.php
│   ├── cadastro.php
│   ├── login.php
│   ├── logout.php
│   ├── indicar-cientista.php
│   ├── admin/
│   │   ├── index.php
│   │   ├── usuarios.php
│   │   ├── cientistas.php
│   │   ├── cientista-formulario.php
│   │   ├── cientista-salvar.php
│   │   ├── cientista-status.php
│   │   └── cientista-excluir.php
│   └── assets/
│       ├── css/
│       ├── js/
│       ├── img/
│       └── vendor/bootstrap/
├── storage/uploads/cientistas/
├── .env
├── .env.example
└── .gitignore
```

O servidor web servirá somente `public/`. Configurações, módulos internos e fotografias enviadas ficarão fora do acesso direto pelo navegador.

| Área | Responsabilidade |
| --- | --- |
| `public/` | Páginas acessíveis pelo navegador e arquivos estáticos. |
| `app/config/` | Inicialização, ambiente e conexão `mysqli`. |
| `app/funcoes/` | Autenticação, CSRF, validação, upload e banco. |
| `app/componentes/` | Cabeçalho e rodapé compartilhados. |
| `database/` | Estrutura SQL e criação manual do administrador inicial. |
| `storage/` | Fotografias enviadas. |

As páginas recebem a requisição, verificam autorização, chamam funções e apresentam a resposta. Não haverá classes obrigatórias nem camadas extras.

## Páginas e requisições

| Página | Método | Finalidade |
| --- | --- | --- |
| `index.php` | GET | Página inicial, destaques e publicações recentes. |
| `catalogo.php` | GET | Catálogo público, em ordem alfabética. |
| `cientista.php?id=...` | GET | Perfil de cientista publicada. |
| `imagem.php?id=...` | GET | Entrega controlada de fotografias. |
| `cadastro.php` | GET/POST | Cadastro público de usuário comum. |
| `login.php` | GET/POST | Autenticação. |
| `logout.php` | POST | Encerramento seguro da sessão. |
| `indicar-cientista.php` | GET/POST | Indicação por usuário autenticado. |
| `admin/index.php` | GET | Painel com quantitativos básicos. |
| `admin/usuarios.php` | GET | Consulta de usuários. |
| `admin/cientistas.php` | GET | Consulta administrativa de cientistas. |
| `admin/cientista-formulario.php` | GET | Formulário de cadastro ou edição. |
| `admin/cientista-salvar.php` | POST | Criação direta ou edição administrativa. |
| `admin/cientista-status.php` | POST | Alteração controlada de status. |
| `admin/cientista-excluir.php` | POST | Exclusão permanente de cientista. |

Operações que modificam dados usarão POST, token CSRF e redirecionamento após a conclusão. Dados sensíveis não serão enviados por URL.

## Banco de dados

O banco utilizará `utf8mb4` e duas tabelas principais.

### `usuarios`

| Campo | Tipo e regra |
| --- | --- |
| `id_usuario` | `INT UNSIGNED`, chave primária e auto incremento. |
| `nome_completo` | `VARCHAR(150)`, obrigatório. |
| `email` | `VARCHAR(190)`, obrigatório, único e armazenado em minúsculas. |
| `senha_hash` | `VARCHAR(255)`, obrigatório. |
| `tipo_usuario` | `ENUM('usuario', 'administrador')`, padrão `usuario`. |
| `criado_em` | `TIMESTAMP`, criação do registro. |
| `atualizado_em` | `TIMESTAMP`, atualização automática. |
| `alterado_por_usuario_id` | Chave estrangeira opcional para `usuarios.id_usuario`. |

O e-mail terá índice único. `alterado_por_usuario_id` usará `ON DELETE SET NULL` e ficará nulo na criação inicial da conta.

### `cientistas`

| Campo | Tipo e regra |
| --- | --- |
| `id_cientista` | `INT UNSIGNED`, chave primária e auto incremento. |
| `nome_completo` | `VARCHAR(180)`, obrigatório. |
| `nacionalidade` | `VARCHAR(100)`, opcional. |
| `data_nascimento` | `DATE`, opcional e somente com data completa. |
| `area_atuacao` | `VARCHAR(255)`, obrigatório. |
| `biografia` | `TEXT`, obrigatório. |
| `contribuicoes` | `TEXT`, obrigatório. |
| `informacoes_adicionais` | `TEXT`, opcional. |
| `imagem_path` | `VARCHAR(255)`, opcional, com nome ou caminho interno. |
| `fontes_referencias` | `TEXT`, obrigatório. |
| `status` | `ENUM('pendente','publicada','rejeitada','oculta')`. |
| `usuario_indicador_id` | Chave estrangeira opcional para quem realizou a indicação. |
| `analisado_por_usuario_id` | Chave estrangeira opcional para a última decisão. |
| `analisado_em` | `DATETIME`, data da última decisão administrativa. |
| `publicado_em` | `DATETIME`, publicação ou republicação mais recente. |
| `criado_em` | `TIMESTAMP`, criação do registro. |
| `atualizado_em` | `TIMESTAMP`, atualização automática. |
| `alterado_por_usuario_id` | Chave estrangeira opcional para a última edição. |

Serão criados índices para `status` com `nome_completo`, `status` com `publicado_em`, `atualizado_em` e as chaves estrangeiras. As relações de análise e alteração usarão `ON DELETE SET NULL`; `usuario_indicador_id` usará `ON DELETE CASCADE`.

## Status e auditoria

| Operação | Status | Auditoria |
| --- | --- | --- |
| Usuário envia indicação | `pendente` | Indicador e última alteração recebem o usuário. |
| Administrador aprova | `publicada` | Administrador, análise e `publicado_em`. |
| Administrador rejeita | `rejeitada` | Administrador e data da análise. |
| Administrador oculta | `oculta` | Administrador e data da decisão. |
| Administrador republica | `publicada` | Atualiza decisão e `publicado_em`. |
| Administrador cadastra diretamente | `publicada` ou `oculta` | Indicador nulo e administrador responsável. |
| Administrador edita | Mantém o status | Atualiza responsável e data de alteração. |

O Back-end recusará transições não permitidas. Um perfil rejeitado permanecerá definitivamente rejeitado e não poderá ser republicado.

## Autenticação, validação e segurança

- Senhas usarão `password_hash()` e `password_verify()`, com 6 a 72 caracteres.
- O cadastro público sempre criará contas do tipo `usuario`.
- A sessão será regenerada após login válido.
- Cookies de sessão usarão `HttpOnly` e `SameSite=Lax`; em HTTPS, também `Secure`.
- Logout e alterações de dados usarão POST e token CSRF.
- `exigirLogin()` protegerá indicação e `exigirAdministrador()` protegerá administração.
- Consultas utilizarão prepared statements com `mysqli`.
- Saídas HTML usarão `htmlspecialchars()` por uma função compartilhada.
- Perfis e imagens não publicados ficarão indisponíveis a visitantes.
- Mensagens públicas não exibirão detalhes de banco, caminhos ou credenciais.

E-mails serão normalizados para minúsculas e validados. Identificadores serão inteiros positivos. Datas inválidas ou futuras serão recusadas. Textos serão tratados como texto simples, sem HTML.

| Campo | Limite |
| --- | ---: |
| Nome da cientista | 180 caracteres |
| Nacionalidade | 100 caracteres |
| Área de atuação | 255 caracteres |
| Biografia | 10.000 caracteres |
| Contribuições | 10.000 caracteres |
| Informações adicionais | 5.000 caracteres |
| Fontes e referências | 5.000 caracteres |

Não serão implementados recuperação ou alteração de senha, alteração de dados da conta, validação de e-mail, bloqueio de contas ou criação de administradores pela interface.

## Fotografias

Serão aceitas imagens JPEG, PNG e WebP de até 5 MB. O Back-end validará erro de upload, tamanho, tipo MIME real, extensão e dimensões. Cada arquivo receberá um nome aleatório seguro e será armazenado em `storage/uploads/cientistas/`.

O banco guardará apenas o caminho interno. Na troca de fotografia, a imagem anterior será removida após atualização bem-sucedida do banco. Na exclusão de uma cientista, a fotografia associada também será removida; falhas de limpeza serão registradas no log técnico.

## Configuração, Bootstrap e testes

O `.env` terá configurações de ambiente, URL base, fuso horário, cookie seguro e conexão com o banco. O `.env.example` terá as mesmas chaves sem credenciais.

```ini
APP_ENV=desenvolvimento
APP_URL=http://localhost/mulheres-na-ciencia
APP_TIMEZONE=America/Fortaleza
SESSION_SECURE_COOKIE=false

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=mulheres_na_ciencia
DB_USER=
DB_PASSWORD=
```

O `.env` e arquivos enviados ficarão fora do Git. Erros internos serão enviados ao log do servidor; a interface exibirá somente mensagens seguras.

Será usada uma cópia local do Bootstrap em `public/assets/vendor/bootstrap/`, evitando dependência de internet durante a demonstração escolar.

Os testes iniciais serão manuais e incrementais, sem framework adicional. Cada entrega incluirá `php -l`, teste de banco, formulários, permissões, transições de status, upload, CSRF, XSS, acesso público e responsividade.
