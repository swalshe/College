<!--
	Credit:
	Completed by Stephanie Walshe (15302291)
	
-->

<?php
$servername = "localhost";
$username = "root";
$password = "cs230";
$dbname = "cs230";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE readingListItem (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
theDate TIMESTAMP,
name VARCHAR(30) NOT NULL,
url VARCHAR(50) NOT NULL,
theDesc VARCHAR(30)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table readingListItem created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
<!DOCTYPE HTML>  
<html>
<head>
    <title>PHP SPA Database Table Assignment</title>
    <link rel="stylesheet" type="text/css" href="solution.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>  
<div class="container">

<?php

/* simple debugging (printing) function  */
$GLOBALS["debug"] = 0;
function debug ($str) {
  if ($GLOBALS["debug"]) echo $str;
}

/* simple data cleansing function */
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

debug ("DEBUG Printing: ON!<br />");

/* This section processes any POST data request */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  switch ($_POST["submit"]) {
     //
     // CREATE : new row data via form from user
     //
     case "Insert New Record" :
                // get the data from the form
                $id = $theDate = $name = $url = $theDesc = "";
              //  $id = test_input($_POST["id"]); 
               // $theDate = test_input($_POST["theDate"]);
				$name = test_input($_POST["name"]);
				$url = test_input($_POST["url"]);
			    $theDesc = test_input($_POST["theDesc"]);
                debug("Creating new Record for: ".$name."<br />");
                // insert data into database table
                $servername = "localhost"; $username = "root"; $password = "cs230";
                $dbname = "cs230"; $tablename = "readingListItem";
				$conn = new mysqli($servername, $username, $password, $dbname);
				if ($conn->connect_error) {
                    die ("Database ".$dbname.": connection failed: " . $conn->connect_error);
                }
                $sql = "INSERT INTO readingListItem (name,url,theDesc) ".
                       "VALUES ('".
                                $name."','".
                                $url."','".
                                $theDesc."');";
                debug ("SQL: ".$sql."<br />");                                                
                if ($conn->query($sql) === TRUE) {
                   debug ("New record created successfully!<br />");
                } else {
                   debug ("Error: " . $sql . "<br>" . $conn->error);
                }
                $conn->close();
                break;
     //
     // DELETE : delete row via form from user
     //
     case "Delete Selected Record" : 
                $del = "";
                $del = test_input($_POST["del"]);
                debug ("Deleting Record with ID: ".$del."<br />");
                // delete database table record
                $servername = "localhost"; $username = "root"; $password = "cs230";
                $dbname = "cs230"; $tablename = "readingListItem";
				$conn = new mysqli($servername, $username, $password, $dbname);
				if ($conn->connect_error) {
                    die ("Database ".$dbname.": connection failed: " . $conn->connect_error);
                }
                $sql = "DELETE FROM readingListItem where id = '".$del."';";
                debug ("SQL: ".$sql."<br />");                                                
                if ($conn->query($sql) === TRUE) {
                   debug ("Record ".$del." Deleted successfully!<br />");
                } else {
                   debug ("Error: " . $sql . "<br>" . $conn->error);
                }
                $conn->close();
                break;
				
				
			//EDIT RECORD SECTION	
			case "Edit Record" : 
               
                // delete database table record
                $servername = "localhost"; $username = "root"; $password = "cs230";
                $dbname = "cs230"; $tablename = "readingListItem";
				$conn = new mysqli($servername, $username, $password, $dbname);
				if ($conn->connect_error) {
                    die ("Database ".$dbname.": connection failed: " . $conn->connect_error);
                }
				$sql = "SELECT * FROM ".$tablename;
				$result = $conn->query($sql);

				
				$upd = "";
                $upd = test_input($_POST["upd"]);
				$update =  $_POST['update'];
				$updateid = $_POST['updateid'];
				
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						if($upd==$row["name"])
						{
						$sql = "UPDATE readingListItem set name = '".$update."' WHERE id='".$updateid."';";
						if ($conn->query($sql) === TRUE) {
							
    echo "Record updated successfully";
break;
	}
						}
                if($upd==$row["url"])
						{
					
						$sql = "UPDATE readingListItem set url= '".$update."' WHERE id='".$updateid."';";
					if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
	
}
						}
				if($upd==$row["theDesc"])
						{
					
						$sql = "UPDATE readingListItem set theDesc = '".$update."' WHERE id='".$updateid."';";
						if ($conn->query($sql) === TRUE) {
						echo "Record updated successfully";
						}
					}
				}
				}
				else {
					echo "Table: ".$tablename." contains no data!<br/>";
						}
	

				$conn->close();
				
				
                break;
     default : 
         echo "No expected CRUD activity!<br/>";  
  }               
}
?>

