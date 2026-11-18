# Plano de desenvolvimento

**Aprovação:** 15 de novembro de 2026  
**Estado atual:** Entregas 1, 2 e 3 concluídas e validadas; Entrega 4 implementada, aguardando validação manual.

Este plano organiza o desenvolvimento do portal Mulheres na Ciência em entregas
pequenas, dependentes e validáveis. Cada entrega só poderá começar após a
autorização explícita do usuário.

## Sequência aprovada

```text
1. Fundação e banco
        ↓
2. Layout compartilhado
        ↓
3. Cadastro e autenticação
        ↓
4. Indicação de cientistas
        ↓
5. Consulta administrativa
        ↓
6. Gestão administrativa de perfis
        ↓
7. Moderação e status
        ↓
8. Catálogo e perfil público
        ↓
9. Página inicial dinâmica
        ↓
Integração geral → revisão final
```

## Controle das entregas

| Entrega | Estado |
| --- | --- |
| 1. Fundação segura e banco de dados | Concluída; validação manual em MySQL/MariaDB confirmada pelo usuário. |
| 2. Layout compartilhado e página inicial básica | Concluída; validação visual confirmada pelo usuário. |
| 3. Cadastro, login e logout | Concluída; validação manual confirmada pelo usuário. |
| 4. Indicação de cientistas | Implementada; aguardando validação manual do usuário. |
| 5. Consulta administrativa | Aguardando a Entrega 4. |
| 6. Gestão administrativa de perfis | Aguardando a Entrega 5. |
| 7. Moderação e controle de publicação | Aguardando a Entrega 6. |
| 8. Catálogo e perfis públicos | Aguardando a Entrega 7. |
| 9. Página inicial dinâmica | Aguardando a Entrega 8. |

## Entrega 1 — Fundação segura e banco de dados

**Objetivo:** preparar a base mínima para o restante da aplicação.

- Funcionalidades: configurações por `.env`, conexão `mysqli`, schema do banco,
  criação manual do administrador inicial e proteção de arquivos sensíveis no Git.
- Arquivos previstos: `.env.example`, `.gitignore`, `app/config/bootstrap.php`,
  `app/config/conexao.php`, `database/schema.sql` e
  `database/criar_administrador.php`.
- Banco: criação das tabelas `usuarios` e `cientistas`, chaves estrangeiras,
  índices, `publicado_em` e codificação `utf8mb4`.
- Dependências: nenhuma.
- Critérios de aceitação: schema executa sem erros; a conexão lê `.env`; o
  administrador é criado com hash; `.env` não é versionado.
- Testes: conexão real, estrutura das tabelas, chaves, índices e criação do
  administrador inicial.

## Entrega 2 — Layout compartilhado e página inicial básica

**Objetivo:** estabelecer identidade visual, responsividade e navegação reutilizável.

- Funcionalidades: Bootstrap local, variáveis CSS, cabeçalho, rodapé, navegação
  e apresentação estática inicial do projeto.
- Arquivos previstos: `public/assets/`, `app/componentes/cabecalho.php`,
  `app/componentes/rodape.php` e `public/index.php`.
- Banco: nenhuma alteração.
- Dependências: Entrega 1.
- Critérios de aceitação: Bootstrap é carregado localmente; layout funciona em
  celular, tablet e computador; navegação aponta para páginas previstas.
- Testes: carregamento sem CDN e inspeção visual responsiva.
- Observação: destaques e publicações recentes serão integrados na Entrega 9.

## Entrega 3 — Cadastro, login e logout

**Objetivo:** permitir autenticação segura de usuários comuns e administradores.

- Funcionalidades: cadastro público, validação de e-mail único, senha de 6 a 72
  caracteres, login, logout, sessão, CSRF, mensagens temporárias e controle
  básico de acesso.
- Arquivos previstos: `app/funcoes/comuns.php`, `autenticacao.php`, `csrf.php`,
  `flash.php`, `validacao.php`, `usuarios.php`; `public/cadastro.php`,
  `login.php`, `logout.php`; ajustes no cabeçalho.
- Banco: utiliza `usuarios`; nenhuma alteração estrutural.
- Dependências: Entregas 1 e 2.
- Critérios de aceitação: cadastro cria apenas `usuario`; senha fica em hash;
  login regenera a sessão; logout exige POST e CSRF; usuário comum não acessa
  administração.
- Testes: cadastro válido, e-mail duplicado, senha inválida, login correto e
  incorreto, sessão, logout, CSRF e autorização.

## Entrega 4 — Indicação de cientistas por usuários

**Objetivo:** permitir que usuários autenticados enviem indicações com segurança.

- Funcionalidades: formulário de indicação, validação dos dados, upload de
  imagem e confirmação de envio.
- Arquivos previstos: `app/funcoes/cientistas.php`, `upload.php`,
  `public/indicar-cientista.php` e `storage/uploads/cientistas/`.
