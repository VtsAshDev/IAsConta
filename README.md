# IAsConta - Guia de Configuração e Execução

Este guia explica como configurar o ambiente, resetar o banco de dados e iniciar o projeto localmente com integração ao Telegram.

## Pré-requisitos

*   PHP 8.2+
*   Composer
*   Docker (opcional, se estiver usando banco via container)
*   Cloudflared (para expor o localhost para o Telegram)

## 1. Configuração Inicial

1.  Copie o arquivo de exemplo de variáveis de ambiente:
    ```bash
    cp .env.example .env
    ```
2.  Edite o arquivo `.env` e preencha suas credenciais (Banco de dados, Token do Telegram, API Key do Gemini).

## 2. Comandos Disponíveis

### Resetar o Banco de Dados

Para apagar o banco atual, recriá-lo e rodar as fixtures (dados de teste), execute:

```bash
composer db-reset
```

> **O que isso faz?**
> 1. Dropa o banco de dados (se existir).
> 2. Cria o banco novamente.
> 3. Cria o schema (tabelas).
> 4. Carrega os dados iniciais (fixtures).

---

### Iniciar o Ambiente de Desenvolvimento (Recomendado)

Para facilitar o desenvolvimento, criamos um script `dev.sh` que automatiza o reset do banco e a configuração do Webhook do Telegram.

**Passo a passo:**

1.  Em um terminal separado, inicie o túnel para expor sua aplicação:
    ```bash
    cloudflared tunnel --url http://127.0.0.1:8000
    ```
    *(Copie a URL gerada, ex: `https://sua-url-randomica.trycloudflare.com`)*

2.  Em outro terminal, inicie o servidor do Symfony:
    ```bash
    symfony server:start
    ```
    *(Ou `php -S 127.0.0.1:8000 -t public` se não tiver a CLI do Symfony)*

3.  Execute o script de desenvolvimento (Git Bash ou Linux/Mac):
    ```bash
    sh dev.sh
    ```

4.  O script irá:
    *   Resetar o banco de dados.
    *   Pedir para você colar a URL do túnel (gerada no passo 1).
    *   Configurar automaticamente o Webhook do seu Bot do Telegram para essa URL.

## Estrutura de Pastas Importantes

*   `src/Controller/TelegramController.php`: Ponto de entrada das mensagens do Telegram.
*   `src/Builder/TelegramUserBuilder.php`: Lógica de criação de usuários via Telegram.
*   `src/Service/AiService.php`: Integração com a IA (Gemini).
