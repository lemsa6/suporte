# Sistema de Cores Padrão - Documentação

> **📚 Para documentação completa, consulte o [Compêndio do Sistema](COMPENDIO_SISTEMA_SUPORTE.md)**

## 🎯 **Visão Geral**

O sistema agora possui **cores padrão automáticas** que são aplicadas quando elementos não possuem classes específicas de cor. Isso garante consistência visual em todo o projeto.

## 🔧 **Como Funciona**

### **1. Variáveis CSS Padrão**
```css
:root {
  --cor-padrao: var(--roxo);           /* Cor padrão para elementos sem classe específica */
  --cor-padrao-hover: var(--roxo-claro); /* Hover padrão */
  --cor-texto-padrao: var(--cinza);    /* Texto padrão */
  --cor-texto-secundario: var(--cinza-claro); /* Texto secundário padrão */
  --cor-fundo-padrao: var(--branco);   /* Fundo padrão */
  --cor-borda-padrao: var(--cinza-claro); /* Borda padrão */
}
```

### **2. Aplicação Automática**

#### **Botões sem classe específica:**
```html
<!-- Este botão automaticamente terá cor roxa -->
<button>Clique aqui</button>

<!-- Este botão mantém sua classe específica -->
<button class="btn-danger">Excluir</button>
```

#### **Links sem classe específica:**
```html
<!-- Este link automaticamente terá cor roxa -->
<a href="/dashboard">Dashboard</a>

<!-- Este link mantém sua classe específica -->
<a href="/login" class="text-verde">Login</a>
```

#### **Inputs sem classe específica:**
```html
<!-- Este input terá borda roxa no focus -->
<input type="text" placeholder="Digite algo">

<!-- Este input mantém suas classes específicas -->
<input type="text" class="border-verde focus:border-verde">
```

## 🎨 **Classes Utilitárias Disponíveis**

### **Classes de Cores Padrão:**
```html
<div class="bg-padrao">Fundo roxo padrão</div>
<p class="text-padrao">Texto cinza padrão</p>
<span class="text-padrao-secundario">Texto cinza claro</span>
<div class="border-padrao">Borda cinza claro</div>
<button class="hover-padrao">Hover roxo claro</button>
```

### **Classes de Herança:**
```html
<div class="bg-roxo text-branco">
  <p class="herda-cor">Herdará a cor branca do pai</p>
  <div class="herda-bg">Herdará o fundo roxo do pai</div>
</div>
```

## 📋 **Exemplos Práticos**

### **Exemplo 1: Botão Simples**
```html
<!-- Sem classes - usa cor padrão automaticamente -->
<button onclick="salvar()">Salvar</button>

<!-- Com classes específicas - sobrescreve o padrão -->
<button class="btn-danger" onclick="excluir()">Excluir</button>
<button class="btn-success" onclick="aprovar()">Aprovar</button>
```

### **Exemplo 2: Formulário**
```html
<form>
  <!-- Inputs sem classe específica usam borda padrão -->
  <input type="text" name="nome" placeholder="Nome completo">
  <input type="email" name="email" placeholder="E-mail">
  
  <!-- Input com classe específica mantém sua cor -->
  <input type="text" name="telefone" class="border-verde" placeholder="Telefone">
  
  <!-- Botão sem classe usa cor padrão -->
  <button type="submit">Enviar</button>
</form>
```

### **Exemplo 3: Lista de Links**
```html
<nav>
  <!-- Links sem classe específica usam cor roxa -->
  <a href="/dashboard">Dashboard</a>
  <a href="/tickets">Tickets</a>
  <a href="/relatorios">Relatórios</a>
  
  <!-- Link com classe específica mantém sua cor -->
  <a href="/logout" class="text-vermelho">Sair</a>
</nav>
```

## ⚡ **Vantagens do Sistema**

### **1. Consistência Automática**
- Elementos sem classes específicas sempre seguem o padrão visual
- Reduz a necessidade de lembrar classes de cor
- Evita elementos "sem cor" no projeto

### **2. Flexibilidade**
- Classes específicas sempre sobrescrevem o padrão
- Sistema de herança para elementos filhos
- Fácil manutenção centralizada

### **3. Performance**
- CSS otimizado com seletores eficientes
- Menos classes necessárias no HTML
- Carregamento mais rápido

## 🔄 **Como Personalizar**

### **Alterar Cores Padrão:**
```css
:root {
  --cor-padrao: #sua-cor-aqui;
  --cor-padrao-hover: #sua-cor-hover-aqui;
  --cor-texto-padrao: #sua-cor-texto-aqui;
}
```

### **Adicionar Novos Elementos:**
```css
/* Exemplo: Selects sem classe específica */
select:not([class*="border-"]) {
  border-color: var(--cor-borda-padrao);
}

select:not([class*="border-"]):focus {
  border-color: var(--cor-padrao);
}
```

## 🎯 **Casos de Uso Recomendados**

### **✅ Use o Sistema Padrão quando:**
- Criar elementos simples sem necessidade de cores específicas
- Desenvolver rapidamente protótipos
- Quiser consistência visual automática
- Trabalhar com formulários básicos

### **❌ Use Classes Específicas quando:**
- Precisar de cores específicas (sucesso, erro, aviso)
- Desenvolver componentes complexos
- Quiser controle total sobre a aparência
- Trabalhar com temas personalizados

## 📝 **Notas Importantes**

1. **Prioridade:** Classes específicas sempre sobrescrevem o padrão
2. **Herança:** Use `herda-*` para elementos que devem seguir o pai
3. **Manutenção:** Altere as variáveis CSS para mudanças globais
4. **Performance:** O sistema usa seletores CSS eficientes
5. **Compatibilidade:** Funciona com todos os navegadores modernos

---

**Sistema implementado em:** `resources/css/tailwind.css`  
**Última atualização:** Dezembro 2024
