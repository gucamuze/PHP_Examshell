<?php

class Exercise
{
    public $name, $type, $instructions, $shellInstructions;

    function __construct($exerciseName, $exercisePath)
    {
        $this->name = $exerciseName;

        if (strpos($exerciseName, "ft_") !== false) {
            $this->type =  "function";
        } else $this->type = "program";

        $this->shellInstructions = "New excercise : " . $exerciseName . " !\nDirectory " . $exerciseName . " succesfuly created\n";

        $subjectPath = $exercisePath . "/subject.en.txt";
        $this->instructions = fread(fopen($subjectPath, 'r'), filesize($subjectPath));
    }
}

class JsonGenerator
{
    private $EncodeThisMofo = [];
    private $ExercisesList = [];
    private $helpSection = [];
    private $ExercisesDirectoryPath = "./Level_0";
    private $timeLimit = 14400;

    function __construct()
    {
        $tempExercisesList = array_values(array_diff(scandir($this->ExercisesDirectoryPath), [".", ".."]));
        $this->build_help();
        $this->build_exercises($tempExercisesList);
        $this->build_final_json();
    }

    private function build_final_json()
    {
        $finalJson = array_merge(["timeLimit" => $this->timeLimit], ["exercises" => $this->ExercisesList], $this->helpSection);
        $this->EncodeThisMofo = json_encode($finalJson, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents("./exam.json", $this->EncodeThisMofo);
    }
    
    private function build_exercises($tempExercisesList)
    {
        foreach ($tempExercisesList as $value) {
            $exercisePath = $this->ExercisesDirectoryPath . "/" . $value;
            $exercise = new Exercise($value, $exercisePath);
            array_push($this->ExercisesList, $exercise);
        }
    }

    private function build_help()
    {
        $help = ["/help" => "Displays this (duh)"];
        $time = ["/time" => "Displays remaining time"];
        $current = ["/current" => "Displays the number of the current exercise"];
        $score = ["/score" => "Displays the current score"];

        $this->helpSection = ["help" => array_merge($help, $time, $current, $score)];
    }
}

$jsonGenerator = new JsonGenerator();
