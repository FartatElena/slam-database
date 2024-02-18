<?php
$title = "Loops";
include 'includes/header.php'; 
?>
</br>
<!DOCTYPE html>
<html>
<head>
    <title>Loops</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 3px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
            z-index: 50; /* Increase z-index to ensure it's above the first row */
        }

        tr:first-child th {
            background-color: #f2f2f2;
            position: sticky;
            padding: 8px;
            top: 50px; /* Adjust the top value to account for the header's height */
            z-index: 50;
        }
        td:nth-child(6), th:nth-child(6) {
            width: 150px;
        }
       
        .equipment-dropdown {
            width: 100%;
        }

        .highlighted-row {
            background-color: white;
        }
        .copyable-info {
            cursor: pointer;
            max-width: 100px; /* Adjust the maximum width as needed */
            max-height: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .red-row {
            background-color: #C12808;
            color: white; /* Optional, to make text more readable on a red background */
        }
    </style>

    <div id="hidden-data" data-content="$neededInfo"></div>

    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <table>
    <thead>
        <tr>
            <th>Block ID</th>
            <th>Trip ID</th>
            <th>VR ID</th>
            <th>Stop 1</th>
            <th>Stop 2</th>
            <th>Stop 1 Yard Arrival</th>
            <th>Driver</th>
            <th>Reg Number</th>
            <th>CR_ID</th>
            <th>Shipper Accounts</th>
            <th>Vehicle ID</th>
            <th>(A)DHOC/ (U)PDATED</th>
            <th>Needed Info</th>
            <th>Cancelled</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $servername = "slam-database.c78imuwuqt5q.eu-west-2.rds.amazonaws.com";
        $username = "elena";
        $password = "25K27ab976EF!";
        $dbname = "SLAM";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to fetch and display data
    function displayData($conn) {
        // SQL query to retrieve data and sort by Block ID and Stop 1 Yard Arrival
        $sql = "SELECT * FROM LOOPS ORDER BY `BLOCK_ID`, STR_TO_DATE(`STOP_1_ARRIVAL`, '%d.%m.%Y %H:%i') DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr class='" . ($row["CANCELLED"] == "yes" ? "red-row" : "") . "'>";
                echo "<td>" . $row["BLOCK_ID"] . "</td>";
                echo "<td>" . $row["TRIP_ID"] . "</td>";
                echo "<td>" . $row["VRID"] . "</td>";
                echo "<td>" . $row["STOP_1"] . "</td>";
                echo "<td>" . $row["STOP_2"] . "</td>";
                echo "<td>" . $row["STOP_1_ARRIVAL"] . "</td>";
                echo "<td class='editable' data-field='Driver'>" . $row["DRIVER"] . "</td>";
                echo "<td class='editable' data-field='Reg Number'>" . $row["REG_NUMBER"] . "</td>";
                echo "<td>" . $row["CR_ID"] . "</td>";
                echo "<td>" . $row["SHIPPER_ACCOUNTS"] . "</td>";
                echo "<td class='editable' data-field='Vehicle ID'>" . $row["VEHICLE_ID"] . "</td>";
                echo "<td class='editable' data-field='(A)DHOC/ (U)PDATED'>" . $row["ADHOC"] . "</td>";
                echo "<td class='copyable-info'>
                    <div class='copyable-content'>
                    In light of another bridge strike, this time involving an Amazon double deck trailer.
                    Please review the latest Safety Briefing.
                    If you do not understand or have specific questions regarding this, please contact your transport manager / office.

                    ‼️ DOUBLE DECK TRAILER ‼️
                    VD41, VD42: connect trailer-> with engine on and headlights on, take a picture of the EBS alarm fitted to the trailer (light should be off)-> post it on your group-> check that no ABS warning is showing on the dashboard
                    </div>
                </td>";
                echo "<td class='editable' data-field='Cancelled'>" . $row["CANCELLED"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='13'>No data found</td></tr>";
        }
    }

    // Display initial data
    displayData($conn);

    $conn->close();
    ?>
    </tbody>
</table>




<script>
    $(document).ready(function() {
        // Function to enable cell editing
        function enableCellEditing(cell) {
            var input = $("<input/>").val(cell.text());
            cell.html(input);
            input.focus();

            // Listen for Enter key press to save the value
            input.on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent line break
                    input.blur(); // Trigger blur event to save the value
                }
            });

            input.blur(function() {
                var newValue = $(this).val();
                cell.text(newValue);
                updateCellValue(cell, newValue);
            });
        }

        // Function to update cell value in the database
        function updateCellValue(cell, newValue) {
            var row = cell.closest("tr");
            var field = cell.data("field");
            var ref = row.find("td:first-child").text();

            // Send an AJAX request to update the value in the database
            $.ajax({
                type: "POST",
                url: getUpdateUrl(field),
                data: { ref: ref, field: field, value: newValue },
                success: function(response) {
                    // Handle the response if needed
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Enable cell editing for existing and new rows when a cell is clicked
        $(document).on("dblclick", ".editable", function() {
            enableCellEditing($(this));
        });

        // Function to get the appropriate update URL based on the field
        function getUpdateUrl(field) {
            var updateUrl = "";

            switch (field) {
                case "Driver":
                    updateUrl = "update_driver_loops.php";
                    break;
                case "Reg Number":
                    updateUrl = "update_reg_loops.php";
                    break;
                case "Vehicle ID":
                    updateUrl = "update_vehicle_loops.php";
                    break;
                case "(A)DHOC/ (U)PDATED":
                    updateUrl = "update_adhoc_loops.php";
                    break;
                default:
                    // Handle unsupported field
                    break;
                case "Cancelled":
                    updateUrl = "update_cancelled_loops.php";
                break;
            }

            return updateUrl;
        }

        // Function to highlight matching rows
        function highlightRows(searchTerm) {
            // Remove any previous highlights
            $('tbody tr').removeClass('highlighted-row');

            // Convert search term to lowercase for case-insensitive search
            searchTerm = searchTerm.toLowerCase();

            // Iterate through the rows and check each cell for a match
            $('tbody tr').each(function() {
                var row = $(this);

                // Concatenate all cell data in lowercase
                var rowData = row.find('td').map(function() {
                    return $(this).text().toLowerCase();
                }).get().join(' ');

                if (rowData.includes(searchTerm)) {
                    row.addClass('highlighted-row');
                }
            });
        }

        // Function to scroll to the first highlighted row
        function scrollToHighlightedRow() {
            var firstHighlightedRow = $('tbody tr.highlighted-row').first();
            if (firstHighlightedRow.length > 0) {
                var scrollY = firstHighlightedRow.offset().top - 125; // Scroll 100 pixels below the top of the highlighted row
                $('html, body').animate({
                    scrollTop: scrollY
                }, 'slow');
            }
        }

        // Handle form submission
        $('#searchForm').submit(function(event) {
            event.preventDefault(); // Prevent form submission

            // Get the search term from the input field
            var searchTerm = $('#searchInput').val().trim();

            // Call the function to highlight matching rows
            highlightRows(searchTerm);

            // Scroll to the first highlighted row
            scrollToHighlightedRow();
        });
    });
</script>
<script>
$(document).ready(function() {
    var copiedText = ""; // Variable to store copied text

    // Handle clicking on the "NEEDED INFO" cell
    $(document).on("click", ".copyable-info", function() {
    var copyId = $(this).data("copy-id");
    var copyText = $("#hidden-data").data("content");

        // Create a hidden textarea to copy the text content
        var tempTextarea = $("<textarea></textarea>");
        tempTextarea.val(copyText);
        tempTextarea.css({ position: "fixed", left: "-9999px" }); // Move the textarea off-screen
        $("body").append(tempTextarea);

        // Select and copy the text content
        tempTextarea.select();
        document.execCommand("copy");

        // Store the copied text content
        copiedText = copyText;

        // Remove the temporary textarea
        tempTextarea.remove();

        // Change the text in the cell to indicate copied status
        $(this).text("Copied!");

        $(document).on("click", ".copyable-info", function() {
    console.log("Clicked on .copyable-info"); // Check if this message appears in the console
    // Rest of your code for copying content
});

    });
});
</script>
</br>
</br>
<?php require 'includes/footer.php'; ?>
