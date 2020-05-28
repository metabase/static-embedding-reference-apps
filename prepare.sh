#!/bin/sh
# This script downloads the latest version of Metabase into the metabase directory, and runs the jar.
set -eu

METABASE_JAR="./metabase/metabase.jar"
METABASE_URL="https://downloads.metabase.com/latest/metabase.jar"

echo "======> Downloading Metabase... "
curl -s --retry 3 -o $METABASE_JAR -L $METABASE_URL
echo "Metabase jar downloaded into ./metabase/"
echo "Changing into metabase directory..."
cd ./metabase/
echo "======> Running 'java -jar metabase.jar'..."
java -jar metabase.jar
