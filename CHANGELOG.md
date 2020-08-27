# CHANGELOG

**Beta v1.1**
- Start changelog
- Changed jsongenerator use : now need to be launched from root ("php ./.jsongenerator/jsongenerator")
    - Also added an option to change subject languages using argv (IE: "php ./.jsongenerator/jsongenerator fr")
- Bug fix on countdown, was expecting a function instead of a program

**Beta v1.2**
- Improved startup to include option to create a new json for the examshell, or not
    - Also includes a way to select Level (0 or 1 for now), and language (fr / en)
    - Changed jsongenerator.php accordingly, now can receive two args (IE: "php .jsongenerator fr 1")
- Changed /.jsongenerator .gitignore not to include Level_0 anymore, as it is now needed for the startup of the examshell
- Bugfix on print_numbers, expecting a "\n" while this was not asked 
*known issues*
- While level 1 exam.json is generated, the expected outputs/args haven't been created yet, no it won't work.