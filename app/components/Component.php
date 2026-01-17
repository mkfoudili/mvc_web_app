<?php

abstract class Component {
    protected array $props = [];
    protected array $attributes = [];
    
    public function __construct(array $props = []) {
        $this->props = $props;
        $this->extractAttributes();
    }
    
    public function render(): string {
        ob_start();
        $this->template();
        return ob_get_clean();
    }

    abstract protected function template(): void;

    protected function prop(string $key, $default = null) {
        return $this->props[$key] ?? $default;
    }

    private function extractAttributes(): void {
        $htmlAttrs = ['class', 'id', 'style', 'title', 'aria-label'];
        
        foreach ($this->props as $key => $value) {
            if (in_array($key, $htmlAttrs) || strpos($key, 'data-') === 0) {
                $this->attributes[$key] = $value;
                unset($this->props[$key]);
            }
        }
    }

    protected function renderAttributes(array $additional = []): string {
        $attrs = array_merge($this->attributes, $additional);
        $html = [];
        
        foreach ($attrs as $key => $value) {
            if ($value !== null && $value !== false) {
                if ($value === true) {
                    $html[] = $this->e($key);
                } else {
                    $html[] = $this->e($key) . '="' . $this->e($value) . '"';
                }
            }
        }
        
        return implode(' ', $html);
    }

    public static function make(array $props = []): string {
        $component = new static($props);
        return $component->render();
    }

    protected function has(string $key): bool {
        return isset($this->props[$key]) && !empty($this->props[$key]);
    }
}