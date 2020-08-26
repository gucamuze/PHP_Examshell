<?php

class Exercise
{
    public $name, $type, $instructions, $shellInstructions;
    public $expectedOutput = "";
    public $argv = false;

    function __construct($exerciseName, $exercisePath, $subjectLanguage)
    {
        $this->name = $exerciseName;

        if (strpos($exerciseName, "hc_") !== false) {
            $this->type =  "function";
        } else $this->type = "program";

        $this->shellInstructions = "New excercise : " . $exerciseName . " !\nDirectory ./rendu/" . $exerciseName . " succesfuly created\n";

        $subjectPath = $exercisePath . "/subject." . $subjectLanguage . ".txt";
        $this->instructions = fread(fopen($subjectPath, 'r'), filesize($subjectPath));

        $expectedOutputPath = $exercisePath . "/expectedOutput.txt";
        $this->expectedOutput = fread(fopen($expectedOutputPath, 'r'), filesize($expectedOutputPath));

        if (file_exists($exercisePath . "/args")) {
            $args = fread(fopen($exercisePath . "/args", 'r'), filesize($exercisePath . "/args"));
            $argsArray = explode("\n", $args);
            // print_r($argsArray);
            $this->argv = $argsArray;
        }
    }
}

class JsonGenerator
{
    private $EncodeThisMofo = [];
    private $ExercisesList = [];
    private $helpSection = [];
    private $ExercisesDirectoryPath = "./.jsongenerator/Level_0";
    private $FinalJsonDirectoryPath = "./.assets/exams/exam_Level_0";
    private $timeLimit = 14400;

    function __construct($subjectLanguage)
    {
        $tempExercisesList = array_values(array_diff(scandir($this->ExercisesDirectoryPath), [".", ".."]));
        shuffle($tempExercisesList);
        $this->build_help();
        $this->build_exercises($tempExercisesList, $subjectLanguage);
        $this->build_final_json();
    }

    private function build_final_json()
    {
        $finalJson = array_merge(["timeLimit" => $this->timeLimit], ["exercises" => $this->ExercisesList], $this->helpSection);
        $this->EncodeThisMofo = json_encode($finalJson, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($this->FinalJsonDirectoryPath . "/exam.json", $this->EncodeThisMofo);
    }

    private function build_exercises($tempExercisesList, $subjectLanguage)
    {
        foreach ($tempExercisesList as $value) {
            $exercisePath = $this->ExercisesDirectoryPath . "/" . $value;
            $exercise = new Exercise($value, $exercisePath, $subjectLanguage);
            array_push($this->ExercisesList, $exercise);
        }
    }

    private function build_help()
    {
        $help = ["/help" => "Displays this (duh)"];
        $time = ["/time" => "Displays remaining time"];
        $current = ["/current" => "Displays the number of the current exercise"];
        $score = ["/score" => "Displays the current score"];
        $exit = ["/exit" => "Ends and exits the current exam"];

        $this->helpSection = ["help" => array_merge($help, $time, $current, $score, $exit)];
    }
}

$subjectLanguage = "en";

if (isset($argv[1])) {
    // echo "coucou";
    if ($argv[1] == "fr") {
        // echo "salut";
        $subjectLanguage = "fr";
    }
}

echo "lang = " . $subjectLanguage;
$jsonGenerator = new JsonGenerator($subjectLanguage);
