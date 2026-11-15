# Portal Mulheres na Ciência

## Documento de especificação do sistema

**Versão:** 1.1  
**Data:** 15 de novembro de 2026  
**Finalidade:** definir o escopo funcional, os requisitos técnicos e a estrutura inicial do banco de dados para orientar o desenvolvimento do portal.

---

## 1. Apresentação do sistema

O **Mulheres na Ciência** será um portal web informativo e colaborativo dedicado à divulgação de mulheres que contribuíram ou contribuem para o desenvolvimento científico.

A plataforma permitirá que visitantes conheçam as cientistas cujos perfis foram publicados e que pessoas com cadastro na plataforma indiquem novas mulheres para compor o catálogo. As indicações feitas por usuários não serão publicadas automaticamente: todas deverão passar pela análise de um administrador.

### 1.1. Objetivo

Valorizar e dar visibilidade à participação feminina na ciência por meio da divulgação de biografias, trajetórias profissionais, áreas de atuação, descobertas e contribuições científicas, reunidas em um catálogo de acesso público.

### 1.2. Tipos de acesso

O sistema terá três tipos de acesso:

| Perfil                 | Permissões principais                                        |
| ---------------------- | ------------------------------------------------------------ |
| **Visitante**          | Acessar a página inicial, consultar o catálogo público e visualizar os perfis publicados. Também poderá criar uma conta. |
| **Usuário cadastrado** | Realizar login e enviar indicações de mulheres cientistas para análise administrativa. Também poderá utilizar as funcionalidades públicas do portal. |
| **Administrador**      | Acessar a área administrativa, consultar usuários, gerenciar perfis de cientistas, analisar indicações e controlar a publicação dos conteúdos. |

O perfil de uma cientista **não representa uma conta de acesso**. Uma pessoa usuária poderá indicar uma cientista histórica ou contemporânea sem que essa cientista precise se cadastrar na plataforma.

### 1.3. Escopo da primeira versão

A primeira versão deverá priorizar:

- Cadastro e autenticação de usuários.
- Envio de indicações de mulheres cientistas por usuários autenticados.
- Aprovação ou rejeição obrigatória das indicações por um administrador.
- Catálogo público com listagem simples de perfis publicados.
- Página individual para cada cientista publicada.
- Área administrativa para gerenciamento e moderação dos perfis.

O catálogo não terá pesquisa por nome nem filtros por área de atuação nesta versão.

---

## 2. Requisitos funcionais

Os requisitos funcionais descrevem os serviços e comportamentos que o sistema deverá oferecer.

### RF01 — Página inicial

O sistema deverá disponibilizar uma página principal que apresente a plataforma, seu propósito e formas de acesso ao conteúdo.

A página inicial deverá:

- Apresentar uma breve descrição do projeto Mulheres na Ciência.
- Exibir destaques de cientistas com perfis publicados.
- Apresentar algumas das cientistas publicadas recentemente.
- Oferecer acesso ao catálogo, ao cadastro de usuário e ao login.

### RF02 — Catálogo público de cientistas

O sistema deverá disponibilizar uma página com a listagem das mulheres cientistas cujos perfis tenham sido aprovados e estejam publicados.

O catálogo deverá:

- Exibir, no mínimo, fotografia (quando disponível), nome e área de atuação.
- Permitir que o visitante acesse a página individual de cada cientista.
- Apresentar somente registros com situação **Publicada**.
- Não oferecer pesquisa por nome ou filtros por área nesta versão.

### RF03 — Perfil individual da cientista

O sistema deverá apresentar uma página individual com as informações disponíveis de cada cientista publicada.

O perfil poderá apresentar:

- Nome completo.
- Fotografia, quando disponível.
- Nacionalidade, quando disponível.
- Data de nascimento, quando disponível.
- Área de atuação científica.
- Biografia e trajetória profissional/científica.
- Principais descobertas, pesquisas e contribuições para a ciência.
- Informações adicionais.
- Fontes e referências cadastradas.

Perfis pendentes, rejeitados ou ocultos não poderão ser exibidos publicamente, inclusive quando alguém tentar acessar diretamente o endereço de uma página individual.

### RF04 — Cadastro de usuários

O sistema deverá permitir que visitantes criem uma conta para enviar indicações de mulheres cientistas.

O formulário de cadastro deverá coletar:

