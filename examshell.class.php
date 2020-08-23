<?php

class Examshell
{
    private $userInput;
    private $score, $startTime, $currentTime, $timeLimit = 0;
    private $exercises, $help = [];
    private $on = true;

    function __construct()
    {
        $this->parse_examshell_json();
        $this->startTime = time();
        $this->display_welcome_message();
        $this->userInput = fgets(STDIN);
        if ($this->userInput == "\n") {
        }
        if ($this->userInput == "time\n") {
            $this->currentTime = time();
            echo strftime("%H:%M:%S", 14400 - ($this->currentTime - $this->startTime));
        }
    }

    // function __destruct()
    // {
    //     $this->currentTime = time();
    //     echo $this->currentTime - $this->startTime . PHP_EOL;
    //     echo strftime("%H:%M:%S", $this->currentTime - $this->startTime) . PHP_EOL;
    //     echo strftime("%H:%M:%S", 14400 - ($this->currentTime - $this->startTime));
    // }

    private function display_welcome_message()
    {
        @system("clear");
        $logo = fread(fopen("./logo.asciiart", "r"), filesize("./logo.asciiart"));
        echo $logo . PHP_EOL;
        echo "Welcome to PHP Examshell 00 ! Please read the README.md file before continuing" . PHP_EOL . "Press enter to begin..." . PHP_EOL;
    }

    private function parse_examshell_json()
    {
        try {
            if (file_exists("./exam.json")) {
                $json = fread(fopen("./exam.json", "r"), filesize("./exam.json"));
                $arrayFromJson = json_decode($json, true);
                $this->timeLimit = (int) $arrayFromJson["timeLimit"];
                $this->exercises = $arrayFromJson["exercises"];
                $this->help = $arrayFromJson["help"];
            } else {
                throw new Exception();
            }
        } catch (\Throwable $th) {
            echo "Fatal error : mandatory exam.json file missing" . PHP_EOL . "Exiting...";
            die;
        }
        // var_dump($arrayFromJson["exercises"]);
        // foreach ($arrayFromJson as $key => $value) {
        //     var_dump($key);
        //     var_dump($value);
        // }
    }

    // private function shellDisplay()
    // {
    //     while ($this->on == true) {
    //         $this->userInput = fgets(STDIN);
    //         if ($this->userInput == "\n") {
    //             $success = $this->testResult($this->listeExercices[$this->exercice]);
    //             if ($success == true) {
    //                 $this->exercice++;
    //                 $this->createRepo($this->listeExercices[$this->exercice]);
    //                 break;
    //             }
    //         }
    //     }
    // }

}
