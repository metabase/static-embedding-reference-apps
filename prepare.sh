#!/bin/sh

set -eu

METABASE_JAR="./metabase/metabase.jar"
METABASE_VERSION=v0.28.3
METABASE_URL="http://downloads.metabase.com/$METABASE_VERSION/metabase.jar"

echo -n "-----> Downloading Metabase... "
curl -s --retry 3 -o $METABASE_JAR -L $METABASE_URL
echo "done"
