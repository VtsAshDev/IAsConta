#!/bin/bash

CYAN='\033[0;36m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

if [ -f .env.local ]; then
    export $(grep -v '^#' .env.local | xargs)
elif [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
fi

echo -e "${CYAN}♻️  1. Resetando Banco de Dados e Fixtures...${NC}"
composer db-reset

echo -e "${GREEN}✅ Banco de dados pronto!${NC}"
echo ""

echo -e "${YELLOW}⚠️  LEMBRETE: O servidor Symfony e o Tunnel devem estar rodando!${NC}"
echo "   Terminal 1: symfony server:start"
echo "   Terminal 2: cloudflared tunnel --url https://127.0.0.1:8000"
echo ""

echo -ne "${CYAN}👉 Cole a URL gerada pelo Cloudflare: ${NC}"
read TUNNEL_URL

TUNNEL_URL="${TUNNEL_URL%/}"

echo -e "${CYAN}🤖 3. Configurando Webhook no Telegram...${NC}"

RESPONSE=$(curl -s -X POST "https://api.telegram.org/bot$TELEGRAM_TOKEN/setWebhook" \
     -d "url=$TUNNEL_URL/api/webhook/telegram")

if echo "$RESPONSE" | grep -q '"ok":true'; then
    echo -e "${GREEN}✅ Webhook configurado com sucesso!${NC}"
else
    echo -e "${RED}❌ Erro ao configurar Webhook: $RESPONSE${NC}"
fi

echo -e "${CYAN}-----------------------------------------------------------------${NC}"
echo -e "${YELLOW}📺 Iniciando stream de logs (Pressione CTRL+C para sair)${NC}"
echo -e "${CYAN}-----------------------------------------------------------------${NC}"

symfony server:log | grep "app"
