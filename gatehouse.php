<?php
$title = "Gatehouse";
include 'includes/header.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gatehouse</title>
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
            z-index: 40; /* Increase z-index to ensure it's above the first row */
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
        .yellow-row {
    background-color: yellow;
    font-weight: bold; /* Optional, for emphasis */
}

    </style>

    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <table>
    <thead>
        <tr>
            <th>VRID</th>
            <th>STOP 1</th>
            <th>STOP 2</th>
            <th>STOP 1 YARD ARRIVAL</th>
            <th>STOP 2 YARD ARRIVAL</th>
            <th>EQUIPMENT TYPE</th>
            <th>SHIPPER ACCOUNTS</th>
            <th>DRIVER</th>
            <th>TRACTOR</th>
            <th>TRAILER TYPE</th>
            <th>ADHOC/UPDATED</th>
            <th>LICENCE PLATE</th>
            <th>TRAILER NO</th>
            <th>NEEDED INFO</th>
            <th>CANCELLED</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "Non Amazon";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to fetch and display data
    function displayData($conn) {
        // SQL query to retrieve data
        $sql = "SELECT * FROM gh";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $data = array(); // Initialize the data array
            while ($row = $result->fetch_assoc()) {
                $data[] = $row; // Append each row to the array
            }

            // Custom sorting function
            function customDateSort($a, $b) {
                $dateA = DateTime::createFromFormat('d.m.Y H:i', $a['Stop 1 Yard Arrival']);
                $dateB = DateTime::createFromFormat('d.m.Y H:i', $b['Stop 1 Yard Arrival']);

                return $dateB <=> $dateA; // Sort in descending order
            }

            // Apply sorting to the data array
            usort($data, 'customDateSort');

            // Output sorted data
            foreach ($data as $row) {
                $rowClass = '';
                if ($row["Cancelled"] == "yes") {
                    $rowClass = 'red-row';
                }
                echo "<tr class='$rowClass'>";
                echo "<td>" . $row["VR ID"] . "</td>";
                echo "<td>" . $row["Stop 1"] . "</td>";
                echo "<td>" . $row["Stop 2"] . "</td>";
                echo "<td>" . $row["Stop 1 Yard Arrival"] . "</td>";
                echo "<td>" . $row["Stop 2 Yard Arrival"] . "</td>";
                echo "<td class='editable' data-field='Equipment Type'>" . $row["Equipment Type"] . "</td>";
                echo "<td>" . $row["Shipper Accounts"] . "</td>";
                echo "<td class='editable' data-field='Driver'>" . $row["Driver"] . "</td>";
                echo "<td class='editable' data-field='Tractor'>" . $row["Tractor"] . "</td>";
                echo "<td class='editable' data-field='Trailer'>" . $row["Trailer"] . "</td>";
                echo "<td class='editable' data-field='(A)DHOC/ (U)PDATED'>" . $row["(A)DHOC/ (U)PDATED"] . "</td>";
                echo "<td class='editable' data-field='LICENCE PLATE'>" . $row["LICENCE PLATE"] . "</td>";
                echo "<td class='editable' data-field='TRAILER NO.'>" . $row["TRAILER NO."] . "</td>";
            
                // Check if Shipper Accounts contains "SWA" and set Needed Info accordingly
                $neededInfo = "";
                if (strpos($row["Shipper Accounts"], "SWA") !== false) {
                    $neededInfo = "<td class='copyable-info'>
                        <div class='copyable-content'>
       Please do your best to arrive on time
When loading from 'SWA' locations, *DO NOT leave the site* earlier than the right departure time (relay time-30 mins), even if it appears that loading has finished. You may be loaded SEVERAL TIMES during your stay on site (unless you are given paperwork with the total number of pallets- then you can tell when you are fully loaded)
If you are not given seals, please use your spares and indicate serial numbers on your group before departure.
If anything is unclear, please ask on group.
                        </div>
                    </td>";
                }

                if (strpos($row["Shipper Accounts"], "ATSExternal") !== false) {
                    $neededInfo = "<td class='copyable-info'>
                        <div class='copyable-content'>
Please check your relay on arrival to make sure it automatically detected your arrival and if it does not, please swipe by clicking on please press need help with arrival -- app not detecting arrival do not leave origin site without paperwork and send us pictures of *signed* paperwork after delivery as we need to provide amazon with proof of delivery. 
            *PAYMENT for this run depends on PAPERWORK.*
                     ‼️please read the above‼️
                        </div>
                    </td>";
                } elseif ($row["Stop 1"] == "SWA_PROTEINW" && $row["Stop 2"] == "SWA_ABNORMAL.MAN8") {
                    // Additional condition for Stop 1 and Stop 2
                    $neededInfo = "<td class='copyable-info'>
                        <div class='copyable-content'>
    ℹ️ When loading from SWA sites on your tour:<br>
o (1) Accept and double-check CMR/hand over document for accuracy<br>
o (2) Let Shipper loading team Seal vehicle<br>
o (3) Hand over CMR at injection site to TOM team<br>
o (4) Only have Seals broken by Amazon site teams<br>
     Please do your best to arrive on time.
                        </div>
                    </td>";
                }

            // Display the hidden full message with a data-copy attribute
            echo "<td class='copyable-info' data-copy='$neededInfo'></td>";
            
            
            echo "<td class='editable' data-field='Cancelled'>" . $row["Cancelled"] . "</td>";
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
                case "Equipment Type":
                    updateUrl = "update_equipment_type.php";
                    break;
                case "Driver":
                    updateUrl = "update_driver.php";
                    break;
                case "Tractor":
                    updateUrl = "update_tractor.php";
                    break;
                case "Trailer":
                    updateUrl = "update_trailer.php";
                    break;
                case "(A)DHOC/ (U)PDATED":
                    updateUrl = "update_adhoc_updated.php";
                    break;
                case "LICENCE PLATE":
                    updateUrl = "update_licence_plate.php";
                    break;
                case "TRAILER NO.":
                    updateUrl = "update_trailer_no.php";
                    break;
                case "Info Needed":
                    updateUrl = "update_info.php";
                    break;
                case "Cancelled":
                    updateUrl = "update_cancelled.php";
                    break;    

                default:
                    // Handle unsupported field
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
        var copyText = $(this).find(".copyable-content").text();

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


    });
});
</script>

