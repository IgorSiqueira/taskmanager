# Task Manager - Projeto FullStack PHP (FluentPDO & Medoo)

## Introdução

Este é um sistema de gerenciamento de tarefas (Task Manager) desenvolvido como um projeto FullStack. O objetivo principal é demonstrar habilidades no desenvolvimento de uma aplicação web completa, desde a configuração do ambiente com Docker até a implementação de funcionalidades de backend e frontend, seguindo boas práticas de segurança e organização de código.

O projeto consiste em dois sistemas funcionalmente idênticos, diferindo apenas na biblioteca de acesso ao banco de dados utilizada:
* **Sistema 1**: Utilizando FluentPDO (acessível via `https://projetofluentpdo.test`)
* **Sistema 2**: Utilizando Medoo (acessível via `https://projetomedoo.test`)

A aplicação será desenvolvida utilizando PHP 8.2+ em uma abordagem 100% Orientada a Objetos, sem o uso de frameworks PHP tradicionais, para focar nos fundamentos.

## Funcionalidades Principais (Planejadas)

* **Autenticação de Usuários:**
  * Registro de novos usuários.
  * Login e Logout.
  * Recuperação de senha.
  * Proteção de rotas.
* **Gerenciamento de Tarefas (CRUD):**
  * Listar todas as tarefas do usuário logado.
  * Adicionar nova tarefa.
  * Editar tarefa existente.
  * Excluir tarefa.
  * Marcar tarefa como concluída.
* **Organização e Visualização de Tarefas:**
  * Categorias de tarefas.
  * Filtrar tarefas por categoria ou status.
  * Ordenar tarefas por data, prioridade, etc.
* **API REST:**
  * Endpoints para todas as operações CRUD de tarefas.
  * Autenticação via token.
  * Respostas em formato JSON e status HTTP apropriados.

## Tecnologias Utilizadas

* **Backend:** PHP 8.2+ (100% Orientado a Objetos)
* **Bibliotecas de Banco de Dados:** FluentPDO, Medoo
* **Banco de Dados:** MySQL 8.0
* **Servidor Web:** Nginx
* **Containerização:** Docker, Docker Compose
* **Frontend:** HTML5 Semântico, CSS (Flexbox para layout responsivo), JavaScript, jQuery (para manipulação DOM e AJAX)
* **Segurança:** Conexão HTTPS obrigatória, hashing de senhas, proteção contra SQL Injection, XSS, CSRF tokens, validação de entradas.
* **Gerenciamento de Dependências PHP:** Composer

## Arquitetura e Organização do Projeto

O projeto adota uma arquitetura inspirada nos princípios do Domain-Driven Design (DDD), implementada com PHP Orientado a Objetos, sem o uso de frameworks MVC tradicionais. O objetivo é promover uma clara separação de responsabilidades, facilitar a manutenção e permitir a coexistência das duas implementações de acesso a dados.

As camadas principais são:
* **`app/Domain`**: Contém a lógica de negócios central e as entidades (Usuário, Tarefa, Categoria).
* **`app/Application`**: Orquestra os casos de uso através de serviços da aplicação.
* **`app/Infrastructure`**: Lida com detalhes técnicos como acesso ao banco de dados (com implementações separadas para FluentPDO e Medoo), configurações, etc.
* **`public/`**: Pontos de entrada HTTP da aplicação (DocumentRoots), com subdiretórios `fluentpdo/` e `medoo/`.

Para mais detalhes, veja a estrutura de diretórios abaixo.

## Pré-requisitos

* Docker Desktop (ou Docker Engine + Docker Compose) instalado.
* OpenSSL (geralmente já incluído no macOS e Linux).
* Um editor de código (ex: PHPStorm).
* Navegador Web moderno.

## Configuração do Ambiente de Desenvolvimento Local

Siga os passos abaixo para configurar e executar o projeto em seu ambiente local:

### 1. Clonar o Repositório
```bash
git clone <URL_DO_SEU_REPOSITORIO_GIT>
cd taskmanager # ou o nome da pasta do seu projeto
```

### 2. Edite seu arquivo hosts para mapear os domínios dos projetos para o seu localhost.
No macOS/Linux:
```bash
sudo nano /etc/hosts
```
No Windows:(execute o editor como administrador)
```bash
C:\Windows\System32\drivers\etc\hosts
```
Pode ser necessário limpar o cache DNS do seu sistema operacional após a alteração. No macOS:

```bash
sudo dscacheutil -flushcache; sudo killall -HUP mDNSResponder
```
### 3. Gerar Certificados SSL Autoassinados.
Os certificados são necessários para acesso HTTPS e serão armazenados em docker/ssl/.
Na raiz do projeto, execute:

```bash
openssl req -x509 -nodes -days 3650 -newkey rsa:2048 \
    -keyout docker/ssl/projetofluentpdo.test.key \
    -out docker/ssl/projetofluentpdo.test.crt \
    -subj "/C=BR/ST=Minas Gerais/L=Belo Horizonte/O=LocalDev/CN=projetofluentpdo.test"

```

```bash
openssl req -x509 -nodes -days 3650 -newkey rsa:2048 \
    -keyout docker/ssl/projetomedoo.test.key \
    -out docker/ssl/projetomedoo.test.crt \
    -subj "/C=BR/ST=Minas Gerais/L=Belo Horizonte/O=LocalDev/CN=projetomedoo.test"
```

### 3. Instalar Dependências do PHP (Composer)
As dependências PHP são gerenciadas pelo Composer e já estão listadas no composer.json.
Execute na raiz do projeto:

```bash
composer install
```
### 4. Instalar Dependências do PHP (Composer)
Com todas as configurações prontas, suba os contêineres:

```bash
docker-compose up -d --build
```
```bash
docker-compose up -d
```

### 5. Acesso ao projeto
Após os contêineres estarem rodando (verifique com docker-compose ps):

Sistema FluentPDO: Acesse https://projetofluentpdo.test
Sistema Medoo: Acesse https://projetomedoo.test
Seu navegador exibirá um aviso sobre o certificado SSL ser autoassinado. Você precisará aceitar o risco para continuar (geralmente clicando em "Avançado" e depois em "Continuar para o site").

Atualmente, cada site exibe uma página index.php de teste com uma mensagem distinta.

Estrutura de Diretórios Principal
```bash
├── app/                      # Contém o núcleo da aplicação (Domain, Application, Infrastructure)
├── public/                   # Pontos de entrada HTTP (fluentpdo/, medoo/)
├── docker/                   # Configurações do Docker (php/Dockerfile, nginx/conf.d/, ssl/)
├── scripts/                  # Scripts auxiliares (init.sql para o banco)
├── vendor/                   # Dependências do Composer
├── composer.json             # Definição das dependências PHP
├── docker-compose.yml        # Orquestração dos contêineres Docker
└── README.md                 # Este arquivo
```

### 6. Scripts SQL (Banco de Dados)

O arquivo scripts/init.sql é usado para inicializar o esquema do banco de dados quando o contêiner MySQL é criado pela primeira vez com um volume de dados vazio. Atualmente, ele está vazio. As tabelas necessárias (users, tasks, task_categories, user_sessions) serão definidas aqui.