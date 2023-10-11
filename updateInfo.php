<?php
// link to video https://youtu.be/9HWCwLkNkhs
// check that no fields are empty
if (empty($_POST["firstName"]) || empty($_POST["lastName"]) || empty($_POST["phoneNumber"]) || empty($_POST["emailAddress"])) {
    echo "All fields are required.";
} else {
    // check that the name field only contains letters and whitespace and is less than 20 characters
    if (!preg_match("/^[a-zA-Z ]*$/", $_POST["firstName"]) || strlen($_POST["firstName"]) > 20) {
        echo "Only letters and white space allowed in name field. Length must be under 20 characters.";
        echo "</br><a href='index.html'>Back to index page</a>";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $_POST["lastName"]) || strlen($_POST["lastName"]) > 20) {
        echo "Only letters and white space allowed in name field.";
        echo "</br><a href='index.html'>Back to index page</a>";
    } // check if The email address is a combination of letters and numbers, followed by @, followed by a series of letters only, and then a .com or .edu.
    else  if ((preg_match("/^[a-zA-Z0-9]+@[a-zA-Z]+\\.(com|edu)$/", $email))) {
        echo "Invalid email format.";
        echo "</br><a href='index.html'>Back to index page</a>";
    } else if (strlen($_POST["email"]) > 30){
        echo "Email address must be under 30 characters.";
        echo "</br><a href='index.html'>Back to index page</a>";
    } else if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST["phoneNumber"])) {
        echo "Invalid phone number format.";
        echo "</br><a href='index.html'>Back to index page</a>";
    } else if (strlen($_POST["phoneNumber"]) != 12) {
        echo "Phone number must be exactly 12 characters.";
        echo "</br><a href='index.html'>Back to index page</a>";
    } else {
        // if all fields are valid, update the userInfo.txt file
        $file = fopen("userInfo.txt", "a+");
        if ($file === false) {
            echo "Error opening the file.";
        } else {
            if (fwrite($file, $_POST["lastName"] . ":" . $_POST["firstName"] . ":" . $_POST["phoneNumber"] . ":" . $_POST["emailAddress"] . "\n") === false) {
                echo "Error writing to the file.";
            } else {
                
                // Read data from the file
                $file_path = "userInfo.txt";
                $data = file_get_contents($file_path);
                
                // Split the data into an array of records
                $records = explode("\n", trim($data));
                $sorted_records = [];
                
                // Parse each record and store it in an associative array
                foreach ($records as $record) {
                    $fields = explode(":", $record);
                    if (count($fields) === 4) {
                        list($lastName, $firstName, $phoneNumber, $emailAddress) = $fields;
                        $sorted_records[] = [
                            'lastName' => $lastName,
                            'firstName' => $firstName,
                            'phoneNumber' => $phoneNumber,
                            'emailAddress' => $emailAddress,
                        ];
                    }
                }
                
                // Sort the records by last name
                usort($sorted_records, function ($a, $b) {
                    return strcmp($a['lastName'], $b['lastName']);
                });
                
                // Write the sorted data back to the file
                $sorted_data = '';
                foreach ($sorted_records as $record) {
                    $sorted_data .= implode(':', $record) . "\n";
                }
                file_put_contents($file_path, $sorted_data);
                
                // Generate an HTML table to display the data
                echo '<table border="1">';
                echo '<tr><th>Last Name</th><th>First Name</th><th>Phone Number</th><th>Email Address</th></tr>';
                foreach ($sorted_records as $record) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($record['last']) . '</td>';
                    echo '<td>' . htmlspecialchars($record['first']) . '</td>';
                    echo '<td>' . htmlspecialchars($record['phone']) . '</td>';
                    echo '<td>' . htmlspecialchars($record['email']) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo "<a href='index.html'>Back to index page</a>";
            
                    
                }

                
            }
        }        
    }

?>
