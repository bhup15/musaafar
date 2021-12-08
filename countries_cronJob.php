<?php
include ("includes/connection.php");

$url = "http://sandbox.raynatours.com/api/Tour/countries";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-type: application/json"
));

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIwNTY5NWQxMy1lMGY4LTRlZTktYjEwNi1lYTI4NDUwNjg1MmMiLCJVc2VySWQiOiIzNzU0NSIsIlVzZXJUeXBlIjoiQWdlbnQiLCJQYXJlbnRJRCI6IjAiLCJFbWFpbElEIjoiaW5mb0BkaXJoYW1zdHJldGNoZXIuYWUiLCJpc3MiOiJodHRwOi8vc2FuZGJveC5yYXluYXRvdXJzLmNvbSIsImF1ZCI6Imh0dHA6Ly9zYW5kYm94LnJheW5hdG91cnMuY29tIn0.XFF6ZwxbuyXrNzDipAMH99ZVe3b7D08V6FnXoK2NnqQ"
));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);

curl_close($ch);

$decoded_json = json_decode($response);

/*
print_r($decoded_json);
*/
foreach ($decoded_json as $country_key => $country_value)
{

    foreach ($country_value as $country_keys => $country_values)
    {

        $today = date("Y-m-d H:i:s");

        $select_country = mysqli_query($con, "SELECT * FROM country WHERE name = '" . $country_values->countryName . "'");
        $country = mysqli_fetch_array($select_country);

        if ($country['country_code'] == $country_values->countryId)
        {

            $update_date = date("Y-m-d H:i:s");

            $update_country = mysqli_query($con, "UPDATE country SET country_code = '" . $country_values->countryId . "',name = '" . $country_values->countryName . "',url = '" . $country_values->countryName . "',update_at = '" . $update_date . "'  WHERE country_code = '" . $country_values->countryId . "'");

        }
        else
        {

            $insert_country = mysqli_query($con, "INSERT INTO country (country_code,name,url,created_at,status) VALUES('" . $country_values->countryId . "','" . $country_values->countryName . "','" . $country_values->countryName . "','" . $today . "', 0 )");

        }

        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
                 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
        echo '<script>
                         setTimeout(function() {
                            swal({
                             title: "Data Added!",
                             text: "Country Data Successfully Added!",
                             type: "success"
                            });
                      }, 1000);
                     </script>';

    }
}

