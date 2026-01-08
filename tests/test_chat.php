<?php
// Mock PHP input stream
stream_wrapper_unregister("php");
stream_wrapper_register("php", "MockPhpStream");

class MockPhpStream
{
    public $context;
    private $string;
    private $position;

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        if ($path == "php://input") {
            // Mock user message
            $this->string = json_encode([
                'message' => 'Apa menu andalan hari ini?',
                'history' => []
            ]);
            $this->position = 0;
            return true;
        }
        return false;
    }

    public function stream_read($count)
    {
        $ret = substr($this->string, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    public function stream_eof()
    {
        return $this->position >= strlen($this->string);
    }

    public function stream_stat()
    {
        return [];
    }
}

// Run against api/chat.php
chdir(__DIR__ . '/../api');
require 'chat.php';
?>