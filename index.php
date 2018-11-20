<?php
require 'vendor/autoload.php';

// File with the list of clients and GST
$fileName = "ClientGST.xlsx";

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileName);

$dataArray = $spreadsheet->getActiveSheet()
    ->rangeToArray(
        'A1:B10',     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        TRUE,
        TRUE,
        TRUE         // Should the array be indexed by cell row and cell column
    );

    foreach ( $dataArray as $data  ) {
        // Identify the PAN number from GST
        $panNumber = substr($data[B], 2, 10);

        // Validate Pan
        $isValidPan = validatePAN($panNumber);

       if ($isValidPan === false) {
           echo "Incorrect PAN found for " . $data[A] . " <br />";
           continue;
       }

       // Validate GST
       $isValidGst = isValidGst($data[B]);

       if ($isValidGst === false) {
           echo "Invalid GST found for " . $data[A] . " <br />";
       }

    }


/**
  * Validate GST
  *
  * @param string $gst  The GST number which you want to validate
  *
  * @return boolean
*/
function isValidGst($gst) {
    $regex = "^([0][1-9]|[1-2][0-9]|[3][0-5])([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$";
    return preg_match($regex, $gst);
}

/**
  * Validate PAN
  *
  * @param string $panNumber  The PAN number which you want to validate
  *
  * @return boolean
*/
function validatePAN($panNumber) {
    $regex = "/^([a-zA-Z]([a-zA-Z]([a-zA-Z]([a-zA-Z]([a-zA-Z]([0-9]([0-9]([0-9]([0-9]([a-zA-Z])?)?)?)?)?)?)?)?)?)?$/";
    return preg_match($regex, $panNumber);
  }
?>
