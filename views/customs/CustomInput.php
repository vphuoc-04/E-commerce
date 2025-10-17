<?php
interface InputInterface {
    public function render(): string;
}

class CustomInput implements InputInterface {
    private string $type;
    private string $class;
    private string $placeholder;
    private string $name;
    private bool $required;
    private string $value;
    private ?string $error;
    private array $attributes;

    public function __construct(
        string $type, 
        string $class, 
        string $placeholder, 
        string $name, 
        bool $required = false, 
        string $value = "",
        ?string $error = null,
        array $attributes = []
    ){
        $this->type = $type;
        $this->class = $class;
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->required = $required;
        $this->value = $value;
        $this->error = $error;
        $this->attributes = $attributes;
    }

    public function render(): string {
        static $resourcesLoaded = false;
        $html = '';

        if (!$resourcesLoaded) {
            $html .= '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomInput.css">';
            $html .= '<script src="http://localhost/WEBBANHANG/views/customs/js/custom-input.js"></script>';
            $resourcesLoaded = true;
        }

        $attrHtml = '';

        foreach ($this->attributes as $attr => $val) {
            if ($val !== null && $val !== '') {
                $attrHtml .= sprintf(' %s="%s"', htmlspecialchars($attr, ENT_QUOTES), htmlspecialchars($val, ENT_QUOTES));
            }
        }

        $html .= sprintf(
            '<input type="%s" class="%s" placeholder="%s" name="%s" value="%s"%s%s>',
            htmlspecialchars($this->type, ENT_QUOTES),
            htmlspecialchars($this->class, ENT_QUOTES),
            htmlspecialchars($this->placeholder, ENT_QUOTES),
            htmlspecialchars($this->name, ENT_QUOTES),
            htmlspecialchars($this->value, ENT_QUOTES),
            $this->required ? ' required' : '',
            $attrHtml
        );

        if ($this->required) {
            $html .= '<span class="text-danger d-block mt-1"></span>';
        }

        if (!empty($this->error)) {
            $html .= sprintf('<span class="text-danger d-block mt-1">%s</span>', htmlspecialchars($this->error, ENT_QUOTES));
        }

        return $html;
    }
}