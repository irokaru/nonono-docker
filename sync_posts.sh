#!/bin/bash
echo "start sync post data..."
SCRIPT_DIR=$(cd $(dirname $0); pwd)
rsync -auzv $SCRIPT_DIR/src/nonono/storage/app/posts nonono:~/nonono-docker/src/nonono/storage/app
echo "finished"