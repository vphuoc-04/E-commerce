<?php
include_once 'CustomLoading.php';

interface CustomButtonInterface {
    public function render(): string;
}

class CustomButton implements CustomButtonInterface {
    private string $text;
    private bool $loading;
    private bool $disabled;
    private string $class;

    public function __construct(string $text,bool $loading = false,bool $disabled = false, string $class = '') {
        $this->text = $text;
        $this->loading = $loading;
        $this->disabled = $disabled;
        $this->class = $class;
    }

    public function render(): string {
        static $resourcesLoaded = false;
        $html = '';

        if (!$resourcesLoaded) {
            $html .= '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomButton.css">';
            $html .= '<script src="http://localhost/WEBBANHANG/views/customs/js/custom-button.js"></script>';
            $resourcesLoaded = true;
        }

        $isDisabled = $this->loading || $this->disabled;
        $class = ($isDisabled ? "btn disabled " : "btn active ") . $this->class;
        $disabledAttr = $isDisabled ? "disabled" : "";

        $spinner = new CustomLoading($this->loading, '25px', '25px');
        $content = $this->loading? $spinner->render() : htmlspecialchars($this->text);

        $html .= sprintf(
            '<div class="loading"><button type="submit" class="%s" %s>%s</button></div>',
            $class,
            $disabledAttr,
            $content
        );

        return $html;
    }
}