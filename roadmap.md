# 🗺️ Roadmap: Bot Financeiro com IA (Symfony + Telegram)

Este documento rastreia o progresso do desenvolvimento do Bot de Finanças Pessoais. O objetivo é criar um sistema onde o usuário envia fotos de recibos via Telegram, e o sistema processa os gastos automaticamente usando IA.

---

## 🚀 Fase 1: Fundação e Ambiente
Antes da lógica, precisamos da casa arrumada.

- [x] **Configuração do Symfony**
    - [x] Criar novo projeto Symfony (versão atual estável).
    - [x] Configurar conexão com Banco de Dados (Docker ou Local).
    - [x] Instalar o pacote `symfony/http-client` (para requisições externas).
    - [x] Instalar o `symfony/maker-bundle` (para criar classes rápido).

- [ ] **Credenciais e Segurança**
    - [ ] Criar Bot no Telegram (@BotFather) e pegar o Token.
    - [ ] Pegar API Key da OpenAI.
    - [ ] Configurar variáveis sensíveis no arquivo `.env` (NUNCA subir isso pro GitHub).

---

## 📡 Fase 2: O Ouvido (Integração com Telegram)
Fazer o Symfony escutar e responder mensagens básicas.

- [ ] **Configuração do Webhook**
    - [ ] Criar Rota (Controller) no Symfony para receber POSTs.
    - [ ] Configurar ferramenta de túnel (Ngrok) para expor o localhost.
    - [ ] Registrar a URL do Ngrok como Webhook na API do Telegram.

- [ ] **Processamento de Mensagens**
    - [ ] Criar lógica para receber JSON do Telegram.
    - [ ] Identificar quem enviou (Chat ID) e o tipo de mensagem (Texto vs Foto).
    - [ ] Implementar resposta simples de texto ("Olá, recebi sua mensagem").

---

## 🧠 Fase 3: O Cérebro (Integração com IA)
A mágica de transformar imagem em dados estruturados.

- [ ] **Manipulação de Imagem**
    - [ ] Detectar quando a mensagem tem uma foto.
    - [ ] Resgatar o `file_path` da imagem na API do Telegram.
    - [ ] Fazer o download temporário da imagem ou preparar a URL pública para a IA.

- [ ] **Serviço de Inteligência (OpenAI)**
    - [ ] Criar Service dedicado para comunicação com OpenAI.
    - [ ] Implementar chamada para o modelo GPT-Vision (gpt-4o ou similar).
    - [ ] **Engenharia de Prompt:** Criar instrução para retornar APENAS JSON (Data, Valor, Local).
    - [ ] Tratar a resposta da IA e converter de string JSON para Array PHP.

---

## 💾 Fase 4: A Memória (Banco de Dados)
Salvar os dados para gerar histórico.

- [ ] **Modelagem de Dados (Entities)**
    - [ ] Criar Entidade `User` (para salvar ID do Telegram e Nome).
    - [ ] Criar Entidade `Transaction` (Valor, Data, Estabelecimento, Categoria, Foto_URL).
    - [ ] Criar relacionamento (One-to-Many): Um usuário tem várias transações.

- [ ] **Fluxo de Persistência**
    - [ ] Salvar ou atualizar o Usuário ao receber mensagem.
    - [ ] Salvar os dados extraídos pela IA na tabela `Transaction`.
    - [ ] Tratar erros: O que fazer se a IA não conseguir ler o recibo?

---

## 📊 Fase 5: Interface e Relatórios
Entregar valor de volta ao usuário.

- [ ] **Feedback Imediato**
    - [ ] Responder ao usuário com os dados formatados ("Salvei: R$ 50,00 no Mercado X").
    - [ ] Adicionar botões de confirmação no Telegram (Inline Keyboards) - *Opcional*.

- [ ] **Comandos de Relatório**
    - [ ] Criar comando `/saldo` ou `/mes`.
    - [ ] Implementar lógica para somar gastos do mês atual no banco (QueryBuilder).
    - [ ] Retornar o total gasto para o usuário.

---

## 🔮 Futuro (Backlog)
Ideias para versões futuras.

- [ ] Suporte a áudio (transcrever "gastei 10 reais" com Whisper).
- [ ] Gráficos gerados via biblioteca PHP e enviados como imagem.
- [ ] Exportação para planilha (CSV/Excel).