- Banco: utiliza `cientistas`; nenhuma alteração estrutural.
- Dependências: Entrega 3.
- Critérios de aceitação: visitante não acessa o formulário; indicação sempre
  nasce como `pendente`; indicador vem da sessão; não há acompanhamento ou
  edição pelo usuário; imagens aceitas são JPEG, PNG ou WebP de até 5 MB.
- Testes: envio válido, campos obrigatórios, data futura, arquivo inválido,
  tamanho excedido, CSRF e confirmação após POST.

## Entrega 5 — Consulta administrativa

**Objetivo:** permitir visualização segura dos dados administrativos.

- Funcionalidades: painel com quantitativos, consulta de usuários e consulta de
  cientistas por situação.
- Arquivos previstos: `public/admin/index.php`, `usuarios.php`,
  `cientistas.php` e suas funções de consulta e contagem.
- Banco: consultas e contagens nas tabelas existentes.
- Dependências: Entregas 3 e 4.
- Critérios de aceitação: apenas administrador acessa; senhas nunca aparecem;
  cientistas são ordenadas pela alteração mais recente; painel mostra totais
  básicos por status.
- Testes: bloqueio de visitante e usuário comum; validação dos quantitativos e
  da ordenação.

## Entrega 6 — Cadastro, edição e exclusão administrativa

**Objetivo:** permitir que administradores gerenciem os dados dos perfis.

- Funcionalidades: cadastro direto com status `publicada` ou `oculta`, edição
  de qualquer perfil e exclusão permanente com remoção da fotografia.
- Arquivos previstos: `public/admin/cientista-formulario.php`,
  `cientista-salvar.php`, `cientista-excluir.php`, `public/imagem.php` e ajustes
  em `cientistas.php` e `upload.php`.
- Banco: utiliza a estrutura existente.
- Dependências: Entregas 4 e 5.
- Critérios de aceitação: cadastro direto não define indicador; administrador
  edita inclusive pendentes; substituição remove imagem anterior; exclusão
  remove o registro e sua fotografia.
- Testes: cadastro nos dois status permitidos, edição, troca de imagem,
  exclusão, CSRF e acesso não autorizado.

## Entrega 7 — Moderação e controle de publicação

**Objetivo:** implementar as transições de status aprovadas.

- Funcionalidades: aprovar pendentes, rejeitar definitivamente, ocultar
  publicadas e republicar ocultas.
- Arquivos previstos: `public/admin/cientista-status.php`; ajustes em
  `admin/cientistas.php` e `app/funcoes/cientistas.php`.
- Banco: atualizações de `status`, `analisado_por_usuario_id`, `analisado_em`,
  `publicado_em` e `alterado_por_usuario_id`.
- Dependências: Entregas 5 e 6.
- Critérios de aceitação: pendente só muda para publicada ou rejeitada;
  rejeitada nunca volta; publicação e republicação atualizam `publicado_em`;
  ocultação remove a visibilidade pública.
- Testes: transições válidas, transições inválidas, auditoria e CSRF.

## Entrega 8 — Catálogo e perfis públicos

**Objetivo:** tornar disponíveis somente os conteúdos autorizados.

- Funcionalidades: catálogo alfabético, perfil individual e entrega controlada
  de imagens.
- Arquivos previstos: `public/catalogo.php`, `public/cientista.php`,
  `public/imagem.php` e consultas públicas em `cientistas.php`.
- Banco: consultas com filtro obrigatório `status = 'publicada'`.
- Dependências: Entrega 7.
- Critérios de aceitação: catálogo mostra foto quando houver, nome e área;
  perfil mostra dados completos; pendentes, rejeitadas e ocultas são
  indisponíveis mesmo por URL direta.
- Testes: catálogo com diferentes estados, perfil por ID, imagens públicas e
  tentativas de acessar perfil ou imagem não publicados.

## Entrega 9 — Página inicial dinâmica

**Objetivo:** concluir a página inicial com dados reais do catálogo.

- Funcionalidades: destaques e cientistas publicadas recentemente, ordenadas
  por `publicado_em`.
- Arquivos previstos: atualização de `public/index.php` e consultas em
  `app/funcoes/cientistas.php`.
- Banco: consulta de perfis `publicada` por `publicado_em`.
- Dependências: Entrega 8.
- Critérios de aceitação: página apresenta propósito, navegação, destaques e
  publicações recentes; apenas perfis publicados aparecem.
- Testes: sem cientistas, com cientistas publicadas, com perfis ocultos e após
  republicação.

## Etapa 6 — Integração e testes gerais

Após as entregas aprovadas, serão verificados os fluxos completos de cadastro,
login, indicação, moderação, publicação, exclusão, permissões, imagens,
responsividade e regressões.

## Etapa 7 — Revisão e documentação final

Serão revisados organização, segurança, aderência à especificação, qualidade
pedagógica do código, documentação, arquivos ignorados e pendências.
