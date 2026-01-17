<?php

/**
 * Usage: component('Button', ['text' => 'Click me', 'variant' => 'primary'])
 */
function component(string $name, array $props = []): string {
    static $componentBase = null;
    static $loaded = [];
    
    if ($componentBase === null) {
        require_once __DIR__ . '/../components/Component.php';
        $componentBase = true;
    }
    
    $componentFile = __DIR__ . "/../components/ui/{$name}.php";
    
    if (!file_exists($componentFile)) {
        $componentFile = __DIR__ . "/../components/layout/{$name}.php";
    }
    
    if (!file_exists($componentFile)) {
        error_log("Component '{$name}' not found");
        return "<!-- Component '{$name}' not found -->";
    }
    
    if (!isset($loaded[$name])) {
        require_once $componentFile;
        $loaded[$name] = true;
    }
    
    if (!class_exists($name)) {
        error_log("Component class '{$name}' not found");
        return "<!-- Component class '{$name}' not found -->";
    }
    
    return $name::make($props);
}

/**
 * Usage dans une vue: layout('base', ['title' => 'My Page', 'content' => $htmlContent])
 */
function layout(string $name, array $data = []): void {
    $layoutFile = __DIR__ . "/../layouts/{$name}.php";
    
    if (!file_exists($layoutFile)) {
        die("Layout '{$name}' not found at {$layoutFile}");
    }
    
    extract($data);
    require $layoutFile;
}


function e(?string $value): string {
    return $value ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : '';
}