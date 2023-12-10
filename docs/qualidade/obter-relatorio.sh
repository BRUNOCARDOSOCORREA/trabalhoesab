#!/bin/bash
arq="$(date +%d%m%Y)".txt
phploc --log-xml $arq ../../../www 
