<?php
interface CustomAlertInterface {
    public function render(): string;
}

class CustomAlert implements CustomAlertInterface {
    public function __construct(
        private string $id = 'custom-alert',
        private string $class = '',
        private string $position = 'top-right',
        private ?string $description = null
    ) {}

    public function render(): string {
        static $resourcesLoaded = false;
        $html = '';

        if(!$resourcesLoaded) {
            $html .= '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomAlert.css">';
            $html .= '<script src="http://localhost/WEBBANHANG/views/customs/js/custom-alert.js"></script>';
            $resourcesLoaded = true;
        }

        $classes = "custom-alert-container position-{$this->position} {$this->class}";
        $descHtml = $this->description 
            ? "<div class='custom-alert-description'>" . htmlspecialchars($this->description) . "</div>" 
            : '';

        return $html . sprintf(
            '<div id="%s" class="%s" aria-live="polite">%s</div>',
            htmlspecialchars($this->id),
            htmlspecialchars(trim($classes)),
            $descHtml
        );
    }
}