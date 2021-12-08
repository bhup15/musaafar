<?php
include ("includes/connection.php");

$url = "http://sandbox.raynatours.com/api/Tour/tourlist";

$curl = curl_init($url);

$headers = array(

    "Content-Type: application/json",

    "Authorization: Bearer eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIwNTY5NWQxMy1lMGY4LTRlZTktYjEwNi1lYTI4NDUwNjg1MmMiLCJVc2VySWQiOiIzNzU0NSIsIlVzZXJUeXBlIjoiQWdlbnQiLCJQYXJlbnRJRCI6IjAiLCJFbWFpbElEIjoiaW5mb0BkaXJoYW1zdHJldGNoZXIuYWUiLCJpc3MiOiJodHRwOi8vc2FuZGJveC5yYXluYXRvdXJzLmNvbSIsImF1ZCI6Imh0dHA6Ly9zYW5kYm94LnJheW5hdG91cnMuY29tIn0.XFF6ZwxbuyXrNzDipAMH99ZVe3b7D08V6FnXoK2NnqQ"

);

$select_country = mysqli_query($con, "SELECT country_code, city_code FROM mu_city ");

foreach ($select_country as $key => $value)
{
    $data = ' 

{     

  "countryId": ' . $value['country_code'] . ',

  "cityId": ' . $value['city_code'] . ',

  "travelDate": "12/30/2021"

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
 
    foreach ($decoded_json as $tlist_key => $tlist_value)
    {

        foreach ($tlist_value as $tlist_keys => $tlist_values)
        {

            $today = date("Y-m-d H:i:s");
            

            $select_city = mysqli_query($con, "SELECT * FROM tour_list WHERE tourId = '" . $tlist_values->tourId . "'");
            $list = mysqli_fetch_array($select_city);

            if ($list['tourId'] == $tlist_values->tourId)
            {

                $update_date = date("Y-m-d H:i:s");
                $update_city = mysqli_query($con, "UPDATE tour_list SET tourId = '" . $tlist_values->tourId . "',contractId = '" . $tlist_values->contractId . "',amount = '" . $tlist_values->amount . "', discount = '" . $tlist_values->discount . "', rewardPoints = '" . $tlist_values->rewardPoints . "', sortOrder = '" . $tlist_values->sortOrder . "', country_code = '" . $value['country_code'] . "', city_code = '" . $value['city_code'] . "'   WHERE tourId = '" . $tlist_values->tourId . "'");

            }
            else
            {

                $insert_city = mysqli_query($con, "INSERT INTO tour_list (tourId,contractId,amount,discount,sortOrder,rewardPoints,country_code,city_code) VALUES('" . $tlist_values->tourId . "','" . $tlist_values->contractId . "','" . $tlist_values->amount . "','" . $tlist_values->discount . "','" . $tlist_values->sortOrder . "','" . $tlist_values->rewardPoints . "','" . $value['country_code'] . "', '" . $value['city_code'] . "')");
 
            }

            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
                 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
            echo '<script>
                         setTimeout(function() {
                            swal({
                             title: "Data Added!",
                             text: "TourList Data Successfully Added!",
                             type: "success"
                            });
                      }, 1000);
                     </script>';

        }
    }

}

?>
