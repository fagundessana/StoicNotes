# 📅 Stoic Notes — Reflexões Diárias

Este repositório contém um projeto desenvolvido para uma atividade avaliativa da disciplina de **Programação Web**, com foco na criação de uma aplicação web que realiza requisições HTTP a APIs públicas, processa dados no formato JSON e apresenta informações dinamicamente ao usuário.

O **Stoic Notes** é uma aplicação inspirada na filosofia estoica, criada para proporcionar um momento de pausa e reflexão por meio de frases filosóficas. O usuário pode selecionar uma data para consultar uma reflexão, visualizar sua autoria e acessar a tradução em português.

---

## 🎯 Objetivo da Atividade

- Desenvolver uma aplicação web integrada a uma API pública.
- Realizar requisições HTTP utilizando PHP.
- Receber e processar respostas no formato JSON.
- Permitir que o usuário informe um dado para consulta por meio de um formulário.
- Apresentar os resultados dinamicamente na página.
- Implementar validações e tratamento de erros durante as consultas.
- Organizar e documentar o projeto utilizando o GitHub.

---

## 🧠 Tecnologias Utilizadas

- **HTML5** — estruturação semântica das páginas.
- **CSS3** — estilização da interface, cores, tipografia e organização dos elementos.
- **PHP** — processamento das requisições, validação dos dados e geração de conteúdo dinâmico.
- **cURL** — realização de requisições HTTP para as APIs públicas.
- **JSON** — formato utilizado para receber, interpretar e armazenar os dados retornados pelas APIs.

---

## 🔌 APIs Públicas Utilizadas

- **One Stoic Quote API** — consulta de frases estoicas e informações sobre sua autoria.
- **MyMemory Translation API** — tradução das frases para português, quando disponível.

As respostas recebidas são processadas pelo PHP, permitindo que as informações sejam apresentadas na interface de acordo com a data selecionada pelo usuário.

---

## ✨ Funcionalidades

- 📅 **Consulta por data:** permite selecionar uma data para buscar uma reflexão.
- 💭 **Frases estoicas:** apresenta uma frase relacionada à filosofia estoica.
- ✍️ **Informações de autoria:** exibe o autor e o status de verificação da atribuição.
- 🌎 **Tradução para português:** disponibiliza a tradução da frase quando a API retorna um resultado válido.
- ⚠️ **Validação de dados:** impede consultas com datas inválidas ou futuras.
- 🛡️ **Tratamento de erros:** apresenta mensagens quando uma consulta não pode ser concluída.
- 💾 **Cache local:** armazena reflexões consultadas para evitar requisições repetidas.
- 🌿 **Interface intuitiva:** utiliza uma identidade visual inspirada em tranquilidade, simplicidade e reflexão.

---

## 🎨 Representação Visual

O Stoic Notes possui uma interface com tons suaves de verde e creme, elementos inspirados em cadernos e uma organização visual que busca transmitir serenidade e incentivar a reflexão diária.

<img width="1917" height="910" alt="image" src="https://github.com/user-attachments/assets/f673cd17-fdef-495c-82f8-1922ba2be708" />

<img width="1917" height="901" alt="image" src="https://github.com/user-attachments/assets/c5560f91-4908-4bc9-b60b-2c57f4dbc9a3" />

---

## ☑️ Telas da Aplicação

### Página inicial

Apresenta o propósito do Stoic Notes, introduzindo a proposta de reflexão inspirada na filosofia estoica e permitindo acessar a página de consulta.

### Reflexão do dia

Disponibiliza um formulário para seleção da data e apresenta a frase consultada, sua autoria, a tradução disponível e as informações de verificação.

---

## 🚀 Como Executar

1. Clone o repositório:

   ```bash
   git clone https://github.com/fagundessana/StoicNotes/new/master?filename=README.md
   ```

2. Mova a pasta do projeto para o diretório `htdocs` do XAMPP:

   ```text
   C:\xampp\htdocs\StoicNotes\
   ```

3. Inicie o **Apache** pelo XAMPP Control Panel.

4. Acesse a página inicial pelo navegador:

   ```text
   http://localhost/StoicNotes/index.html
   ```

5. Clique em **Refletir hoje**, selecione uma data e realize a consulta.

**Observação:** é necessário ter uma versão do PHP compatível com o projeto, com suporte a cURL ou à alternativa de requisição HTTP utilizada no código. A consulta às APIs também depende de conexão com a internet e da disponibilidade dos serviços externos.

---

## 🎥 Demonstração

<!-- Insira aqui o link do vídeo publicado no YouTube -->

---

## 📌 Observações Finais

Este projeto foi desenvolvido para fins educacionais, com o objetivo de aplicar conceitos de desenvolvimento web, integração com APIs públicas, requisições HTTP e processamento de dados JSON utilizando PHP.

As frases, traduções e informações de autoria dependem das respostas fornecidas pelas APIs utilizadas. Portanto, a disponibilidade e a precisão desses dados podem variar.

---

## 👩‍💻 Autoria

**Ana Beatriz Araujo Fagundes**

Estudante de Desenvolvimento de Sistemas — ETEC da Zona Leste.
