<?php

class Examshell
{
    private $examJsonPath = "./.assets/exams/exam_template/";
    private $userInput, $startTime, $timeLimit, $pointsPerExercise;
    private $currentExercise = 0, $score = 0;
    private $exercises, $help = [];
    private $on = true;

    function __construct()
    {
        $this->parse_examshell_json();
        $this->display_welcome_message();
        $this->userInput = fgets(STDIN);
        if ($this->userInput == "\n") {
            $this->startTime = time();
            $this->start_exercise();
        }
    }

    private function start_exercise()
    {
        $this->create_repo();
        $this->shell_display();
    }

    private function create_repo()
    {
        $currentExerciseArray = $this->exercises[$this->currentExercise];
        $currentExerciseName = $currentExerciseArray["name"];
        $currentExerciseInstructions = $currentExerciseArray["instructions"];
        $currentExerciseShellInstructions = $currentExerciseArray["shellInstructions"] . "Press Enter when done, or type /help to display a list of available commands" . PHP_EOL;

        if (!is_dir("./" . $currentExerciseArray["name"])) {
            @system("mkdir " . $currentExerciseArray["name"]);
            @system("touch " . $currentExerciseName . "/" . $currentExerciseName . ".txt");
            file_put_contents("./" . $currentExerciseName . "/" . $currentExerciseName . ".txt", $currentExerciseInstructions);
        }

        echo $currentExerciseShellInstructions;
    }

    private function compile_and_check()
    {
        $currentExerciseArray = $this->exercises[$this->currentExercise];
        $currentExerciseName = $currentExerciseArray["name"];
        $currentExerciseType = $currentExerciseArray["type"];
        $currentExerciseExpectedOutput = $currentExerciseArray["expectedOutput"];
        $result = null;

        try {
            if (file_exists("./" . $currentExerciseName . "/" . $currentExerciseName . ".c")) {
                if ($currentExerciseType == "program") {
                    @system("gcc ./" . $currentExerciseName . "/" . $currentExerciseName . ".c 2>./errorlog.txt");
                } else {
                    @system("gcc ./" . $currentExerciseName . "/" . $currentExerciseName . ".c ./.mains/" . $currentExerciseName . "_main.c 2>./errorlog.txt");
                }
                if (file_exists("./a.out")) {
                    echo "a/out" . PHP_EOL;
                    @system("./a.out > result.yo");
                    @system("rm ./a.out");
                    $result = fread(fopen("./result.yo", "r"), filesize("./result.yo"));
                    @system("rm result.yo");
                } else {
                    throw new Exception("compilation error");
                }
            } else {
                throw new Exception(".c File not found in requested directory !");
            }
        } catch (\Throwable $th) {
            // echo "\033[0;31mERROR : .c File not found in directory !\033[0m" . PHP_EOL;
            echo "\033[0;31mERROR : " . $th->getMessage() . "\033[0m" . PHP_EOL;
        }

        if ($result == $currentExerciseExpectedOutput) {
            if (file_exists("./errorlog.txt")) {
                @system("rm ./errorlog.txt");
            }
            return true;
        } else {
            echo "\033[0;31mERROR : expected output not matching results !\033[0m" . PHP_EOL;
            echo "\033[0;31m>>>>> FAILURE :x Try again !\033[0m" . PHP_EOL . "Press enter when you're done, or enter /help to see available commands" . PHP_EOL;
        }
    }

    private function shell_display()
    {
        while ($this->on == true) {
            $this->userInput = trim(fgets(STDIN));
            $this->check_user_input();
        }
    }

    private function check_user_input()
    {
        if ($this->userInput == "") {
            // $success = false;
            $success = $this->compile_and_check();
            // echo "success value = " .  $success . PHP_EOL;
            if ($success == true) {
                $this->currentExercise++;
                $this->score += $this->pointsPerExercise;
                echo "\033[0;32m>>>>> SUCCESS !" . PHP_EOL . "Your new score is " . round($this->score, 1) . "/100 !\033[0m" . PHP_EOL . PHP_EOL;
                if ($this->currentExercise > count($this->exercises)) {
                    echo "\033[0;32mCongratulations, you finished this Examshell \\o/\033[0m" . PHP_EOL;
                    die;
                } else {
                    $this->create_repo($this->exercises[$this->currentExercise]);
                }
            }
        } else if ($this->userInput == "/help") {
            foreach ($this->help as $key => $value) {
                echo "Command : " . $key . " -> " . $value . PHP_EOL;
            }
        } else if ($this->userInput == "/time") {
            echo "Remaining time : " .  strftime("%H:%M:%S", $this->timeLimit - (time() - $this->startTime)) . PHP_EOL;
        } else if ($this->userInput == "/current") {
            echo "You are currently on exercise number " . $this->currentExercise . " : " . $this->exercises[$this->currentExercise]["name"] . PHP_EOL;
        } else if ($this->userInput == "/score") {
            echo "You current score is " . round($this->score, 1) . " / 100" . PHP_EOL;
        } else if ($this->userInput == "/exit") {
            
            echo "You final score is " . round($this->score, 1) . " / 100 !" . PHP_EOL . "Don't hesitate to send feedback and/or bugreports by mail at gcamuzea42@gmail.com !" . PHP_EOL;
            die;
        }
    }

    private function parse_examshell_json()
    {
        try {
            if (file_exists($this->examJsonPath . "exam.json")) {
                $json = fread(fopen($this->examJsonPath . "exam.json", "r"), filesize($this->examJsonPath . "exam.json"));
                $arrayFromJson = json_decode($json, true);
                $this->timeLimit = (int) $arrayFromJson["timeLimit"];
                $this->exercises = $arrayFromJson["exercises"];
                $this->help = $arrayFromJson["help"];
                $this->pointsPerExercise = 100 / count($this->exercises);
            } else {
                throw new Exception();
            }
        } catch (\Throwable $th) {
            echo "Fatal error : mandatory exam.json file missing" . PHP_EOL . "Exiting...";
            die;
        }
    }

    private function display_welcome_message()
    {
        @system("clear");
        $logo = fread(fopen("./.assets/logo.asciiart", "r"), filesize("./.assets/logo.asciiart"));
        echo $logo . PHP_EOL;
        echo "Welcome to PHP Examshell 00 ! Please read the README.md file before continuing" . PHP_EOL . "Press enter to begin..." . PHP_EOL;
    }
}
