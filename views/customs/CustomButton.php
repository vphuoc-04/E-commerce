<?php
include_once 'CustomLoading.php';

interface CustomButtonInterface {
    public function render(): string;
}

class CustomButton implements CustomButtonInterface {
    private string $text;
    private bool $loading;
    private bool $disabled;
    private string $extraClass;
    private CustomLoading $spinner;

    public function __construct(
        string $text, 
        bool $loading = false, 
        bool $disabled = false, 
        string $extraClass = "",
        ?CustomLoading $spinner = null
    ){
        $this->text = $text;
        $this->loading = $loading;
        $this->disabled = $disabled;
        $this->extraClass = $extraClass;
        $this->spinner = $spinner ?? new CustomLoading("20px", "20px");
    }

    public function render(): string {
        $isDisabled = $this->loading || $this->disabled;
        $class = $isDisabled ? "btn disabled ".$this->extraClass : "btn active ".$this->extraClass;
        $disabledAttr = $isDisabled ? "disabled" : "";

        $css = '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomButton.css">';
        $js = '<script src="http://localhost/WEBBANHANG/views/customs/js/custom-button.js"></script>';

        $content = $this->loading ? $this->spinner->render() : htmlspecialchars($this->text);

        return sprintf('%s%s<div class="loading"><button type="submit" class="%s" %s>%s</button></div>',
            $css, $js, $class, $disabledAttr, $content
        );
    }
}