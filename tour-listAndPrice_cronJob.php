<?php
include ("includes/connection.php");

$url = "http://sandbox.raynatours.com/api/Tour/tourstaticdata";

$curl = curl_init($url);

$headers = array(

    "Content-Type: application/json",

    "Authorization: Bearer eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIwNTY5NWQxMy1lMGY4LTRlZTktYjEwNi1lYTI4NDUwNjg1MmMiLCJVc2VySWQiOiIzNzU0NSIsIlVzZXJUeXBlIjoiQWdlbnQiLCJQYXJlbnRJRCI6IjAiLCJFbWFpbElEIjoiaW5mb0BkaXJoYW1zdHJldGNoZXIuYWUiLCJpc3MiOiJodHRwOi8vc2FuZGJveC5yYXluYXRvdXJzLmNvbSIsImF1ZCI6Imh0dHA6Ly9zYW5kYm94LnJheW5hdG91cnMuY29tIn0.XFF6ZwxbuyXrNzDipAMH99ZVe3b7D08V6FnXoK2NnqQ"

);

$select_country = mysqli_query($con, "SELECT * FROM mu_city");

foreach ($select_country as $key => $value)
{

    $data = '{

  "CountryId": ' . $value['country_code'] . ',

  "CityId": ' . $value['city_code'] . '

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

            $today = date("Y-m-d H:i:s");

            $select_city = mysqli_query($con, "SELECT * FROM product WHERE tour_id = '" . $city_values->tourId . "'");
            $city = mysqli_fetch_array($select_city);

            if ($city['tour_id'] == $city_values->tourId)
            {

                $content = str_replace("'", "''", $city_values->tourShortDescription);
                $update_date = date("Y-m-d H:i:s");
                $update_city = mysqli_query($con, "UPDATE product SET tour_id = '" . $city_values->tourId . "',name = '" . $city_values->tourName . "',image = '" . $city_values->imagePath . "', city_id = '" . $city_values->cityId . "',content= '" . $content . "' ,country_id = '" . $city_values->countryId . "',country_name= '" . $city_values->countryName . "',city_name = '" . $city_values->cityName . "',review_count = '" . $city_values->reviewCount . "',ratting = '" . $city_values->ratting . "',duration = '" . $city_values->duration . "',image_caption_name = '" . $city_values->imageCaptionName . "',city_tour_type_id = '" . $city_values->cityTourTypeId . "',city_tour_type = '" . $city_values->cityTourType . "',cancelation_policy = '" . $city_values->cancellationPolicyName . "',only_child = '" . $city_values->onlyChild . "',contract_id = '" . $city_values->contractId . "',recommended = '" . $city_values->recommended . "' WHERE tour_id = '" . $city_values->tourId . "'");

            }
            else
            {
                $content = str_replace("'", "''", $city_values->tourShortDescription);

                $insert_city = mysqli_query($con, "INSERT INTO product (tour_id,name,image,city_id,content,country_id,country_name,city_name,review_count,ratting,duration,image_caption_name,city_tour_type_id,city_tour_type,cancelation_policy,only_child,contract_id,recommended) VALUES('" . $city_values->tourId . "','" . $city_values->tourName . "','" . $city_values->imagePath . "', '" . $city_values->cityId . "','" . $content . "','" . $city_values->countryId . "','" . $city_values->countryName . "','" . $city_values->cityName . "','" . $city_values->reviewCount . "','" . $city_values->rating . "','" . $city_values->duration . "','" . $city_values->imageCaptionName . "','" . $city_values->cityTourTypeId . "','" . $city_values->cityTourType . "','" . $city_values->cancellationPolicyName . "','" . $city_values->onlyChild . "','" . $city_values->contractId . "','" . $city_values->recommended . "')");

            }

            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
                 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
            echo '<script>
                         setTimeout(function() {
                            swal({
                             title: "Data Added!",
                             text: "Product Data Successfully Added!",
                             type: "success"
                            });
                      }, 1000);
                     </script>';

        }
    }

}

?>
