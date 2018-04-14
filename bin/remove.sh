#!/bin/bash

if [ $# -ne 2 ]; then
  echo "Usage: $0 position candidate"
  exit 1
fi

position=$1
candidate=$2

sed -i /$candidate/d positions/$position

for absentee in absentees/*; do
  absentee=${absentee#absentees/}
  if [ -f absentees/$absentee/$position ]; then
    sed -i /$candidate/d absentees/$absentee/$position
  fi
done
