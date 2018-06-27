#!/bin/bash
# Script to automate the steps needed to run phpunit
# Wat doet dit script?
# 1. Kijkt of /tmp/wordpress/ bestaat, indien niet dan wordt bin/install-wp-tests.sh tests root 123 localhost latest uitgevoerd.
# 2. Stopt bestaande processen selenium server: java en Xvfb (indien gevraagd).
# 3. Start de selenium server  (indien gevraagd).
# 4. Start PHPUnit.
# 5. Stopt bestaande processen selenium server: java en Xvfb  (indien gevraagd).
#
# Op dit moment kan je enkel --testsuite, --group, --list-groups, --list-suites, --exclude-group,
# --filter en --verbose als parameter meegeven aan phpunit.
# Indien nodig dan kunnen we andere parameters toevoegen.
# zie https://phpunit.de/manual/5.7/en/textui.html#textui.clioptions voor alle optie die mogelijk zijn.

##### Constants
# Hou bij in welke map dit bestand zit
base_directory=$(dirname "$0")
# Pad naar tijdelijke WordPress setup
wordpress_tests_directory="/tmp/wordpress-tests-lib"
wordpress_core_directory="/tmp/wordpress"
# Variabelen voor argumenten
force_wp_install=0
verbose=0
phpunit_command="phpunit"
selenium=on #default op on
index=0
# kleuren en stijlen voor output
black=$(tput -Txterm setaf 0)
red=$(tput -Txterm setaf 1)
green=$(tput -Txterm setaf 2)
cyan=$(tput -Txterm setaf 6)
bold=$(tput -Txterm bold)
reset=$(tput -Txterm sgr0)

# Maak nieuwe array met naam "arguments"
declare -A arguments

# Maak een nieuwe associatieve array aan waarin alle meegestuurde argumenten
# zitten.
# We doen dit met oog op onderlinge relaties tussen argumenten
# (vb. '--group testgroep' --> testgroep is de naam van de group die we
# meesturen)
# Shell ziet elk argument als een apart argument ('--group', 'testgroep'). Om
# dus te weten dat 'testgroep' bij '--group' hoort, moeten we weten welke key
# '--group' heeft, om zo de value van de volgende waarde in de array
# ('testgroep') op te kunnen halen en waar nodig in te vullen.
# Aangezien de standaard manier om argumenten op te halen geen array weergeeft,
# en deze dus ook niet over een key beschikken, moeten we deze functionaliteit
# zelf voorzien.

# Overloop alle meegestuurde argumenten
# $@ = alle meegestuurde argumenten
for i in "$@"
do
    # Vul "arguments"-array om associatieve array te bekomen
    # $i = argument
    arguments[$index]=$i
    # index van array optellen
    index=$((index+1))
done

# Functie om argumenten te controleren
check_arguments()
{
    # Overloop de 'arguments'-array
    for j in "${!arguments[@]}";
    do
        case "${arguments[$j]}"
        in
            # Controleer of de huidige value gelijk is aan een variant van
            # "group", "testsuite", "filter" of "exclude-group"
            -e|--exclude-group|-f|--filter|-g|--group|-t|--testsuite)
                # Indien we de korte naam hebben gebruikt maken we die terug lang
                case "${arguments[$j]}"
                in
                    -e)
                        phpunit_arg=--exclude-group
                        ;;
                    -f)
                        phpunit_arg=--filter
                        ;;
                    -g)
                        phpunit_arg=--group
                        ;;
                    -t)
                        phpunit_arg=--testsuite
                        ;;
                    *)
                        phpunit_arg="${arguments[$j]}"
                        ;;
                esac
                phpunit_command="$phpunit_command $phpunit_arg ${arguments[$((j+1))]}"
                ;;
            # Controleer of de huidige value gelijk is aan een variant van "help"
            -h|--help)
                # Toon help in Terminal
                show_help >&2
                exit 1
                ;;
            # Controleer of de huidige value gelijk is aan een variant van "list-group|list-suites|debug|no-coverage"
            --list-groups|--list-suites|--debug|--no-coverage)
                phpunit_command="$phpunit_command ${arguments[$j]}"
                ;;
            # Controleer of de huidige value gelijk is aan een variant van "selenium"
            -s|--selenium)
                # Bijhouden welke status Selenium moet krijgen
                selenium=${arguments[$((j+1))]}
                ;;
            # Controleer of we tmp wordpress geforceerd moeten installeren
            --force-wp-install)
                force_wp_install=1
                ;;
            # Controleer of de huidige value gelijk is aan een variant van "verbose"
            -v|--verbose)
                # Bijhouden dat verbose actief zal zijn
                verbose=1
                phpunit_command="$phpunit_command --verbose"
                ;;
        esac
    done

    verbose_output "${cyan}Using arguments: ${arguments[@]} ${reset}"
}

