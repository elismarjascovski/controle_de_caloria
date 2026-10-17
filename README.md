# 🥗 Controle de Calorias

Uma aplicação web em PHP desenvolvida para ajudar no monitoramento e gerenciamento da ingestão diária de calorias e macronutrientes, combinando relatórios gráficos no front-end, arquitetura MVC no back-end e orquestração completa via **Docker Compose**.

---

## 📌 Sumário

- [Visão Geral](#-visão-geral)
- [Funcionalidades](#-funcionalidades)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [📊 Integração com RGraph](#-integração-com-rgraph)
- [🚀 Como Executar o Projeto com Docker](#-como-executar-o-projeto-com-docker)
- [💻 Execução Manual (Sem Docker)](#-execução-manual-sem-docker)
- [📁 Estrutura do Projeto](#-estrutura-do-projeto)
- [📄 Licença](#-licença)

---

## 👁️ Visão Geral

O **Controle de Calorias** permite o registro prático das refeições diárias. Através de uma interface responsiva acoplada a um back-end estruturado em PHP (arquitetura MVC), o usuário pode cadastrar alimentos, acompanhar totais nutricionais e comparar seu progresso com metas pré-definidas.

---

## ✨ Funcionalidades

- 🍎 **Cadastro de Alimentos:** Registro de alimentos com calorias e macronutrientes (carboidratos, proteínas, gorduras).
- 📊 **Relatórios em Gráficos:** Exibição interativa do consumo calórico via biblioteca **RGraph**.
- ✉️ **Envio de E-mails:** Notificações e validação de usuários utilizando **PHPMailer** via SMTP do Gmail.
- 🔐 **Autenticação:** Autenticação por e-mail/senha e login via **Google Identity Services**.
- 🧮 **Cálculo Nutricional:** Soma e balanço automático em tempo real das metas do dia.
- 📱 **Layout Responsivo:** Adaptado para navegadores desktop e dispositivos móveis.

---

## 🛠️ Tecnologias Utilizadas

### **Back-end:**
- **PHP** (8.2+) — Lógica principal e arquitetura MVC.
- **MySQL 8.0** — Banco de dados relacional.
- **Twig Template Engine** (`^3.14`) — Renderização dinâmica das views HTML.
- **Google API Client** (`^2.17`) — Serviços de autenticação e integração Google.
- **PHPMailer** (`^6.9`) — Envio de e-mails de validação de conta.
- **Composer** — Gerenciador de dependências com padrão PSR-4 para autocarregamento (`app/` e `core/`).

### **Front-end:**
- **HTML5 & CSS3** — Interface e estilização responsiva com Bootstrap.
- **JavaScript (ES6+)** — Manipulação DOM, requisições assíncronas e SDK do Google/Telegram.
- **RGraph** — Biblioteca Canvas para renderização dos gráficos nutricionais.

### **Infraestrutura:**
- **Docker & Docker Compose** — Conteinerização dos serviços (`app`, `webserver` Nginx e `db` MySQL).

---

## 📊 Integração com RGraph

A biblioteca [RGraph](https://www.rgraph.net/) é utilizada para renderizar os gráficos de consumo nutricional em elementos `<canvas>`. Os arquivos estão salvos no diretório `public/assets/RGraph/`.

```html
<!-- Núcleo do RGraph -->
<script src="public/assets/RGraph/RGraph.common.core.js"></script>

<!-- Módulos de gráficos usados na aplicação -->
<script src="public/assets/RGraph/RGraph.bar.js"></script>
<script src="public/assets/RGraph/RGraph.pie.js"></script>
```

🚀 Como Executar o Projeto com Docker
Esta é a forma recomendada de rodar a aplicação, pois todo o ambiente (PHP, Nginx, MySQL) é configurado e inicializado automaticamente com o banco populado (tabelas.sql).

Pré-requisitos
Docker Desktop instalado e em execução.

Passo a Passo
1. Clonar o repositório:
```html
git clone [https://github.com/elismarjascovski/controle_de_caloria.git](https://github.com/elismarjascovski/controle_de_caloria.git)
cd controle_de_caloria
```

2. Configurar as Credenciais (config.php e configbot.php):
Crie/ajuste os arquivos de configuração na raiz do projeto com suas credenciais.

config.php (O host do banco no Docker deve ser db):

<?php
define('SITE_URL', 'http://localhost');
```html
return [
    'database' => [
        'driver'   => 'mysql',
        'host'     => 'db',
        'port'     => 3306,
        'dbname'   => 'name_database',
        'username' => 'root',
        'password' => '1234',
        'charset'  => 'utf8mb4',
        'options'  => []
    ],
];
```

configbot.php:
```html
<?php
define('CLIENT_ID_GOOGLE', 'SEU_CLIENT_ID_DO_GOOGLE.apps.googleusercontent.com');
```

3. Subir os Contêineres com Docker Compose:
```html
docker compose up -d --build
```

4. Acessar a Aplicação:
Abra no seu navegador: http://localhost (ou http://127.0.0.1).

💻 Execução Manual (Sem Docker)
Se preferir rodar diretamente na sua máquina local sem o Docker:

Pré-requisitos
PHP 8.2+ com as extensões pdo_mysql e zip habilitadas no php.ini.

Composer instalado.

MySQL Server ativo na porta 3306.

Passo a Passo
1. Instalar as dependências do PHP:
```html
composer install
```

2. Criar e Importar a Base de Dados no MySQL:
```html
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS controle_caloria;"

# Importar no PowerShell (Windows):
Get-Content tabelas.sql | mysql -u root -p controle_caloria
```

3. Iniciar o Servidor Embutido do PHP:
```html
php -S localhost:8000
```

4. Acessar: http://localhost:8000

📁 Estrutura do Projeto
```html
controle_de_caloria/
├── app/                  # Controladores, Models e Helpers (Namespace: app\)
│   ├── controllers/      # Controladores MVC
│   ├── models/           # Camada de Dados e Regras de Negócio
│   └── views/            # Templates HTML / Twig
├── core/                 # Estrutura base do framework e roteamento (Namespace: core\)
├── docker/               # Arquivos de configuração do Nginx para o Docker
│   └── nginx/
│       └── conf.d/
│           └── app.conf
├── public/               # Ponto de entrada público do servidor
│   ├── assets/           # RGraph, imagens e recursos estáticos
│   ├── css/              # Estilos CSS
│   └── js/               # Scripts JS
├── composer.json         # Autoload PSR-4 e pacotes do Composer
├── Dockerfile            # Configuração da imagem PHP da aplicação
├── docker-compose.yml    # Orquestração dos serviços (PHP, Nginx, MySQL)
├── tabelas.sql           # Dump estrutural e dados iniciais do banco
└── README.md             # Documentação do projeto
```

📄 Licença
Este projeto é de uso livre sob a licença MIT.

Desenvolvido por Elismar Eldor Jascovski
