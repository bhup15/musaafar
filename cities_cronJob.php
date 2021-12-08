<?php
include ("includes/connection.php");

$url = "http://sandbox.raynatours.com/api/Tour/cities";

$curl = curl_init($url);

$headers = array(

    "Content-Type: application/json",

    "Authorization: Bearer eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIwNTY5NWQxMy1lMGY4LTRlZTktYjEwNi1lYTI4NDUwNjg1MmMiLCJVc2VySWQiOiIzNzU0NSIsIlVzZXJUeXBlIjoiQWdlbnQiLCJQYXJlbnRJRCI6IjAiLCJFbWFpbElEIjoiaW5mb0BkaXJoYW1zdHJldGNoZXIuYWUiLCJpc3MiOiJodHRwOi8vc2FuZGJveC5yYXluYXRvdXJzLmNvbSIsImF1ZCI6Imh0dHA6Ly9zYW5kYm94LnJheW5hdG91cnMuY29tIn0.XFF6ZwxbuyXrNzDipAMH99ZVe3b7D08V6FnXoK2NnqQ"

);

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_POST, true);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$select_country = mysqli_query($con, "SELECT * FROM country");

foreach ($select_country as $key => $value)
{

    $data = '{"CountryId":' . $value['country_code'] . '}';

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    //for debug only!
    //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

    $resp = curl_exec($curl);

    curl_close($curl);
    /*
     var_dump($resp);*/

    $decoded_json = json_decode($resp);

    foreach ($decoded_json as $city_key => $city_value)
    {

        foreach ($city_value as $city_keys => $city_values)
        {

            $select_city = mysqli_query($con, "SELECT * FROM city WHERE city_code = '" . $city_values->cityId . "'");
            $city = mysqli_fetch_array($select_city);

            if ($city['city_code'] == $city_values->cityId)
            {

                $update_date = date("Y-m-d H:i:s");

                $update_city = mysqli_query($con, "UPDATE city SET city_code = '" . $city_values->cityId . "',name = '" . $city_values->cityName . "',url = '" . $city_values->cityName . "',country_code  = '" . $value['country_code'] . "', updated_at = '" . $update_date . "' WHERE city_code = '" . $city_values->cityId . "'");

            }
            else
            {
                $today = date("Y-m-d H:i:s");

                $insert_city = mysqli_query($con, "INSERT INTO city (city_code,name,url,country_code,created_at,status) VALUES('" . $city_values->cityId . "','" . $city_values->cityName . "','" . $city_values->cityName . "','" . $value['country_code'] . "','" . $today . "', 0)");

            }

            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
                 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
            echo '<script>
                         setTimeout(function() {
                            swal({
                             title: "Data Added Successfully!",
                             text: "Cities Successfully inserted!",
                             type: "success"
                            });
                      }, 1000);
                     </script>';

        }
    }

}

?>
