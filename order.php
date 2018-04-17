<!DOCTYPE HTML PUBLIC "-//w3c//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<!-- order.php - Processes the form described in index.html -->
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title> Process the index.html form </title>
    </head>
    
    <body>

    <?php
      // Get form data values
      $customerName = $_POST["customer"];
      $nApples = $_POST["apples"];
      $nOranges = $_POST["oranges"];
      $nBananas = $_POST["bananas"];
      $total = $_POST["total"];
      $paymentType = $_POST["payment"];

      ########## Get data from order.txt ###########
      $filename = 'order.txt';
      
      // Retrieve data from order.txt
      if(file_exists($filename)){
        $file = fopen($filename, 'r+');
			  $file_lines[] = file($file);
      } else $file = fopen($filename, 'w+');

      // Get current tally of ordered fruits
      $applesOrdered = substr($file_lines[0], 24);
		  $orangesOrdered = substr($file_lines[1], 25);
      $bananasOredered = substr($file_lines[2], 25);
      
      // Update tally of ordered fruits
      $applesOrdered += $nApples;
      $orangesOrdered += $nOranges;
      $bananasOredered += $nBananas;

      // Lock the file to prevent other processes from accessing the file
      if (flock($file, LOCK_EX)) {
        // Write to file
        fwrite($file, "Total number of apples: ".$applesOrdered."\r\n");
        fwrite($file, "Total number of oranges: ".$orangesOrdered."\r\n");
        fwrite($file, "Total number of bananas: ".$bananasOredered."\r\n");
        flock($file, LOCK_UN);  //Unlock the file
      } else {
          die("Couldn't get the lock!");
      }       

      // Close the file!
      fclose($file);


    ?>
    <h2>Order Information</h2>
    <h4> Customer: <?php print $customerName;?></h4>
    
    <p /> <p />

    <table border = "border">
      <caption> Order Information </caption>
      <tr>
        <th> Produce </th>
        <th> Unit Price ($SGD) </th>
        <th> Quantity Ordered </th>
        <th> Item Cost </th>
      </tr>
      <tr>
        <td> Apples </td>
        <td> $0.69 </td>
        <td> <?php print "$nApples"; ?> </td>
        <td> <?php printf ("$ %4.2f", ($nApples * 0.69)); ?></td>
      </tr>
      <tr>
        <td> Oranges </td>
        <td> $0.59 </td>
        <td> <?php print "$nOranges"; ?> </td>
        <td> <?php printf ("$ %4.2f", ($nOranges * 0.59)); ?></td>
        </tr>
      <tr>
        <td> Bananas </td>
        <td> $0.39 </td>
        <td> <?php print "$nBananas"; ?> </td>
        <td> <?php printf ("$ %4.2f", ($nBananas * 0.39)); ?></td>
      </tr>
    </table>
    
    <p /> <p />

    <?php
      printf ("You ordered %d fruit items <br />", $nApples + $nBananas + $nOranges);
      printf ("Your total bill is: $%5.2f <br />", $total);
      print ("Your chosen method of payment is: $paymentType <br />");
    ?>

    </body>
</html>