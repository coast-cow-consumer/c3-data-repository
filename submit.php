<?php
include "utils/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cross-Origin-Opener-Policy" content="unsafe-none">
  <meta http-equiv="Cross-Origin-Embedder-Policy" content="unsafe-none">
  <title>C3 | Submit</title>

  <link rel="stylesheet" href="css/dashboard-common.css">
  <link rel="stylesheet" href="css/submit.css">
</head>

<body>
  <?php if (!empty($error)): ?>
    <div class="error-div">
      <p id="error-message">
        <?php echo $error; ?>
      </p>
    </div>
  <?php endif; ?>
  <div class="wrapper-background">
    <p> </p>
  </div>
  <div class="wrapper">
    <?php include "navbar.php"; ?>
    <div class="content-wrapper">
      <div class="content">
        <h1>Submit a Dataset</h1>
        <div class="dataset-submission">
          p>Drive API Quickstart</p>

          <!--Add buttons to initiate auth sequence and sign out-->
          <button id="authorize_button" onclick="handleAuthClick()">Authorize</button>
          <button id="signout_button" onclick="handleSignoutClick()">Sign Out</button>

          <pre id="content" style="white-space: pre-wrap;"></pre>

          <script type="text/javascript">
            /* exported gapiLoaded */
            /* exported gisLoaded */
            /* exported handleAuthClick */
            /* exported handleSignoutClick */

            // TODO(developer): Set to client ID and API key from the Developer Console
            const CLIENT_ID = = '<?php echo $drive_clientID ?>';
            const API_KEY = '<?php echo $drive_APIkey ?>';

            // Discovery doc URL for APIs used by the quickstart
            const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/drive/v3/rest';

            // Authorization scopes required by the API; multiple scopes can be
            // included, separated by spaces.
            const SCOPES = 'https://www.googleapis.com/auth/drive.metadata.readonly';

            let tokenClient;
            let gapiInited = false;
            let gisInited = false;

            document.getElementById('authorize_button').style.visibility = 'hidden';
            document.getElementById('signout_button').style.visibility = 'hidden';

            /**
             * Callback after api.js is loaded.
             */
            function gapiLoaded() {
              gapi.load('client', initializeGapiClient);
            }

            /**
             * Callback after the API client is loaded. Loads the
             * discovery doc to initialize the API.
             */
            async function initializeGapiClient() {
              await gapi.client.init({
                apiKey: API_KEY,
                discoveryDocs: [DISCOVERY_DOC],
              });
              gapiInited = true;
              maybeEnableButtons();
            }

            /**
             * Callback after Google Identity Services are loaded.
             */
            function gisLoaded() {
              tokenClient = google.accounts.oauth2.initTokenClient({
                client_id: CLIENT_ID,
                scope: SCOPES,
                callback: '', // defined later
              });
              gisInited = true;
              maybeEnableButtons();
            }

            /**
             * Enables user interaction after all libraries are loaded.
             */
            function maybeEnableButtons() {
              if (gapiInited && gisInited) {
                document.getElementById('authorize_button').style.visibility = 'visible';
              }
            }

            /**
             *  Sign in the user upon button click.
             */
            function handleAuthClick() {
              tokenClient.callback = async (resp) => {
                if (resp.error !== undefined) {
                  throw (resp);
                }
                document.getElementById('signout_button').style.visibility = 'visible';
                document.getElementById('authorize_button').innerText = 'Refresh';
                await listFiles();
              };

              if (gapi.client.getToken() === null) {
                // Prompt the user to select a Google Account and ask for consent to share their data
                // when establishing a new session.
                tokenClient.requestAccessToken({ prompt: 'consent' });
              } else {
                // Skip display of account chooser and consent dialog for an existing session.
                tokenClient.requestAccessToken({ prompt: '' });
              }
            }

            /**
             *  Sign out the user upon button click.
             */
            function handleSignoutClick() {
              const token = gapi.client.getToken();
              if (token !== null) {
                google.accounts.oauth2.revoke(token.access_token);
                gapi.client.setToken('');
                document.getElementById('content').innerText = '';
                document.getElementById('authorize_button').innerText = 'Authorize';
                document.getElementById('signout_button').style.visibility = 'hidden';
              }
            }

            /**
             * Print metadata for first 10 files.
             */
            async function listFiles() {
              let response;
              try {
                response = await gapi.client.drive.files.list({
                  'pageSize': 10,
                  'fields': 'files(id, name)',
                });
              } catch (err) {
                document.getElementById('content').innerText = err.message;
                return;
              }
              const files = response.result.files;
              if (!files || files.length == 0) {
                document.getElementById('content').innerText = 'No files found.';
                return;
              }
              // Flatten to string to display
              const output = files.reduce(
                (str, file) => `${str}${file.name} (${file.id})\n`,
                'Files:\n');
              document.getElementById('content').innerText = output;
            }
          </script>
          <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
          <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>

        </div>
      </div>
    </div>
  </div>
</body>

</html>