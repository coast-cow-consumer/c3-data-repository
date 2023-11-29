<?php include "utils/config.php";
//File: readme.php
//Authors: Gordon Doore, Parth Parth
//Last modified: 11/28/2023
//Purpose: Readme form for submitting data to SQL db

/**
 * Inserts a new record into the `C3DataMasterTest` table in the database using the data from a form submission.
 *
 * @param MySQLi $conn The MySQLi connection object to the database.
 * @param array $post_data The array of form data to be inserted into the database.
 * @param string $user_id The user's id.
 *
 * @return string $error The error message, if any error occurred while inserting the data into the database. Empty if no errors occurred.
 *
 * @throws PDOException If there is an error executing the SQL statement.
 */
function fieldsToReadMeATSQL($conn, $post_data, $user_id)
{
  $error = "";
  // Retrieve variables from session
  $title = $post_data['title'];
  $acknowledgements = $post_data['acknowledgements'];
  $data_usage_agreement = $post_data['data_usage_agreement'];
  $keywords = $post_data['keywords'];
  $licensed_data = $post_data['licensed_data'];
  $data_overview = $post_data['data_overview'];
  $sharing_access_info = $post_data['sharing_access_info'];
  $publications_links = $post_data['publications_links'];
  $iacuc_compliance = $post_data['iacuc_compliance'];
  $data_links = $post_data['data_links'];
  $ancillary_relationships = $post_data['ancillary_relationships'];
  $github_link = $post_data['github_link'];
  $num_files = $post_data['num_files'];
  $creation_date = $post_data['creation_date'];
  $change_log = $post_data['change_log'];
  $tech_for_creation = $post_data['tech_for_creation'];
  $sample_collection_procedure = $post_data['sample_collection_procedure'];
  $collection_conditions = $post_data['collection_conditions'];
  $other_collection = $post_data['other_collection'];
  $cleaned_data = $post_data['cleaned_data'];
  $cleaning_methods = $post_data['cleaning_methods'];
  $qa_procedures = $post_data['qa_procedures'];
  $key_analytic_methods = $post_data['key_analytic_methods'];
  $key_softwares = $post_data['key_softwares'];
  $key_software_address = $post_data['key_software_address'];
  $other_software_info = $post_data['other_software_info'];
  $naming_conventions = $post_data['naming_conventions'];
  $abbreviations_used = $post_data['abbreviations_used'];
  $variables_used = $post_data['variables_used'];
  $dependencies = $post_data['dependencies'];
  $additional_info = $post_data['additional_info'];
  $subcommittee = $post_data['subcommittee'];
  $primary_contact = $post_data['primary_contact'];
  $creators = $post_data['creators'];
  $file_desc = $post_data['file_desc'];

  //prepare query
  $sql = "INSERT INTO `C3ReadMeAT` (`creation_date`,`subcommittee`,`primary_contact`,`title_subtitle`,`institution`,
  `acknow`,`data_usage_agreement`,`keywords`,`licensed_data`,`iacuc`,`alternate_available_link`,`ancillary_link`,
  `publication_link`,`github_link`,`technology_for_creation`,`sample_collection_procedure`,`conditions_collection`,
  `data_collection_other`,`cleaning_desc`,`qa_procedures`,`key_analytical_methods`,`key_softwares`,`key_software_address`,
  `other_software_information`,`dataset_change_log`,`num_files_readme`,`cleaned`,`naming_conventions`,`file_description`,
  `abbreviations_definition`,`variables_description`,`dependencies`,`other_information`) 
  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt = $conn->prepare($sql);

  try {
    $stmt->execute([
      //variables
      $creation_date,
      $subcommittee,
      $primary_contact,
      $title,
      $creators,
      $acknowledgements,
      $data_usage_agreement,
      $keywords,
      $licensed_data,
      $iacuc_compliance,
      $data_links,
      $ancillary_relationships,
      $publications_links,
      $github_link,
      $tech_for_creation,
      $sample_collection_procedure,
      $collection_conditions,
      $other_collection,
      $cleaning_methods,
      $qa_procedures,
      $key_analytic_methods,
      $key_softwares,
      $key_software_address,
      $other_software_info,
      $change_log,
      $num_files,
      $cleaned_data,
      $naming_conventions,
      $file_desc,
      $abbreviations_used,
      $variables_used,
      $dependencies,
      $additional_info
    ]);


  } catch (PDOException $e) {
    $error = "Error submitting dataset. Please try again.";
    $error = $e->getMessage();
    error_log($error);
  }
  return $error;
}

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$_SESSION['session_name'] = session_name();
$_SESSION['update'] = [];
$error = "";
$readme_submitted = false;

