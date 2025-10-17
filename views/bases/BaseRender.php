<?php

class BaseRender {
    protected $describe;
    protected $table;
    protected $buttonAction;
    protected $buttonTableActions;

    protected function getValue($item, $key) {
        if(is_array($item)) {
            return $item[$key] ?? '';
        } else {
            return $item->$key ?? '';
        }
    }

    protected function renderField($item, $field) {
        $value = $this->getValue($item, $field);
        return "<span>{$value}</span>";
    }

    protected function renderNumberField($item, $field) {
        $value = $this->getValue($item, $field);
        return number_format($value, 0, ".", ".");
    }

    public function renderButtonAction($actions = []) {
        if (empty($actions)) return '';

        $html = '<div class="button-action">';
        foreach ($actions as $btn) {
            $label = $btn['label'] ?? '';
            $class = $btn['class'] ?? 'btn';
            $onClick = $btn['onClick'] ?? null;
            $type = $btn['type'] ?? 'button';
            $id = $btn['id'] ?? '';
            $icon = $btn['icon'] ?? ''; 

            $onClickAttr = $onClick ? "onclick=\"{$onClick}\"" : "";
            $idAttr = $id ? "id=\"{$id}\"" : "";

            $iconHtml = $icon ? "<i class='{$icon}'></i> " : '';

            $html .= "<button type='{$type}' class='{$class}' {$idAttr} {$onClickAttr}>{$iconHtml} {$label}</button>";
        }
        $html .= '</div>';
        return $html;
    }

    protected function renderTableButtonAction($item, $actions = []) {
        if (empty($actions)) return '';

        $html = '<div class="table-button-actions">';
        foreach ($actions as $btn) {
            $label = $btn['label'] ?? '';
            $class = $btn['class'] ?? 'btn';
            $onClick = $btn['onClick'] ?? null;

            if ($onClick) {
                $html .= "<button class='{$class}' onclick=\"{$onClick}(" . htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') . ")\">{$label}</button>";
            } else {
                $html .= "<button class='{$class}'>{$label}</button>";
            }
        }
        $html .= '</div>';
        return $html;
    }

    public function getDescribe() {
        return $this->describe;
    }

    public function getTable() {
        return $this->table;
    }

    public function getButtonAction() {
        return $this->buttonAction;
    }

    public function getButtonTableActions() {
        return $this->buttonTableActions;
    }

    public function setDescribe($describe) {
        $this->describe = $describe;
    }

    public function setTable($table) {
        $this->table = $table;
    }

    public function setButtonAction($buttonAction) {
        $this->buttonAction = $buttonAction;
    }

    public function setButtonTableActions($buttonTableActions) {
        $this->buttonTableActions = $buttonTableActions;
    }
}