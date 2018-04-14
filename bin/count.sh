#!/bin/bash

for absentee in absentees/*; do
  absentee=${absentee#absentees/}
  for position in positions/*; do
    position=${position#positions/}
    if [ -f absentees/$absentee/$position ]; then
      mkdir -p votes/$absentee
      head -1 absentees/$absentee/$position >votes/$absentee/$position
    fi
  done
done

for position in positions/*; do
  position=${position#positions/}
  echo votes for $position:
  find votes/ -name $position -execdir cat '{}' \; \
    | awk '{
             count++;
             votes[$1]++;
           }
	   END {
             for (v in votes)
               printf "%.2f%% %s\n", votes[v] / count * 100, v;
           }' \
    | sort -n
done
