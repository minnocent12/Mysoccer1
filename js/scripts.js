

// Confirmation before deleting items
function confirmDelete(){
    return confirm("Are you sure you want to delete this item?");
}


// Toggle the navigation menu on mobile for admin header
function toggleAdminMenu() {
    const adminNavMenu = document.querySelector('.admin-nav-menu');
    adminNavMenu.classList.toggle('active');
}

// Toggle the profile dropdown on mobile
function toggleProfileDropdown() {
    const profileDropdown = document.querySelector('.admin-profile-dropdown');
    profileDropdown.classList.toggle('active');
}

// Event Listener for Admin Menu Toggle (Hamburger Icon)
document.addEventListener('DOMContentLoaded', function() {
    const adminMenuToggle = document.querySelector('.admin-header .menu-toggle');
    if (adminMenuToggle) {
        adminMenuToggle.addEventListener('click', toggleAdminMenu);
    }

    // Event Listener for Profile Icon Click on Mobile
    const profileIcon = document.querySelector('.admin-profile-dropdown .profile-icon');
    if (profileIcon) {
        profileIcon.addEventListener('click', toggleProfileDropdown);
    }

    // Optional: Close the profile dropdown when clicking outside
    window.addEventListener('click', function(event) {
        const profileDropdown = document.querySelector('.admin-profile-dropdown');
        if (profileDropdown && !profileDropdown.contains(event.target)) {
            profileDropdown.classList.remove('active');
        }
    });
});

function validateLoginForm() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();

    if(username === '' || password === ''){
        alert('Please fill in both username and password.');
        return false;
    }
    return true;
}

// scripts.js

// Function to fetch teams based on selected tournament and ensure home team is not repeated in away team
function fetchTeams() {
    const tournamentId = document.getElementById('tournamentMatch').value;

    // Reset team dropdowns
    const homeTeamSelect = document.getElementById('homeTeam');
    const awayTeamSelect = document.getElementById('awayTeam');
    homeTeamSelect.innerHTML = '<option value="">Select Home Team</option>';
    awayTeamSelect.innerHTML = '<option value="">Select Away Team</option>';

    if (tournamentId) {
        fetch(`fetch_teams.php?tournament_id=${tournamentId}`)
            .then(response => response.json())
            .then(teams => {
                teams.forEach(team => {
                    const option = new Option(team.name, team.id);
                    homeTeamSelect.add(option);
                    awayTeamSelect.add(option.cloneNode(true)); // Clone for away team
                });

                // Add event listeners to ensure same team can't be selected in both home and away
                homeTeamSelect.addEventListener('change', filterAwayTeams);
                awayTeamSelect.addEventListener('change', filterHomeTeams);
            })
            .catch(error => console.error('Error fetching teams:', error));
    }
}

// Function to filter out the selected home team from away team options
function filterAwayTeams() {
    const homeTeamSelected = document.getElementById('homeTeam').value;
    const awayTeamSelect = document.getElementById('awayTeam');

    Array.from(awayTeamSelect.options).forEach(option => {
        if (option.value === homeTeamSelected) {
            option.disabled = true;
        } else {
            option.disabled = false;
        }
    });
}

// Function to filter out the selected away team from home team options
function filterHomeTeams() {
    const awayTeamSelected = document.getElementById('awayTeam').value;
    const homeTeamSelect = document.getElementById('homeTeam');

    Array.from(homeTeamSelect.options).forEach(option => {
        if (option.value === awayTeamSelected) {
            option.disabled = true;
        } else {
            option.disabled = false;
        }
    });
}


// Other existing JavaScript functions (if any) can remain here
function openTournamentModal() {
    document.getElementById('tournamentModal').style.display = 'block';
}

function openTeamModal() {
    document.getElementById('teamModal').style.display = 'block';
}

function openMatchModal() {
    document.getElementById('matchModal').style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}