- Nome completo.
- E-mail.
- Senha.

Regras do cadastro:

- O e-mail deverá ser único.
- A senha deverá ser armazenada somente em formato de hash seguro, nunca em texto puro.
- Todo cadastro público deverá receber automaticamente o perfil **Usuário**.
- O visitante não poderá escolher ou atribuir a si mesmo o perfil de Administrador.
- A criação de contas administrativas não será disponibilizada no cadastro público.

### RF05 — Autenticação de usuários

O sistema deverá permitir que usuários cadastrados e administradores acessem as funcionalidades restritas por meio de autenticação.

O sistema deverá:

- Permitir login utilizando e-mail e senha.
- Permitir o encerramento da sessão (logout).
- Restringir o envio de indicações a usuários autenticados.
- Restringir a área administrativa a usuários com perfil de Administrador.
- Verificar as permissões no Back-end, e não apenas ocultar opções na interface.
- Não disponibilizar cadastro público de administradores. A primeira conta administrativa será criada manualmente, conforme as instruções da seção 5.1.

### RF06 — Indicação de mulheres na ciência

O sistema deverá permitir que usuários autenticados enviem informações sobre mulheres que tiveram ou têm contribuições relevantes para a ciência.

O formulário de indicação deverá coletar:

- Nome completo da cientista.
- Fotografia, quando disponível.
- Nacionalidade, quando disponível.
- Data de nascimento, quando disponível.
- Área de atuação científica.
- Biografia e trajetória profissional/científica.
- Principais descobertas, pesquisas e contribuições científicas.
- Informações adicionais, quando houver.
- Fontes e referências que permitam verificar as informações.

Ao enviar uma indicação, o sistema deverá:

- Registrar o usuário responsável pelo envio.
- Registrar a data de criação.
- Definir automaticamente a situação inicial como **Pendente**.
- Exibir uma confirmação de recebimento do cadastro.
- Manter a indicação indisponível nas páginas públicas até sua aprovação.

Após o envio, o usuário não terá uma área para acompanhar a situação da indicação e não poderá editar ou cancelar o cadastro. Caso sejam necessárias correções, elas deverão ser realizadas por um administrador.

### RF07 — Aprovação obrigatória das indicações

Toda indicação enviada por usuário deverá ser analisada por um administrador antes de ser publicada.

O administrador poderá:

- **Aprovar:** alterar a situação para **Publicada**, tornando o perfil disponível no catálogo e na página individual.
- **Rejeitar:** alterar a situação para **Rejeitada**, mantendo o registro fora das páginas públicas. Não será necessário informar um motivo para a rejeição.

O sistema deverá registrar somente o administrador responsável pela decisão mais recente e a data/hora dessa decisão. Não será mantido um histórico de todas as análises ou mudanças de situação.

Nenhuma indicação enviada por usuário poderá ser publicada automaticamente.

### RF08 — Área administrativa

O sistema deverá disponibilizar uma área restrita para gerenciamento dos usuários e dos perfis de cientistas.

A área administrativa deverá permitir:

- Consultar os usuários cadastrados.
- Consultar indicações pendentes.
- Consultar perfis publicados, rejeitados e ocultos.
- Cadastrar diretamente uma cientista, quando necessário.
- Editar informações de uma cientista, inclusive quando a indicação estiver pendente.
- Excluir registros de cientistas.
- Aprovar ou rejeitar indicações pendentes.
- Ocultar um perfil anteriormente publicado.
- Republicar um perfil que esteja oculto.
- Visualizar quantitativos básicos de usuários e cientistas por situação.

Quando o administrador cadastrar diretamente uma cientista, poderá controlar sua publicação. Esse cadastro não será tratado como uma indicação enviada por usuário comum.

Nesta versão, a área administrativa não oferecerá funcionalidade para excluir contas de usuários.

### RF09 — Controle de publicação

O sistema deverá garantir que somente perfis com situação **Publicada** sejam exibidos nas páginas públicas.

Regras:

- Perfis **Pendentes**, **Rejeitados** ou **Ocultos** não deverão aparecer no catálogo nem ser acessíveis por consulta pública direta.
- Alterações de situação deverão ser realizadas por administrador.
- Um perfil publicado poderá ser ocultado e, posteriormente, republicado por um administrador.
- A exclusão deverá respeitar as regras de integridade do banco de dados.

---

