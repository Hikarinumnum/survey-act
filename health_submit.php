<?php
session_start();

try {
    // Direct connection to your port 3307 database
    $pdo = new PDO("mysql:host=localhost:3307;dbname=survey;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Checkbox arrays to text
        $symptoms = isset($_POST['sym']) ? implode(", ", $_POST['sym']) : '';
        $conditions = isset($_POST['cond']) ? implode(", ", $_POST['cond']) : '';
        $mental = isset($_POST['mental']) ? implode(", ", $_POST['mental']) : '';
        $programs = isset($_POST['prog']) ? implode(", ", $_POST['prog']) : '';

        $sql = "INSERT INTO health_surveys (
            submitted_by, fullname, age, dob, gender, civil_status, barangay, 
            contact_number, occupation, household_members, vaccination_status, 
            vaccine_brand, covid_history, covid_exposure, symptoms, 
            medical_conditions, last_checkup, taking_meds, medications_list, 
            mental_score, mental_issues, bhc_access, exercise_freq, diet_type, 
            smoking_status, alcohol_status, water_source, bhc_satisfaction, 
            needed_programs, health_notes
        ) VALUES (
            :submitted_by, :fullname, :age, :dob, :gender, :civil_status, :barangay, 
            :contact_number, :occupation, :household_members, :vaccination_status, 
            :vaccine_brand, :covid_history, :covid_exposure, :symptoms, 
            :medical_conditions, :last_checkup, :taking_meds, :medications_list, 
            :mental_score, :mental_issues, :bhc_access, :exercise_freq, :diet_type, 
            :smoking_status, :alcohol_status, :water_source, :bhc_satisfaction, 
            :needed_programs, :health_notes
        )";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':submitted_by' => $_POST['user_id'] ?? 'Guest',
            ':fullname' => $_POST['fullname'] ?? '',
            ':age' => $_POST['age'] ?? null,
            ':dob' => $_POST['dob'] ?? null,
            ':gender' => $_POST['gender'] ?? '',
            ':civil_status' => $_POST['civil'] ?? '',
            ':barangay' => $_POST['purok'] ?? '',
            ':contact_number' => $_POST['contact'] ?? '',
            ':occupation' => $_POST['occup'] ?? '',
            ':household_members' => $_POST['household'] ?? null,
            ':vaccination_status' => $_POST['vaccination'] ?? '',
            ':vaccine_brand' => $_POST['vaccine'] ?? '',
            ':covid_history' => $_POST['covidHist'] ?? '',
            ':covid_exposure' => $_POST['exposure'] ?? '',
            ':symptoms' => $symptoms,
            ':medical_conditions' => $conditions,
            ':last_checkup' => $_POST['lastChk'] ?? null,
            ':taking_meds' => $_POST['meds'] ?? '',
            ':medications_list' => $_POST['medList'] ?? '',
            ':mental_score' => $_POST['mentalScore'] ?? null,
            ':mental_issues' => $mental,
            ':bhc_access' => $_POST['bhcAccess'] ?? '',
            ':exercise_freq' => $_POST['exercise'] ?? '',
            ':diet_type' => $_POST['diet'] ?? '',
            ':smoking_status' => $_POST['smoke'] ?? '',
            ':alcohol_status' => $_POST['alc'] ?? '',
            ':water_source' => $_POST['water'] ?? '',
            ':bhc_satisfaction' => $_POST['bhcSat'] ?? null,
            ':needed_programs' => $programs,
            ':health_notes' => $_POST['healthNotes'] ?? ''
        ]);

        header("Location: index.html?status=health_submitted");
        exit();

    }
} catch (PDOException $e) {
    // This will print the EXACT reason if it fails
    die("Database Error: " . $e->getMessage());
}
?>