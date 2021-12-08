<?php
include ("includes/connection.php");

$url = "http://sandbox.raynatours.com/api/Tour/tourStaticDataById";

$curl = curl_init($url);

$headers = array(

    "Content-Type: application/json",

    "Authorization: Bearer eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIwNTY5NWQxMy1lMGY4LTRlZTktYjEwNi1lYTI4NDUwNjg1MmMiLCJVc2VySWQiOiIzNzU0NSIsIlVzZXJUeXBlIjoiQWdlbnQiLCJQYXJlbnRJRCI6IjAiLCJFbWFpbElEIjoiaW5mb0BkaXJoYW1zdHJldGNoZXIuYWUiLCJpc3MiOiJodHRwOi8vc2FuZGJveC5yYXluYXRvdXJzLmNvbSIsImF1ZCI6Imh0dHA6Ly9zYW5kYm94LnJheW5hdG91cnMuY29tIn0.XFF6ZwxbuyXrNzDipAMH99ZVe3b7D08V6FnXoK2NnqQ"

);

$select_tour_data = mysqli_query($con, "SELECT * FROM product");

foreach ($select_tour_data as $key => $value)
{

    $data = '{

  "CountryId": ' . $value['country_id'] . ',

  "CityId": ' . $value['city_id'] . ',

  "tourId": ' . $value['tour_id'] . ',

  "contractId": ' . $value['contract_id'] . ',

  "travelDate": "12/15/2021"



}';

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_POST, true);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

    $resp = curl_exec($curl);

    curl_close($curl);

    $decoded_json = json_decode($resp);

    foreach ($decoded_json as $city_key => $city_value)
    {

        foreach ($city_value as $city_keys => $city_values)
        {

            // $today = date("Y-m-d H:i:s");
            echo "<pre>";

            $select_detail = mysqli_query($con, "SELECT * FROM product_detail WHERE tourId = '" . $city_values->tourId . "'");
            $city = mysqli_fetch_array($select_detail);

            if ($city['tourId'] == $city_values->tourId)
            {

                $tour_description = str_replace("'", "''", $city_values->tourDescription);
                $tour_short_description = str_replace("'", "''", $city_values->tourShortDescription);
                $importantInformation = str_replace("'", "''", $city_values->importantInformation);
                $usefulInformation = str_replace("'", "''", $city_values->usefulInformation);

                $update_date = date("Y-m-d H:i:s");

                $update_city = mysqli_query($con, "UPDATE product_detail SET tourId = '" . $city_values->tourId . "',countryId = '" . $city_values->countryId . "',countryName = '" . $city_values->countryName . "',cityId = '" . $city_values->cityId . "',cityName = '" . $city_values->cityName . "',tourName = '" . $city_values->tourName . "',duration = '" . $city_values->duration . "',departurePoint = '" . $city_values->departurePoint . "', reportingTime = '" . $city_values->reportingTime . "',tourLanguage = '" . $city_values->tourLanguage . "',content= '" . $content . "' ,imagePath = '" . $city_values->imagePath . "',imageCaptionName= '" . $city_values->imageCaptionName . "',cityTourTypeId = '" . $city_values->cityTourTypeId . "',cityTourType = '" . $city_values->cityTourType . "',tourDescription = '" . $tour_description . "',tour_short_description = '" . $tour_short_description . "',importantInformation = '" . $importantInformation . "',itenararyDescription = '" . $city_values->itenararyDescription . "',usefulInformation = '" . $city_values->usefulInformation . "',cancellationPolicyName = '" . $city_values->cancellationPolicyName . "',cancellationPolicyDescription = '" . $city_values->cancellationPolicyDescription . "',childCancellationPolicyName = '" . $city_values->childCancellationPolicyName . "',childCancellationPolicyDescription = '" . $city_values->childCancellationPolicyDescription . "',childAge = '" . $city_values->childAge . "',infantAge = '" . $city_values->infantAge . "',contractId = '" . $city_values->contractId . "',latitude = '" . $city_values->latitude . "',longitude = '" . $city_values->longitude . "',startTime = '" . $city_values->startTime . "',meal = '" . $city_values->meal . "',isFrontImage = '" . $city_values->isFrontImage . "',isBannerImage = '" . $city_values->isBannerImage . "' WHERE tourId = '" . $city_values->tourId . "'");

            }
            else
            {
                $tour_description = str_replace("'", "''", $city_values->tourDescription);
                $tour_short_description = str_replace("'", "''", $city_values->tourShortDescription);
                $importantInformation = str_replace("'", "''", $city_values->importantInformation);
                $usefulInformation = str_replace("'", "''", $city_values->usefulInformation);

                $insert_city = mysqli_query($con, "INSERT INTO product_detail (tourId,countryId,countryName,cityId,cityName,tourName,reviewCount,rating,duration,departurePoint,reportingTime,tourLanguage,imagePath,imageCaptionName,cityTourTypeId,cityTourType,tourDescription,tour_short_description,tourInclusion,importantInformation,itenararyDescription,usefulInformation,cancellationPolicyName,cancellationPolicyDescription,childCancellationPolicyName,childCancellationPolicyDescription,childAge,infantAge,contractId,latitude,longitude,startTime,isFrontImage,isBannerImage,isBannerRotateImage) VALUES('" . $city_values->tourId . "','" . $city_values->countryId . "','" . $city_values->countryName . "', '" . $city_values->cityId . "','" . $city_values->cityName . "','" . $city_values->tourName . "','" . $city_values->reviewCount . "','" . $city_values->rating . "','" . $city_values->duration . "','" . $city_values->departurePoint . "','" . $city_values->reportingTime . "','" . $city_values->tourLanguage . "','" . $city_values->imagePath . "','" . $city_values->imageCaptionName . "','" . $city_values->cityTourTypeId . "','" . $city_values->cityTourType . "','" . $tour_description . "','" . $tour_short_description . "','" . $city_values->tourInclusion . "','" . $importantInformation . "','" . $city_values->itenararyDescription . "','" . $usefulInformation . "','" . $city_values->cancellationPolicyName . "','" . $city_values->cancellationPolicyDescription . "','" . $city_values->childCancellationPolicyName . "','" . $city_values->childCancellationPolicyDescription . "','" . $city_values->childAge . "','" . $city_values->infantAge . "','" . $city_values->contractId . "','" . $city_values->latitude . "','" . $city_values->longitude . "','" . $city_values->startTime . "','" . $city_values->isFrontImage . "','" . $city_values->isBannerImage . "','" . $city_values->isBannerRotateImage . "')");

            
            }

            foreach ($city_values->tourImages as $image_key => $image_value)
            {

                $select_images = mysqli_query($con, "SELECT * FROM tour_images WHERE tour_id = '" . $image_value->tourId . "'");
                $images = mysqli_fetch_array($select_images);

                if ($images['tourId'] == $image_value->tourId)
                {
                    $update_images = mysqli_query($con, "UPDATE tour_images SET tour_id = '" . $image_value->tourId . "',imagePath = '" . $image_value->imagePath . "',imageCaptionName = '" . $image_value->imageCaptionName . "',isFrontImage = '" . $image_value->isFrontImage . "',isBannerImage = '" . $image_value->isBannerImage . "',isBannerRotateImage = '" . $image_value->isBannerRotateImage . "' WHERE tour_id = '" . $image_value->tourId . "'");
                }
                else
                {

                    $insert_image = mysqli_query($con, "INSERT INTO tour_images (tour_id,imagePath,imageCaptionName,isFrontImage,isBannerImage,isBannerRotateImage) VALUES('" . $image_value->tourId . "','" . $image_value->imagePath . "','" . $image_value->imageCaptionName . "', '" . $image_value->isFrontImage . "','" . $image_value->isBannerImage . "','" . $image_value->isBannerRotateImage . "')");
                }

            }

            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
                 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
            echo '<script>
                         setTimeout(function() {
                            swal({
                             title: "Data Added!",
                             text: "Tour Data Successfully Added!",
                             type: "success"
                            });
                      }, 1000);
                     </script>';

        }
    }

}

?>