## 3. Regras de negócio

As regras abaixo deverão ser respeitadas em todas as telas, operações e rotinas do sistema.

### RN01 — Cadastro de usuários

Qualquer visitante poderá criar uma conta como usuário comum. O perfil de Administrador deverá ser atribuído somente por um procedimento restrito, fora do cadastro público.

### RN02 — Autenticação para indicar

Somente usuários autenticados poderão enviar indicações de mulheres cientistas.

### RN03 — Situação inicial da indicação

Toda indicação enviada por usuário deverá receber automaticamente a situação `pendente`.

### RN04 — Aprovação obrigatória

Uma indicação só poderá aparecer no portal após um administrador aprová-la e alterar sua situação para `publicada`.

### RN05 — Rejeição

Uma indicação rejeitada deverá permanecer armazenada para consulta administrativa, mas não poderá ser exibida publicamente.

### RN06 — Ocultação e republicação

Um administrador poderá alterar a situação de um perfil publicado para `oculta`. Um perfil oculto poderá voltar a ser exibido quando o administrador alterar sua situação para `publicada`.

### RN07 — Acesso público

As páginas públicas deverão consultar somente cientistas com situação `publicada`. Essa regra também deverá ser aplicada ao acesso direto à página individual.

### RN08 — Separação entre usuário e cientista

A tabela de cientistas armazenará informações biográficas e científicas. Ela não será utilizada para autenticação. A autenticação será realizada exclusivamente por contas cadastradas na tabela de usuários.

### RN09 — Rastreabilidade da última alteração

O sistema deverá registrar as datas de criação e atualização e, quando aplicável, o identificador do usuário responsável pela última alteração. As decisões administrativas deverão registrar apenas o administrador responsável e a data/hora da decisão mais recente. Não será mantido histórico completo de alterações ou decisões.

### RN10 — Indicação após o envio

Depois de enviar uma indicação, o usuário não poderá consultar seu status em uma área própria, editar os dados ou cancelar o envio. Um administrador poderá corrigir as informações diretamente na área administrativa.

### RN11 — Rejeição de indicações

O administrador poderá rejeitar uma indicação sem preencher justificativa ou motivo. O registro permanecerá armazenado com status `rejeitada` e indisponível ao público.

### RN12 — Criação da conta administrativa inicial

A primeira conta administrativa será criada manualmente, fora do cadastro público, utilizando as credenciais iniciais definidas neste documento. A senha deverá ser armazenada com hash seguro.

### RN13 — Exclusão futura de usuários

A exclusão de contas de usuários não fará parte da primeira versão. Caso essa funcionalidade seja implementada futuramente, a exclusão de uma conta deverá remover também, em cascata, as indicações vinculadas ao usuário pelo campo `usuario_indicador_id`. Referências de auditoria para o usuário excluído deverão ser tratadas sem apagar registros de outros usuários.

### RN14 — Indicações duplicadas

A primeira versão não implementará mecanismo automático para identificar, bloquear ou combinar indicações duplicadas.

---

## 4. Requisitos não funcionais

Os requisitos não funcionais estabelecem as tecnologias, padrões de organização, segurança e persistência que deverão orientar a implementação.

### 4.1. Arquitetura do Front-end

- Utilizar HTML5, CSS3 e JavaScript tradicional.
- Utilizar o framework Bootstrap como padrão visual da aplicação.
- Desenvolver páginas responsivas, adaptáveis a computadores, tablets e celulares.
- Utilizar arquivos CSS externos e compartilhados entre as páginas do projeto.
- Definir variáveis CSS para centralizar a paleta de cores e facilitar a padronização visual.
- Organizar os arquivos em pastas específicas para CSS, JavaScript, imagens e demais recursos necessários.
- Enviar dados de formulários e operações que alterem informações por requisições POST.
- Utilizar GET para navegação e apresentação de páginas/consultas públicas, sem transportar senhas ou dados sensíveis.

### 4.2. Arquitetura do Back-end

