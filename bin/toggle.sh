if [ -f no-vote ]; then
  rm -rf votes/
  mkdir votes/
  rm no-vote
else
  touch no-vote
fi
