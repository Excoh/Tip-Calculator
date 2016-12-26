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
      $totalprice = $tip = $tipAmount = $customDiscount = $splitTotal = $splitTip = "";
      $totalpriceErr = $tipErr = $customDiscountErr = $splitErr = "";
      $validTip = $validSplit = $validPrice = false;
      $split = 1;
    ?>

    <div class="container">

      <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4 thin-border no-padding">

      <?php // Input Validation
        if (isset($_POST['submit']))
        {

          $totalprice = $_POST['totalprice'];
          if (empty($_POST['discount']))
          {
            $tipErr = $empty_discount;
          }
          else
          { // $tip is not empty

            $tip = $_POST['discount'];
            $validTip = true;

           if ($tip == "custom")
           {

               $customDiscount = $_POST['customDscnt'];
               if (is_numeric($customDiscount))
               {
                 $customDiscount = floatval($customDiscount);

                 if ($customDiscount > 100)
                 {
                    $customDiscountErr = $tip_too_high;
                    $validTip = false;
                 }
                 else if ($customDiscount < 0)
                 {
                   $customDiscountErr = $wrong_custom_tip;
                   $validTip = false;
                 }
                 else if ($customDiscount == 0)
                 {
                   $customDiscountErr = $zero_tip;
                 }
               }
               else if (empty($customDiscount) || !is_numeric($customDiscount))
               {
                  $customDiscountErr = $empty_custom_discount;
                  $validTip = false;
               }

            }

         } // end of discount check else

          if (!is_numeric($totalprice) || $totalprice < 0 || empty($totalprice))
          {
            $totalpriceErr = $enter_valid_price;
          }
          else
          {
            $tipAmount = $totalprice;
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
              $splitErr = $split_error;
            }

          }

        } // end of if submit
      ?>
      <?php // Calculate Tip

        if ($validTip && $validPrice)
        {

          if ($tip == "custom")
          {
            $tipAmount *= $customDiscount/100;
          }
          else if ($tip == .10)
          {
            $tipAmount *= .10;
          }
          else if ($tip == .15)
          {
            $tipAmount *= .15;
          }
          else if ($tip == .20)
          {
            $tipAmount *= .20;
          }
          $finalprice = floatval($totalprice) + floatval($tipAmount);

          if ($validSplit)
          {
            $split = floatval($_POST['split']);
            $splitTotal = $finalprice / $split;
            $splitTip = $tipAmount / $split;
          }

        }
      ?>

      <h2 class="hx-center no-margin bot-padding-10 top-padding-10 bot-border">TIP CALCULATOR</h2>

      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

          <div class="form-group no-margin bot-padding bot-border">
            <label for="subtotal" class="no-margin bot-padding top-padding-1">
              <span class="h4 hx-center">Bill Subtotal</span>
            </label>
            <div class="form-inline">
              <label for="subtotal">$</label>
              <input type="text" class="form-control <?php if (!$validPrice && isset($_POST['submit'])) echo 'input-red'?>" name="totalprice" value="<?php echo $totalprice?>" id="subtotal" placeholder="0.00">
              <p class="no-margin no-padding error-msg"><?php echo $totalpriceErr; ?></p>
            </div>
          </div>

        <div class="form-group no-margin bot-padding">
        <label class="no-margin bot-padding top-padding-1">
          <span class="h4 hx-center">Tip Percentage</span>
        </label>

        <div class="form-inline">
          <?php
            $value = 0.10;
            for ($i = 0; $i < 3; $i++)
            {
          ?>
            <!-- for loop body -->
              <div class="form-group col-sm-4 col-md-4 no-left-padding bot-padding">
                <input type="radio" name="discount" id="<?php echo $value?>" value="<?php echo $value?>" <?php if (isset($tip) && (round($tip,2) == round($value,2))) echo "checked";?>>
                <label for="<?php echo $value?>" class="radio-inline">
                  <?php echo ($value * 100).'%';?>
                </label>
              </div>
          <?php
              $value += 0.05;
            }
          ?>

        </div>

        <div class="form-inline bot-padding bot-border">
          <div class="form-group">
              <label for="custom">Custom: </label>
              <input type="radio" name="discount" value="custom" <?php if (isset($tip) && $tip == "custom") echo "checked";?>  id="custom">
            <div class="form-group">
              <input type="text" class="form-control <?php if ( ($tip=='custom' && (!isset($_POST['customDscnt']) || !$validTip)) && isset($_POST['submit']) ) echo 'input-red'?>" name ="customDscnt" value="<?php echo $customDiscount?>" placeholder="0">
              <label>%</label>
            </div>
          </div>
          <p class="no-margin no-padding error-msg"><?php echo $tipErr; ?></p>
          <p class="no-margin no-padding error-msg"><?php echo $customDiscountErr; ?></p>
        </div>
      </div><!-- TIP PERCENTAGE CHUNK -->

        <div class="form-group no-margin bot-padding">
          <label for="split" class="no-margin bot-padding">
            <span class="h4">Split</span>
          </label>

          <div class="form-inline no-margin bot-padding">
            <div class="form-group">
              <input type="text" class="form-control <?php if (!$validSplit && isset($_POST['submit'])) echo 'input-red'?>" name="split" id="split" value="<?php echo $split;?>" placeholder="1">
              <label>person(s)</label>
            </div>
            <p class="no-margin no-padding error-msg"><?php echo $splitErr ?></p>
          </div>

        </div>

        <div class="form-group no-margin bot-padding">
          <input type="submit" class="btn btn-default btn-no-radius center-block" name="submit" value="Calculate Tip">
        </div>

      </form>

      <p class="tip">
        <?php
          if ($validPrice && $validTip && $validSplit) {

            echo "Tip: $".number_format($tipAmount,2);
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
