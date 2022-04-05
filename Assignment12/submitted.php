<!DOCTYPE html>
<html>
    <head>
        <title>Thanks for Ordering</title>
        <style>
            h1,h2{
                color:gold;
                width:100%;
                text-align:center;
            }
            #banner{
                width:100%;
                background-color:red;
            }
            * {
                font-family:Arial, Helvetica, sans-serif;
            }
        </style>
    </head>
    <body>
        <div id="banner">
            <h1>Jade Delight</h1>
            <h2>Medford's Premiere Chinese Restaurant!</h2>
        </div>
        <h3>Thanks for submitting your order!</h3>
        <div>Order Details:<br>
        <?php
            class MenuItem{
                public $cost;
                public $name;

                function set_name($itemName){
                    $this->name = $itemName;
                }

                function get_name(){
                    return $this->name;
                }

                function set_cost($price){
                    $this->cost = $price;
                }

                function get_cost(){
                    return $this->cost;
                }
            }
            function getSQLData(){
                //https://www.siteground.com/tutorials/php-mysql/connect-database/
                $server = "localhost";
                $username = "uppqrahq9rfbh";
                $password = "r2$2&JdT:4%1";
                $db = "dbz7evmlpwnnyu";

                //Create connection:
                $conn = new mysqli($server, $username, $password, $db);

                //Validate connection
                if ($conn->connect_error){
                    die("Connection failed: " . $conn->connect_error);
                }
                $conn->select_db($db);

                $sql_get_items = "SELECT * FROM items";
                $items = $conn->query($sql_get_items);

                $returnedString = array();
                if ($items->num_rows > 0){
                    while($row = $items->fetch_assoc()){
                        $tempItem = new MenuItem();
                        $tempItem->set_name($row["name"]);
                        $tempItem->set_cost($row["cost"]);
                        $returnedString[] = $tempItem;
                    }
                }
                else{
                    echo "no results";
                }
                return $returnedString;
            }
            
            $menuItems = getSQLData();
            $i = 0;
            foreach($menuItems as $item){
                if ($_REQUEST['quan'.$i] != 0){
                    echo $item->get_name(). ", " . $item->get_cost(). " - quantity: ". $_REQUEST['quan'.$i]."<br>";
                }
                
                $i++;
            }
            echo "<br>";
            echo "Subtotal:" . $_REQUEST['subtotal'] . "<br>";
            echo "Tax: " . $_REQUEST['tax'] . "<br>";
            $orderTotal = "Total: $" . $_REQUEST['total'] . "<br>";
            echo $orderTotal;

            $message = "Thanks for ordering from Jade Delight! \n";
            $message .= "Total: $" . $_REQUEST['total'] . "\n";
            $deliveryInfo = '';
            
            if ($_REQUEST['p_or_d'] == 'delivery'){
                $deliveryInfo .= "Delivery address: <br>" . $_REQUEST['street'] . "<br>";
                $deliveryInfo .= $_REQUEST['city'] . "<br>";
                $message .= "Delivery address: \n" . $_REQUEST['street'] . "\n";
                $message .= $_REQUEST['city'] . "\n";
            }
            else{
                $deliveryInfo .= "Ordered for Pickup <br>";
                $message .= "Ordered for Pickup \n";
            }

            echo $deliveryInfo;
            echo "<br>Expected by: " . $_REQUEST['arrivalEstimate'];
            
            $message .= "Expected by: " . $_REQUEST['arrivalEstimate'];

            $to = "ji.luo@tufts.edu";
            $subject = "Jade Delight Order Receipt";

            mail($to, $subject, $message);
        ?>

    </div>
    </body>
</html>