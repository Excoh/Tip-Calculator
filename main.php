<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Tip Calculator</title>

    <!-- Bootstrap -->
    <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="extra.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <?php // Variable initializiation
      include 'custom_messages.php';
      $totalprice = $discount = $tipval = $customDiscount = $splitTotal = $splitTip = "";
      $totalpriceErr = $discountErr = $tipvalErr = "";
      $validDiscount = $validSplit = $validPrice = false;
      $split = 1;
    ?>

    <div class="container-fluid">

      <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4">

      <?php // Input Validation
        if (isset($_POST['submit']))
        {

          $totalprice = $_POST['totalprice'];
          if (empty($_POST['discount']))
          { // if variables were used, it would be considered as null
            echo $empty_discount;
          }
          else
          { // $discount is not empty

            $discount = $_POST['discount'];
           if ($discount == "custom")
           {

               $customDiscount = $_POST['customDscnt'];
               if (empty($customDiscount))
               {
                  echo $empty_custom_discount;
               }
               else if ($customDiscount > 100)
               {
                 echo "<small>".$discount_too_high."</small>";
               }
               else
               {
                 $customDiscount = floatval($customDiscount);
               }

            }
             $validDiscount = true;

         }

          if (!is_numeric($totalprice) || $totalprice < 0)
          {
            echo $enter_valid_price;
          }
          else
          {
            $tip = $totalprice;
            $validPrice = true;
          }

          if (!empty($_POST['split']))
          {

            if ($_POST['split'] >= 1)
            {
              $split = floatval($_POST['split']);
              $validSplit = true;
            }
            else
            {
              $split = $_POST['split'];
              echo "<p>".$split_error."</p>";
            }

          }

        } // end of if submit
      ?>
      <?php // Calculate Tip

        if ($validDiscount && $validPrice)
        {

          if ($discount == "custom")
          {
            $tip *= $customDiscount/100;
          }
          else if ($discount == .10)
          {
            $tip *= .10;
          }
          else if ($discount == .15)
          {
            $tip *= .15;
          }
          else if ($discount == .20)
          {
            $tip *= .20;
          }
          $finalprice = floatval($totalprice + $tip);
          if ($validSplit)
          {
            $split = floatval($_POST['split']);
            $splitTotal = $finalprice / $split;
            $splitTip = $tip / $split;
          }

        }
      ?>

      <h3> TIP CALCULATOR </h3>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

        <div class="form-inline">
          <div class="form-group">
            <label  for="subtotal">Bill Subtotal: $</label>
            <input type="text" class="form-control" name="totalprice" value="<?php echo $totalprice?>" id="subtotal" placeholder="0.00">
          </div>
        </div>

        <div class="form-group">
          <label>Tip Percentage:</label>
        </div>

        <div class="form-inline">

          <?php
            $value = 0.10;
            for ($i = 0; $i < 3; $i++)
            {
          ?>
            <!-- for loop body -->
              <div class="form-group">
                <input type="radio" name="discount" id="<?php echo $value?>" value="<?php echo $value?>" <?php if (isset($discount) && (round($discount,2) == round($value,2))) echo "checked";?>>
                <label for="<?php echo $value?>" class="radio-inline">
                  <?php echo ($value * 100).'%';?>
                </label>
              </div>
          <?php
              $value += 0.05;
            }
          ?>

        </div>

        <div class="form-inline">
          <div class="form-group">
            <label class="radio-inline" for="custom">Custom: </label>
            <input type="radio" name="discount" value="custom" <?php if (isset($discount) && $discount == "custom") echo "checked";?> id="custom">
            <input type="text" class="form-control" name ="customDscnt" value="<?php echo $customDiscount?>">
          </div>
        </div>

        <p class="no-margin">
          <span>Split: </span>
          <input type="text" class="form-control" name="split" size =5 value="<?php echo $split;?>">
          <span>person(s)</span>
        </p>

        <p>
          <input type="submit" class="btn btn-default btn-no-radius" name="submit" value="Calculate Tip"> 
        </p>

      </form>

      <p class="tip">
        <?php
          if ($validPrice && $validDiscount && $validSplit) {

            echo "Tip: $".number_format($tip,2);
            echo "<br>";
            echo "Total: $".number_format($finalprice,2);
            echo "<br>";

            if ($split > 1) {
              echo "Tip each: $".number_format($splitTip,2);
              echo "<br>";
              echo "Total each: $".number_format($splitTotal,2);
          }

        }
         ?>
      </p>

    </div> <!-- end of middle column -->
    <div class="col-md-4"></div>
  </div>

    </div> <!-- end of container-->

</body>

</html>
