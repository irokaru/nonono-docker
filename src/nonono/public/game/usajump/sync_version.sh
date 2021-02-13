#!/bin/bash
echo "start sync post data..."
SCRIPT_DIR=$(cd $(dirname $0); pwd)
echo "${SCRIPT_DIR}/update"
rsync -auzv "${SCRIPT_DIR}/update" nonono:~/nonono-docker/src/nonono/public/game/usajump
