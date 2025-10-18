<?php
interface CustomBannerInterface {
    public function render(array $item = []): string;
}

class CustomBanner implements CustomBannerInterface {
    private string $title;
    private string $subtitle;
    private string $backgroundImage;
    private ?string $buttonText;
    private ?string $buttonLink;

    public function __construct(
        string $title,
        string $subtitle = '',
        string $backgroundImage = '',
        ?string $buttonText = null,
        ?string $buttonLink = null
    ) {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->backgroundImage = $backgroundImage;
        $this->buttonText = $buttonText;
        $this->buttonLink = $buttonLink;
    }

    public function render(array $item = []): string {
        static $resourcesLoaded = false;
        $html = '';

        if (!$resourcesLoaded) {
            $html .= '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomBanner.css">';
            $html .= '<script src="http://localhost/WEBBANHANG/views/customs/js/custom-banner.js"></script>';
            $resourcesLoaded = true;
        }
        
        $title = htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8');
        $subtitle = htmlspecialchars($this->subtitle, ENT_QUOTES, 'UTF-8');

        $style = $this->backgroundImage
            ? "style=\"background-image:url('" . htmlspecialchars($this->backgroundImage, ENT_QUOTES, 'UTF-8') . "');background-size:cover;background-position:center;\""
            : '';

        $buttonHtml = '';
        if ($this->buttonText) {
            $href = htmlspecialchars($this->buttonLink ?? '#', ENT_QUOTES, 'UTF-8');
            $buttonText = htmlspecialchars($this->buttonText, ENT_QUOTES, 'UTF-8');
            $buttonHtml = "<a href=\"$href\" class=\"banner-button\">$buttonText</a>";
        }

        $html .= "
            <div class='custom-banner' $style>
                <div class='banner-overlay'></div>
                <div class='banner-content'>
                    <h1>$title</h1>
                    " . ($this->subtitle ? "<p>$subtitle</p>" : "") . "
                    $buttonHtml
                </div>
            </div>
        ";

        return $html;
    }
}
