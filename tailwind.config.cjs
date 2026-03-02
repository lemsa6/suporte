/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/**/*.php",
  ],
  safelist: [
    // Cores de auditoria - usando regex para capturar todas as variações
    {
      pattern: /bg-(verde-claro|amarelo-claro|vermelho-claro|roxo-pastel|roxo-escuro|vermelho-escuro|azul-claro|cinza-claro-2|verde-pastel|lilás)/,
    },
    // Cores de tickets - classes reais geradas pelo SystemHelper
    {
      pattern: /bg-(amarelo-claro|azul-claro|verde-claro|cinza-claro-2|vermelho-claro)/,
    },
    // Classes específicas para badges de status e prioridade
    'bg-amarelo-claro', 'bg-azul-claro', 'bg-verde-claro', 'bg-cinza-claro-2', 'bg-vermelho-claro',
  ],
  theme: {
    extend: {
      colors: {
        // Sistema de Cores Simplificado - 12 Cores Essenciais
        primary: '#3d235a',           // Cor principal (roxo-mate)
        primaryHover: '#4d2f6f',      // Hover principal (roxo-mate-2)
        accent: '#f0ba00',            // Cor de destaque (amarelo)
        success: '#55c38e',           // Alerta verde
        error: '#c35555',             // Alerta vermelho
        info: '#7c55c3',              // Alerta confirmação/detalhes (roxo-det)
        text: '#5a5a5a',              // Texto principal (cinza)
        textSecondary: '#a0a0a0',     // Texto secundário (cinza-claro)
        textHover: '#ffffff',         // Texto hover (branco)
        bgSite: '#f9f9f9',            // Fundo do site (creme)
        bgDiv: '#ffffff',             // Fundo das divs (branco)
        textTertiary: '#ffffff',      // Texto terciário (branco)
        
        // Cores customizadas para compatibilidade com CSS existente
        roxo: '#3d235a',              // Cor principal (roxo-mate)
        'roxo-claro': '#4d2f6f',      // Hover principal (roxo-mate-2)
        'roxo-det': '#7c55c3',        // Alerta confirmação (roxo-det)
        cinza: '#5a5a5a',             // Texto principal (cinza)
        'cinza-claro': '#a0a0a0',     // Texto secundário (cinza-claro)
        amarelo: '#f0ba00',           // Cor de destaque (amarelo)
        verde: '#55c38e',             // Alerta verde
        vermelho: '#c35555',          // Alerta vermelho
        branco: '#ffffff',            // Fundo das divs e texto (branco)
        creme: '#f9f9f9',             // Fundo do site (creme)
        
        // Cores da Sidebar (mantendo compatibilidade)
        sidebar: {
          DEFAULT: '#3d235a',
          hover: '#4d2f6f',
        },
        
        // Cores para auditoria - reutilizáveis
        'verde-claro': '#d1e0c7',        /* Verde claro - Criado */
        'amarelo-claro': '#fff8bb',      /* Amarelo claro - Atualizado */
        'vermelho-claro': '#f8d3d3',     /* Vermelho claro - Excluído */
        'roxo-pastel': '#c9a9e3',        /* Roxo pastel - Respondido */
        'roxo-escuro': '#3c006c',        /* Roxo escuro - Fechado */
        'vermelho-escuro': '#770000',    /* Vermelho escuro - Reaberto */
        'azul-claro': '#b0caff',         /* Azul claro - Atribuído */
        'cinza-claro-2': '#cecece',      /* Cinza claro 2 - Status alterado */
        'verde-pastel': '#b2d9a8',       /* Verde pastel - Prioridade alterada */
        'lilás': '#d3c7e0'               /* Lilás - Visualizado */
      },
             fontFamily: {
               'sans': ['Lato', 'sans-serif'],
               'lato': ['Lato', 'sans-serif'],
             },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
      },
      borderRadius: {
        'xl': '0.75rem',
        '2xl': '1rem',
        '3xl': '1.5rem',
      },
      boxShadow: {
        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
        'medium': '0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
      }
    },
  },
  plugins: [],
}