- Utilizar PHP versão 8.4 ou superior.
- Utilizar MySQL ou MariaDB como sistema gerenciador de banco de dados, com acesso por meio da extensão `mysqli`.
- Organizar funções reutilizáveis em arquivos ou módulos com responsabilidades bem definidas, evitando duplicação de código.
- Comentar os principais blocos do código, explicando sua finalidade e regras relevantes.
- Utilizar um arquivo `.env` para configurações de ambiente e credenciais, como usuário, senha e endereço do banco de dados.
- Não expor o arquivo `.env` publicamente nem versionar credenciais reais.
- Manter em arquivo de configuração as constantes e informações institucionais compartilhadas pela aplicação, quando existirem.
- Validar e tratar no Back-end todos os dados recebidos do Front-end; a validação do navegador não será considerada suficiente.
- Utilizar consultas preparadas (*prepared statements*) com `mysqli` para reduzir o risco de SQL Injection.
- Aplicar codificação/escape adequado na saída de conteúdo para reduzir riscos de XSS.
- Implementar tokens de proteção CSRF nos formulários e operações autenticadas que alterem dados.
- Armazenar senhas utilizando algoritmo apropriado de hash e verificar as credenciais sem armazenar a senha original.
- Controlar sessões e permissões, verificando no servidor se o usuário possui autorização para cada operação restrita ou administrativa.
- Validar arquivos de imagem enviados, incluindo tipo, extensão e tamanho. Armazenar o arquivo em diretório apropriado e registrar no banco apenas seu caminho/nome.
- Não exibir mensagens de erro com credenciais, consultas SQL ou detalhes internos da aplicação.

### 4.3. Banco de dados

- Utilizar MySQL ou MariaDB.
- Utilizar codificação `utf8mb4` para suportar caracteres internacionais.
- Definir chaves primárias e estrangeiras para os relacionamentos necessários.
- Garantir integridade referencial entre as tabelas.
- Criar índice único para o e-mail dos usuários.
- Criar índices para campos utilizados frequentemente em relacionamentos e consultas, especialmente chaves estrangeiras e situação dos perfis.
- Registrar datas de criação e atualização dos registros.
- Identificar o usuário responsável pela última alteração, quando aplicável.
- Não implementar, nesta versão, rotina automática de detecção de perfis de cientistas duplicados. A restrição de e-mail único para contas de usuários permanece obrigatória.

---

## 5. Estrutura preliminar do banco de dados

A proposta inicial utiliza duas tabelas principais: `usuarios` e `cientistas`. Essa estrutura atende ao cadastro e à autenticação de usuários, ao envio de indicações e ao fluxo de aprovação administrativa, sem criar tabelas separadas para áreas científicas ou publicações.

Os tipos de dados apresentados são sugestões iniciais e poderão ser ajustados durante a implementação, desde que sejam preservadas as regras de negócio.

### 5.1. Tabela `usuarios`

Armazena as contas que acessam a plataforma, incluindo usuários comuns e administradores. O campo `tipo_usuario` define o nível de acesso de cada conta.

| Campo                     | Tipo sugerido                      | Obrigatório | Finalidade                                                   |
| ------------------------- | ---------------------------------- | ----------: | ------------------------------------------------------------ |
| `id_usuario`              | `INT UNSIGNED`, PK, AUTO_INCREMENT |         Sim | Identificador único do usuário.                              |
| `nome_completo`           | `VARCHAR(150)`                     |         Sim | Nome da pessoa cadastrada.                                   |
| `email`                   | `VARCHAR(190)`, UNIQUE             |         Sim | Identificação para login; não pode se repetir.               |
| `senha_hash`              | `VARCHAR(255)`                     |         Sim | Hash seguro da senha. Nunca armazenar a senha original.      |
| `tipo_usuario`            | `ENUM('usuario','administrador')`  |         Sim | Define o nível de acesso. O cadastro público cria somente usuários comuns. |
| `criado_em`               | `TIMESTAMP`                        |         Sim | Data e hora de criação do registro.                          |
| `atualizado_em`           | `TIMESTAMP`                        |         Sim | Data e hora da última atualização.                           |
| `alterado_por_usuario_id` | `INT UNSIGNED`, FK, NULL           |         Não | Usuário responsável pela última alteração. É um autorrelacionamento com `usuarios.id_usuario`. |

**Observações:**

- O e-mail deverá possuir restrição `UNIQUE` para impedir contas duplicadas.
- O campo `alterado_por_usuario_id` poderá ser nulo quando não houver um usuário responsável identificado por uma alteração posterior à criação.
- O autorrelacionamento de `alterado_por_usuario_id` deverá referenciar `usuarios.id_usuario`.
- A criação de administradores não deverá ser permitida pelo formulário público de cadastro.

