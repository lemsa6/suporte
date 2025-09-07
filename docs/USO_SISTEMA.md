# 📖 Guia de Uso - Sistema de Suporte v1.2

## **Visão Geral**

O Sistema de Suporte e Tickets é uma plataforma completa para gerenciamento de atendimento ao cliente, desenvolvida para empresas que precisam de um sistema robusto e escalável.

## **👥 Perfis de Usuário**

### **1. Administrador (Admin)**
- **Acesso total** ao sistema
- **Gestão completa** de clientes, tickets e usuários
- **Configurações** do sistema
- **Relatórios** avançados

### **2. Técnico**
- **Gestão de tickets** atribuídos
- **Respostas** e atualizações
- **Upload de anexos**
- **Relatórios** básicos

### **3. Cliente Gestor**
- **Visualização** de todos os tickets da empresa
- **Criação** de tickets para funcionários
- **Acompanhamento** do status

### **4. Cliente Funcionário**
- **Visualização** apenas de seus próprios tickets
- **Criação** de novos tickets
- **Acompanhamento** do status

## **🎫 Gestão de Tickets**

### **1. Criando um Ticket**

#### **Como Cliente**
1. **Acesse** o sistema com suas credenciais
2. **Clique** em "Novo Ticket" no dashboard
3. **Preencha** os campos obrigatórios:
   - **Título**: Descrição resumida do problema
   - **Categoria**: Tipo de problema/solicitação
   - **Prioridade**: Baixa, Média ou Alta
   - **Descrição**: Detalhes completos do problema
4. **Anexe** arquivos se necessário (PDF, imagens, documentos)
5. **Clique** em "Criar Ticket"

#### **Como Admin/Técnico**
1. **Acesse** "Tickets" no menu lateral
2. **Clique** em "Novo Ticket"
3. **Selecione** o cliente e contato
4. **Preencha** os demais campos
5. **Clique** em "Criar Ticket"

### **2. Gerenciando Tickets**

#### **Visualização**
- **Lista de tickets** com filtros por status, prioridade e categoria
- **Busca** por texto livre
- **Ordenação** por data, prioridade ou status
- **Paginação** para grandes volumes

#### **Ações Disponíveis**
- **Ver detalhes** do ticket
- **Responder** ao ticket
- **Alterar status** (aberto → em andamento → resolvido → fechado)
- **Alterar prioridade** (baixa → média → alta)
- **Atribuir** a um técnico
- **Adicionar anexos**
- **Excluir** (soft delete)

#### **Estados do Ticket**
- **🟡 Aberto**: Ticket recém-criado, aguardando atendimento
- **🔵 Em Andamento**: Ticket sendo trabalhado por um técnico
- **🟢 Resolvido**: Problema solucionado, aguardando confirmação
- **🔴 Fechado**: Ticket finalizado e arquivado

### **3. Respondendo a Tickets**

#### **Adicionar Resposta**
1. **Abra** o ticket desejado
2. **Role** até a seção "Adicionar Resposta"
3. **Digite** sua resposta no campo de texto
4. **Selecione** o tipo:
   - **Resposta Pública**: Cliente pode ver
   - **Nota Interna**: Apenas equipe interna
5. **Anexe** arquivos se necessário
6. **Clique** em "Enviar Resposta"

#### **Tipos de Resposta**
- **Resposta Pública**: Visível para o cliente
- **Nota Interna**: Apenas para equipe interna
- **Resposta de Fechamento**: Finaliza o ticket

## **👥 Gestão de Clientes**

### **1. Cadastrando Clientes**

#### **Informações Básicas**
- **Nome da Empresa**: Razão social
- **CNPJ**: Validação automática
- **E-mail**: Contato principal
- **Telefone**: Número de contato
- **Endereço**: Completo da empresa

#### **Status do Cliente**
- **Ativo**: Cliente pode criar tickets
- **Inativo**: Cliente suspenso temporariamente

### **2. Gerenciando Contatos**

#### **Contato Principal**
- **Único** por cliente
- **Obrigatório** para criação de tickets
- **E-mail** para notificações

#### **Contatos Adicionais**
- **Múltiplos** por cliente
- **Diferentes** níveis de acesso
- **Gestão** independente

## **📧 Sistema de Notificações**

### **1. Tipos de Notificação**