<!--
   Zone One: This is the TABLE ROW INPUT FORM (always show the form for this assignment)
-->
<div class="row text-left"><h2>TABLE ROW ENTRY ZONE (readingListItem)</h2></div>
<!-- self-referencing script - form and processor in single page app -->
<div class="container-fluid">
	 <form method="post" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
      
      
       <div class="form-group">
         <label class="control-label col-sm-2" for="name">Name:</label>
         <div class="col-sm-10">
           <input type="text" class="form-control" id="name" name="name">
         </div>
       </div>
       <div class="form-group">
         <label class="control-label col-sm-2" for="url">URL:</label>
         <div class="col-sm-10">
           <input type="text" class="form-control" id="url" name="url">
         </div>
       </div>
       <div class="form-group">
         <label class="control-label col-sm-2" for="theDesc">Description:</label>
         <div class="col-sm-10">
           <input type="text" class="form-control" id="theDesc" name="theDesc">
         </div>
       </div>
       <div class="col-sm-offset-2 col-sm-10">
         <button type="submit" name="submit" value="Insert New Record" class="btn btn-default">Insert New Record</button>
       </div>
	 </form>
</div>	
<br /><br />

<!--
   Zone Two: This is the RETRIEVE TABLE DATA ZONE (always show full table for this assignment)
-->
<div class="row text-left"><h2><h2>RETRIEVE TABLE DATA (readingListItem)</h2></div>

<?php
$servername = "localhost"; $username = "root"; $password = "cs230"; 
$dbname = "cs230"; $tablename = "readingListItem";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Database ".$dbname.": connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM ".$tablename;
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    debug ("Table: ".$tablename."<br/><br/>");
    // output the table header, etc
    echo "<div class=\"container\">";
    echo "<table class=\"tg\">";
    echo "<tr>";
    // echo "  <th class=\"tg-amwm\">ID</th>";
    echo "  <th class=\"tg-amwm\">ID</th>";
    echo "  <th class=\"tg-amwm\">Date</th>";
    echo "  <th class=\"tg-amwm\">Name</th>";
    echo "  <th class=\"tg-amwm\">URL</th>";
    echo "  <th class=\"tg-amwm\">Description</th>";
    echo "</tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
    echo "<tr>";
    // echo "  <td class=\"tg-lqy6\">".$row["id"]."</td>";
    echo "  <td class=\"tg-yw4l\">".$row["id"]."</td>";
    echo "  <td class=\"tg-yw4l\">".$row["theDate"]."</td>";
    echo "  <td class=\"tg-lqy6\">".$row["name"]."</td>";
    echo "  <td class=\"tg-lqy6\">".$row["url"]."</td>";
    echo "  <td class=\"tg-lqy6\">".$row["theDesc"]."</td>";
    echo "</tr>";
    }
    // close the table
    echo "</table>";
    echo "</div>";
} else {
    echo "Table: ".$tablename." contains no data!<br/>";
}
$conn->close();
?>

<br /><br />
<!--
   Zone Four: This is the DELETE TABLE ROW ZONE (using Table)
-->
<div class="row text-left"><h2><h2>DELETE TABLE ROW (readingListItem)</h2></div>

