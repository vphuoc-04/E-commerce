<?php
interface CustomDropBoxMenuInterface {
    public function render(): string;
}

class CustomDropBoxMenu implements CustomDropBoxMenuInterface {
    private string $class;
    private string $name;
    private array $options;
    private ?string $selected;
    private bool $required;
    private ?string $error;
    private string $placeholder;

    public function __construct(
        string $class,
        string $name,
        array $options = [],
        ?string $selected = null,
        bool $required = false,
        ?string $error = null,
        string $placeholder = "Chọn một mục"
    ){
        $this->class = $class;
        $this->name = $name;
        $this->options = $options;
        $this->selected = $selected;
        $this->required = $required;
        $this->error = $error;
        $this->placeholder = $placeholder;
    }

    public function render(): string {
        static $resourcesLoaded = false;
        $html = '';

        if (!$resourcesLoaded) {
            $html .= '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomDropBoxMenu.css">';
            $html .= '<script src="http://localhost/WEBBANHANG/views/customs/js/custom-drop-box-menu.js"></script>';
            $resourcesLoaded = true;
        }

        $html .= sprintf('<select class="%s" name="%s"%s>', 
            htmlspecialchars($this->class, ENT_QUOTES), 
            htmlspecialchars($this->name, ENT_QUOTES),
            $this->required ? ' required' : ''
        );

        $html .= sprintf(
            '<option value="" disabled %s>%s</option>',
            $this->selected === null ? 'selected' : '',
            htmlspecialchars($this->placeholder, ENT_QUOTES)
        );

        foreach ($this->options as $value => $label) {
            $isSelected = ($this->selected !== null && $this->selected == $value) ? 'selected' : '';
            $html .= sprintf(
                '<option value="%s" %s>%s</option>',
                htmlspecialchars($value, ENT_QUOTES),
                $isSelected,
                htmlspecialchars($label, ENT_QUOTES)
            );
        }

        $html .= '</select>';

        if ($this->required) {
            $html .= '<span class="text-danger d-block mt-1"></span>';
        }

        if (!empty($this->error)) {
            $html .= sprintf('<span class="text-danger d-block mt-1">%s</span>', htmlspecialchars($this->error, ENT_QUOTES));
        }

        return $html;
    }
}
