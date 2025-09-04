# Sistema de Chamados - Estrutura Completa (Produtividade + Simplicidade)

## 1. Arquitetura Técnica
- **Backend:** Laravel 12+
- **Banco de dados:** MySQL 8.0
- **Servidor Web:** Nginx + PHP-FPM (Docker com dois containers — app e banco)
- **Cache/Sessões:** Arquivo (nativo Laravel)
- **Frontend:** Blade + Tailwind CSS
- **E-mails:** Notificações nativas do Laravel (novos tickets, respostas, fechamento)
- **Containers:** Docker Compose (Nginx + PHP-FPM, MySQL)
- **Configurações mínimas Laravel:**
  - `php artisan storage:link` (anexos)
  - `QUEUE_CONNECTION=sync`, `CACHE_DRIVER=file`, `SESSION_DRIVER=file` ou `database`
  - Limites de upload (ex.: 25 MB) e tipos permitidos (pdf, jpg, png, zip, log)

---

## 2. Design e Interface
- **Paleta de Cores:** Amarelo, Branco, Cinza (design limpo e elegante)
- **Estilo:** Minimalista, sem sombras pesadas ou linhas grossas
- **Responsividade:** Mobile-first, adaptável a todos os dispositivos
- **Framework CSS:** Tailwind CSS 3.4+ com componentes customizados
- **Tipografia:** Fonte moderna e legível (Inter ou similar)
- **Ícones:** Heroicons ou Lucide (simples e consistentes)
- **Animações:** Transições suaves e micro-interações
- **Layout:** Grid system flexível e espaçamento consistente

---

## 3. Perfis e Permissões
- **Admin:** Tudo
- **Técnico:** Vê todos os tickets, edita apenas os seus ou não atribuídos
- **Cliente:** Funcionários das empresas - veem apenas tickets da sua empresa
- **Regras de permissão:** Usar `TicketPolicy` para `view`, `update`, `assign`, `close`, `reopen`

---

## 4. Fluxo e Regras de Ticket
- **Status:** `aberto` → `em_andamento` → `resolvido` → `fechado` (com opção de reabrir)
- **Prioridade:** `baixa` | `média` | `alta`
- **Atribuição:** 0..1 técnico por ticket
- **Histórico:** Timeline de mensagens e mudanças de status
- **Anexos:** Permitidos nas mensagens
- **Busca:** Por empresa, funcionário, título, status, responsável, categoria

---

## 5. Estrutura de Banco de Dados
**Tabelas principais (7):**
1. `users` (Laravel padrão + role)
2. `clients` (CNPJ, nome da empresa, dados da empresa)
3. `client_contacts` (funcionários da empresa - nome, email, telefone, cargo)
4. `tickets` (cliente, contato_abertura, título, descrição, status, prioridade, responsável, categoria)
5. `ticket_messages` (ticket_id, user_id, mensagem, tipo [note|reply|status_change])
6. `attachments` (ticket_message_id, path, original_name, size)
7. `categories` (TI, Servidores, Banco de Dados, Programas, Apps, Redes, Vídeo, etc.)

**Relacionamentos:**
- Client (CNPJ) → ClientContacts (1:N) - Uma empresa tem vários funcionários
- ClientContact → Tickets (1:N) - Cada funcionário pode abrir tickets
- Ticket → Category (N:1) - Cada ticket tem uma categoria
- Ticket → User responsável (N:1) - Técnico responsável
- Ticket → Messages (1:N) - Histórico de mensagens

---

## 6. Telas Essenciais
1. **Login** (funcionários vs equipe técnica)
2. **Dashboard**
   - Tickets abertos/fechados da empresa (para funcionários)
   - Todos os tickets (para equipe técnica)
   - Cards por prioridade
   - Últimas atualizações
3. **Clientes**
   - CRUD de empresas + funcionários
   - Detalhe com lista de tickets da empresa
4. **Lista de Tickets**
   - Filtros: empresa, funcionário, status, prioridade, responsável, categoria
   - Ações básicas: alterar status, atribuir técnico
5. **Ticket Detalhe**
   - Cabeçalho: título, empresa, funcionário, prioridade, status, responsável
   - Timeline de mensagens/histórico
   - Form de nova mensagem + upload
   - Metadados do ticket

---

## 7. Funcionalidades por Perfil

**Funcionário (Cliente):**
- Ver tickets da sua empresa
- Abrir chamado em nome da empresa
- Responder mensagens
- Anexar arquivos

**Técnico/Admin:**
- Criar/editar tickets
- Responder e fechar chamados
- Categorizar adequadamente
- Ver timeline completa da empresa
- Reatribuir ou reabrir tickets

---

## 8. Rotas
- `GET /` → Dashboard
- `resource /clients` (index, create, edit, show)
- `resource /client-contacts` (index, create, edit, show)
- `resource /tickets` (index, create, edit, show)
- Ações extras:
  - `POST /tickets/{id}/assign`
  - `POST /tickets/{id}/status`
  - `POST /tickets/{id}/message`

---

## 9. Notificações
- **Quando atribuir ticket:** e-mail para responsável
- **Quando fechar ticket:** e-mail para funcionário que abriu
- **Quando funcionário responder:** e-mail para técnico responsável

---

## 10. Docker
- **Container 1:** Nginx + PHP-FPM 8.3
- **Container 2:** MySQL 8.0
- **Volumes:** código Laravel, storage, banco
- **Variáveis importantes:** APP_URL, DB_*, UPLOAD_MAX_FILESIZE, POST_MAX_SIZE

---

