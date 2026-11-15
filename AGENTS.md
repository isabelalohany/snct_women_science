# Mulheres na Ciência

## 1. Contexto do projeto

O **Mulheres na Ciência** é um portal web educativo e colaborativo, desenvolvido como trabalho escolar para a disciplina de Desenvolvimento Web, do curso de Informática integrado ao Ensino Médio.

O objetivo da plataforma é divulgar mulheres que contribuíram ou contribuem para o desenvolvimento científico, apresentando suas histórias, trajetórias, descobertas e contribuições.

O sistema permitirá que visitantes realizem cadastro na plataforma e indiquem mulheres cientistas. As indicações serão analisadas por administradores e somente poderão ser publicadas no catálogo público após aprovação.

O portal terá duas áreas principais:

- **Área pública:** página inicial, catálogo de mulheres cientistas e páginas individuais com suas informações.
- **Área administrativa:** gerenciamento de usuários, cientistas cadastradas e aprovação das indicações.

Este projeto deve priorizar simplicidade, organização, segurança e facilidade de compreensão do código.

## 2. Documentação do projeto

A documentação é a principal referência para compreender o funcionamento e as decisões do sistema.

Utilize os seguintes arquivos como referência:

- `docs/Especificacao_Mulheres_na_Ciencia.md`: especificação funcional e técnica do sistema. É a principal referência para os requisitos e regras de negócio.
- `docs/DECISOES.md`: registro de decisões técnicas e funcionais aprovadas durante o desenvolvimento.
- `docs/PLANO.md`: planejamento das etapas e acompanhamento das funcionalidades implementadas.

Antes de iniciar uma tarefa, consulte a documentação relacionada ao assunto.

Regras importantes:

- Não altere requisitos ou regras de negócio por iniciativa própria.
- Não considere sugestões técnicas como decisões aprovadas.
- Caso encontre contradições entre documentos e instruções mais recentes, apresente o problema antes de realizar alterações.
- Atualizações na especificação e nas decisões devem refletir aquilo que foi efetivamente aprovado.
- Não invente funcionalidades que não estejam previstas no escopo.

## 3. Tecnologias obrigatórias

O projeto deverá utilizar as seguintes tecnologias:

### Front-end

- HTML5.
- CSS3.
- JavaScript tradicional (Vanilla JavaScript).
- Bootstrap como framework visual.
- Páginas responsivas, adaptáveis a computadores, tablets e celulares.

### Back-end

- PHP 8.4 ou superior.
- MySQL ou MariaDB.
- Extensão `mysqli` para conexão e interação com o banco de dados.
- Arquivo `.env` para configurações e informações sensíveis.

### Restrições tecnológicas

- Não utilizar frameworks Back-end como Laravel ou Symfony.
- Não utilizar PDO. A interação com o banco deverá utilizar `mysqli`.
- Não adicionar frameworks, bibliotecas ou dependências sem necessidade e sem aprovação.
- Não introduzir ferramentas de build ou tecnologias adicionais sem justificativa técnica e aprovação.
- Não substituir as tecnologias definidas na especificação.

Priorize as funcionalidades nativas do PHP e as tecnologias já estabelecidas para o projeto.

## 4. Diretrizes pedagógicas e simplicidade do código

Este projeto está sendo desenvolvido em contexto escolar. Portanto, além de entregar funcionalidades corretas, é fundamental que o código seja simples, organizado e de fácil compreensão para estudantes em processo de aprendizagem.

Siga obrigatoriamente estas orientações:

### 4.1. Simplicidade

- Priorize soluções simples, claras e didáticas.
- Evite arquiteturas excessivamente sofisticadas.
- Não implemente abstrações, camadas ou padrões de projeto sem necessidade real.
- Evite criar muitos arquivos ou diretórios para funcionalidades pequenas.
- Não utilize recursos avançados da linguagem quando uma solução mais simples atender ao mesmo objetivo.
- Evite códigos excessivamente compactos ou difíceis de compreender.

### 4.2. Organização

- Separe responsabilidades entre arquivos e componentes.
- Evite duplicação de código, criando funções reutilizáveis quando necessário.
- Utilize nomes claros e consistentes para variáveis, funções e arquivos.
- Prefira nomes em português para conceitos específicos do domínio do sistema.
- Mantenha um padrão de nomenclatura em todo o projeto.
- Evite arquivos excessivamente grandes que concentrem responsabilidades diferentes.

