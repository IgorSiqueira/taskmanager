# Task Manager - Projeto FullStack PHP

Este é um sistema de gerenciamento de tarefas (Task Manager) desenvolvido para demonstrar habilidades FullStack com PHP, MySQL, APIs REST, jQuery, CSS, JavaScript, HTML5 e Docker. O projeto consiste em dois sistemas funcionalmente idênticos, mas que utilizam diferentes bibliotecas de conexão ao banco de dados: FluentPDO e Medoo.

## Arquitetura e Organização do Projeto

O projeto adota uma arquitetura inspirada nos princípios do Domain-Driven Design (DDD), adaptada para um contexto de desenvolvimento com PHP procedural (ou com OOP leve, sem o uso de frameworks MVC tradicionais) e PHP 8.2+. O objetivo é promover uma clara separação de responsabilidades, facilitar a manutenção e permitir a coexistência das duas implementações de acesso a dados (FluentPDO e Medoo) de forma organizada.

A estrutura de diretórios principal é dividida da seguinte forma:



### Camadas da Aplicação (`app/`)

* **`app/Domain`**:
  Esta camada é o coração da aplicação. Ela contém a lógica de negócios pura e as definições das entidades principais do sistema (como `User`, `Task`, `Category`). Idealmente, o código aqui é independente de qualquer detalhe de infraestrutura (como o banco de dados específico ou a forma de apresentação). Mesmo em um estilo procedural, as funções aqui se concentram nas regras e manipulações dos dados do domínio.

* **`app/Application`**:
  Funciona como um maestro, orquestrando as ações e casos de uso do sistema. Os "serviços" da aplicação (ex: `UserService.php`, `TaskService.php`) utilizam o `Domain` para executar a lógica de negócios e interagem com a camada de `Infrastructure` para persistir ou recuperar dados. Eles não contêm lógica de negócios complexa, mas coordenam o fluxo.

* **`app/Infrastructure`**:
  Esta camada lida com todos os aspectos técnicos e preocupações externas.
    * **`Database`**: Contém as implementações concretas para acesso ao banco de dados. É aqui que residem as lógicas específicas para `FluentPDO` e `Medoo`, permitindo que o resto da aplicação (Domain e Application) permaneça agnóstico em relação à biblioteca de banco de dados utilizada. Cada subdiretório (`FluentPDO/`, `Medoo/`) terá seus próprios "repositórios" ou funções de acesso a dados.
    * **`Config`**: Armazena arquivos de configuração, como credenciais de banco de dados.
    * **`Common`**: Pode incluir utilitários compartilhados pela infraestrutura, como gerenciamento de sessão, hashing de senhas, etc.

### Pontos de Entrada e Interface (`public/`)

* A pasta `public/` contém os arquivos que são diretamente acessíveis pelo navegador.
* Existem subdiretórios separados (`fluentpdo/` e `medoo/`) que servem como `DocumentRoot` para os respectivos domínios (`projetofluentpdo.test` e `projetomedoo.test`).
* Cada um desses diretórios terá seus próprios scripts de inicialização (ex: `index.php`, `api.php`) que irão:
    1.  Carregar o autoloader do Composer e configurações.
    2.  Selecionar e configurar a implementação correta da camada de `Infrastructure` (FluentPDO ou Medoo).
    3.  Delegar o tratamento da requisição para os serviços da camada `Application`.
    4.  Renderizar a resposta (HTML para o frontend web, JSON para a API REST).
* Arquivos estáticos como CSS, JavaScript e imagens também residem aqui, possivelmente em subdiretórios `assets/`.

### Outros Diretórios

* **`vendor/`**: Gerenciado pelo Composer, contém as bibliotecas de terceiros (FluentPDO, Medoo, etc.). Não deve ser editado manualmente.
* **`docker/`**: Contém o `Dockerfile` para construir a imagem PHP customizada, configurações do Nginx para os domínios locais e SSL, e quaisquer outros arquivos relacionados ao ambiente Docker.
* **`scripts/`**: Destinado a scripts de utilidade, principalmente os scripts SQL para a criação e manutenção do esquema do banco de dados.

Esta organização visa manter o código modular, testável e mais fácil de entender, mesmo seguindo uma abordagem procedural ou com OOP leve sem um framework completo. Ela também facilita o cumprimento do requisito de ter dois sistemas com diferentes bibliotecas de banco de dados, isolando essas diferenças na camada de `Infrastructure`.

---