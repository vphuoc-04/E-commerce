<?php
interface CustomAlertDialogInterface {
    public function render(): string;
}

class CustomAlertDialog implements CustomAlertDialogInterface {
    public function __construct(
        private string $id = 'custom-alert-dialog',
        private string $class = '',
        private string $title = 'Xác nhận',
        private string $description = 'Bạn có chắc chắn muốn thực hiện hành động này?',
        private string $closeLabel = 'Hủy bỏ',
        private string $confirmLabel = 'Xác nhận',
        private bool $isOpen = false
    ) {}

    public function render(): string {
        static $resourcesLoaded = false;
        $html = '';

        if(!$resourcesLoaded) {
            $html .= '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomAlertDialog.css">';
            $html .= '<script src="http://localhost/WEBBANHANG/views/customs/js/custom-alert-dialog.js"></script>';
            $resourcesLoaded = true;
        }

        $display = $this->isOpen ? "block" : "none";
        $classes = "custom-alert-dialog {$this->class}";

        $title = htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($this->description, ENT_QUOTES, 'UTF-8');
        $closeLabel = htmlspecialchars($this->closeLabel, ENT_QUOTES, 'UTF-8');
        $confirmLabel = htmlspecialchars($this->confirmLabel, ENT_QUOTES, 'UTF-8');

        return $html . sprintf(
            '<div id="%s" class="%s" style="display: %s;">
                <div class="custom-alert-overlay"></div>
                <div class="custom-alert-content">
                    <div class="custom-alert-header">
                        <h3 class="custom-alert-title">%s</h3>
                    </div>
                    <div class="custom-alert-body">
                        <div class="custom-alert-icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <p class="custom-alert-description">%s</p>
                    </div>
                    <div class="custom-alert-footer">
                        <button type="button" class="custom-alert-btn custom-alert-cancel">
                            %s
                        </button>
                        <button type="button" class="custom-alert-btn custom-alert-confirm">
                            %s
                        </button>
                    </div>
                </div>
            </div>',
            htmlspecialchars($this->id),
            htmlspecialchars(trim($classes)),
            $display,
            $title,
            $description,
            $closeLabel,
            $confirmLabel
        );
    }

    // Phương thức static để render dialog cơ bản
    public static function renderStatic(): string {
        static $rendered = false;
        if ($rendered) return '';
        
        $rendered = true;
        return '
            <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomAlertDialog.css">
            <script src="http://localhost/WEBBANHANG/views/customs/js/custom-alert-dialog.js"></script>
            <div id="custom-alert-dialog" class="custom-alert-dialog" style="display: none;">
                <div class="custom-alert-overlay"></div>
                <div class="custom-alert-content">
                    <div class="custom-alert-header">
                        <h3 class="custom-alert-title">Xác nhận</h3>
                    </div>
                    <div class="custom-alert-body">
                        <div class="custom-alert-icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <p class="custom-alert-description" id="custom-alert-message">Bạn có chắc chắn muốn thực hiện hành động này?</p>
                    </div>
                    <div class="custom-alert-footer">
                        <button type="button" class="custom-alert-btn custom-alert-cancel">Hủy bỏ</button>
                        <button type="button" class="custom-alert-btn custom-alert-confirm">Xác nhận</button>
                    </div>
                </div>
            </div>
        ';
    }
}