#!/bin/bash

for position in positions/*; do
  position=${position#positions/}
  echo "votes for $position:"
  find votes/ -type f -name "$position" -execdir cat '{}' \; \
    | awk '{
             count++;
             votes[$1]++;
           }
	   END {
             for (v in votes)
               printf "%.2f%% %s\n", votes[v] / count * 100, v;
           }' \
    | sort -rn
done