#### Criação manual do primeiro administrador

A primeira conta administrativa deverá ser inserida manualmente no banco de dados, antes da utilização da área administrativa. Não haverá tela pública ou formulário de criação de administradores.

Credenciais iniciais para o ambiente de desenvolvimento/demonstração:

| Campo           | Valor inicial     |
| --------------- | ----------------- |
| E-mail          | `admin@email.com` |
| Senha inicial   | `123456`          |
| Tipo de usuário | `administrador`   |

A senha `123456` é uma credencial inicial simplificada para uso no ambiente escolar de desenvolvimento/demonstração. Ela **não deverá ser armazenada em texto puro**: deverá ser convertida em hash com `password_hash()` do PHP antes da inserção no banco. Não utilizar essa senha em ambiente público ou de produção; antes de qualquer exposição externa, substituir por uma senha forte.

### 5.2. Tabela `cientistas`

Armazena os perfis de mulheres cientistas indicadas pelos usuários ou cadastradas diretamente por um administrador. O campo `status` controla a situação do perfil e sua disponibilidade pública.

| Campo                      | Tipo sugerido                                       | Obrigatório | Finalidade                                                   |
| -------------------------- | --------------------------------------------------- | ----------: | ------------------------------------------------------------ |
| `id_cientista`             | `INT UNSIGNED`, PK, AUTO_INCREMENT                  |         Sim | Identificador único do perfil.                               |
| `nome_completo`            | `VARCHAR(180)`                                      |         Sim | Nome completo da cientista.                                  |
| `nacionalidade`            | `VARCHAR(100)`                                      |         Não | País de origem/nacionalidade, quando conhecido.              |
| `data_nascimento`          | `DATE`                                              |         Não | Data de nascimento, quando disponível.                       |
| `area_atuacao`             | `VARCHAR(255)`                                      |         Sim | Área ou áreas de atuação em texto livre; não haverá tabela de categorias nesta versão. |
| `biografia`                | `TEXT`                                              |         Sim | Biografia e trajetória profissional/científica.              |
| `contribuicoes`            | `TEXT`                                              |         Sim | Descobertas, pesquisas e contribuições relevantes.           |
| `informacoes_adicionais`   | `TEXT`                                              |         Não | Curiosidades e outras informações complementares.            |
| `imagem_path`              | `VARCHAR(255)`                                      |         Não | Caminho/nome do arquivo de imagem armazenado no servidor.    |
| `fontes_referencias`       | `TEXT`                                              |         Sim | Links e/ou referências utilizadas para verificar as informações. |
| `status`                   | `ENUM('pendente','publicada','rejeitada','oculta')` |         Sim | Controla a situação da indicação e sua visibilidade pública. |
| `usuario_indicador_id`     | `INT UNSIGNED`, FK, NULL                            |         Não | Usuário que enviou a indicação. Nulo quando o perfil for criado diretamente pelo administrador. |
| `analisado_por_usuario_id` | `INT UNSIGNED`, FK, NULL                            |         Não | Administrador responsável pela análise ou decisão administrativa mais recente. |
| `analisado_em`             | `DATETIME`                                          |         Não | Data e hora da análise ou decisão administrativa mais recente. |
| `criado_em`                | `TIMESTAMP`                                         |         Sim | Data e hora de criação do registro.                          |
| `atualizado_em`            | `TIMESTAMP`                                         |         Sim | Data e hora da última atualização.                           |
| `alterado_por_usuario_id`  | `INT UNSIGNED`, FK, NULL                            |         Não | Usuário responsável pela última alteração do registro.       |

**Observações:**

- Indicações enviadas por usuários deverão ser gravadas com `status = 'pendente'`.
- Os campos `usuario_indicador_id`, `analisado_por_usuario_id` e `alterado_por_usuario_id` deverão referenciar `usuarios.id_usuario`.
- `usuario_indicador_id` será nulo quando o perfil for cadastrado diretamente por um administrador.
- `analisado_por_usuario_id` e `analisado_em` serão preenchidos quando houver uma decisão administrativa, como aprovação, rejeição, ocultação ou republicação. Esses campos guardarão somente a decisão administrativa mais recente; não haverá tabela ou registro de histórico de decisões.
- `imagem_path` guardará o caminho ou nome do arquivo. A imagem será armazenada no servidor, e não como conteúdo binário no banco.
- `fontes_referencias` armazenará inicialmente um ou mais links e/ou descrições em texto. Uma tabela específica para fontes poderá ser avaliada em versões futuras, caso seja necessário.
- `area_atuacao` será um campo textual simples. Não haverá cadastro administrativo de categorias científicas nem relacionamento com tabela de áreas nesta versão.

