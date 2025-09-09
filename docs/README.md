# 📚 Documentação - Sistema de Suporte v1.2

Bem-vindo à documentação completa do Sistema de Suporte e Tickets. Aqui você encontrará todos os recursos necessários para instalar, configurar e usar o sistema.

## **📖 Índice de Documentação**

### **🚀 Para Começar**
- **[Compêndio Completo](COMPENDIO_SISTEMA_SUPORTE.md)** - Documentação consolidada do sistema
- **[Instalação Rápida](../INSTALL.md)** - Instalação em 5 passos
- **[Guia de Instalação Detalhado](INSTALACAO.md)** - Instalação completa passo a passo

### **👥 Para Usuários**
- **[Guia de Uso](USO_SISTEMA.md)** - Como usar todas as funcionalidades do sistema
- **[FAQ](USO_SISTEMA.md#-faq---perguntas-frequentes)** - Perguntas frequentes e soluções

### **🔧 Para Desenvolvedores**
- **[Arquitetura do Sistema](ARQUITETURA.md)** - Documentação técnica completa
- **[Componentes do Sistema](COMPONENTES_SISTEMA.md)** - Documentação dos componentes Blade
- **[Sistema de Cores Padrão](SISTEMA_CORES_PADRAO.md)** - Sistema de cores e design

## **🎯 Por Onde Começar?**

### **Se você é um usuário final:**
1. Leia o **[README Principal](../README.md)** para entender o sistema
2. Siga o **[Guia de Instalação Rápido](../INSTALL.md)** para instalar
3. Consulte o **[Guia de Uso](USO_SISTEMA.md)** para aprender a usar

### **Se você é um administrador:**
1. Leia o **[Guia de Instalação Detalhado](INSTALACAO.md)**
2. Configure o sistema seguindo as instruções
3. Consulte o **[Guia de Uso](USO_SISTEMA.md)** para administrar

### **Se você é um desenvolvedor:**
1. Leia a **[Arquitetura do Sistema](ARQUITETURA.md)**
2. Entenda os padrões e convenções
3. Consulte a documentação da API

## **📋 Funcionalidades Principais**

### **✅ Sistema Completo de Tickets**
- Criação, edição e gerenciamento de tickets
- Sistema de status e prioridades
- Histórico completo de mensagens
- Soft delete para preservar dados

### **✅ Gestão de Clientes e Contatos**
- CRUD completo de empresas
- Sistema de contatos hierárquico
- Validação de CNPJ
- Controle de status ativo/inativo

### **✅ Sistema de Notificações Avançado**
- Notificações para usuários internos (Laravel Notifications)
- E-mails para clientes (Laravel Mailables)
- Templates personalizáveis
- Dados dinâmicos da empresa

### **✅ Sistema de Anexos com Preview**
- Upload de arquivos com validação
- Preview de PDFs, imagens e texto
- Controle de acesso baseado em roles
- Modal responsivo para visualização

### **✅ Painel de Configurações Administrativas**
- Configurações gerais do sistema
- Configuração de e-mail com presets
- Editor de templates de notificação
- Dados dinâmicos da empresa

### **✅ Sistema de Roles Hierárquicos**
- Admin: Acesso total
- Técnico: Gestão de tickets atribuídos
- Cliente Gestor: Todos os tickets da empresa
- Cliente Funcionário: Apenas seus tickets

## **🛠 Tecnologias Utilizadas**

### **Backend**
- **Laravel 12** - Framework PHP moderno
- **PHP 8.2+** - Linguagem de programação
- **MySQL 8.0** - Banco de dados relacional
- **Docker** - Containerização completa

### **Frontend**
- **Tailwind CSS** - Framework CSS utilitário
- **Componentes Blade** - Componentes reutilizáveis
- **JavaScript Vanilla** - Interatividade
- **Vite** - Build tool moderno

### **Infraestrutura**
- **Docker Compose** - Orquestração de containers
- **Nginx** - Web server otimizado
- **Mailpit** - E-mail para desenvolvimento
- **Gmail SMTP** - E-mail para produção

## **📊 Estrutura do Projeto**

```
suporte/
├── app/                    # Código da aplicação
│   ├── Http/Controllers/   # Controllers
│   ├── Models/            # Models Eloquent
│   ├── Services/          # Serviços de negócio
│   ├── Mail/              # Classes Mailable
│   └── Notifications/     # Notificações
├── database/              # Banco de dados
│   ├── migrations/        # Migrações
│   └── seeders/          # Seeders
├── resources/             # Recursos frontend
│   ├── views/            # Templates Blade
│   ├── scss/             # Estilos
│   └── js/               # JavaScript
├── docker/               # Configurações Docker
└── docs/                 # Documentação
```

## **🔐 Segurança e Performance**

### **Segurança**
- Autenticação Laravel Breeze
- Autorização com Gates e Policies
- Proteção CSRF
- Validação e sanitização de dados
- Controle de acesso granular

### **Performance**
- Consultas otimizadas com Eager Loading
- Cache de configurações
- Assets comprimidos e minificados
- Índices de banco de dados
- Containers Docker otimizados

## **📈 Roadmap**

### **v1.2 (Próxima versão)**
- [ ] Sistema de SLA (Service Level Agreement)
- [ ] Busca avançada com filtros combinados
- [ ] Templates de resposta personalizáveis
- [ ] Sistema de tags para tickets
- [ ] Relatórios avançados com exportação

### **v1.3 (Futuro)**
- [ ] Notificações push (WebSockets)
- [ ] Integração WhatsApp Business API
- [ ] Sistema de workflows automatizados
- [ ] API REST completa
- [ ] App mobile nativo

## **🤝 Contribuição**

### **Como Contribuir**
1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

### **Padrões de Código**
- Siga as convenções do Laravel
- Use PSR-12 para PHP
- Documente funções complexas
- Escreva testes quando possível

## **📞 Suporte e Contato**

### **Problemas Técnicos**
- **GitHub Issues**: [Criar issue](https://github.com/lemsa6/suporte/issues)
- **E-mail**: contato@8bits.pro
- **Documentação**: Esta pasta `docs/`

### **Comandos de Suporte**
```bash
# Ver logs de erro
docker-compose logs app | grep ERROR

# Testar notificações
docker-compose exec app php artisan test:notifications

# Limpar cache
docker-compose exec app php artisan optimize:clear
```

## **📄 Licença**

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## **👥 Desenvolvedor**

**8Bits Pro** - [GitHub](https://github.com/lemsa6)

---

**Documentação v1.1** - Sistema de Suporte e Tickets

*Última atualização: 05/09/2025*

**Desenvolvido com ❤️ em Laravel 12**
