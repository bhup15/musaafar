<?php
include ("includes/connection.php");

$url = "http://sandbox.raynatours.com/api/Tour/availability";

$curl = curl_init($url);

$headers = array(

    "Content-Type: application/json",

    "Authorization: Bearer eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIwNTY5NWQxMy1lMGY4LTRlZTktYjEwNi1lYTI4NDUwNjg1MmMiLCJVc2VySWQiOiIzNzU0NSIsIlVzZXJUeXBlIjoiQWdlbnQiLCJQYXJlbnRJRCI6IjAiLCJFbWFpbElEIjoiaW5mb0BkaXJoYW1zdHJldGNoZXIuYWUiLCJpc3MiOiJodHRwOi8vc2FuZGJveC5yYXluYXRvdXJzLmNvbSIsImF1ZCI6Imh0dHA6Ly9zYW5kYm94LnJheW5hdG91cnMuY29tIn0.XFF6ZwxbuyXrNzDipAMH99ZVe3b7D08V6FnXoK2NnqQ"

);

$select_country = mysqli_query($con, "SELECT tourOptions.tourId, tourOptions.tourOptionId, tourOptions.transferId, product.contract_id FROM tourOptions INNER JOIN product ON tourOptions.tourId=product.tour_id");

foreach ($select_country as $key => $value)
{

    $obj = '{

  "tourId": ' . $value['tourId'] . ',

  "tourOptionId": ' . $value['tourOptionId'] . ',

  "transferId": ' . $value['transferId'] . ',

  "travelDate": "12/20/2021",

  "adult": 5,

  "child": 2,

  "infant": 0,

  "contractId": ' . $value['contract_id'] . '

}';

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_POST, true);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $obj);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

    $resp = curl_exec($curl);

    curl_close($curl);

    /*var_dump($resp);*/

    $decoded_json = json_decode($resp);
    foreach ($decoded_json as $availibility_key => $availibility_value)
    {

        $select_availibility = mysqli_query($con, "SELECT * FROM availibility WHERE tour_id = '" . $value['tourId'] . "'");
        $availibility_list = mysqli_fetch_array($select_availibility);

        if ($availibility_list['tour_id'] == $value['tourId'])
        {

            $update_availibility = mysqli_query($con, "UPDATE availibility SET status = '" . $availibility_value->status . "',message = '" . $availibility_value->message . "',productType = '" . $availibility_value->productType . "', tour_id = '" . $value['tourId'] . "'   WHERE tour_id = '" . $value['tourId'] . "'");

        }
        else
        {

            $insert_availibility = mysqli_query($con, "INSERT INTO availibility (tour_id,status,message,productType) VALUES('" . $value['tourId'] . "','" . $availibility_value->status . "','" . $availibility_value->message . "','" . $availibility_value->productType . "')");

        }

        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
                 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
        echo '<script>
                         setTimeout(function() {
                            swal({
                             title: "Data Added!",
                             text: "Availibility Data Successfully Added!",
                             type: "success"
                            });
                      }, 1000);
                     </script>';

    }

}

?>