### 4.3. Comentários e explicações

- Comente os principais blocos de código e regras de negócio.
- Não adicione comentários desnecessários para instruções simples e autoexplicativas.
- Explique as decisões técnicas relevantes.
- Ao concluir uma funcionalidade, apresente uma explicação didática sobre seu funcionamento.
- Explique como os dados percorrem o sistema, desde o formulário até o banco de dados, quando aplicável.

### 4.4. Objetivo pedagógico

O código produzido deverá permitir que o estudante consiga:

- Compreender seu funcionamento.
- Identificar a responsabilidade de cada arquivo.
- Entender as operações realizadas no banco de dados.
- Explicar as principais decisões técnicas.
- Realizar alterações e correções futuramente.

Não entregue soluções complexas sem justificativa e explicação adequadas.

## 5. Regras de negócio do sistema

As seguintes regras já foram definidas e deverão ser respeitadas durante todo o desenvolvimento.

### 5.1. Tipos de usuários

O sistema possuirá dois tipos de usuários:

- **Usuário comum:** poderá realizar cadastro, efetuar login e indicar mulheres cientistas.
- **Administrador:** terá acesso às funcionalidades administrativas e será responsável pelo gerenciamento e aprovação dos cadastros.

O cadastro público deverá criar exclusivamente contas de usuários comuns.

Não deverá existir funcionalidade pública para cadastro de administradores.

### 5.2. Cadastro de usuários

Os visitantes poderão criar uma conta informando:

- Nome completo.
- E-mail.
- Senha.

O e-mail deverá ser único.

As senhas deverão ser armazenadas utilizando mecanismos seguros de hash do PHP.

### 5.3. Indicação de mulheres na ciência

Somente usuários autenticados poderão indicar mulheres cientistas.

O cadastro deverá contemplar as informações definidas na especificação, incluindo:

- Nome completo.
- Fotografia.
- Nacionalidade.
- Data de nascimento, quando aplicável.
- Área de atuação científica.
- Biografia e trajetória profissional.
- Principais descobertas e contribuições.
- Fontes e referências.
- Informações adicionais, quando necessário.

A área de atuação deverá ser armazenada diretamente no cadastro da cientista. Não deverá existir uma tabela ou módulo independente para gerenciamento de áreas científicas.

### 5.4. Aprovação das indicações

Toda indicação realizada por um usuário deverá possuir inicialmente o status `pendente`.

Nenhuma indicação pendente poderá aparecer no catálogo público.

O administrador será responsável por analisar cada indicação e poderá:

- Aprovar a indicação, alterando seu status para `publicada`.
- Rejeitar a indicação, alterando seu status para `rejeitada`.

Não será necessário informar um motivo para rejeição.

O usuário que realizou a indicação não poderá acompanhar seu andamento pela plataforma e não poderá editar ou corrigir o cadastro depois do envio.

Não será implementado mecanismo automático de identificação ou bloqueio de indicações duplicadas.

### 5.5. Gerenciamento administrativo

O administrador poderá:

- Consultar os usuários cadastrados.
- Consultar as cientistas cadastradas.
- Cadastrar novas cientistas.
- Editar informações das cientistas.
- Excluir cadastros de cientistas.
- Aprovar ou rejeitar indicações.
- Publicar, ocultar e republicar perfis.
- Visualizar quantitativos básicos de usuários e cientistas.

Os perfis poderão possuir os seguintes estados:

- `pendente`: indicação aguardando análise administrativa.
- `publicada`: perfil aprovado e disponível publicamente.
- `rejeitada`: indicação recusada pelo administrador.
- `oculta`: perfil anteriormente publicado, mas temporariamente indisponível ao público.

Somente perfis com status `publicada` poderão aparecer no catálogo público.

### 5.6. Administração inicial

O primeiro administrador deverá ser criado manualmente, sem necessidade de uma tela pública de cadastro administrativo.

Credenciais iniciais definidas para o ambiente escolar:

- E-mail: `admin@email.com`
- Senha inicial: `123456`

