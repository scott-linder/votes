<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CCoWMU Votes</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <style>
    body {
      max-width: 30em;
      margin: 0 auto;
    }
    h1, p {
      text-align: center;
    }
    input[type="text"],
    input[type="password"]  {
      display: block;
    }
    input[type="submit"] {
      width: 100%;
      margin: 1em 0;
    }
  </style>
</head>
<body>
<?php
function clean_username($s) {
  return preg_replace("/[^a-zA-Z0-9]/", "", $s);
}
function not_dot($s) {
  return !($s === "." or $s === "..");
}
if (file_exists('no-vote')):
  echo "<h1>Hold Up</h1>";
  echo "<p>The vote has either not begin, is paused, or has ended.</p>";
elseif ($_SERVER['REQUEST_METHOD'] === "POST"):
  is_string($_POST['username']) and is_string($_POST['password']) or die("invalid POST parameters");
  $ldap = ldap_connect("localhost") or die("failed to connect to ldap");
  ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3) or die("failed to set protocol");
  $user = clean_username($_POST['username']);
  $binddn = "uid=$user,cn=members,dc=yakko,dc=cs,dc=wmich,dc=edu";
  $pass = $_POST['password'];
  if ($user and $pass and $bind = ldap_bind($ldap, $binddn, $pass)) {
    $votes_dir = 'votes/' . $user;
    echo "<h1>Thank you for your vote, $user!</h1>";
    echo "<p>The candidates you approved for each position are listed below.</p>";
    echo "<dl>";
    file_exists($votes_dir) or mkdir($votes_dir, 0777, true);
    foreach (array_filter(scandir("positions"), 'not_dot') as $position) {
      echo "<dt>$position</dt>";
      $position_file = $votes_dir . '/' . $position;
      $possible = array_map('trim', file('positions/' . $position));
      $votes = '';
      if (array_key_exists($position, $_POST) and is_array($_POST[$position])) {
        foreach (array_map('clean_username', $_POST[$position]) as $vote) {
          if (in_array($vote, $possible, true)) {
            echo "<dd>$vote</dd>";
            $votes .= $vote . "\n";
          }
        }
      } else {
        echo "<dd><em>no candidates approved</em></dd>";
      }
      file_put_contents($position_file, $votes);
    }
    echo "</dl>";
    echo '<p>If you made a mistake on your ballot <a href=".">you may fill out a fresh one</a> to replace it.</p>';
  } else {
    echo "<p>Login failed; go back in your browser history and try again.</p>";
  }
else: ?>
<h1>CCoWMU Votes</h1>
<p>CCoWMU elections use <a
href="https://en.wikipedia.org/wiki/Approval_voting">Approval Voting</a>.</p>
<p>For each position below, please select ("approve")
<em><strong>all</strong></em> candidates which you think are fit for the
position. The winner for each position will be the candidate with the most
votes of approval.</p>
<form method="POST">
<?php foreach (array_filter(scandir("positions"), 'not_dot') as $position): ?>
  <fieldset>
    <legend><?php echo $position ?></legend>
  <?php foreach (array_map('trim', file('positions/' . $position)) as $candidate): ?>
    <div>
    <input type="checkbox" name="<?php echo $position ?>[]" value="<?php echo $candidate ?>">
      <label for="<?php echo $candidate ?>"><?php echo $candidate ?></label>
    </div>
  <?php endforeach; ?>
  </fieldset>
<?php endforeach; ?>
<div>
  <label for="username">username</label>
  <input type="text" name="username" class="form-control">
</div>
<div>
  <label for="password">password</label>
  <input type="password" name="password" class="form-control">
</div>
<input type="submit" value="cast vote" class="btn btn-primary">
</form>
<?php endif; ?>
</body>
</html>