// Function to toggle tournament details
function toggleDetails(tournamentId) {
    const detailsRow = document.getElementById('details-' + tournamentId);
    if (detailsRow.style.display === 'table-row') {
        detailsRow.style.display = 'none';
    } else {
        detailsRow.style.display = 'table-row';
    }
}

// Function to open modals
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

// Function to close modals
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Function to open edit tournament modal
function openEditModal(tournamentId) {
    // Fetch tournament data via AJAX
    fetch(`get_tournament.php?tournament_id=${tournamentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('editTournamentId').value = data.tournament.id;
                document.getElementById('editTournamentName').value = data.tournament.name;
                document.getElementById('editNumberOfTeams').value = data.tournament.num_teams;
                document.getElementById('editStartDate').value = data.tournament.start_date;
                document.getElementById('editEndDate').value = data.tournament.end_date;
                document.getElementById('editOrganizer').value = data.tournament.organizer;
                document.getElementById('editLocation').value = data.tournament.location;
                // Open the modal
                openModal('editTournamentModal');
            } else {
                alert('Failed to fetch tournament data.');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Function to confirm deletion
let deleteItemId = null;
let deleteItemType = null; // 'tournament', 'team', 'match'
function confirmDelete(id, type) {
    deleteItemId = id;
    deleteItemType = type;
    openModal('deleteConfirmationModal');
}

// Handle delete confirmation
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteItemId && deleteItemType) {
        window.location.href = `delete_${deleteItemType}.php?id=${deleteItemId}`;
    }
});

// Function to open edit team modal
function openEditTeamModal(teamId) {
    // Fetch team data via AJAX
    fetch(`get_team.php?team_id=${teamId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('editTeamId').value = data.team.id;
                document.getElementById('editTournamentSelect').value = data.team.tournament_id;
                document.getElementById('editTeamName').value = data.team.name;
                document.getElementById('editCoachName').value = data.team.coach_name;
                // Optionally, display current logo
                openModal('editTeamModal');
            } else {
                alert('Failed to fetch team data.');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Function to open edit match modal
function openEditMatchModal(matchId) {
    // Fetch match data via AJAX
    fetch(`get_match.php?match_id=${matchId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('editMatchId').value = data.match.id;
                document.getElementById('editMatchTournament').value = data.match.tournament_id;
                // Fetch teams based on tournament
                fetchTeamsForEdit(data.match.tournament_id, 'editHomeTeam', 'editAwayTeam', data.match.team1_id, data.match.team2_id);
                document.getElementById('editMatchDate').value = data.match.match_date;
                document.getElementById('editMatchTime').value = data.match.match_time; // Add this line
                document.getElementById('editMatchLocation').value = data.match.location;
                openModal('editMatchModal');
            } else {
                alert('Failed to fetch match data.');
            }
        })
        .catch(error => console.error('Error:', error));
}


// Function to fetch teams for editing match
function fetchTeamsForEdit(tournamentId, homeTeamSelectId, awayTeamSelectId, selectedHomeTeamId = null, selectedAwayTeamId = null) {
    const homeTeamSelect = document.getElementById(homeTeamSelectId);
    const awayTeamSelect = document.getElementById(awayTeamSelectId);
    homeTeamSelect.innerHTML = '<option value="">Select Home Team</option>';
    awayTeamSelect.innerHTML = '<option value="">Select Away Team</option>';

    if (tournamentId) {
        fetch(`fetch_teams.php?tournament_id=${tournamentId}`)
            .then(response => response.json())
            .then(teams => {
                teams.forEach(team => {
                    const homeOption = new Option(team.name, team.id);
                    if (team.id == selectedHomeTeamId) homeOption.selected = true;
                    homeTeamSelect.add(homeOption);

                    const awayOption = new Option(team.name, team.id);
                    if (team.id == selectedAwayTeamId) awayOption.selected = true;
                    awayTeamSelect.add(awayOption);
                });
            })
            .catch(error => console.error('Error fetching teams:', error));
    }
}

// Fetch teams when tournament is selected in edit match modal
document.getElementById('editMatchTournament').addEventListener('change', function() {
    const tournamentId = this.value;
    fetchTeamsForEdit(tournamentId, 'editHomeTeam', 'editAwayTeam');
});

// Function to open edit score modal
function openEditScoreModal(matchId) {
    // Fetch match data via AJAX
    fetch(`get_match.php?match_id=${matchId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('editScoreMatchId').value = data.match.id;
                
                // Display team names
                document.getElementById('homeTeamName').textContent = data.match.team1_name || 'Home Team'; // Default name if not available
                document.getElementById('awayTeamName').textContent = data.match.team2_name || 'Away Team'; // Default name if not available

                // Populate score inputs
                document.getElementById('editHomeScore').value = data.match.team1_score || 0; // Default to 0 if no score
                document.getElementById('editAwayScore').value = data.match.team2_score || 0; // Default to 0 if no score
                
                openModal('editScoreModal');
            } else {
                alert('Failed to fetch match data.');
            }
        })
        .catch(error => console.error('Error:', error));
}









// Function to restrict date selection to today and future dates
function restrictMatchDate() {
    const tournament_editEndDate = document.getElementById('editEndDate');
    const tournament_editStartDate = document.getElementById('editStartDate');
    const tournament_starting_date = document.getElementById('startDate');
    const tournament_ending_date = document.getElementById('endDate');
    const matchDateInput = document.getElementById('matchDate');
    
    // Get today's date (with time set to midnight to avoid timezone issues)
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set the time to midnight

    // Format the date as YYYY-MM-DD
    const formattedToday = today.toISOString().split('T')[0];

    // Set the minimum attributes to today's date
    if (tournament_editEndDate) tournament_editEndDate.setAttribute('min', formattedToday);
    if (tournament_editStartDate) tournament_editStartDate.setAttribute('min', formattedToday);
    if (matchDateInput) matchDateInput.setAttribute('min', formattedToday);
    if (tournament_starting_date) tournament_starting_date.setAttribute('min', formattedToday);
    if (tournament_ending_date) tournament_ending_date.setAttribute('min', formattedToday);
}

// Call restrictMatchDate when the page loads
document.addEventListener('DOMContentLoaded', function() {
    restrictMatchDate();
});


// Fetch tournament date range and restrict match date to fall within that range
function fetchTournamentDetails(tournamentId) {
    fetch(`get_tournament_dates.php?tournament_id=${tournamentId}`)
        .then(response => response.json())
        .then(tournament => {
            if (tournament.start_date && tournament.end_date) {
                const matchDateInput = document.getElementById('matchDate');
                matchDateInput.setAttribute('min', tournament.start_date);
                matchDateInput.setAttribute('max', tournament.end_date);
            }
        })
        .catch(error => console.error('Error fetching tournament details:', error));
}

// Modify fetchTeams to also fetch tournament date details
function fetchTeams() {
    const tournamentId = document.getElementById('tournamentMatch').value;

    // Reset team dropdowns
    const homeTeamSelect = document.getElementById('homeTeam');
    const awayTeamSelect = document.getElementById('awayTeam');
    homeTeamSelect.innerHTML = '<option value="">Select Home Team</option>';
    awayTeamSelect.innerHTML = '<option value="">Select Away Team</option>';

    if (tournamentId) {
        // Fetch teams
        fetch(`fetch_teams.php?tournament_id=${tournamentId}`)
            .then(response => response.json())
            .then(teams => {
                teams.forEach(team => {
                    const option = new Option(team.name, team.id);
                    homeTeamSelect.add(option);
                    awayTeamSelect.add(option.cloneNode(true));
                });

                homeTeamSelect.addEventListener('change', filterAwayTeams);
                awayTeamSelect.addEventListener('change', filterHomeTeams);
            })
            .catch(error => console.error('Error fetching teams:', error));

        // Fetch tournament dates and restrict match date input
        fetchTournamentDetails(tournamentId);
    }
}




