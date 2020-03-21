# Usage

## Setup

Typically you want to instruct your webserver to execute `index.php` (CGI or
what have you) when users browse to a given path, such as `votes/`.

Then, whoever is running the voting will want a shell with `bin/` in their
path.

To create the initial directories needed to run everything else, you should
first run `init.sh`. Also note, you *must* run `init.sh` and all other commands
from the root of this repository, as `index.php` assumes the relative path
`votes/` exists.

Positions and candidates are declared by creating text files in `positions/`.
There is one text file per position, with the filename being the position's
name, and the contents representing candidate names (one per line).

In almost all cases throughout the source spaces are not handled correctly
for identifiers (such as positions and candidates); I recommend sticking
to all lowercase identifiers with no spaces.

## Running a vote

Voting can be turned on (for people to cast votes) and off (to allow you to
tally the votes) with `toggle.sh`.

Votes can be tallied with `count.sh` which gives the percentage of votes of
approval for each candidate, not just the winner, to allow for things like a
candidate dropping out or accepting another position.
