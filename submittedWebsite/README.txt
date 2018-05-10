// Cyrus Dobbs 1529854

css/electago.css - The css file used throughout the website

databaseFiles/finalDatabase.sql - This can be used to replicate the database needed to run the website

images/... - images used throughout the website

includes/functions - a few functions that are included into some php scripts
        /getConnCandidate.php - connect to the candidate table using PDO
        /getConnElection.php - connect to the election table using PDO
        /login-admin.php - log an admin into the website
        /login.php - log a voter into the website
        /password.php - hash password and validate
        /settings.php - contains variables needed to connect to the database

pages/admin/addCandidate.php - contains a query to add a candidate to database
           /addElection.php - contains a query to add an election to database
           /candidates.php - page that displays all candidates to admins
           /changeCandidate.php - contains a query to change a candidate on the database
           /decryptScreen.php - admins chose which election they want to decrypt from this screen
           /decryptVotes.php - this script runs all the code to decrypt the votes of the chosen election
           /deleteCandidate.php - contains a query to delete a candidate from database
           /deleteElection.php - contains a query to delete a election from database
           /demographics.php - page used to display a list of all elections for the admin to select one they wish to view results for
           /elections.php - this the create/edit an election page on the admin side of the site
           /getCandidates.php - contains a query to pull all the candidate information from the data database
           /index.php - admin home page
           /results.php - calculates and displays all results of a finished&decrypted election to admins
           /uploadKey.php - page for admins to upload their part of the key to decrypt votes
           /uploadScript.php - script that handles adding the key to the database
           /viewElections.php - used to view all elections that are on the database

pages/admin-login.php - Page where admins enter their login details
     /dashboard.php - Displays all relivant elections to voter
     /voted.php - Script that encrypts vote and sends to database
     /voting.php - Page that displays candidates to the user once they have selected the election they wish to vote in

RSA/privkey.pem - This is the private key used to decrypt the votes. This WOULD NOT be stored on the webserver in reality.
   /pubkey.pem - This is the public key used to encrypt the votes. This WOULD be stored on the webserver.

adminHelp.html - admin screen to provide help on using the website
voterHelp.html - voter screen to provide help on using the website
index.php - page that voters enter their login details