A senha deverá ser armazenada utilizando `password_hash()` do PHP, nunca em texto puro.

Essas credenciais são destinadas ao ambiente local de desenvolvimento e demonstração. Não deverão ser mantidas em qualquer ambiente exposto publicamente.

### 5.7. Exclusão de usuários

A funcionalidade de exclusão de usuários não faz parte do escopo inicial.

Caso seja aprovada futuramente, a remoção de um usuário deverá excluir também as indicações vinculadas a ele, respeitando a integridade referencial do banco de dados.

Não implemente essa funcionalidade sem autorização.

### 5.8. Auditoria e alterações

O sistema deverá registrar:

- Data de criação dos registros.
- Data da última atualização.
- Identificação do usuário responsável pela última alteração, quando aplicável.

Não será implementado histórico completo de alterações ou tabela de eventos de auditoria.

Deverá ser mantido somente o registro da última alteração.

## 6. Requisitos de segurança

A segurança deverá ser considerada em todas as funcionalidades que envolvam dados, autenticação e permissões.

Adote as seguintes práticas:

### Banco de dados

- Utilizar consultas preparadas (*Prepared Statements*) com `mysqli`.
- Nunca concatenar diretamente dados recebidos do usuário em consultas SQL.
- Validar os dados no Back-end antes de armazená-los.
- Utilizar chaves primárias e estrangeiras para manter a integridade dos relacionamentos.

### Autenticação

- Utilizar `password_hash()` para armazenar senhas.
- Utilizar `password_verify()` para verificar senhas.
- Regenerar o identificador da sessão após o login.
- Proteger páginas administrativas com verificações de autenticação e autorização.
- Não confiar exclusivamente em validações realizadas no Front-end.

### Proteção de formulários

- Implementar proteção contra CSRF utilizando tokens.
- Escapar adequadamente os dados apresentados nas páginas com `htmlspecialchars()`.
- Validar todos os dados recebidos pelo Back-End.
- Não apresentar informações sensíveis em URLs.

### Envio de fotografias

- Validar o tipo real dos arquivos enviados.
- Restringir o envio a formatos de imagem permitidos.
- Validar tamanho e extensão dos arquivos.
- Gerar nomes de arquivos seguros.
- Não confiar no nome ou na extensão original do arquivo.
- Impedir a execução de arquivos enviados como se fossem código PHP.
- Não armazenar imagens diretamente no banco de dados; armazenar seus caminhos ou nomes de arquivo.

### Configurações

- Armazenar credenciais e informações sensíveis no arquivo `.env`.
- Não versionar o arquivo `.env`.
- Disponibilizar um `.env.example` sem credenciais reais.
- Não exibir mensagens de erro contendo informações sensíveis do servidor ou banco de dados aos visitantes.

## 7. Banco de dados

A estrutura inicial deverá seguir a especificação do projeto.

As tabelas principais são:

- `usuarios`: armazena informações de usuários comuns e administradores.
- `cientistas`: armazena os perfis das mulheres cientistas indicadas ou cadastradas administrativamente.

Não criar tabelas adicionais sem necessidade e sem aprovação.

Requisitos gerais:

- Utilizar MySQL ou MariaDB.
- Utilizar o padrão `utf8mb4`.
- Definir chaves primárias e estrangeiras adequadamente.
- Manter integridade referencial.
- Utilizar campos de data e hora para criação e atualização.
- Registrar o identificador do último usuário responsável pela alteração, conforme a modelagem aprovada.

A estrutura definitiva dos campos e relacionamentos deverá respeitar a especificação e as decisões aprovadas durante a etapa de arquitetura.

## 8. Organização de arquivos e diretórios

A organização da aplicação deverá ser definida durante a etapa de arquitetura.

Considere as seguintes orientações:

- Separar configurações, componentes compartilhados, páginas, arquivos estáticos e funcionalidades administrativas.
- Manter CSS, JavaScript e imagens em diretórios próprios.
- Evitar excesso de subdivisões.
- Reutilizar componentes de layout quando necessário.
- Manter uma estrutura adequada ao desenvolvimento com PHP tradicional.

Não crie toda a estrutura da aplicação antecipadamente.

