<?php
session_start();

try {
    // Direct connection to your port 3307 database
    $pdo = new PDO("mysql:host=localhost:3307;dbname=survey;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Convert checkboxes to text
        $info_source = isset($_POST['source']) ? implode(", ", $_POST['source']) : '';
        $amenities = isset($_POST['amen']) ? implode(", ", $_POST['amen']) : '';
        $amen_improve = isset($_POST['improve']) ? implode(", ", $_POST['improve']) : '';

        $sql = "INSERT INTO tourist_form (
            SubmittedBy, Nationality, AgeGroup, VisitDate, GroupSize, VisitorType, 
            PurposeOfVisit, InfoSource, LanguagePreference, SpotCategory, 
            SpecificPlaceName, DurationOfVisit, EstimatedSpending, Transportation, 
            RatingCleanliness, RatingSafety, RatingAccessibility, RatingFriendliness, 
            RatingValueForMoney, RatingOverall, AmenitiesAvailable, AmenitiesToImprove, 
            WouldRecommend, WouldVisitAgain, EnjoyedMost, NeedsImprovement, OtherFeedback
        ) VALUES (
            :SubmittedBy, :Nationality, :AgeGroup, :VisitDate, :GroupSize, :VisitorType, 
            :PurposeOfVisit, :InfoSource, :LanguagePreference, :SpotCategory, 
            :SpecificPlaceName, :DurationOfVisit, :EstimatedSpending, :Transportation, 
            :RatingCleanliness, :RatingSafety, :RatingAccessibility, :RatingFriendliness, 
            :RatingValueForMoney, :RatingOverall, :AmenitiesAvailable, :AmenitiesToImprove, 
            :WouldRecommend, :WouldVisitAgain, :EnjoyedMost, :NeedsImprovement, :OtherFeedback
        )";

        $stmt = $pdo->prepare($sql);

           $stmt->execute([
            ':SubmittedBy' => $_SESSION['fname'] ?? 'Guest',
            ':Nationality' => $_POST['nat'] ?? '',
            ':AgeGroup' => $_POST['ageGrp'] ?? '',
            ':VisitDate' => $_POST['visitDate'] ?? null,
            ':GroupSize' => $_POST['groupSize'] ?? null,
            ':VisitorType' => $_POST['visType'] ?? '',
            ':PurposeOfVisit' => $_POST['purpose'] ?? '',
            ':InfoSource' => $info_source,
            ':LanguagePreference' => $_POST['lang_pref'] ?? 'EN',
            ':SpotCategory' => $_POST['spot'] ?? '',
            ':SpecificPlaceName' => $_POST['spotName'] ?? '',
            ':DurationOfVisit' => $_POST['duration'] ?? '',
            ':EstimatedSpending' => $_POST['spend'] ?? '',
            ':Transportation' => $_POST['transport'] ?? '',
            ':RatingCleanliness' => $_POST['rClean'] ?? null,
            ':RatingSafety' => $_POST['rSafe'] ?? null,
            ':RatingAccessibility' => $_POST['rAccess'] ?? null,
            ':RatingFriendliness' => $_POST['rStaff'] ?? null,
            ':RatingValueForMoney' => $_POST['rValue'] ?? null,
            ':RatingOverall' => $_POST['rOverall'] ?? null,
            ':AmenitiesAvailable' => $amenities,
            ':AmenitiesToImprove' => $amen_improve,
            ':WouldRecommend' => $_POST['recommend'] ?? '',
            ':WouldVisitAgain' => $_POST['revisit'] ?? '',
            ':EnjoyedMost' => $_POST['enjoyed'] ?? '',
            ':NeedsImprovement' => $_POST['improvement'] ?? '',
            ':OtherFeedback' => $_POST['other'] ?? ''
        ]);

        header("Location: index.html?status=tourism_submitted");
        exit();

    }
} catch (PDOException $e) {
    // Will tell us exactly what's wrong if it fails
    die("Database Error: " . $e->getMessage());
}
?>