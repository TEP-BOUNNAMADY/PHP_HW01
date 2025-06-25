<?php

class Student {
    private $data = 'data.json';
    private $students = [];

    public function __construct() {
        if (file_exists($this->data)) {
            $json = file_get_contents($this->data);
            $this->students = json_decode($json, true) ?? [];
        }
    }

    public function saveData() {
        file_put_contents($this->data, json_encode($this->students, JSON_PRETTY_PRINT));
    }

    public function addStudent($name, $email) {
        $counter = $this->students['counter'] ?? 0;
        $counter++;
        $id = $counter;

        $this->students['students'][$id] = [
            'id' => $id,
            'name' => $name,
            'email' => $email
        ];

        $this->students['counter'] = $counter;
        $this->saveData();
    }

    public function getStudents() {
        return $this->students['students'] ?? [];
    }

    public function getStudent($id) {
        return $this->students['students'][$id] ?? null;
    }

    public function editStudent($id, $name, $email) {
        if (isset($this->students['students'][$id])) {
            $this->students['students'][$id]['name'] = $name;
            $this->students['students'][$id]['email'] = $email;
            $this->saveData();
        }
    }

    public function deleteStudent($id) {
        if (isset($this->students['students'][$id])) {
            unset($this->students['students'][$id]);
            $this->saveData();
        }
    }

    public function exists($id) {
        return isset($this->students['students'][$id]);
    }
}