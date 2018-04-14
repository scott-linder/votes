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
tally the votes) with `toggle.sh` (this also deletes `votes/` when enabling
voting, so be careful).

Votes can be tallied with `count.sh`, which also applies any absentee votes
found in `absentees/`. Each absentee voter should have a subdirectory in
`absentees/` named after them, and within it should have a text file for
each position being voted on, containing an ordered list of their vote.

For example, if users `bob` and `sue` are voting, and `joe` and `sally` are
running for `president`, the structure would look like this:

    absentess/
        bob/
            president
        sue/
            president

Both files named `president` would contain between zero or two lines,
representing their preferences for president; for example:

    sally
    joe

## Removing candidates

If the desire is to require some percentage of the vote for a winner to be
declared (50%, for example), and the vote does not produce such a candidate,
the `remove.sh` script can be used to remove the lowest percentage candidate.
The `remove.sh` script accepts two positional arguments: a `position` and an
`candidate`. It handles updating the files in `positions/` as well as any
`absentees/`. After this is run, you can simply `toggle.sh` back on the vote
and request everyone cast a new vote.
