#!/bin/bash
# Script to automate distribution of start-test.sh to all plugins + /var/www/klasse/bin
# Met dit script worden alle start-test.sh in de site vervangen door de versie die in deze folder zit

script_directory=$(dirname "$0")
root_directory="/var/www/klasse/"
plugin_directory="/var/www/klasse/httpdocs/wp/wp-content/plugins/"
script="start-test.sh"
# voeg de namen van de plugins tot die testen hebben
plugins=(
    'klasse-abonnementen'
    'klasse-cta'
    'klasse-end-date'
    'klasse-export'
    'klasse-landingspagina'
    'klasse-lerarenkaart'
    'klasse-opmerkingen'
    'klasse-profielen'
    'klasse-reeks'
    'klasse-related-content'
    'klasse-voordelen'
)

# remove script + copy script to root
rm -f "${root_directory}/bin/${script}"
cp "${script_directory}/${script}" "${root_directory}/bin/${script}"

# remove script + copy script plugins
for i in "${plugins[@]}"
do
    rm -f "${plugin_directory}/${i}/bin/${script}"
    cp "${script_directory}/${script}" "${plugin_directory}/${i}/bin/${script}"
done
