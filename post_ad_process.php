<?php
session_start();
header('Content-Type: application/json');
include 'db.php';
$conn = connect();


//     if ($_SERVER["REQUEST_METHOD"] == "POST")
//     {

      $petID = !empty($_POST['pet_ad'])? test_user_input(($_POST['pet_ad'])): null;
      $adTitle = !empty($_POST['ad_title'])? test_user_input(($_POST['ad_title'])): null;
      $descript = !empty($_POST['description'])? test_user_input(($_POST['description'])): null;
      $location = !empty($_POST['location'])? test_user_input(($_POST['location'])): null;
      $booking = !empty($_POST['booking_type'])? test_user_input(($_POST['booking_type'])): null;
      $price = !empty($_POST['price'])? test_user_input(($_POST['price'])): null;
      $radios = !empty($_POST['optionsRadios'])? test_user_input(($_POST['optionsRadios'])): null;

    	try
    	{

        if(isset($_POST['ad_button'])){

              $currentAd = "SELECT * FROM tblad WHERE petID = '$petID';";
              $stmt = $conn->prepare($currentAd);
              $stmt->execute();
              $result = $stmt->fetch(PDO::FETCH_ASSOC);


              if($stmt->rowCount() > 0){

                echo json_encode(Array('petAd'=>false));
                exit();
              }else{

              $insert_ad =
                 "INSERT INTO tblad (adTitle, adDescription, location, petPrice, priceType, bookingType, updateAdDT, petID) VALUES ('$adTitle', '$descript', '$location', '$price', '$radios', '$booking', SYSDATE(), '$petID');";

              $stmt = $conn->prepare($insert_ad);
              $stmt->execute();

              $adID = $conn->lastinsertid();

              // UPDATE AD IMAGES adID
              $updateAdImgs = "UPDATE tbladimages
              SET adID = '$adID'
              WHERE adID = :aid;";
              $stmt = $conn->prepare($updateAdImgs);

              $stmt->bindParam(':aid', test_user_input($petID), PDO::PARAM_INT);
              $stmt->execute();

              echo json_encode(Array('petAd'=>true));
              exit();

            }
        }

    	}
    		catch(PDOException $e)
    		{
    			echo "Error: ".$e -> getMessage();
    			die();
    		}
//     }

?>