## 11. Sementes (Seeders)
- Usuário admin
- 1 técnico fictício
- Categorias padrão (TI, Servidores, Banco de Dados, Programas, Apps, Redes, Vídeo)
- 2 empresas + 3 funcionários e 3-5 tickets de teste

---

## 12. Backups e Logs
- **MySQL:** dump diário
- **Anexos:** incluir no backup
- **Logs:** `storage/logs/laravel.log` com rotação simples

---

## 13. Diferenciais de Produtividade
- **Timeline por empresa** para histórico unificado
- **Categorias específicas para T.I.** para organização eficiente
- **Múltiplos funcionários por empresa** com controle individual
- **Filtros rápidos** por empresa, funcionário, categoria
- **UX focada em produtividade** sem sobrecarga

---

## 14. Funcionalidades Adicionais Recomendadas

### **🔥 PRIORIDADE ALTA (Implementar Primeiro):**
1. **Sistema de Notificações por E-mail**
   - Notificação automática quando ticket é atribuído
   - Notificação quando ticket é fechado
   - Notificação quando funcionário responde
   - Notificação de tickets urgentes

2. **Sistema de Anexos/Uploads**
   - Upload de arquivos nos tickets
   - Preview de imagens
   - Validação de tipos e tamanhos
   - Storage configurado para anexos

3. **Sistema de Busca e Filtros Avançados**
   - Busca global por texto
   - Filtros combinados
   - Busca por empresa, funcionário, categoria
   - Histórico de buscas

### ** PRIORIDADE MÉDIA (Implementar Segundo):**
4. **Sistema de SLA (Service Level Agreement)**
   - Tempo limite para resolução por prioridade
   - Alertas de SLA expirando
   - Relatórios de conformidade
   - Notificações automáticas

5. **Sistema de Templates de Resposta**
   - Respostas pré-definidas para problemas comuns
   - Categorização de templates
   - Atalhos de teclado
   - Histórico de uso

6. **Sistema de Tags/Labels**
   - Tags personalizadas para tickets
   - Filtros por tags
   - Relatórios por tags
   - Autocomplete

7. **Dashboard de Métricas Avançadas**
   - Tempo médio de resolução
   - Satisfação do cliente
   - Performance por técnico
   - Gráficos interativos
   - Export para Excel/PDF

### ** PRIORIDADE BAIXA (Implementar Por Último):**
8. **Sistema de 2FA (Two-Factor Authentication)**
   - Autenticação em duas etapas
   - Códigos QR para apps
   - Backup codes
   - Configuração por usuário

9. **Sistema de Notificações Push**
   - Notificações em tempo real
   - WebSockets para atualizações
   - Notificações no navegador
   - Configurações por usuário

10. **Sistema de Workflows Automáticos**
    - Atribuição automática por categoria
    - Escalação automática de tickets
    - Lembretes automáticos
    - Fechamento automático após inatividade

---

## 15. Exemplo Prático de Funcionamento
```
Empresa: ABC Ltda (CNPJ: 12.345.678/0001-90)
├── Funcionário: João Silva (joao@abc.com) - Gerente
├── Funcionário: Maria Santos (maria@abc.com) - Analista
└── Funcionário: Pedro Costa (pedro@abc.com) - Suporte

Tickets:
├── Ticket #001: João Silva - Problema no servidor (Categoria: Servidores)
├── Ticket #002: Maria Santos - App não carrega (Categoria: Apps)
└── Ticket #003: Pedro Costa - Rede lenta (Categoria: Redes)

Histórico: Todos os funcionários veem histórico completo da empresa
```

---

## 16. Status de Implementação Atual

### **✅ IMPLEMENTADO (80% do Sistema Base):**
- ✅ Infraestrutura Docker completa
- ✅ Banco de dados e migrations
- ✅ Modelos e relacionamentos
- ✅ Controladores principais
- ✅ Sistema de autorização
- ✅ Rotas principais
- ✅ Interface de usuário completa
- ✅ Sistema de relatórios básico
- ✅ CRUD de tickets, clientes e categorias
- ✅ Dashboard com estatísticas
- ✅ Sistema de autenticação
- ✅ Gerenciamento de perfil

### **❌ NÃO IMPLEMENTADO (20% - Funcionalidades Avançadas):**
- ❌ Sistema de notificações por e-mail
- ❌ Sistema de anexos/uploads
- ❌ Sistema de busca avançada
- ❌ Sistema de SLA
- ❌ Sistema de templates
- ❌ Sistema de tags
- ❌ Dashboard de métricas avançadas
- ❌ Sistema de auditoria/logs
- ❌ Sistema de 2FA
- ❌ Sistema de notificações push
- ❌ Sistema de workflows automáticos

---

## 17. Próximos Passos Recomendados

1. **Implementar funcionalidades de prioridade alta** (e-mails, anexos, busca)
2. **Testar sistema completo** com dados reais
3. **Implementar funcionalidades de prioridade média** (SLA, templates, tags)
4. **Configurar ambiente de produção**
5. **Implementar funcionalidades de prioridade baixa** (2FA, push, workflows)

---

## 18. Conclusão

O sistema atual está **80% funcional** para uso básico de suporte técnico. Para se tornar uma **ferramenta profissional e altamente produtiva**, é essencial implementar as funcionalidades de prioridade alta, que transformarão o sistema de um "CRUD básico" para uma **solução completa de gestão de tickets** com comunicação automática e gestão de arquivos.

**Status Atual: Sistema Base Completo - Pronto para Funcionalidades Avançadas** 🚀
