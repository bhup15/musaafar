<?php
include ("includes/connection.php");

$url = "http://sandbox.raynatours.com/api/Tour/touroption";

$curl = curl_init($url);

$headers = array(

    "Content-Type: application/json",

    "Authorization: Bearer eyJhbGciOiJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGRzaWctbW9yZSNobWFjLXNoYTI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIwNTY5NWQxMy1lMGY4LTRlZTktYjEwNi1lYTI4NDUwNjg1MmMiLCJVc2VySWQiOiIzNzU0NSIsIlVzZXJUeXBlIjoiQWdlbnQiLCJQYXJlbnRJRCI6IjAiLCJFbWFpbElEIjoiaW5mb0BkaXJoYW1zdHJldGNoZXIuYWUiLCJpc3MiOiJodHRwOi8vc2FuZGJveC5yYXluYXRvdXJzLmNvbSIsImF1ZCI6Imh0dHA6Ly9zYW5kYm94LnJheW5hdG91cnMuY29tIn0.XFF6ZwxbuyXrNzDipAMH99ZVe3b7D08V6FnXoK2NnqQ"

);

$select_country = mysqli_query($con, "SELECT * FROM product");

foreach ($select_country as $key => $value)
{

    $obj = '{

  "tourId": ' . $value['tour_id'] . ',  // tour List api

  "contractId": ' . $value['contract_id'] . ',

  "travelDate": "10/29/2021",

  "NoOfAdult": 1,

  "NoOfChild": 1,

  "NoOfInfant": 1

}';

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_POST, true);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $obj);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

    $resp = curl_exec($curl);

    curl_close($curl);
    /*
     var_dump($resp);*/

    $decoded_json = json_decode($resp);
    /*echo "<pre>";
     print_r($decoded_json);*/

    foreach ($decoded_json as $toption_key => $toption_value)
    {

        foreach ($toption_value as $toption_keys => $toption_values)
        {
            $select_country = mysqli_query($con, "SELECT * FROM tourOptions WHERE tourId = '" . $toption_values->tourId . "'");
            $country = mysqli_fetch_array($select_country);

            if ($country['tourId'] == $toption_values->tourId)
            {

                $update_country = mysqli_query($con, "UPDATE tourOptions SET tourId = '" . $toption_values->tourId . "',transferId = '" . $toption_values->transferId . "',tourOptionId = '" . $toption_values->tourOptionId . "',transferName = '" . $toption_values->transferName . "',adultPrice = '" . $toption_values->adultPrice . "',childPrice = '" . $toption_values->childPrice . "',infantPrice = '" . $toption_values->infantPrice . "',withoutDiscountAmount = '" . $toption_values->withoutDiscountAmount . "',finalAmount = '" . $toption_values->finalAmount . "',startTime = '" . $toption_values->startTime . "',departureTime = '" . $toption_values->departureTime . "',disableChild = '" . $toption_values->disableChild . "',disableInfant = '" . $toption_values->disableInfant . "',allowTodaysBooking = '" . $toption_values->allowTodaysBooking . "',cutOff = '" . $toption_values->cutOff . "',isSlot = '" . $toption_values->isSlot . "',isDefaultTransfer = '" . $toption_values->isDefaultTransfer . "',rateKey = '" . $toption_values->rateKey . "',inventoryId = '" . $toption_values->inventoryId . "',adultBuyingPrice = '" . $toption_values->adultBuyingPrice . "',childBuyingPrice = '" . $toption_values->childBuyingPrice . "',infantBuyingPrice = '" . $toption_values->infantBuyingPrice . "',adultSellingPrice = '" . $toption_values->adultSellingPrice . "',childSellingPrice = '" . $toption_values->childSellingPrice . "',infantSellingPrice = '" . $toption_values->infantSellingPrice . "',companyBuyingPrice = '" . $toption_values->companyBuyingPrice . "',companySellingPrice = '" . $toption_values->companySellingPrice . "',agentBuyingPrice = '" . $toption_values->agentBuyingPrice . "',agentSellingPrice = '" . $toption_values->agentSellingPrice . "',subAgentBuyingPrice = '" . $toption_values->subAgentBuyingPrice . "',subAgentSellingPrice = '" . $toption_values->subAgentSellingPrice . "',finalSellingPrice = '" . $toption_values->finalSellingPrice . "',vatbuying = '" . $toption_values->vatbuying . "',vatselling = '" . $toption_values->vatselling . "',currencyFactor = '" . $toption_values->currencyFactor . "',agentPercentage = '" . $toption_values->agentPercentage . "',transferBuyingPrice = '" . $toption_values->transferBuyingPrice . "',transferSellingPrice = '" . $toption_values->transferSellingPrice . "',serviceBuyingPrice = '" . $toption_values->serviceBuyingPrice . "',serviceSellingPrice = '" . $toption_values->serviceSellingPrice . "',rewardPoints = '" . $toption_values->rewardPoints . "',tourChildAge = '" . $toption_values->tourChildAge . "',maxChildAge = '" . $toption_values->maxChildAge . "',maxInfantAge = '" . $toption_values->maxInfantAge . "',minimumPax = '" . $toption_values->minimumPax . "'  WHERE tourId = '" . $toption_values->tourId . "'");

            }
            else
            {

                $insert_country = mysqli_query($con, "INSERT INTO tourOptions (tourId,transferId,tourOptionId,transferName,adultPrice,childPrice,infantPrice,withoutDiscountAmount,finalAmount,startTime,departureTime,disableChild,disableInfant,allowTodaysBooking,cutOff,isSlot,isDefaultTransfer,rateKey,inventoryId,adultBuyingPrice,childBuyingPrice,infantBuyingPrice,adultSellingPrice,childSellingPrice,infantSellingPrice,companyBuyingPrice,companySellingPrice,agentBuyingPrice,agentSellingPrice,subAgentBuyingPrice,subAgentSellingPrice,finalSellingPrice,vatbuying,vatselling,currencyFactor,agentPercentage,transferBuyingPrice,transferSellingPrice,serviceBuyingPrice,serviceSellingPrice,rewardPoints,tourChildAge,maxChildAge,maxInfantAge,minimumPax) VALUES('" . $toption_values->tourId . "','" . $toption_values->transferId . "','" . $toption_values->tourOptionId . "','" . $toption_values->transferName . "','" . $toption_values->adultPrice . "','" . $toption_values->childPrice . "','" . $toption_values->infantPrice . "','" . $toption_values->withoutDiscountAmount . "','" . $toption_values->finalAmount . "','" . $toption_values->startTime . "','" . $toption_values->departureTime . "','" . $toption_values->disableChild . "','" . $toption_values->disableInfant . "','" . $toption_values->allowTodaysBooking . "','" . $toption_values->cutOff . "','" . $toption_values->isSlot . "','" . $toption_values->isDefaultTransfer . "','" . $toption_values->rateKey . "','" . $toption_values->inventoryId . "','" . $toption_values->adultBuyingPrice . "','" . $toption_values->childBuyingPrice . "','" . $toption_values->infantBuyingPrice . "','" . $toption_values->adultSellingPrice . "','" . $toption_values->childSellingPrice . "','" . $toption_values->infantSellingPrice . "','" . $toption_values->companyBuyingPrice . "','" . $toption_values->companySellingPrice . "','" . $toption_values->agentBuyingPrice . "','" . $toption_values->agentSellingPrice . "','" . $toption_values->subAgentBuyingPrice . "','" . $toption_values->subAgentSellingPrice . "','" . $toption_values->finalSellingPrice . "','" . $toption_values->vatbuying . "','" . $toption_values->vatselling . "','" . $toption_values->currencyFactor . "','" . $toption_values->agentPercentage . "','" . $toption_values->transferBuyingPrice . "','" . $toption_values->transferSellingPrice . "','" . $toption_values->serviceBuyingPrice . "','" . $toption_values->serviceSellingPrice . "','" . $toption_values->rewardPoints . "','" . $toption_values->tourChildAge . "','" . $toption_values->maxChildAge . "','" . $toption_values->maxInfantAge . "','" . $toption_values->minimumPax . "' )");

            }
            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
                 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
            echo '<script>
                         setTimeout(function() {
                            swal({
                             title: "Data Added!",
                             text: "TourOption Data Successfully Added!",
                             type: "success"
                            });
                      }, 1000);
                     </script>';

        }

    }

}

exit;

?>