<?php
$servername = "localhost"; $username = "root"; $password = "cs230"; 
$dbname = "cs230"; $tablename = "readingListItem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Database ".$dbname.": connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM ".$tablename;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    debug ("Table: ".$tablename."<br/><br/>");
    // output the table header, etc
    echo "<div class=\"container\">";
    echo "<form method=\"post\" action=\"".htmlspecialchars($_SERVER["PHP_SELF"])."\">";  
    echo "<table class=\"tg\">";
    echo "<tr>";
    echo "  <th class=\"tg-amwm\"> </th>";
    echo "  <th class=\"tg-amwm\">ID</th>";
    echo "  <th class=\"tg-amwm\">Date</th>";
    echo "  <th class=\"tg-amwm\">Name</th>";
    echo "  <th class=\"tg-amwm\">URL</th>";
    echo "  <th class=\"tg-amwm\">Description</th>";
    echo "</tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td class=\"tg-yw4l\"><input type=\"radio\" name=\"del\" value=\"".$row["id"]."\"></td>";
    // echo "<td class=\"tg-lqy6\">".$row["id"]."</td>";
    echo "<td class=\"tg-yw4l\">".$row["id"]."</td>";
    echo "<td class=\"tg-yw4l\">".$row["theDate"]."</td>";
    echo "<td class=\"tg-lqy6\">".$row["name"]."</td>";
    echo "<td class=\"tg-lqy6\">".$row["url"]."</td>";
    echo "<td class=\"tg-lqy6\">".$row["theDesc"]."</td>";
    echo "</tr>";
    }
    // close the table
    echo "</table><br />";
    echo "<input type=\"submit\" name=\"submit\" value=\"Delete Selected Record\">";  
    echo "</form>";
    echo "</div>";
} else {
    echo "Table: ".$tablename." contains no data!<br/>";
}
$conn->close();
?>

<br /><br />

<!--
   EDIT TABLE
-->


<div class="row text-left"><h2><h2>UPDATE TABLE (readingListItem)</h2></div>
<h4>To update an entry you must first select it using the radio beside it's id, then select which column in that row you want to change.
Then you can type the change into the textbox below and hit edit record<h4>
<?php
$servername = "localhost"; $username = "root"; $password = "cs230"; 
$dbname = "cs230"; $tablename = "readingListItem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Database ".$dbname.": connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM ".$tablename;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    debug ("Table: ".$tablename."<br/><br/>");
    // output the table header, etc
    echo "<div class=\"container\">";
    echo "<form method=\"post\" action=\"".htmlspecialchars($_SERVER["PHP_SELF"])."\">";  
    echo "<table class=\"tg\">";
    echo "<tr>";
    echo "  <th class=\"tg-amwm\"> </th>";
	echo "  <th class=\"tg-amwm\">ID</th>";
    echo "  <th class=\"tg-amwm\">Date</th>";
    echo "  <th class=\"tg-amwm\">Name</th>";
    echo "  <th class=\"tg-amwm\">URL</th>";
    echo "  <th class=\"tg-amwm\">Description</th>";
    echo "</tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td class=\"tg-lqy6\"><input type=\"radio\" name=\"updateid\" value=\"".$row["id"]."\"></td>";
    echo "<td class=\"tg-yw4l\">".$row["id"]."</td>";
    echo "<td class=\"tg-yw4l\">".$row["theDate"]."</td>";
    echo "<td class=\"tg-lqy6\">".$row["name"]."<input type=\"radio\" name=\"upd\" value=\"".$row["name"]."\"></td>";
    echo "<td class=\"tg-lqy6\">".$row["url"]."<input type=\"radio\" name=\"upd\" value=\"".$row["url"]."\"></td>";
    echo "<td class=\"tg-lqy6\">".$row["theDesc"]."<input type=\"radio\" name=\"upd\" value=\"".$row["theDesc"]."\"></td>";
    echo "</tr>";
    }
    // close the table
    echo "</table><br />";
	echo "<input type=\"text\" name=\"update\" id=\"update\" value=\"update\">";  
    echo "<input type=\"submit\" name=\"submit\" value=\"Edit Record\">";  
    echo "</form>";
    echo "</div>";
} else {
    echo "Table: ".$tablename." contains no data!<br/>";
}
$conn->close();
?>
</div>
</body>
</html>