$user_id = $_SESSION['user_id'];
$U_Id = substr($user_id, 2);

$sql = "SELECT * FROM C3SignUp WHERE U_Id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$U_Id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
  $email = $user['Email'];
  $_SESSION['email'] = $email;
  $last_name = $user['LastName'];
  $first_name = $user['FirstName'];
}

if (isset($_POST['submitReadme'])) {
  // Call your PHP function here
  fieldsToReadMeATSQL($conn, $_POST, $user_id);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cross-Origin-Opener-Policy" content="unsafe-none">
  <meta http-equiv="Cross-Origin-Embedder-Policy" content="unsafe-none">
  <title>C3 | Readme</title>

  <link rel="stylesheet" href="utils/css/dashboard-common.css">
  <link rel="stylesheet" href="utils/css/readme.css">
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
    <?php include "loader.php"; ?>
    <?php include "navbar.php"; ?>
    <div class="content-wrapper">
      <div class="content">
        <h1>Readme Form</h1>
        <div class="dataset-submission">
          <div class="enter-data">
            <form id="readme-form" action="" method="POST">
              <h2>Readme Dataset Submission Form</h2>
              <p><em>* Indicates required question</em></p>

              <!-- General Information -->
              <label for="creation_date">Creation Date (mm-dd-yyyy) *</label>
              <input type="date" id="creation_date" name="creation_date" value="<?php echo date("Y-m-d") ?>" required>

              <!-- Subcommittee(s) -->
              <label for="subcommittee">C3 Subcommittee(s) *</label>
              <select id="subcommittee" name="subcommittee" required>
                <option value="Algal Characteristics">Algal Characteristics</option>
                <option value="Milk Yield/Quality & Animal Wellness">Milk Yield/Quality & Animal Wellness</option>
                <option value="Natural Resources">Natural Resources</option>
                <option value="Social/Environmental Implications">Social/Environmental Implications</option>
                <option value="Life Cycle Assessment">Life Cycle Assessment</option>
                <option value="Extension & Education">Extension & Education</option>
                <option value="Database">Database</option>
              </select>

              <!-- Primary Contact -->
              <!-- Doesn't work correctly yet -->
              <label for="primary_contact">Primary Contact Name and Email *</label>
              <div id="primary_contact_container">
                <label for="author1">Author 1:</label>
                <input type="text" id="primary_contact" class="primary_contact" name="primary_contact" required>
              </div>
              <button type="button" id="add-author-btn">Add Another Author</button>

              <!-- Base your readme on an existing table: -->
              <label for="selected_title">Base your readme on an existing table:</label>
              <select id="selected_title" name="selected_title">
                <option value="None">None</option>
                <?php
                $sql = "SELECT title_subtitle FROM C3ReadMeAT";
                $result = $conn->query($sql);

                if ($result) {
                  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row["title_subtitle"] . "'>" . $row["title_subtitle"] . "</option>";
                  }
                }
                ?>
                <input type="button" id="fill_from_title" value="Fill">

                <!-- Title/Sub-title FOR SOME REASON THIS DOESN'T SHOW UP-->
                <label for="title">Title/Subtitle *</label>
                <input type="text" id="title" name="title" required>

                <!-- Creators/Authors and Institutions -->
                <label for="creators">Creators/Authors Institutions *</label>
                <label for="colbyCollege">
                  <input type="checkbox" id="colbyCollege" name="creators" value="Colby College"> Colby College
                </label><br>

                <label for="bigelowLaboratory">
                  <input type="checkbox" id="bigelowLaboratory" name="creators"
                    value="Bigelow Laboratory for Ocean Sciences"> Bigelow Laboratory for Ocean Sciences
                </label><br>

                <label for="universityOfVermont">
                  <input type="checkbox" id="universityOfVermont" name="creators" value="University of Vermont">
                  University of Vermont
                </label><br>

                <label for="universityOfNewHampshire">
                  <input type="checkbox" id="universityOfNewHampshire" name="creators"
                    value="University of New Hampshire"> University of New Hampshire
                </label><br>

                <label for="clarksonUniversity">
                  <input type="checkbox" id="clarksonUniversity" name="creators" value="Clarkson University"> Clarkson
                  University
                </label><br>

                <label for="syracuseUniversity">
                  <input type="checkbox" id="syracuseUniversity" name="creators" value="Syracuse University"> Syracuse
                  University
                </label><br>

                <label for="williamHMinerInstitute">
                  <input type="checkbox" id="williamHMinerInstitute" name="creators" value="William H. Miner Institute">
                  William H. Miner Institute
                </label><br>

                <label for="cornellCooperativeExtension">
                  <input type="checkbox" id="cornellCooperativeExtension" name="creators"
                    value="Cornell Cooperative Extension"> Cornell Cooperative Extension
                </label><br>

                <label for="wolfesNeckCenter">
                  <input type="checkbox" id="wolfesNeckCenter" name="creators" value="Wolfe's Neck Center"> Wolfe's Neck
                  Center
                </label><br>

                <!-- Acknowledgements -->
                <label for="acknowledgements">Acknowledgements</label>
                <textarea id="acknowledgements" name="acknowledgements" rows="4" required></textarea>


                <!-- Data Usage Agreement -->
                <label for="data_usage_agreement">Data Usage Agreement *</label>
                <textarea id="data_usage_agreement" name="data_usage_agreement" rows="4" required></textarea>

                <!-- Associated Keywords -->
                <label for="keywords">Associated Keywords *</label>
                <input type="text" id="keywords" name="keywords" required>

                <!-- Licensed Data -->
                <label for="licensed_data">Licensed Data (Y/N) *</label>
                <select id="licensed_data" name="licensed_data" required>
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>

                <!-- Data and File(s) Overview -->
                <label for="data_overview">Data and File(s) Overview *</label>
                <textarea id="data_overview" name="data_overview" rows="6" required></textarea>

                <!-- Links to Publications -->
                <label for="publications_links">Links to Publications That Cite or Use the Data</label>
                <input type="text" id="publications_links" name="publications_links">

                <!-- IACUC and Other Compliance THIS LIKELY NEEDS TO CHANGE TO INCLUDE IACUC-->
                <label for="iacuc_compliance">IACUC,etc. (Y/N)</label>
                <select id="iacuc_compliance" name="iacuc_compliance">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>

                <!-- Links to publicly accessible locations of the data -->
                <label for="data_links">Links to Publicly Accessible Locations of the Data</label>
                <input type="url" id="data_links" name="data_links" required>

                <!-- Relationships to ancillary datasets -->
                <label for="ancillary_relationships">Links to any Ancillary Datasets, description if no link is
                  available</label>
                <textarea id="ancillary_relationships" name="ancillary_relationships" rows="4" required></textarea>

                <!-- GitHub link to any relevant code, libraries, etc... -->
                <label for="github_link">GitHub Link to Relevant Code, Libraries, etc...</label>
                <input type="url" id="github_link" name="github_link" required>

                <!-- Number of files associated with this readme -->
                <label for="num_files">Number of Files Associated with This Readme</label>
                <input type="number" id="num_files" name="num_files" required>

                <!-- Dataset Change Log -->
                <label for="change_log">Dataset Change Log</label>
                <textarea id="change_log" name="change_log" rows="4"></textarea>

                <!-- Methodological Information -->
                <label for="methodological_info">Methodological Information</label>
                <!-- tech for creation  -->
                <label for="tech_for_creation">List of any relevant technologies (software, hardware, instruments, and
                  versions) used in creating the data include standards and calibration information, if
                  appropriate</label>
                <textarea id="tech_for_creation" name="tech_for_creation" rows="6"></textarea>
                <!-- sample_collection_procedure -->
                <label for="sample_collection_procedure">Sample collection, processing, analysis and/or submission
                  procedures</label>
                <!-- potentially change to numerical inputs for written stuff -->
                <textarea id="sample_collection_procedure" name="sample_collection_procedure" rows="6"></textarea>
                <!-- environmental or experimental conditions for collection -->
                <label for="collection_conditions">Experimental & environmental conditions of collection</label>
                <input type="text" id="collection_conditions" name="collection_conditions" rows="4">
                <!-- other info about how data was collected or obtained -->
                <label for="other_collection">Other key information related to data collection or generation.</label>
                <input type="text" id="other_collection" name="other_collection" rows="4">
                <!-- cleaned data? -->
                <label for="cleaned_data">Cleaned Data? (Y/N)</label>
                <select id="cleaned_data" name="cleaned_data">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
                <!-- description on how data was cleaned / prepared for submission-->
                <label for="cleaning_methods">Descriptions of your methods used for data cleaning/processing</label>
                <input type="text" id="cleaning_methods" name="cleaning_methods" rows="4">
                <!-- QA procedures -->
                <label for="qa_procedures">Descriptions of your methods used for quality assurance of data</label>
                <input type="text" id="qa_procedures" name="qa_procedures" rows="4">
                <!-- key analytical methods -->
                <label for="key_analytic_methods">Your analytical methods, procedures, theories, etc used in analyzing
                  data</label>
                <input type="text" id="key_analytic_methods" name="key_analytic_methods" rows="4">
                <!-- key softwares -->
                <label for="key_softwares">Softwares used in obtaining, cleaning, or analyzing data</label>
                <input type="text" id="key_softwares" name="key_softwares" rows="4">
                <!-- key software addresses (where is software from) -->
                <label for="key_software_address">If you put a software above, please include an address to the
                  software/s you referenced</label>
                <input type="text" id="key_software_address" name="key_software_address" rows="4">
                <!-- other software information -->
                <label for="other_software_info">Any other relevant information regarding software used?</label>
                <input type="text" id="other_software_info" name="other_software_info" rows="4">
                <!-- nameing conventions -->
                <label for="naming_conventions">If you have important naming conventions, describe them</label>
                <input type="text" id="naming_conventions" name="naming_conventions" rows="4">
                <!-- abbreviations used -->
                <label for="abbreviations_used">List any abbreviations in submitted file with their definition</label>
                <input type="text" id="abbreviations_used" name="abbreviations_used" rows="4">
                <!-- variables description -->
                <label for="variables_used">List any variables and describe them, please include any relevent
                  units</label>
                <input type="text" id="variables_used" name="variables_used" rows="4">
                <!-- dependencies to use data -->
                <label for="dependencies">List any system dependencies to use this data</label>
                <input type="text" id="dependencies" name="dependencies" rows="4">
                <!-- File Description -->
                <label for="file_desc">Describe the submitted files</label>
                <textarea id="file_desc" name="file_desc" rows="4"></textarea>
                <!-- Additional Information -->
                <label for="additional_info">Additional Information</label>
                <textarea id="additional_info" name="additional_info" rows="4"></textarea>

                <input type="submit" name="submitReadme" value="Submit">
          </div>
        </div>
      </div>
      <script src="utils/js/readme.js"></script>
</body>

</html>