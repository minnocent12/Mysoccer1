<?php

// admin_dashboard.php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../includes/db_connect.php';

// Access Control: Ensure only logged-in admins can access the dashboard
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); // Correct path to login.php
    exit();
}

// Fetch tournaments from the database
$sql = "SELECT * FROM tournaments";
$result = $conn->query($sql);
$tournaments = [];
while ($row = $result->fetch_assoc()) {
    $tournaments[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MySoccer</title>
    <link rel="stylesheet" href="../css/styles.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_styles.css?v=1.0"> 
</head>
<body>
    <?php include '../includes/admin_header.php'; ?>

    <main>
    <div class="container">
        <section class="dashboard">
            <h2>Welcome to the Admin Dashboard</h2>
            <p>Manage tournaments, teams, and matches below.</p>
            
            <div class="dashboard-buttons">
                <button onclick="openTournamentModal()">Create Tournament</button>
                <button onclick="openTeamModal()">Add Team</button>
                <button onclick="openMatchModal()">Set Up Matches</button>
            </div>
            
            <div class="dashboard-content">
                <h3>Upcoming Tournaments</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Tournament Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Teams</th>
                            <th>Registered Teams</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tournaments as $tournament): ?>
                            <tr>
                                <td>
                                    <a href="javascript:void(0);" onclick="toggleDetails(<?php echo htmlspecialchars($tournament['id']); ?>)">
                                        <?php echo htmlspecialchars($tournament['name']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($tournament['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($tournament['end_date']); ?></td>
                                <td><?php echo htmlspecialchars($tournament['num_teams']); ?></td>
                                <td><?php echo htmlspecialchars($tournament['register']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="edit-btn" onclick="openEditModal(<?php echo htmlspecialchars($tournament['id']); ?>)">Edit</button>
                                        <button class="delete-btn" onclick="confirmDelete(<?php echo htmlspecialchars($tournament['id']); ?>, 'tournament')">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Details Row -->
                            <tr id="details-<?php echo htmlspecialchars($tournament['id']); ?>" class="tournament-details">
                                <td colspan="5">
                                    <!-- Fetch teams and matches for this tournament -->
                                    <?php
                                        // Fetch teams for this tournament
                                        $t_id = $tournament['id'];
                                        $teams_sql = "SELECT * FROM teams WHERE tournament_id = $t_id";
                                        $teams_result = $conn->query($teams_sql);
                                        $teams = [];
                                        while ($team = $teams_result->fetch_assoc()) {
                                            $teams[] = $team;
                                        }

                                        // Fetch matches for this tournament
                                        $matches_sql = "SELECT m.*, t1.name AS team1_name, t2.name AS team2_name 
                                                        FROM matches m
                                                        JOIN teams t1 ON m.team1_id = t1.id
                                                        JOIN teams t2 ON m.team2_id = t2.id
                                                        WHERE m.tournament_id = $t_id";
                                        $matches_result = $conn->query($matches_sql);
                                        $matches = [];
                                        while ($match = $matches_result->fetch_assoc()) {
                                            $matches[] = $match;
                                        }
                                    ?>
                                    <!-- Teams Section -->
                                    <h4>Teams</h4>
                                    <table class="teams-table">
                                        <thead>
                                            <tr>
                                                <th>Team Name</th>
                                                <th>Logo</th>
                                                <th>Coach Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($teams as $team): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($team['name']); ?></td>
                                                    <td>
                                                        <?php if (!empty($team['logo'])): ?>
                                                            <img src="../uploads/teams/<?php echo htmlspecialchars($team['logo']); ?>" alt="Team Logo" class="logo-image">
                                                        <?php else: ?>
                                                            <span>No Logo</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($team['coach_name']); ?></td>
                                                    <td>
                                                        <button class="edit-team-btn" onclick="openEditTeamModal(<?php echo htmlspecialchars($team['id']); ?>)">Edit</button>
                                                        <button class="delete-team-btn" onclick="confirmDelete(<?php echo htmlspecialchars($team['id']); ?>, 'team')">Delete</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>




                                    <!-- Matches Section -->
                                    <div class="matches-results">
                                        <div class="matches-left">
                                            <h4>Matches</h4>
                                            <table class="matches-table">
                                                <thead>
                                                    <tr>
                                                        <th>Match Date</th>
                                                        <th>Home Team</th>
                                                        <th>Away Team</th>
                                                        <th>Location</th>
                                                        <th>Time</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($matches as $match): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($match['match_date'] ?? ''); ?></td>
                                                            <td><?php echo htmlspecialchars($match['team1_name'] ?? ''); ?></td>
                                                            <td><?php echo htmlspecialchars($match['team2_name'] ?? ''); ?></td>
                                                            <td><?php echo htmlspecialchars($match['location'] ?? ''); ?></td>
                                                            <td><?php echo htmlspecialchars($match['match_time'] ?? ''); ?></td>
                                                            <td>
                                                                <button class="edit-match-btn" onclick="openEditMatchModal(<?php echo htmlspecialchars($match['id'] ?? '0'); ?>)">Edit</button>
                                                                <button class="delete-match-btn" onclick="confirmDelete(<?php echo htmlspecialchars($match['id'] ?? '0'); ?>, 'match')">Delete</button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="matches-right">
                                            <h4>Results</h4>
                                            <table class="results-table">
                                                <thead>
                                                    <tr>
                                                        <th>Home Team</th>
                                                        <th>Away Team</th>
                                                        <th>Score</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($matches as $match): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($match['team1_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($match['team2_name']); ?></td>
                                                            <td>
                                                                <?php if (!is_null($match['team1_score']) && !is_null($match['team2_score'])): ?>
                                                                    <?php echo htmlspecialchars($match['team1_score']); ?> - <?php echo htmlspecialchars($match['team2_score']); ?>
                                                                <?php else: ?>
                                                                    <span>Score not updated</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <button class="edit-match-btn" onclick="openEditScoreModal(<?php echo htmlspecialchars($match['id']); ?>)">Edit Score</button>
                                                                <button class="delete-match-btn" onclick="confirmDelete(<?php echo htmlspecialchars($match['id']); ?>, 'match')">Delete</button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                   

                                    <!-- Standings Section -->






                                    <!-- Standings Section -->
                                    <h4>Standings</h4>
                                    <table class="standings-table">
                                        <thead>
                                            <tr>
                                                <th>Position</th>
                                                <th>Team Name</th>
                                                <th>Played</th>
                                                <th>Won</th>
                                                <th>Drawn</th>
                                                <th>Lost</th>
                                                <th>GF</th>
                                                <th>GA</th>
                                                <th>GD</th>
                                                <th>Points</th>
                                                <th>Next</th> <!-- Added next column -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Initialize standings array
                                            $standings = [];

                                            // Loop through each team to calculate the necessary values
                                            foreach ($teams as $team) {
                                                $team_id = $team['id'];
                                                $team_name = $team['name'];

                                                // Initialize stats for each team
                                                $played = $won = $drawn = $lost = $gf = $ga = 0;
                                                $next_opponent = 'Match not updated'; // Default value

                                                // Calculate stats from matches
                                                foreach ($matches as $match) {
                                                    if ($match['team1_id'] == $team_id || $match['team2_id'] == $team_id) {
                                                        $played++;

                                                        // Check if the match is upcoming (score not updated)
                                                        if (is_null($match['team1_score']) && is_null($match['team2_score'])) {
                                                            $match_date = new DateTime($match['match_date']);
                                                            $now = new DateTime();

                                                            // Check if this is the closest upcoming match
                                                            if ($match_date >= $now) {
                                                                if ($match['team1_id'] == $team_id) {
                                                                    $next_opponent = $match['team2_name'];
                                                                } elseif ($match['team2_id'] == $team_id) {
                                                                    $next_opponent = $match['team1_name'];
                                                                }
                                                            }
                                                        }

                                                        // Update stats if match has a score
                                                        if (!is_null($match['team1_score']) && !is_null($match['team2_score'])) {
                                                            if ($match['team1_id'] == $team_id) {
                                                                // Team1 is the current team
                                                                $gf += $match['team1_score'];
                                                                $ga += $match['team2_score'];

                                                                if ($match['team1_score'] > $match['team2_score']) {
                                                                    $won++;
                                                                } elseif ($match['team1_score'] == $match['team2_score']) {
                                                                    $drawn++;
                                                                } else {
                                                                    $lost++;
                                                                }
                                                            } elseif ($match['team2_id'] == $team_id) {
                                                                // Team2 is the current team
                                                                $gf += $match['team2_score'];
                                                                $ga += $match['team1_score'];

                                                                if ($match['team2_score'] > $match['team1_score']) {
                                                                    $won++;
                                                                } elseif ($match['team2_score'] == $match['team1_score']) {
                                                                    $drawn++;
                                                                } else {
                                                                    $lost++;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                                // Calculate points and GD (goal difference)
                                                $points = ($won * 3) + ($drawn * 1);
                                                $gd = $gf - $ga;

                                                // Add team data to standings array
                                                $standings[] = [
                                                    'team_name' => $team_name,
                                                    'played' => $played,
                                                    'won' => $won,
                                                    'drawn' => $drawn,
                                                    'lost' => $lost,
                                                    'gf' => $gf,
                                                    'ga' => $ga,
                                                    'gd' => $gd,
                                                    'points' => $points,
                                                    'next_opponent' => $next_opponent
                                                ];
                                            }

                                            // Sort teams by points, GD, and team name
                                            usort($standings, function ($a, $b) {
                                                if ($a['points'] == $b['points']) {
                                                    if ($a['gd'] == $b['gd']) {
                                                        return strcmp($a['team_name'], $b['team_name']);
                                                    }
                                                    return $b['gd'] - $a['gd'];
                                                }
                                                return $b['points'] - $a['points'];
                                            });

                                            // Output standings and insert into database
                                            $position = 1;
                                            foreach ($standings as $team) {
                                                echo "<tr>";
                                                echo "<td>{$position}</td>";
                                                echo "<td>" . htmlspecialchars($team['team_name']) . "</td>";
                                                echo "<td>{$team['played']}</td>";
                                                echo "<td>{$team['won']}</td>";
                                                echo "<td>{$team['drawn']}</td>";
                                                echo "<td>{$team['lost']}</td>";
                                                echo "<td>{$team['gf']}</td>";
                                                echo "<td>{$team['ga']}</td>";
                                                echo "<td>{$team['gd']}</td>";
                                                echo "<td>{$team['points']}</td>";
                                                echo "<td>" . htmlspecialchars($team['next_opponent']) . "</td>"; // Added next opponent
                                                echo "</tr>";

                                                // Insert updated standings with position into standings_history table
                                                $stmt = $conn->prepare("INSERT INTO standings_history 
                                                    (tournament_id, position, team_name, played, won, drawn, lost, gf, ga, gd, points, next_opponent) 
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                                                    ON DUPLICATE KEY UPDATE 
                                                        position = VALUES(position),
                                                        played = VALUES(played),
                                                        won = VALUES(won),
                                                        drawn = VALUES(drawn),
                                                        lost = VALUES(lost),
                                                        gf = VALUES(gf),
                                                        ga = VALUES(ga),
                                                        gd = VALUES(gd),
                                                        points = VALUES(points),
                                                        next_opponent = VALUES(next_opponent)");

                                                $stmt->bind_param(
                                                    'iisiisiiiiis',
                                                    $tournament['id'],
                                                    $position, // Add position to the insert
                                                    $team['team_name'],
                                                    $team['played'],
                                                    $team['won'],
                                                    $team['drawn'],
                                                    $team['lost'],
                                                    $team['gf'],
                                                    $team['ga'],
                                                    $team['gd'],
                                                    $team['points'],
                                                    $team['next_opponent']
                                                );

                                                $stmt->execute();
                                                $position++; // Increment position for the next team
                                            }
                                            ?>
                                        </tbody>
                                    </table>






                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>




    <?php include '../includes/footer.php'; ?>

    <!-- Modals -->




    <!-- Tournament Modal -->
    <div id="tournamentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('tournamentModal')">&times;</span>
            <h2>Create Tournament</h2>
            <form action="add_tournament.php" method="POST" enctype="multipart/form-data">
                <label for="tournamentName">Tournament Name:</label>
                <input type="text" id="tournamentName" name="tournamentName" required>

                <label for="tournamentLogo">Tournament Logo:</label>
                <input type="file" id="tournamentLogo" name="tournamentLogo" accept="image/*" required>

                <label for="numberOfTeams">Number of Teams:</label>
                <input type="number" id="numberOfTeams" name="numberOfTeams" required>

                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" name="startDate" required>

                <label for="endDate">End Date:</label>
                <input type="date" id="endDate" name="endDate" required>

                <label for="organizer">Organizer:</label>
                <input type="text" id="organizer" name="organizer" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <button type="submit">Create Tournament</button>
            </form>
        </div>
    </div>

        <!-- Team Modal -->

        <!-- Team Modal -->
        <div id="teamModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('teamModal')">&times;</span>
                <h2>Add Team</h2>

                <!-- Display available slots dynamically -->
                <div id="slotMessage" style="margin-bottom: 10px; color: red;"></div>

                <form id="teamForm" action="add_team.php" method="POST" enctype="multipart/form-data">
                    <label for="tournamentSelect">Select Tournament:</label>
                    <select id="tournamentSelect" name="tournamentSelect" required onchange="checkSlots()">
                        <option value="">Select a tournament</option>
                        <?php foreach ($tournaments as $tournament): ?>
                            <option value="<?php echo htmlspecialchars($tournament['id']); ?>"><?php echo htmlspecialchars($tournament['name']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="teamName">Team Name:</label>
                    <input type="text" id="teamName" name="teamName" required>

                    <label for="teamLogo">Team Logo:</label>
                    <input type="file" id="teamLogo" name="teamLogo" accept="image/*" required>

                    <label for="coachName">Coach Name:</label>
                    <input type="text" id="coachName" name="coachName" required>

                    <!-- Add Team button -->
                    <button type="submit" id="addTeamButton">Add Team</button>
                </form>
            </div>
        </div>

        <script>
        // Function to check remaining slots in the selected tournament
        function checkSlots() {
            var tournamentId = document.getElementById("tournamentSelect").value;
            
            if (tournamentId) {
                // AJAX request to check remaining slots
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "check_slots.php?tournament_id=" + tournamentId, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        var slotMessage = document.getElementById("slotMessage");
                        var addTeamButton = document.getElementById("addTeamButton");

                        if (response.status === 'success') {
                            var slotsLeft = response.slots_left;

                            if (slotsLeft > 0) {
                                // Show how many slots are left
                                slotMessage.innerHTML = "Slots available: " + slotsLeft;
                                slotMessage.style.color = 'green';
                                addTeamButton.disabled = false;
                            } else {
                                // No slots left, disable button
                                slotMessage.innerHTML = "No slots left for this tournament.";
                                slotMessage.style.color = 'red';
                                addTeamButton.disabled = true;
                            }
                        } else {
                            slotMessage.innerHTML = "Error fetching slot data.";
                            slotMessage.style.color = 'red';
                            addTeamButton.disabled = true;
                        }
                    }
                };
                xhr.send();
            }
        }
        </script>


            <!-- Match Modal -->
        <div id="matchModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('matchModal')">&times;</span>
                <h2>Set Up Matches</h2>
                <form action="add_match.php" method="POST">
                    <label for="tournamentMatch">Select Tournament:</label>
                    <select id="tournamentMatch" name="tournamentMatch" required onchange="fetchTeams()">
                        <option value="">Select a tournament</option>
                        <?php foreach ($tournaments as $tournament): ?>
                            <option value="<?php echo htmlspecialchars($tournament['id']); ?>"><?php echo htmlspecialchars($tournament['name']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="homeTeam">Home Team:</label>
                    <select id="homeTeam" name="homeTeam" required>
                        <option value="">Select Home Team</option>
                        <!-- Populate with team options via JavaScript based on selected tournament -->
                    </select>

                    <label for="awayTeam">Away Team:</label>
                    <select id="awayTeam" name="awayTeam" required>
                        <option value="">Select Away Team</option>
                        <!-- Populate with team options via JavaScript based on selected tournament -->
                    </select>

                    <label for="matchDate">Match Date:</label>
                    <input type="date" id="matchDate" name="matchDate" required>

                    <label for="matchTime">Match Time:</label>
                    <input type="time" id="matchTime" name="matchTime" required>

                    <label for="matchLocation">Match Location:</label>
                    <input type="text" id="matchLocation" name="location" required>

                    <button type="submit">Set Up Match</button>
                </form>
            </div>
        </div>


    <!-- Edit Tournament Modal -->
    <div id="editTournamentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editTournamentModal')">&times;</span>
            <h2>Edit Tournament</h2>
            <form id="editTournamentForm" action="edit_tournament.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editTournamentId" name="tournament_id">
                
                <label for="editTournamentName">Tournament Name:</label>
                <input type="text" id="editTournamentName" name="tournamentName" required>

                <label for="editTournamentLogo">Tournament Logo:</label>
                <input type="file" id="editTournamentLogo" name="tournamentLogo" accept="image/*">

                <label for="editNumberOfTeams">Number of Teams:</label>
                <input type="number" id="editNumberOfTeams" name="numberOfTeams" required>

                <label for="editStartDate">Start Date:</label>
                <input type="date" id="editStartDate" name="startDate" >

                <label for="editEndDate">End Date:</label>
                <input type="date" id="editEndDate" name="endDate" required>

                <label for="editOrganizer">Organizer:</label>
                <input type="text" id="editOrganizer" name="organizer" required>

                <label for="editLocation">Location:</label>
                <input type="text" id="editLocation" name="location" required>

                <button type="submit">Update Tournament</button>
            </form>
        </div>
    </div>

    <!-- Edit Team Modal -->
    <div id="editTeamModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editTeamModal')">&times;</span>
            <h2>Edit Team</h2>
            <form id="editTeamForm" action="edit_team.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editTeamId" name="team_id">
                
                <label for="editTournamentSelect">Select Tournament:</label>
                <select id="editTournamentSelect" name="tournament_id" required>
                    <option value="">Select a tournament</option>
                    <?php foreach ($tournaments as $tournament): ?>
                        <option value="<?php echo htmlspecialchars($tournament['id']); ?>"><?php echo htmlspecialchars($tournament['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="editTeamName">Team Name:</label>
                <input type="text" id="editTeamName" name="teamName" required>

                <label for="editTeamLogo">Team Logo:</label>
                <input type="file" id="editTeamLogo" name="teamLogo" accept="image/*">

                <label for="editCoachName">Coach Name:</label>
                <input type="text" id="editCoachName" name="coachName" required>

                <button type="submit">Update Team</button>
            </form>
        </div>
    </div>




        <!-- Edit Match Modal -->
    <div id="editMatchModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editMatchModal')">&times;</span>
            <h2>Edit Match</h2>
            <form id="editMatchForm" action="edit_match.php" method="POST">
                <input type="hidden" id="editMatchId" name="match_id">
                
                <label for="editMatchTournament">Select Tournament:</label>
                <select id="editMatchTournament" name="tournament_id" required>
                    <option value="">Select a tournament</option>
                    <?php foreach ($tournaments as $tournament): ?>
                        <option value="<?php echo htmlspecialchars($tournament['id']); ?>"><?php echo htmlspecialchars($tournament['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="editHomeTeam">Home Team:</label>
                <select id="editHomeTeam" name="home_team_id" required>
                    <option value="">Select Home Team</option>
                </select>

                <label for="editAwayTeam">Away Team:</label>
                <select id="editAwayTeam" name="away_team_id" required>
                    <option value="">Select Away Team</option>
                </select>

                <label for="editMatchDate">Match Date:</label>
                <input type="date" id="editMatchDate" name="match_date" required>

                <label for="editMatchTime">Match Time:</label>
                <input type="time" id="editMatchTime" name="match_time" required>

                <label for="editMatchLocation">Match Location:</label>
                <input type="text" id="editMatchLocation" name="location" required>

                <button type="submit">Update Match</button>
            </form>
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('deleteConfirmationModal')">&times;</span>
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete this item? This action cannot be undone.</p>
            <button id="confirmDeleteBtn" style="background-color: #dc3545;">Delete</button>
            <button onclick="closeModal('deleteConfirmationModal')" style="background-color: #6c757d;">Cancel</button>
        </div>
    </div>


   <!-- Edit Match Score Modal -->
<div id="editScoreModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editScoreModal')">&times;</span>
        <h2>Edit Match Score</h2>
        <form id="editScoreForm" action="edit_score.php" method="POST">
            <input type="hidden" id="editScoreMatchId" name="match_id">

            <label for="editHomeScore">Home Team Score:</label>
            <span id="homeTeamName"></span> <!-- Placeholder for Home Team Name -->
            <input type="number" id="editHomeScore" name="home_score" required min="0">

            <label for="editAwayScore">Away Team Score:</label>
            <span id="awayTeamName"></span> <!-- Placeholder for Away Team Name -->
            <input type="number" id="editAwayScore" name="away_score" required min="0">

            <button type="submit">Update Score</button> <!-- Submit Button -->
        </form>
    </div>
</div>











    <!-- Additional Modals for Editing Teams and Matches can be added similarly -->

    <script src="../js/scripts.js"></script> <!-- Your existing script -->
    
</body>
</html>