### 5.3. Relacionamentos entre as tabelas

| Relacionamento                                         | Cardinalidade       | Descrição                                                    |
| ------------------------------------------------------ | ------------------- | ------------------------------------------------------------ |
| `usuarios` → `cientistas` (`usuario_indicador_id`)     | 1 para N            | Um usuário pode enviar várias indicações. Cada indicação pode ter um usuário indicador. O campo é nulo quando o cadastro é feito diretamente pelo administrador. |
| `usuarios` → `cientistas` (`analisado_por_usuario_id`) | 1 para N            | Um administrador pode analisar vários perfis. Cada perfil analisado registra, quando aplicável, o administrador responsável pela decisão mais recente. |
| `usuarios` → `cientistas` (`alterado_por_usuario_id`)  | 1 para N            | Uma conta pode ser responsável pela última alteração de vários registros de cientistas. |
| `usuarios` → `usuarios` (`alterado_por_usuario_id`)    | Autorrelacionamento | Uma conta pode ser indicada como responsável pela última alteração de outra conta. |

Representação simplificada:

```text
USUARIOS (1) ───────── (N) CIENTISTAS

Um usuário pode enviar várias indicações.
A tabela CIENTISTAS também referencia o administrador que analisou o perfil
 e o usuário responsável pela última alteração.

USUARIOS (1) ───────── (N) USUARIOS

Autorrelacionamento usado em alterado_por_usuario_id.
```

### 5.4. Situações possíveis de um perfil

O campo `status` da tabela `cientistas` deverá aceitar os seguintes valores:

| Situação    | Significado                                                  | Visibilidade pública                                    |
| ----------- | ------------------------------------------------------------ | ------------------------------------------------------- |
| `pendente`  | Indicação enviada e ainda não analisada.                     | Não aparece no site.                                    |
| `publicada` | Indicação aprovada pelo administrador ou perfil publicado diretamente por um administrador. | Aparece no catálogo e possui página individual pública. |
| `rejeitada` | Indicação não aprovada pelo administrador.                   | Não aparece no site.                                    |
| `oculta`    | Perfil anteriormente publicado, mas retirado temporariamente da exibição. | Não aparece no site.                                    |

Fluxo padrão de uma indicação:

```text
Usuário autenticado envia a indicação
                 │
                 ▼
       status = "pendente"
                 │
                 ▼
      Administrador analisa
             /       \
            /         \
           ▼           ▼
      "publicada"  "rejeitada"
           │
           ▼
  Disponível ao público
           │
           ▼
  Administrador pode ocultar
  ou voltar a publicar o perfil
```

### 5.5. Observações sobre armazenamento e auditoria

- As imagens deverão ser armazenadas no servidor, em diretório apropriado. O banco guardará apenas o caminho ou nome do arquivo no campo `imagem_path`.
- O envio de imagem deverá validar tipo, extensão e tamanho no Back-end.
- As fontes e referências serão armazenadas inicialmente em um campo de texto, permitindo registrar uma ou mais referências. Uma tabela exclusiva poderá ser considerada em uma versão futura.
- A área de atuação será armazenada como texto no próprio perfil, sem tabela de categorias.
- Os campos `criado_em`, `atualizado_em` e `alterado_por_usuario_id` deverão ser utilizados para rastrear a criação e a última alteração dos registros, quando aplicável.
- `alterado_por_usuario_id` identifica somente o responsável pela **última alteração**; não representa um histórico completo de auditoria.
- `analisado_por_usuario_id` e `analisado_em` identificam o responsável e a data/hora da decisão administrativa mais recente; não representam um histórico de todas as decisões anteriores.
- Na eventual implementação futura de exclusão de usuários, a chave estrangeira `cientistas.usuario_indicador_id` deverá utilizar exclusão em cascata (`ON DELETE CASCADE`), para remover as indicações vinculadas ao usuário excluído. As referências de auditoria (`analisado_por_usuario_id` e `alterado_por_usuario_id`) deverão ser anuladas (`ON DELETE SET NULL`) para preservar os demais registros. A exclusão de usuários não estará disponível na primeira versão.
- Na tabela `usuarios`, o autorrelacionamento de `alterado_por_usuario_id` deverá utilizar `ON DELETE SET NULL` caso a exclusão de contas seja implementada futuramente.

