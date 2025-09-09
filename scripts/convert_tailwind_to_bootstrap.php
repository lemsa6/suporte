<?php

/**
 * Script para converter classes Tailwind para Bootstrap nas views
 */

// Mapeamento de classes Tailwind para Bootstrap
$classMap = [
    // Layout
    'flex' => 'd-flex',
    'inline-flex' => 'd-inline-flex',
    'flex-col' => 'flex-column',
    'flex-row' => 'flex-row',
    'flex-1' => 'flex-grow-1',
    'flex-shrink-0' => 'flex-shrink-0',
    'justify-between' => 'justify-content-between',
    'justify-center' => 'justify-content-center',
    'justify-end' => 'justify-content-end',
    'items-center' => 'align-items-center',
    'items-start' => 'align-items-start',
    'items-end' => 'align-items-end',
    'space-x-(\d+)' => 'gap-$1',
    'space-y-(\d+)' => 'gap-$1',
    'gap-(\d+)' => 'gap-$1',
    'grid' => 'grid',
    'grid-cols-1' => 'row-cols-1',
    'grid-cols-2' => 'row-cols-2',
    'grid-cols-3' => 'row-cols-3',
    'grid-cols-4' => 'row-cols-4',
    
    // Spacing
    'p-(\d+)' => 'p-$1',
    'px-(\d+)' => 'px-$1',
    'py-(\d+)' => 'py-$1',
    'pt-(\d+)' => 'pt-$1',
    'pr-(\d+)' => 'pe-$1',
    'pb-(\d+)' => 'pb-$1',
    'pl-(\d+)' => 'ps-$1',
    'm-(\d+)' => 'm-$1',
    'mx-(\d+)' => 'mx-$1',
    'my-(\d+)' => 'my-$1',
    'mt-(\d+)' => 'mt-$1',
    'mr-(\d+)' => 'me-$1',
    'mb-(\d+)' => 'mb-$1',
    'ml-(\d+)' => 'ms-$1',
    
    // Typography
    'text-xs' => 'fs-6',
    'text-sm' => 'fs-6',
    'text-base' => 'fs-5',
    'text-lg' => 'fs-4',
    'text-xl' => 'fs-3',
    'text-2xl' => 'fs-2',
    'text-3xl' => 'fs-1',
    'font-medium' => 'fw-medium',
    'font-semibold' => 'fw-semibold',
    'font-bold' => 'fw-bold',
    'text-gray-400' => 'text-muted',
    'text-gray-500' => 'text-muted',
    'text-gray-600' => 'text-muted',
    'text-gray-700' => 'text-dark',
    'text-gray-800' => 'text-dark',
    'text-gray-900' => 'text-dark',
    'text-white' => 'text-white',
    'text-black' => 'text-black',
    'text-brand-600' => 'text-primary',
    
    // Backgrounds
    'bg-white' => 'bg-white',
    'bg-gray-50' => 'bg-light',
    'bg-gray-100' => 'bg-light',
    'bg-brand-600' => 'bg-primary',
    'bg-red-100' => 'bg-danger bg-opacity-10',
    'bg-green-100' => 'bg-success bg-opacity-10',
    'bg-yellow-100' => 'bg-warning bg-opacity-10',
    'bg-blue-100' => 'bg-info bg-opacity-10',
    
    // Borders
    'rounded-md' => 'rounded',
    'rounded-lg' => 'rounded',
    'rounded-full' => 'rounded-circle',
    'border' => 'border',
    'border-gray-200' => 'border-light',
    'border-gray-300' => 'border-light',
    
    // Effects
    'shadow' => 'shadow-sm',
    'shadow-sm' => 'shadow-sm',
    'shadow-md' => 'shadow',
    'shadow-lg' => 'shadow-lg',
    'shadow-xl' => 'shadow-lg',
    
    // Components
    'btn' => 'btn',
    'btn-primary' => 'btn-primary',
    'btn-secondary' => 'btn-secondary',
];

// Diretório das views
$viewsDir = __DIR__ . '/resources/views';

// Função para converter classes Tailwind para Bootstrap
function convertClasses($content, $classMap) {
    // Encontrar atributos class=""
    preg_match_all('/class="([^"]+)"/', $content, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $match) {
        $originalClasses = $match[1];
        $newClasses = $originalClasses;
        
        // Substituir classes
        foreach ($classMap as $tailwind => $bootstrap) {
            $pattern = '/\b' . preg_quote($tailwind, '/') . '\b/';
            $newClasses = preg_replace($pattern, $bootstrap, $newClasses);
        }
        
        // Substituir no conteúdo
        if ($originalClasses !== $newClasses) {
            $content = str_replace('class="' . $originalClasses . '"', 'class="' . $newClasses . '"', $content);
        }
    }
    
    return $content;
}

// Função recursiva para processar arquivos
function processDirectory($dir, $classMap) {
    $files = scandir($dir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            processDirectory($path, $classMap);
        } else if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            echo "Processando: $path\n";
            
            $content = file_get_contents($path);
            $newContent = convertClasses($content, $classMap);
            
            if ($content !== $newContent) {
                file_put_contents($path, $newContent);
                echo "  Atualizado!\n";
            } else {
                echo "  Sem alterações.\n";
            }
        }
    }
}

// Iniciar processamento
echo "Iniciando conversão de Tailwind para Bootstrap...\n";
processDirectory($viewsDir, $classMap);
echo "Conversão concluída!\n";
