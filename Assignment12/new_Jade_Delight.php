<!doctype html>
<html>
    <head>
        <meta charset = "uft-8">
        <meta name='viewport' content="width=device-width, initial-scale=1">
        <title>Jade Delight II</title>
        <style>
            label{
                display:inline-block;
                width:100px;
                text-align:left;
            }
            .address{
                display:none;
            }
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
        <!-- Modified Jade Delight I Code -->
        <script src="https://code.jquery.com/jquery-3.6.0.js" 
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" 
            crossorigin="anonymous"></script>
        <script>
            //document.forms[0].elements[KEY].value to access items 
            //Key:for elements, 0 is fname; 1 lname; 2 street; 3 city; 4 phone; 5 radio left, 6 radio right
            //7 select row 0,8 cost row0...;17 is sub; 18 tax; 19 total; 20 is submit
            lNameIdx = 1;
            streetIdx = 2;
            cityIdx = 3;
            pickupIdx = 5;
            deliveryIdx = 6;
            colOffset = 7;//First item select, every odd 
            rowOffset = 8;//First item cost, every even
            subtotalIdx = 17;
            taxIdx = 18;
            totalIdx = 19;
            submitIdx = 20;
            numItems=0;
            
            $(document).ready(function(){
                numItems = document.getElementById("itemTable").rows.length -1;
                //When a user selects a quanitity 
                $(".selectQuantity").change(function(){
                    for (i=0; i< numItems; i++){
                        updateCost(i);
                    }
                    updateTotalSection();
                });
                
            });
        
            window.onload = function(){
                //show/hide address fields 
                document.forms[0].elements[pickupIdx].onclick = function(){
                    $(".address").css("display","none")//pickup
                }
                document.forms[0].elements[deliveryIdx].onclick = function(){
                    $(".address").css("display","block")//delivery
                }

                //Validate form 
            }

            
            function updateCost(selectIndex){
                totalCostIndex = rowOffset + (2*selectIndex); 
                if(parseInt(document.forms[0]["quan"+selectIndex].value) == 0){
                    document.forms[0].elements[totalCostIndex].value = "0.00";
                }
                else{
                    cost = document.getElementsByClassName("cost")[selectIndex].innerHTML;
                    cost = parseFloat(cost.substr(1));
                    cost = cost * document.forms[0]["quan"+selectIndex].value;
                    document.forms[0].elements[totalCostIndex].value = cost.toFixed(2);
                }
            }

            function updateTotalSection(){
                subtotal = 0;
                for (i=0; i < numItems; i++){
                    selectIndex = rowOffset + (2*i);
                    cost = document.getElementsByClassName("cost")[i].innerHTML;
                    cost = parseFloat(cost.substr(1));
                    subtotal += cost * document.forms[0]["quan"+i].value;
                }
                $("#subtotal").val(subtotal.toFixed(2));
                tax = (subtotal*0.0625).toFixed(2);
                $("#tax").val(tax);
                total = (parseFloat(subtotal) + parseFloat(tax)).toFixed(2); 
                $("#total").val(total);
            }
            
            function formValidate(){
                [valid,time] = formValidateChecks();
                if (valid){
                    alert("Successfully Ordered - thank you!");
                    document.getElementById("arrivalEstimate").value = time;
                    return true;
                }
                return false;
            }

            function formValidateChecks(){
                returnArray = [];
                deliveryStatus = document.forms[0].elements[deliveryIdx].checked;
                issues = "";
                successfullyValidate = true;
                if(invalidText(lNameIdx)){
                    issues += "Enter a last name\n";
                    successfullyValidate = false; 
                }
                if (invalidNumber()){
                    issues += "Enter a phone number\n";
                    successfullyValidate = false; 
                }
                timeString = "";
                if (deliveryStatus){
                    timeString = updateTime(30);
                    if (invalidText(streetIdx)){
                        issues += "Enter a street\n";
                        successfullyValidate = false; 
                    }
                    if (invalidText(cityIdx)){
                        issues += "Enter a city\n";
                        successfullyValidate = false; 
                    }
                }
                else {
                    timeString = updateTime(15);
                }
                if(numberItems()==0){
                    issues += "Select at least one item"; 
                    successfullyValidate = false; 
                }
                if(!successfullyValidate){
                    alert("Please correct the following issues: \n" + issues);
                    return [false,""];
                }
                return [true,timeString];
            }

            function invalidText(idx){
                return document.forms[0].elements[idx].value =="";
            }

            function invalidNumber(){
                numberIdx = 4;
                enteredString = document.forms[0].elements[numberIdx].value;
                numberDigits = 0;
                for (i = 0; i<enteredString.length; i++){
                    if (!isNaN(enteredString[i])){
                        numberDigits++;
                    }
                }
                if (numberDigits == 7 || numberDigits == 10){
                    return false;
                }
                return true; 
            }

            function numberItems(){
                itemCount = 0;
                for (i=0; i < numItems; i++){
                    itemCount += parseInt(document.forms[0]["quan"+i].value);
                }
                return itemCount;
            }

            function updateTime(minutes){
                currentDate = new Date; 
                currentMin = currentDate.getMinutes();
                currentHr = currentDate.getHours();

                currentMin += minutes;
                if (currentMin > 60){
                    currentMin = currentMin % 60;
                    currentHr++;
                }
                currentHr = currentHr%24;
                if (currentMin < 10){
                    currentMin = "0" + currentMin;
                }

                return currentHr + ":" + currentMin;
            }
        
            function getOrderDetails(deliverTime){
                returnedString = "<h1>Jade Delight</h1><br>";
                returnedString += "<h2>Thanks for ordering!</h2><br>";
                returnedString += "<div>Order Details:<br>";
                for (i = 0; i<menuItems.length; i++){
                    if(document.forms[0].elements[colOffset + (2*i)].value != 0){
                        returnedString += menuItems[i].name + ", $" + menuItems[i].cost.toFixed(2) + " - quantity: ";
                        returnedString += document.forms[0][colOffset + (2*i)].value + "<br>";
                    }
                }
                returnedString += "Subtotal: $" + document.forms[0].elements[subtotalIdx].value + "<br>";
                returnedString += "Tax: $" + document.forms[0].elements[taxIdx].value + "<br>";
                returnedString += "Total: $" + document.forms[0].elements[totalIdx].value + "<br></div>";

                returnedString += "<br>Expected by: " + deliverTime + "<br>"; 
                return returnedString;
            }
        </script>     
    </head>

    <body>
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
            function makeSelect($name, $minRange, $maxRange){
                $t = "<select name = '$name' size = '1'>";
                for ($j=$minRange; $j<=$maxRange; $j++){
                    $t .= "<option>$j</option>";
                }
                return "$t </select>";
            }
            function td($content, $className=""){
                return "<td class = '$className'>$content</td>";
            }
            $menuItems = getSQLData();

        ?>

    <div id = "banner">
        <h1>Jade Delight</h1>
        <h2>Medford's Premiere Chinese Restaurant!</h2>
    </div>

    <form onsubmit = "return formValidate();" method='get' action='submitted.php'> 
        <p class="userInfo"><label>First Name:</label> <input type="text"  name='fname' /></p>
        <p class="userInfo"><label>Last Name*:</label>  <input type="text"  name='lname' /></p>
        <p class="userInfo address"><label>Street*:</label> <input type="text" name='street' /></p>
        <p class="userInfo address"><label>City*:</label> <input type="text" name='city' /></p>
        <p class="userInfo"><label>Phone*:</label> <input type="text"  name='phone' /></p>
        <p> 
            <input type="radio"  name="p_or_d" value = "pickup" checked="checked"/>Pickup  
            <input type="radio"  name='p_or_d' value = 'delivery'/>
            Delivery
        </p>
        <table border="0" cellpadding="3" id = "itemTable">
            <tr>
                <th>Select Item</th>
                <th>Item Name</th>
                <th>Cost Each</th>
                <th>Total Cost</th>
            </tr>
            <?php
                $numItems = count($menuItems);
                $s = "";
                for ($i=0; $i<$numItems; $i++){
                    $s .= "<tr>";
                    $s .= td(makeSelect("quan$i", 0, 10),"selectQuantity");
                    $s .= td($menuItems[$i]->get_name(), "itemName");
                    $s .= td("\$ " . $menuItems[$i]->get_cost(), "cost");
                    $s .= td("\$ <input type='text' name='cost'/>", "totalCost");
                    $s .= "</tr>";
                }
                echo $s . "<br>";
            ?>
        </table>
        <input type="text" id ="arrivalEstimate" name="arrivalEstimate" style="display:none">
        <p class="subtotal totalSection"><label>Subtotal:</label>
        $ <input type="text"  name='subtotal' id="subtotal" />
        </p>
        <p class="tax totalSection"><label>Mass tax 6.25%:</label>
        $ <input type="text"  name='tax' id="tax" />
        </p>
        <p class="total totalSection"><label>Total:</label> $ <input type="text"  name='total' id="total" />
        </p>
        <input type = "submit" value = "Submit Order" />

    </form>

    </body>

</html>