<?php

interface CustomFormInterface {
    public function render(array $item): string;
}

class CustomForm implements CustomFormInterface {
    private array $children = [];
    private string $class;
    private string $action;
    private string $method;
    private ?string $enctype = null;

    public function __construct(array|object $children = [], string $class = '', string $action = '', string $method = 'POST', ?string $enctype = null) {
        $this->children = is_array($children) ? $children : [$children];
        $this->class = $class;
        $this->action = $action;
        $this->method = strtoupper($method);
        $this->enctype = $enctype;
    }

    public function render(array $item): string {
        static $resourcesLoaded = false;
        $html = '';

        if (!$resourcesLoaded) {
            $html .= '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomForm.css">';
            $html .= '<script src="http://localhost/WEBBANHANG/views/customs/js/custom-form.js"></script>';
            $resourcesLoaded = true;
        }

        $class = trim("custom-form " . $this->class);
        $actionAttr = $this->action ? "action='{$this->action}'" : '';
        $methodAttr = "method='{$this->method}'";
        $enctypeAttr = $this->enctype ? "enctype='{$this->enctype}'" : '';

        $html .= "<form {$actionAttr} {$methodAttr} {$enctypeAttr} class='{$class}'>";

        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'render')) {
                $html .= $child->render($item);
            } elseif(is_string($child)) {
                $html .= $child;
            }
        }

        $html .= '</form>';
        return $html;
    }
}
