# Decisões aprovadas

Este documento registra decisões funcionais e técnicas aprovadas durante o
desenvolvimento. Ele complementa a especificação e não substitui seus requisitos.

## 15 de novembro de 2026 — Conclusão da Etapa 2

### Ciclo de vida dos perfis de cientistas

- Um perfil com status `rejeitada` permanece definitivamente rejeitado. Ele não
  poderá voltar para `pendente` nem ser publicado posteriormente.
- No cadastro direto de uma cientista, o administrador poderá escolher o status
  inicial entre `publicada` e `oculta`.
- O catálogo público será ordenado alfabeticamente pelo nome da cientista.
- As listagens administrativas serão ordenadas da alteração mais recente para a
  mais antiga.

### Data de publicação

- A estrutura definitiva do banco incluirá o campo `publicado_em`.
- Esse campo será usado para identificar e ordenar os perfis publicados mais
  recentemente na página inicial.
- `publicado_em` registrará a publicação mais recente: será preenchido na
  primeira publicação e atualizado quando um perfil oculto for republicado.

### Dados da cientista e exclusão

- O campo `data_nascimento` continuará utilizando o tipo `DATE`. Quando houver
  apenas o ano ou uma informação parcial, ele ficará vazio (`NULL`).
- A exclusão administrativa de uma cientista será permanente e também deverá
  remover do servidor a fotografia associada, quando existir.

### Senhas e escopo

- O cadastro público exigirá senha com mínimo de 6 e máximo de 72 caracteres.
- A senha inicial simplificada do administrador permanece restrita ao ambiente
  local de desenvolvimento e demonstração.
- Recuperação e alteração de senha, alteração de dados da conta, validação de
  e-mail, bloqueio de contas e criação de novos administradores pela interface
  permanecem fora do escopo da primeira versão.

## 15 de novembro de 2026 — Arquitetura aprovada

- A arquitetura detalhada aprovada está registrada em
  [`ARQUITETURA.md`](ARQUITETURA.md).
- O diretório `public/` será a única raiz acessível pelo navegador.
- A aplicação utilizará funções PHP organizadas por responsabilidade, sem
  framework Back-end, ORM ou camadas adicionais.
- Fotografias serão armazenadas fora da área pública e entregues por uma página
  PHP que verifica a autorização de acesso.
- O Bootstrap será mantido localmente para não depender de internet na
  demonstração escolar.
