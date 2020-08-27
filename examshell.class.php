<?php

class Examshell
{
    private $examJsonPath = "./.assets/exams/exam_Level_";
    private $mainsDirectoryPath = "./.mains/Level_";
    private $assignmentsDirectoryPath = "./rendu/";
    private $userInput, $startTime, $timeLimit, $pointsPerExercise;
    private $currentExercise = 0, $score = 0;
    private $exercises, $help = [];
    private $on = true;
    private $examLevel = "0";
    private $subjectLanguage = "en";

    function __construct()
    {
        $this->startup();

        $this->parse_examshell_json();

        $this->display_welcome_message();

        $this->userInput = fgets(STDIN);
        if ($this->userInput == "\n") {
            $this->startTime = time();
            $this->start_exercise();
        }
    }

    private function startup()
    {
        // Check for existing /rendu folder //
        if (!is_dir($this->assignmentsDirectoryPath)) {
            @system("mkdir " . $this->assignmentsDirectoryPath);
        } else {
            @system("clear");
            echo "Folder ./rendu already exists : do you want to delete its contents ? [y/n]" . PHP_EOL;
            $delete = trim(fgets(STDIN));
            if ($delete == "y") {
                @system("rm -rf ./rendu && mkdir " . $this->assignmentsDirectoryPath);
                echo "Folder ./rendu contents succesfully deleted !" . PHP_EOL . PHP_EOL;
                echo "Press any key to continue..." . PHP_EOL;
                fgets(STDIN);
            }
        }

        echo "Please select a level for subjects [0, 1]" . PHP_EOL;
        $level = trim(fgets(STDIN));
        if ($level == '0' || $level == '1') {
            $this->examLevel = $level;
        }
        $this->examJsonPath .= $this->examLevel . "/";
        $this->mainsDirectoryPath .= $this->examLevel . "/";

        echo "Exam level set on [" . $this->examLevel . "]" . PHP_EOL . PHP_EOL;

        // New exam.json generation //
        if (file_exists($this->examJsonPath . "exam.json")) {
            echo "Generate a new examshell ? [y/n]" . PHP_EOL;
            $new = trim(fgets(STDIN));
        } else {
            echo "No exam.json found for this level, generating a new one..." . PHP_EOL;
            $new = "y";
        }
        if ($new == "y") {
            // echo "Press any key to continue..." . PHP_EOL;
            // fgets(STDIN);

            echo "Please select a language for subjects : type [fr/en] (defaults to [en])" . PHP_EOL;
            $lang = trim(fgets(STDIN));
            if ($lang == 'fr') {
                $this->subjectLanguage = "fr";
            }
            echo "Subjects language set on [" . $this->subjectLanguage . "]" . PHP_EOL;
            echo "Press any key to continue..." . PHP_EOL;
            fgets(STDIN);

            echo $this->examJsonPath . PHP_EOL;
            echo $this->mainsDirectoryPath . PHP_EOL;

            @system("php ./.jsongenerator/jsongenerator.php " . $this->subjectLanguage . " " . $this->examLevel);
        }
        // Defaults to level 0
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

        if (!is_dir($this->assignmentsDirectoryPath . $currentExerciseArray["name"])) {
            @system("mkdir " . $this->assignmentsDirectoryPath . $currentExerciseName);
            file_put_contents($this->assignmentsDirectoryPath . $currentExerciseName . "/" . $currentExerciseName . ".txt", $currentExerciseInstructions);
        }

        echo $currentExerciseShellInstructions;
    }

    private function compile_and_check()
    {
        $currentExerciseArray = $this->exercises[$this->currentExercise];
        $currentExerciseName = $currentExerciseArray["name"];
        $currentExerciseType = $currentExerciseArray["type"];
        $currentExerciseExpectedOutput = $currentExerciseArray["expectedOutput"];
        $currentExerciseArgs = $currentExerciseArray["argv"];
        $result = null;

        try {
            if (file_exists($this->assignmentsDirectoryPath . $currentExerciseName . "/" . $currentExerciseName . ".c")) {
                if ($currentExerciseType == "program") {
                    @system("gcc " . $this->assignmentsDirectoryPath . $currentExerciseName . "/" . $currentExerciseName . ".c 2>./errorlog.txt");
                } else {
                    @system("gcc " . $this->assignmentsDirectoryPath . $currentExerciseName . "/" . $currentExerciseName . ".c " . $this->mainsDirectoryPath . $currentExerciseName . "_main.c 2>./errorlog.txt");
                }
                if (file_exists("./a.out")) {
                    if ($currentExerciseArgs == null) {
                        @system("./a.out > result.yo");
                    } else {
                        $binaryOutputString = "";
                        foreach ($currentExerciseArgs as $key => $value) {
                            $binaryOutputString .= "./a.out " . $value . " >> result.yo";
                            if ($key < (count($currentExerciseArgs) - 1)) {
                                $binaryOutputString .= " && ";
                            }
                        }
                        @system($binaryOutputString);
                    }
                    @system("rm ./a.out");
                    $result = fread(fopen("./result.yo", "r"), 1000000);
                    @system("rm ./result.yo");
                } else {
                    throw new Exception("compilation error");
                }
            } else {
                throw new Exception(".c File not found in requested directory !");
            }
        } catch (\Throwable $th) {
            echo "\033[0;31mERROR : " . $th->getMessage() . "\033[0m" . PHP_EOL;
        }
        echo "result = "  . $result . PHP_EOL;
        echo "expected = " . $currentExerciseExpectedOutput . PHP_EOL;

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
            $success = $this->compile_and_check();
            if ($success == true) {
                $this->currentExercise++;
                $this->score += $this->pointsPerExercise;
                echo "\033[0;32m>>>>> SUCCESS !" . PHP_EOL . "Your new score is " . round($this->score, 1) . "/100 !\033[0m" . PHP_EOL . PHP_EOL;
                if ($this->currentExercise >= count($this->exercises)) {
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