<div id="table-container" style="overflow-x: auto;">
    <table>
        <!-- Your table content here -->
    </table>
</div>

<script>
    // JavaScript for handling horizontal dragging
    var isMouseDown = false;
    var startX, scrollLeft;

    document.getElementById('table-container').addEventListener('mousedown', function(e) {
        isMouseDown = true;
        startX = e.pageX - this.offsetLeft;
        scrollLeft = this.scrollLeft;
    });

    document.getElementById('table-container').addEventListener('mouseleave', function() {
        isMouseDown = false;
    });

    document.getElementById('table-container').addEventListener('mouseup', function() {
        isMouseDown = false;
    });

    document.getElementById('table-container').addEventListener('mousemove', function(e) {
        if (!isMouseDown) return;
        e.preventDefault();
        var x = e.pageX - this.offsetLeft;
        var walk = x - startX;
        this.scrollLeft = scrollLeft - walk;
    });
</script>

<script>
$(document).ready(function() {
    // Function to check and update row styles and positions
    function updateRowStylesAndPosition() {
        var now = new Date();
        now.setHours(now.getHours() + 4); // Add 4 hours to the current time

        $('tbody tr').each(function() {
            var row = $(this);
            var driverCell = row.find('td[data-field="Driver"]');
            var yardArrivalCell = row.find('td[data-field="Stop 1 Yard Arrival"]');
            var driverText = driverCell.text();
            var yardArrivalText = yardArrivalCell.text();
            var yardArrivalDate = new Date(yardArrivalText);

            // Check if the Driver cell is empty or contains "slam"
            if (driverText === "" || driverText.toLowerCase().includes("slam")) {
                // Check if the Stop 1 Yard Arrival is less than 4 hours from now
                if (yardArrivalDate < now) {
                    row.addClass('yellow-row');
                }
            }
        });

        // Sort rows so that yellow rows appear at the top
        var table = $('table');
        var tbody = table.find('tbody');
        var rows = tbody.find('tr').toArray();
        rows.sort(function(a, b) {
            if ($(a).hasClass('yellow-row') && !$(b).hasClass('yellow-row')) {
                return -1;
            } else if (!$(a).hasClass('yellow-row') && $(b).hasClass('yellow-row')) {
                return 1;
            }
            return 0;
        });

        tbody.empty();
        $.each(rows, function(index, row) {
            tbody.append(row);
        });
    }

    // Call the function to initially apply styles and sort rows
    updateRowStylesAndPosition();

    // Periodically check and update row styles and positions
    setInterval(updateRowStylesAndPosition, 60000); // Update every minute (adjust as needed)
});
</script>



</br>
</br>
<?php require 'includes/footer.php'; ?>