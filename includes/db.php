<?php

class DB {
    private $menuFile = __DIR__ . '/../data/menu.json';

    public function getMenu() {
        if (!file_exists($this->menuFile)) {
            return [];
        }
        $json = file_get_contents($this->menuFile);
        return json_decode($json, true);
    }
}