# Functie om info over gebruik weer te geven
show_help() {
cat << EOF
Usage: ${0##*/} [options]
Run tests with PHPUnit. Execute install-wp-tests.sh when needed. Start/stop selenium server when needed.
    
    --debug             Display debugging information during test execution.
    -e|--exclude-group  Exclude tests from the specified group(s).
    -f|--filter         Filter which tests to run.
    --force-wp-install  Force temporary WP install.
    -g|--group          Only runs tests from the specified group(s).
                        For groups use 'acceptance,login' (comma and no space).
    -h|--help           Display this help and exit.
    --list-groups       List available test groups.
    --list-suites       List available test suites.
    --no-coverage       Ignore code coverage configuration.
    -s|--selenium       Start selenium server: 'on' (default) or 'off'.
    -t|--testsuite      Filter which testsuite to run.
    -v|--verbose        Output more verbose information.
EOF
}

##### Functions
# Functie om WordPress bestanden voor tijdelijke setup te installeren
install_wp()
{
    verbose_output "${bold}Function install_wp started${reset}"
    verbose_output "${cyan}Looking for temporary WordPress folders...${reset}"

    # Kijk of een tijdelijke WordPress setup bestaat
    if [[ ! -d "$wordpress_tests_directory" || ! -d "$wordpress_core_directory" || $force_wp_install -eq 1 ]];
    then
        if [ $force_wp_install -eq 1 ];
        then
            verbose_output "${cyan}Force install temporary Wordpress install.${reset}"
            if [ -d $wordpress_core_directory ];
            then
                verbose_output "${cyan}Removing $wordpress_core_directory.${reset}"
                rm -rf $wordpress_core_directory
            fi
            if [ -d $wordpress_tests_directory ];
            then
                verbose_output "${cyan}Removing $wordpress_tests_directory.${reset}"
                rm -rf $wordpress_tests_directory
            fi
        else
            # Nee: maak een nieuwe tijdelijke setup aan
            verbose_output "${cyan}Folder $wordpress_tests_directory or $wordpress_core_directory does not exist yet.${reset}"
        fi

        # Pad naar script dat de tijdelijke setup zal aanmaken
        path_to_script="$base_directory/install-wp-tests.sh"

        verbose_output "${cyan}Looking for script to install WordPress...${reset}"
        # Controleer of het script bestaat
        if [ -f "$path_to_script" ]
        then
            # Ja: voer het script uit

            verbose_output "${green}Script $path_to_script found. Attempting to create a new temporary WordPress installation for testing...${reset}"

            bash $path_to_script tests root 123 localhost latest
            # Controleer of uitvoeren van script gelukt is
            if [ $? -eq 0 ];
            then
                verbose_output "${green}Successfully executed $path_to_script. A new temporary WordPress installation for testing has been created.${reset}"
            else
                verbose_output "${red}Failed to execute script $path_to_script. See explanation above.${reset}"
            fi
        else
            # Nee: doe niks
            verbose_output "${red}Script $path_to_script not found.${reset}"
        fi
    else
        # Ja: doe niks
        verbose_output "${cyan}Folder $wordpress_tests_directory and $wordpress_core_directory already exists. Moving on...${reset}"
    
    fi

    verbose_output "${bold}Function install_wp ended${reset}"
}

# Functie om processen gerelateerd aan Selenium Server stop te zetten
# Processen overgenomen uit volgend script:
# https://github.com/giorgiosironi/phpunit-selenium/blob/master/.ci/start.sh
stop_processes()
{

    verbose_output "${bold}Function stop_processes started${reset}"
    verbose_output "${cyan}Killing Selenium Server related processes...${reset}"

    # Stop java-proces (gerelateerd aan Selenium Server)
    ERROR=$(sudo killall -9 java 2>&1 >/dev/null)
    # Controleer of proces succesvol stopgezet werd
    if [ $? -eq 0 ];
    then
        verbose_output "${green}Successfully stopped java.${reset}"
    else
        verbose_output "${red}Failed to stop java. ($ERROR).${reset}"
    fi

    # Stop xvfb-proces (gerelateerd aan Selenium Server)
    ERROR=$(sudo killall -9 Xvfb 2>&1 >/dev/null)
    # Controleer of proces succesvol stopgezet werd
    if [ $? -eq 0 ];
    then
        verbose_output "${green}Successfully stopped xvfb.${reset}"
    else
        verbose_output "${red}Failed to stop xvfb. ($ERROR).${reset}"
    fi

    # Verwijder .x99-lock-bestand (gerelateerd aan Selenium Server)
    ERROR=$(sudo rm -f /tmp/.X99-lock 2>&1 >/dev/null)
    # Controleer of bestand succesvol verwijderd werd
    if [ $? -eq 0 ];
    then
        verbose_output "${green}Successfully removed .X99-lock.${reset}"
    else
        verbose_output "${red}Failed to remove .X99-lock. ($ERROR).${reset}"
    fi

    # Bekijk alle momenteel lopende processen
    # ps -aux

    verbose_output "${bold}Function stop_processes ended${reset}"

}

# Functie om Selenium Server te starten
start_selenium_server()
{

    verbose_output "${bold}Function start_selenium_server started${reset}"
    verbose_output "${cyan}Attempting to start Selenium Server...${reset}"

    # Start Selenium Server
    # > /dev/null 2>&1 zorgt ervoor dat de output in een niet-bestaande file
    # wordt geschreven en dus niet geprint wordt
    # & zorgt ervoor dat het commando in de achtergrond gedraaid wordt
    xvfb-run --server-args='-screen 0, 1920x1200x24' java -jar /usr/local/bin/selenium-server-standalone-3.4.0.jar > /dev/null 2>&1 &
    # Controleer of Selenium Server succesvol gestart werd
    if [ $? -eq 0 ];
    then
        verbose_output "${green}Successfully started Selenium Server.${reset}"
    else
        verbose_output "${red}Failed to start Selenium Server. See explanation above.${reset}"
    fi

    verbose_output "${bold}Function start_selenium_server ended${reset}"
}

# Functie om PHPUnit uit te voeren
run_phpunit()
{

    verbose_output "${bold}Function run_phpunit started${reset}"
    verbose_output "${cyan}Attempting to execute PHPUnit tests...${reset}"

    # Navigeer naar de map waar het start-test.sh-script staat
    cd $base_directory
    # Ga één map omhoog
    cd ..

    # Voer PHPUnit testen uit
    verbose_output "${cyan}With command: ${phpunit_command}${reset}"
    $phpunit_command

    # Kijk of PHPUnit succesvol uitgevoerd werd
    if [ $? -eq 0 ];
    then
        verbose_output "${green}Successfully executed PHPUnit.${reset}"
    else
        verbose_output "${red}Failed to executed PHPUnit.${reset}"
    fi

    verbose_output "${bold}Function run_phpunit ended${reset}"
}

# Functie om info te tonen als verbose aanstaat
verbose_output()
{
    # Controleer of '--verbose' gebruikt is
    if [ $verbose -eq 1 ];
    then
        echo $@
    fi
}

# Volgorde waarin functies uitgevoerd moeten worden.
check_arguments
install_wp
# Selenium starten indien '--selenium' op 'on' staat
if [ "$selenium" == "on" ];
then
    stop_processes
    start_selenium_server
else
    verbose_output "${cyan}Start Selenium was skipped because --selenium off.${reset}"
fi
run_phpunit
# Selenium stoppen indien '--selenium' op 'on' staat
if [ "$selenium" == "on" ];
then
    stop_processes
fi