Primeiro apresente uma proposta de organização, explique a responsabilidade de cada diretório e aguarde aprovação.

O arquivo `.env` deverá permanecer fora do controle de versão.

## 9. Metodologia de desenvolvimento

O desenvolvimento deverá acontecer de maneira incremental, dividido em etapas.

As etapas gerais do projeto são:

1. Compreensão da especificação.
2. Análise crítica e viabilidade tecnológica.
3. Definição da arquitetura e decisões técnicas.
4. Planejamento do desenvolvimento.
5. Implementação incremental.
6. Integração e testes gerais.
7. Revisão e documentação final.

Não avance automaticamente entre as etapas.

O usuário deverá validar os resultados antes de autorizar o início da próxima etapa.

### 9.1. Implementação incremental

Cada funcionalidade deverá seguir um ciclo de trabalho:

1. Planejar a implementação.
2. Apresentar arquivos e componentes envolvidos.
3. Identificar alterações necessárias no banco de dados.
4. Definir critérios de aceitação.
5. Implementar somente o escopo aprovado.
6. Executar testes.
7. Revisar o código.
8. Apresentar os resultados e eventuais pendências.

Não implemente várias funcionalidades independentes simultaneamente.

Não avance para a próxima entrega sem validação do usuário.

### 9.2. Testes e validação

- Verifique a sintaxe dos arquivos PHP com `php -l` sempre que possível.
- Teste as operações de banco de dados.
- Valide formulários com dados corretos e incorretos.
- Verifique permissões de usuários e administradores.
- Teste os principais fluxos da aplicação.
- Verifique possíveis regressões nas funcionalidades já desenvolvidas.

Não afirme que testes foram executados ou aprovados sem realmente tê-los realizado.

Priorize inicialmente testes simples e verificações manuais. Não introduza ferramentas complexas de testes automatizados sem justificativa e aprovação.

## 10. Controle de versão e alterações

Utilize Git para acompanhar a evolução do projeto.

Siga estas orientações:

- Preserve as alterações existentes.
- Consulte o estado do repositório antes de realizar mudanças relevantes.
- Não sobrescreva arquivos sem necessidade.
- Não remova arquivos ou dados sem autorização.
- Não execute operações destrutivas sem explicar seus impactos.
- Não realize commits ou envios ao GitHub sem autorização explícita.

Ao concluir funcionalidades, sugira uma mensagem de commit clara e objetiva.

## 11. Comunicação e apresentação dos resultados

Durante o desenvolvimento:

- Explique o que será feito antes de implementar alterações relevantes.
- Apresente justificativas para decisões técnicas.
- Informe problemas e limitações identificados.
- Faça perguntas quando uma decisão importante não estiver definida.
- Evite perguntas desnecessárias sobre informações já documentadas.
- Não presuma que uma funcionalidade foi aprovada apenas porque foi sugerida.

Ao concluir uma tarefa, apresente:

1. Resumo do que foi realizado.
2. Arquivos criados ou modificados.
3. Explicação do funcionamento.
4. Testes executados e seus resultados.
5. Pendências ou limitações identificadas.
6. Sugestão de próximo passo.

O objetivo é manter o desenvolvimento transparente e permitir que o estudante acompanhe e compreenda a evolução do sistema.

## 12. Funcionalidades fora do escopo inicial

Não implementar as seguintes funcionalidades sem aprovação explícita:

- Pesquisa de cientistas.
- Filtros por área científica.
- Cadastro independente de áreas científicas.
- Comentários e avaliações.
- Favoritos.
- Rede social ou interação entre usuários.
- Acompanhamento de indicações pelos usuários.
- Edição de indicações pelos usuários após o envio.
- Justificativa obrigatória para rejeição.
- Detecção automática de indicações duplicadas.
- Exclusão de contas de usuários.
- Histórico completo de auditoria.
- Gerenciamento independente de publicações científicas.

Não amplie o escopo do projeto por iniciativa própria.

---

## Diretriz principal

**Priorize sempre uma implementação simples, segura, didática e compatível com os objetivos escolares do projeto.**

O sistema deverá ser desenvolvido de forma que o estudante consiga compreender sua arquitetura, explicar seu funcionamento e realizar futuras alterações sem depender completamente de código gerado por inteligência artificial.