---

## 6. Funcionalidades fora do escopo inicial

Para manter o projeto objetivo e viável, não estão previstas nesta primeira versão:

- Pesquisa por nome de cientista ou filtros por área de atuação.
- Cadastro e gerenciamento de áreas científicas em tabela própria.
- Comentários, avaliações, notas ou curtidas nos perfis.
- Favoritos ou listas pessoais de cientistas.
- Rede social, mensagens ou interação entre usuários.
- Cadastro independente de artigos, pesquisas ou publicações científicas.
- Publicação automática de indicações enviadas pelos usuários.
- Notificações por e-mail ou acompanhamento do status das indicações pelo usuário.
- Edição ou cancelamento de indicações pelo usuário após o envio.
- Campo de justificativa/motivo obrigatório ou opcional para rejeição de uma indicação.
- Exclusão de contas de usuários pela interface administrativa.
- Identificação automática, bloqueio ou consolidação de indicações duplicadas.
- Histórico completo de alterações e decisões administrativas.

---

## 7. Diretrizes para o desenvolvimento

Ao implementar o sistema, considerar as seguintes diretrizes:

1. Desenvolver primeiro a estrutura do banco de dados e a configuração de conexão com MySQL/MariaDB.
2. Implementar cadastro, login, logout e controle de permissões antes das funcionalidades restritas.
3. Implementar o formulário de indicação e garantir que toda indicação de usuário seja criada com situação `pendente`.
4. Implementar a área administrativa para análise, aprovação, rejeição, edição, ocultação, republicação e exclusão de perfis.
5. Implementar o catálogo e as páginas individuais garantindo que somente registros `publicada` sejam acessíveis publicamente.
6. Manter a validação dos dados e as verificações de autorização no Back-end, mesmo quando também existirem validações no Front-end.
7. Não adicionar funcionalidades fora do escopo sem antes revisar esta especificação.

## 8. Decisões consolidadas

| Tema                         | Definição                                                    |
| ---------------------------- | ------------------------------------------------------------ |
| Propósito                    | Portal informativo e colaborativo para valorizar mulheres na ciência. |
| Contribuição de conteúdo     | Usuários cadastrados e autenticados podem indicar mulheres cientistas. |
| Moderação                    | Toda indicação de usuário começa como pendente e exige aprovação administrativa antes da publicação. |
| Publicação                   | Somente perfis com situação `publicada` são exibidos no catálogo e nas páginas públicas. |
| Administração                | O administrador pode cadastrar diretamente, aprovar, rejeitar, editar, ocultar, republicar ou excluir perfis. Não haverá exclusão de contas de usuários na primeira versão. |
| Conta administrativa inicial | Criada manualmente com e-mail `admin@email.com` e senha inicial `123456`; a senha deverá ser armazenada com hash seguro. |
| Acompanhamento de indicações | O usuário não terá painel para acompanhar o status das indicações enviadas. |
| Edição pelo usuário          | Após o envio, o usuário não poderá editar ou cancelar sua indicação; correções serão feitas pelo administrador. |
| Rejeição                     | Não será necessário informar motivo para rejeitar uma indicação. |
| Duplicidade                  | Não haverá detecção automática ou bloqueio de indicações duplicadas nesta versão. |
| Exclusão de usuários         | Fora do escopo inicial. Se implementada futuramente, excluirá em cascata as indicações vinculadas ao usuário. |
| Auditoria                    | Serão armazenados somente os dados da última alteração e da decisão administrativa mais recente, sem histórico completo. |
| Áreas científicas            | Armazenadas como texto no próprio perfil, sem tabela de categorias nesta versão. |
| Catálogo                     | Listagem simples, sem pesquisa e sem filtros na primeira versão. |
| Entidade cientista           | O perfil de uma cientista não é uma conta de usuário; não exige senha nem login. |
| Modelo de dados              | Duas tabelas principais: `usuarios` e `cientistas`. Os campos são uma proposta inicial e poderão ser ajustados antes da implementação. |