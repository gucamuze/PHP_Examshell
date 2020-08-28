# CHANGELOG

**Beta v1.4**
- **Added Level_1**
    - Featuring 12 new exercises, and a good bump in difficuly
    - All have been tested and should work properly, but don't hesitate to report any bug
    - Not a lot more to say than yayyy finally
- Added a check on existing /traces folder
- Bugfix on fr subjects for Level0 functions, expecting "ft_" instead of "hc_"
- Changed changelog display order to latest to oldest

**Beta v1.3**
- Implementation of traces : in case of a valid compilation but when output differs, a trace will be generated in ./traces/{exercise name}
    - Compilation errors still get recorded in ./errorlog.txt, and this is intended
    - Changed gitignore accordingly to include ./traces folder
    - Also muted the result / expectedOutput directly in examshell, no longer needed thanks to the traces
- Separated startup() method in three parts as it was getting a bit bloated
- Added "How it works" section in README.md to guide new users
*known issues*
- While level 1 exam.json is generated, the expected outputs/args haven't been created yet, so it won't work.

**Beta v1.2**
- Improved startup to include option to create a new json for the examshell, or not
    - Also includes a way to select Level (0 or 1 for now), and language (fr / en)
    - Changed jsongenerator.php accordingly, now can receive two args (IE: "php .jsongenerator fr 1")
- Changed /.jsongenerator .gitignore not to include Level_0 anymore, as it is now needed for the startup of the examshell
- Bugfix on print_numbers, expecting a "\n" while this was not asked 
*known issues*
- While level 1 exam.json is generated, the expected outputs/args haven't been created yet, so it won't work.

**Beta v1.1**
- Start changelog
- Changed jsongenerator use : now need to be launched from root ("php ./.jsongenerator/jsongenerator")
    - Also added an option to change subject languages using argv (IE: "php ./.jsongenerator/jsongenerator fr")
- Bug fix on countdown, was expecting a function instead of a program