#### **Para Usuários Internos (Admin/Técnicos)**
- **🎫 Novo Ticket**: Quando um ticket é criado
- **👤 Ticket Atribuído**: Quando um ticket é atribuído
- **💬 Nova Resposta**: Quando há uma nova resposta
- **🔄 Status Alterado**: Quando o status muda
- **🔒 Ticket Fechado**: Quando um ticket é fechado
- **🚨 Ticket Urgente**: Quando um ticket é marcado como urgente
- **⚡ Prioridade Alterada**: Quando a prioridade muda

#### **Para Clientes**
- **🎫 Ticket Criado**: Confirmação de criação
- **🎫 Ticket Criado para Você**: Quando admin cria ticket para o cliente
- **💬 Nova Resposta**: Quando há uma resposta pública
- **🔄 Status Alterado**: Quando o status muda
- **🔒 Ticket Fechado**: Quando o ticket é finalizado

### **2. Configuração de Notificações**

#### **No Perfil do Usuário**
1. **Acesse** seu perfil (menu dropdown)
2. **Clique** na aba "Notificações"
3. **Marque/desmarque** os tipos desejados
4. **Clique** em "Salvar Configurações"

#### **No Painel Administrativo**
1. **Acesse** "Configurações" no menu lateral
2. **Clique** em "Templates"
3. **Edite** os templates de e-mail
4. **Configure** dados da empresa
5. **Salve** as alterações

## **📎 Sistema de Anexos**

### **1. Upload de Anexos**

#### **Tipos Suportados**
- **Documentos**: PDF, DOC, DOCX, TXT
- **Imagens**: JPG, PNG, GIF, WEBP
- **Planilhas**: XLS, XLSX, CSV
- **Outros**: ZIP, RAR (até 10MB)

#### **Como Anexar**
1. **Crie** ou **edite** um ticket
2. **Clique** em "Escolher Arquivos"
3. **Selecione** os arquivos desejados
4. **Aguarde** o upload
5. **Salve** o ticket

### **2. Visualização de Anexos**

#### **Preview Direto**
- **PDFs**: Visualização no navegador
- **Imagens**: Galeria de imagens
- **Texto**: Visualização inline

#### **Download**
- **Clique** no botão "Download"
- **Arquivo** será baixado automaticamente

## **⚙️ Configurações do Sistema**

### **1. Configurações Gerais**

#### **Dados da Empresa**
- **Nome** do sistema
- **Razão social**
- **E-mail** de contato
- **Telefone**
- **Endereço**
- **Website**
- **Horário** de atendimento

#### **Configurações de E-mail**
- **Servidor SMTP**
- **Porta** e **criptografia**
- **Usuário** e **senha**
- **E-mail** de origem

### **2. Templates de E-mail**

#### **Personalização**
- **Cores** e **logo** da empresa
- **Conteúdo** das mensagens
- **Rodapé** com informações de contato
- **Links** para o sistema

#### **Preview**
- **Visualização** em tempo real
- **Teste** de envio
- **Validação** de templates

## **📊 Relatórios e Dashboard**

### **1. Dashboard Principal**

#### **Estatísticas Gerais**
- **Total** de tickets
- **Tickets abertos**
- **Tickets em andamento**
- **Tickets resolvidos**
- **Tickets fechados**
- **Tickets urgentes**

#### **Gráficos**
- **Tickets por status**
- **Tickets por prioridade**
- **Tickets por categoria**
- **Tendências** temporais

### **2. Relatórios Avançados**

#### **Relatório de Tickets**
- **Filtros** por período, status, prioridade
- **Exportação** em PDF/Excel
- **Métricas** de performance

#### **Relatório de Clientes**
- **Atividade** por cliente
- **Tickets** por empresa
- **Satisfação** do cliente

## **🔍 Busca e Filtros**

### **1. Busca Simples**
- **Digite** palavras-chave
- **Pressione** Enter
- **Resultados** em tempo real

### **2. Filtros Avançados**
- **Status**: Aberto, Em andamento, Resolvido, Fechado
- **Prioridade**: Baixa, Média, Alta
- **Categoria**: Todas as categorias disponíveis
- **Cliente**: Lista de clientes
- **Técnico**: Lista de técnicos
- **Período**: Data de criação

### **3. Ordenação**
- **Data** de criação (mais recente primeiro)
- **Prioridade** (alta primeiro)
- **Status** (aberto primeiro)
- **Cliente** (alfabética)

