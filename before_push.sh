#!/bin/bash

find . -path "*.php" -exec echo -e "\n\033[1;30m"\{\}"\033[0m" \; -exec grep "mypassword = \"" \{\} \;

