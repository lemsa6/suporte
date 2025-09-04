# 📧 Sistema de Notificações por E-mail

## **Visão Geral**

O sistema de notificações por e-mail foi implementado para manter todos os usuários informados sobre as atividades importantes do sistema de tickets. As notificações são enviadas automaticamente baseadas em eventos específicos.

## **🚀 Funcionalidades Implementadas**

### **1. Tipos de Notificações**

#### **🎫 Novo Ticket Criado**
- **Quando:** Um novo ticket é criado no sistema
- **Para quem:** Administradores e técnicos
- **Conteúdo:** Detalhes completos do ticket, incluindo cliente, categoria, prioridade e descrição
- **Prioridade alta:** Notificação imediata para tickets urgentes

#### **👤 Ticket Atribuído**
- **Quando:** Um ticket é atribuído a um técnico
- **Para quem:** Técnico responsável
- **Conteúdo:** Detalhes do ticket, informações do cliente e contato

#### **🔒 Ticket Fechado**
- **Quando:** Um ticket é marcado como fechado
- **Para quem:** Técnico responsável e administradores
- **Conteúdo:** Detalhes da resolução, tempo total de resolução e notas

#### **🚨 Ticket Urgente**
- **Quando:** Um ticket de alta prioridade é criado
- **Para quem:** Todos os administradores e técnicos
- **Conteúdo:** Alerta urgente com informações de contato do cliente

#### **💬 Nova Resposta ao Ticket**
- **Quando:** Uma nova resposta é adicionada ao ticket
- **Para quem:** Técnico responsável e administradores
- **Conteúdo:** Conteúdo da resposta e contexto do ticket

### **2. Configurações**

#### **Arquivo .env**
```env
# Configurações de E-mail
MAIL_FROM_ADDRESS="contato@8bits.pro"
MAIL_FROM_NAME="Sistema de Tickets"

# Configurações de Notificações
NOTIFICATIONS_EMAIL_ENABLED=true
NOTIFICATIONS_NEW_TICKET=true
NOTIFICATIONS_TICKET_ASSIGNED=true
NOTIFICATIONS_TICKET_CLOSED=true
NOTIFICATIONS_TICKET_URGENT=true
NOTIFICATIONS_TICKET_REPLIES=true
NOTIFICATIONS_STATUS_CHANGES=true
NOTIFICATIONS_PRIORITY_CHANGES=true
NOTIFICATIONS_QUEUE_ENABLED=true
```

#### **Arquivo config/notifications.php**
- Configurações detalhadas para cada tipo de notificação
- Controle de destinatários por tipo
- Configurações de templates e filas
- Rate limiting e preferências do usuário

## **🏗️ Arquitetura do Sistema**

### **1. Classes de Notificação**
```
app/Notifications/
├── TicketAssigned.php          # Notificação de ticket atribuído
├── TicketClosed.php            # Notificação de ticket fechado
├── TicketUrgent.php            # Notificação de ticket urgente
├── TicketReplyNotification.php # Notificação de nova resposta
└── NewTicketCreated.php        # Notificação de novo ticket
```

### **2. Serviço de Notificações**
```
app/Services/NotificationService.php
```
- **Responsabilidades:**
  - Gerenciar todas as notificações do sistema
  - Determinar destinatários apropriados
  - Enviar notificações baseadas em eventos
  - Controlar frequência e prioridade

### **3. Integração com Controllers**
- **TicketController:** Integrado com o sistema de notificações
- **Eventos automáticos:** Criação, atualização, atribuição e fechamento
- **Notificações em tempo real:** Enviadas imediatamente após eventos

## **📋 Como Usar**

### **1. Testando as Notificações**

#### **Comando Artisan**
```bash
# Testar todas as notificações
php artisan notifications:test

# Testar notificação específica
php artisan notifications:test --type=new
php artisan notifications:test --type=assigned
php artisan notifications:test --type=closed
php artisan notifications:test --type=urgent
```

#### **Verificar E-mails**
- Acesse o Mailpit: http://localhost:8025
- Todas as notificações serão exibidas lá para desenvolvimento

### **2. Configuração de Produção**

#### **E-mail Real (Gmail, SendGrid, etc.)**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=contato@8bits.pro
MAIL_FROM_NAME="Sistema de Tickets"
```

#### **Fila de Trabalho (Redis/Queue)**
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## **🔧 Personalização**

### **1. Templates de E-mail**
- Localização: `resources/views/vendor/notifications/`
- Personalize cores, layout e conteúdo
- Suporte a múltiplos idiomas

### **2. Novos Tipos de Notificação**
1. Crie nova classe em `app/Notifications/`
2. Implemente métodos `via()`, `toMail()` e `toArray()`
3. Adicione ao `NotificationService`
4. Configure no arquivo de configuração

### **3. Destinatários Personalizados**
- Modifique o `NotificationService` para incluir novos critérios
- Adicione filtros baseados em permissões ou preferências
- Implemente notificações para e-mails externos (clientes)

## **📊 Monitoramento e Logs**

### **1. Logs de Notificação**
- Todas as notificações são logadas automaticamente
- Verifique `storage/logs/laravel.log`
- Use o comando `php artisan notifications:test` para debug

### **2. Métricas**
- Contagem de notificações enviadas
- Taxa de entrega
- Tempo de processamento
- Erros e falhas

## **🚨 Troubleshooting**

### **1. Problemas Comuns**

#### **E-mails não são enviados**
- Verifique configurações do `.env`
- Confirme se o Mailpit está rodando
- Verifique logs do Laravel

#### **Notificações duplicadas**
- Verifique se há múltiplas chamadas para o serviço
- Confirme configurações de fila
- Verifique se há listeners duplicados

#### **E-mails com formatação incorreta**
- Verifique templates personalizados
- Confirme configurações de CSS inline
- Teste com diferentes clientes de e-mail

### **2. Debug**
```bash
# Limpar cache de configuração
php artisan config:clear

# Limpar cache de views
php artisan view:clear

# Verificar status das filas
php artisan queue:work --verbose

# Testar configuração de e-mail
php artisan tinker
Mail::raw('Teste', function($message) { $message->to('teste@exemplo.com')->subject('Teste'); });
```

## **🔮 Próximos Passos**

### **1. Funcionalidades Futuras**
- [ ] Notificações push (WebSockets)
- [ ] Notificações SMS
- [ ] Templates HTML personalizados
- [ ] Agendamento de notificações
- [ ] Relatórios de entrega

### **2. Melhorias**
- [ ] Sistema de preferências do usuário
- [ ] Notificações em lote
- [ ] Integração com WhatsApp Business API
- [ ] Sistema de templates dinâmicos
- [ ] Analytics de notificações

## **📞 Suporte**

Para dúvidas ou problemas com o sistema de notificações:
- **E-mail:** contato@8bits.pro
- **Documentação:** Este arquivo
- **Comandos de teste:** `php artisan notifications:test`

---

**Última atualização:** 19/12/2024 - 16:30
**Versão:** 1.1.0
**Desenvolvido por:** 8Bits Pro

