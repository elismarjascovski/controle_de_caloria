# 🥗 Controle de Calorias

Uma aplicação web em PHP desenvolvida para ajudar no monitoramento e gerenciamento da ingestão diária de calorias e macronutrientes, combinando relatórios gráficos no front-end e arquitetura MVC no back-end.

---

## 📌 Sumário

- [Visão Geral](#-visão-geral)
- [Funcionalidades](#-funcionalidades)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [📊 Integração com RGraph](#-integração-com-rgraph)
- [Como Executar o Projeto](#-como-executar-o-projeto)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Melhorias Futuras](#-melhorias-futuras)
- [Licença](#-licença)

---

## 👁️ Visão Geral

O **Controle de Calorias** permite o registro prático das refeições diárias. Através de uma interface responsiva acoplada a um back-end estruturado em PHP (arquitetura MVC), o usuário pode cadastrar alimentos, acompanhar totais nutricionais e comparar seu progresso com metas pré-definidas.

---

## ✨ Funcionalidades

- 🍎 **Cadastro de Alimentos:** Registro de alimentos com calorias e macronutrientes (carboidratos, proteínas, gorduras).
- 📊 **Relatórios em Gráficos:** Exibição interativa do consumo calórico via biblioteca **RGraph**.
- ✉️ **Envio de E-mails:** Notificações e validação de usuários utilizando **PHPMailer**.
- 🔐 **Autenticação:** Integração de login/autenticação utilizando as APIs do **Google**.
- 🧮 **Cálculo Nutricional:** Soma e balanço automático em tempo real das metas do dia.
- 📱 **Layout Responsivo:** Adaptado para navegação em navegadores desktop e dispositivos móveis.

---

## 🛠️ Tecnologias Utilizadas

### **Back-end:**
- **PHP** (>= 8.2) — Lógica principal e arquitetura MVC.
- **Twig Template Engine** (`^3.14`) — Renderização dinâmica das views HTML.
- **Google API Client** (`^2.17`) — Serviços de autenticação e integração Google.
- **PHPMailer** (`^6.9`) — Envio de e-mails do sistema.
- **Composer** — Gerenciador de dependências com padrão PSR-4 para autocarregamento de classes (`app/` e `core/`).

### **Front-end:**
- **HTML5 & CSS3** — Interface e estilização responsiva.
- **JavaScript (ES6+)** — Manipulação DOM e requisições.
- **RGraph** — Biblioteca Canvas para renderização dos gráficos nutricionais.

---

## 📊 Integração com RGraph

A biblioteca [RGraph](https://www.rgraph.net/) é utilizada para renderizar os gráficos de consumo nutricional em elementos `<canvas>`. Os arquivos estão salvos no diretório `public/assets/RGraph/`.

Para o correto funcionamento dos gráficos na interface, certifique-se de importar os scripts no HTML:

```html
<!-- Núcleo do RGraph -->
<script src="public/assets/RGraph/RGraph.common.core.js"></script>

<!-- Módulos de gráficos usados na aplicação -->
<script src="public/assets/RGraph/RGraph.bar.js"></script>
<script src="public/assets/RGraph/RGraph.pie.js"></script>
```

🚀 Como Executar o Projeto
Pré-requisitos
PHP (versão 8.2 ou superior)

Composer instalado

Servidor local (XAMPP, Laragon) ou o servidor embutido do PHP

Passo a Passo
Clonar o repositório:

```html
git clone [https://github.com/seu-usuario/controle-caloria.git](https://github.com/seu-usuario/controle-caloria.git)
cd controle-caloria
```

Instalar as dependências via Composer:

```html
composer install
```

Iniciar o servidor local de desenvolvimento:

```html
php -S localhost:8000 -t public
```

Acessar no navegador:
Abra http://localhost:8000.

📁 Estrutura do Projeto

```html
controle_caloria/
├── app/                       # Controladores, Models e Helpers (Namespace: app\)
│   └── functions/
│       └── helpers.php        # Funções auxiliares globais
├── core/                      # Estrutura base do framework/sistema (Namespace: core\)
├── public/                    # Ponto de entrada público do servidor
│   ├── assets/
│   │   └── RGraph/            # Biblioteca RGraph para gráficos
│   ├── css/
│   └── js/
├── vendor/                    # Dependências do Composer
├── composer.json              # Mapeamento de pacotes e Autoload PSR-4
└── README.md                  # Documentação do repositório
```

📄 Licença
Este projeto é de uso livre sob a licença MIT.

Desenvolvido por Elismar Eldor Jascovski
