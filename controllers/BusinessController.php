<?php
require_once __DIR__ . '/../models/Business.php';

class BusinessController {
    public function show($id) {
        return Business::findById($id);
    }
}