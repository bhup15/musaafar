<?php
include ("includes/connection.php");

$url = "http://sandbox.raynatours.com/api/Tour/timeslot";

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

  "travelDate": "2021-12-15",

  "transferId": ' . $value['transferId'] . ',

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

    $decoded_json = json_decode($resp);

    foreach ($decoded_json as $timeslot_key => $timeslot_value)
    {

        foreach ($timeslot_value as $timeslot_keys => $timeslot_values)
        {

            $select_timeslot = mysqli_query($con, "SELECT * FROM tour_timeslot WHERE tour_id = '" . $value['tourId'] . "'");
            $timeslot = mysqli_fetch_array($select_timeslot);

            if ($timeslot['tourOptionId'] == $timeslot_values->tourOptionId)
            {

                $update_timeslot = mysqli_query($con, "UPDATE tour_timeslot SET tour_id = '" . $value['tourId'] . "',tourOptionId = '" . $timeslot_values->tourOptionId . "',timeSlotId = '" . $timeslot_values->timeSlotId . "', timeSlot = '" . $timeslot_values->timeSlot . "', available = '" . $timeslot_values->available . "', adultPrice = '" . $timeslot_values->adultPrice . "', childPrice = '" . $timeslot_values->childPrice . "', isDynamicPrice = '" . $timeslot_values->isDynamicPrice . "'   WHERE tour_id = '" . $value['tourId'] . "'");

            }
            else
            {

                $insert_timeslot = mysqli_query($con, "INSERT INTO tour_timeslot (tour_id,tourOptionId,timeSlotId,timeSlot,available,adultPrice,childPrice,isDynamicPrice) VALUES('" . $value['tourId'] . "','" . $timeslot_values->tourOptionId . "','" . $timeslot_values->timeSlotId . "','" . $timeslot_values->timeSlot . "','" . $timeslot_values->available . "','" . $timeslot_values->adultPrice . "','" . $timeslot_values->childPrice . "', '" . $timeslot_values->isDynamicPrice . "')");

            }
            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
                 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
            echo '<script>
                         setTimeout(function() {
                            swal({
                             title: "Data Added!",
                             text: "TimeSlot Data Successfully Added!",
                             type: "success"
                            });
                      }, 1000);
                     </script>';

        }

    }

}

?>
