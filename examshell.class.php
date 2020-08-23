<?php

class Examshell 
{
    private $userInput, $startTime, $currentTime;
    private $on = true;
    private $score = 0;
    private $timeLimit = 14400; // 14.400 seconds -> 4 hours

    function __construct()
    {
        $this->startTime = time();
        $this->display_welcome_message();
        $this->userInput = fgets(STDIN);
        if ($this->userInput == "\n") {
        }
        if ($this->userInput == "time\n") {
            $this->currentTime = time();
            echo strftime("%H:%M:%S", 14400 - ($this-> currentTime - $this->startTime));
        }
    }

    function __destruct()
    {
        $this->currentTime = time();
        echo $this-> currentTime - $this->startTime . PHP_EOL;
        echo strftime("%H:%M:%S", $this-> currentTime - $this->startTime) . PHP_EOL;
        echo strftime("%H:%M:%S", 14400 - ($this-> currentTime - $this->startTime));
        
    }

    private function display_welcome_message() {
        @system("clear");
        $logo = fread(fopen("./logo.asciiart", "r"), filesize("./logo.asciiart"));
        echo $logo . PHP_EOL;
        echo "Welcome to PHP Examshell 00 ! Please read the readme file before continuing" . PHP_EOL . "Press enter to begin..." . PHP_EOL;
    }

    private function shellDisplay()
    {
        while ($this->on == true) {
            $this->userInput = fgets(STDIN);
            if ($this->userInput == "\n") {
                $success = $this->testResult($this->listeExercices[$this->exercice]);
                if ($success == true) {
                    $this->exercice++;
                    $this->createRepo($this->listeExercices[$this->exercice]);
                    break;
                }
            }
        }
    }

}