## **📱 Uso Mobile**

### **1. Interface Responsiva**
- **Adaptação** automática ao tamanho da tela
- **Menu** colapsável
- **Botões** otimizados para touch
- **Formulários** simplificados

### **2. Funcionalidades Mobile**
- **Criação** de tickets
- **Visualização** de tickets
- **Respostas** rápidas
- **Upload** de fotos da câmera

## **🔐 Segurança e Privacidade**

### **1. Controle de Acesso**
- **Login** obrigatório
- **Sessões** seguras
- **Logout** automático
- **Controle** de permissões

### **2. Proteção de Dados**
- **Criptografia** de senhas
- **Validação** de entrada
- **Sanitização** de dados
- **Backup** automático

## **❓ FAQ - Perguntas Frequentes**

### **1. Como criar um usuário cliente?**
- Acesse "Clientes" → "Novo Cliente"
- Preencha os dados da empresa
- Adicione pelo menos um contato
- O contato poderá fazer login com o e-mail cadastrado

### **2. Como alterar a prioridade de um ticket?**
- Abra o ticket desejado
- Clique em "Editar"
- Altere a prioridade no dropdown
- Salve as alterações

### **3. Como anexar arquivos grandes?**
- O limite é de 10MB por arquivo
- Para arquivos maiores, use serviços de nuvem
- Cole o link na descrição do ticket

### **4. Como configurar notificações por e-mail?**
- Acesse "Configurações" → "E-mail"
- Configure o servidor SMTP
- Teste o envio
- Salve as configurações

### **5. Como restaurar um ticket excluído?**
- Tickets excluídos ficam no banco (soft delete)
- Contate o administrador para restaurar
- Ou use comandos específicos no banco

## **🔍 Sistema de Auditoria** 🆕

### **O que é o Sistema de Auditoria?**
O sistema de auditoria registra automaticamente todas as ações realizadas no sistema, incluindo:
- **Criação** de tickets e mensagens
- **Atualizações** de status e prioridade
- **Visualizações** de tickets
- **Respostas** e comentários
- **Fechamento** de tickets

### **Como acessar os logs de auditoria?**
1. Faça login como **Administrador** ou **Técnico**
2. No menu lateral, clique em **"Auditoria"**
3. Visualize todos os logs com filtros avançados

### **Funcionalidades disponíveis:**
- **Lista de Logs**: Visualização paginada de todas as ações
- **Filtros**: Por tipo de evento, usuário, IP, data
- **Detalhes**: Informações completas de cada ação
- **Estatísticas**: Análise de atividade do sistema
- **Exportação**: Download em CSV para análise externa
- **Logs por Ticket**: Histórico específico de cada ticket

### **Informações capturadas:**
- **Usuário**: Quem executou a ação
- **Data/Hora**: Quando a ação foi realizada
- **IP**: Endereço IP do usuário
- **User Agent**: Navegador e sistema operacional
- **URL**: Página onde a ação foi executada
- **Valores**: Dados antes e depois da alteração

## **🆘 Suporte Técnico**

### **1. Problemas Comuns**

#### **Não consigo fazer login**
- Verifique usuário e senha
- Limpe cache do navegador
- Contate o administrador

#### **E-mails não chegam**
- Verifique a pasta de spam
- Confirme o e-mail cadastrado
- Verifique configurações de e-mail

#### **Anexos não carregam**
- Verifique o tamanho do arquivo
- Confirme o tipo de arquivo
- Tente novamente em alguns minutos

### **2. Contato**
- **E-mail**: contato@8bits.pro
- **Sistema**: Use o próprio sistema para reportar problemas
- **Documentação**: Consulte este guia

## **📚 Recursos Adicionais**

### **1. Atalhos de Teclado**
- **Ctrl + N**: Novo ticket
- **Ctrl + S**: Salvar
- **Ctrl + F**: Buscar
- **Esc**: Fechar modal

### **2. Dicas de Uso**
- **Use títulos** descritivos nos tickets
- **Seja específico** na descrição
- **Anexe** evidências quando possível
- **Responda** rapidamente aos clientes

### **3. Boas Práticas**
- **Mantenha** tickets organizados
- **Use** categorias corretas
- **Atualize** status regularmente
- **Comunique-se** claramente

---

**Guia de Uso v1.1** - Sistema de Suporte

*Última atualização: 05/09/2025*
