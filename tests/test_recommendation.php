<?php
// Mock request to api/recommendation.php
// We need to set up the environment since we can't easily do a real HTTP request from CLI without a server running

// Mock Input
$mockInput = [
    'time' => 'lunch',
    'history' => [1] // Assuming ID 1 exists
];

// Write mock input to a temporary file or override file_get_contents streamWrapper if possible.
// Simpler approach: Modify the recommendation.php temporarily or include it and mock stream wrapper, 
// OR just copy the logic here for testing.
// But we want to test the actual file.

// Let's use a workaround:
// We will write a wrapper script that uses output buffering and includes the target file.
// We also need to mock php://input

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
            $this->string = json_encode([
                'time' => 'lunch',
                'history' => [1]
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

// Ensure includes work by setting cwd
chdir(__DIR__ . '/../api');

require 'recommendation.php';
?>