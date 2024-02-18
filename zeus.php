<?php 
$title = "Zeus";
include 'includes/header.php'; 
?> 


<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 2px;
            text-align: center;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
            position: sticky;
            padding: 3px;
            top: 0;
            z-index: 100;
        }

        tr:first-child th {
            background-color: #f2f2f2;
            position: sticky;
            padding: 2px;
            top: 55px; 
            z-index: 50;
        }
        td:nth-child(6), th:nth-child(6) {
            width: 150px;
            height: 20px;
        }
       
        .equipment-dropdown {
            width: 100%;
        }

        .highlighted-row {
        background-color: white;
        }

        .copyable-info {
            cursor: pointer;
            max-width: 100px; 
            max-height: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pagination {
            margin: 10px 0;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

    </style>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </br>
    <table>
    <thead>
        <tr>
            <th>REF</th>
            <th>STOP 1</th>
            <th>STOP 2</th>
            <th>ARRIVAL STOP 1</th>
            <th>ARRIVAL STOP 2</th>
            <th>EQUIPMENT</th>
            <th>DRIVER</th>
            <th>TRAILER IN</th>
            <th>TRAILER OUT</th>
            <th>NOTES</th>
            <th>NEEDED INFO</th>
            <th>ISSUES</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $servername = "slam-database.c78imuwuqt5q.eu-west-2.rds.amazonaws.com";
    $username = "elena";
    $password = "25K27ab976EF!";
    $dbname = "SLAM";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM ZEUS";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row; 
        }

        function customDateSort($a, $b) {
            $dateA = DateTime::createFromFormat('d.m.Y H:i', $a['STOP_1_ARRIVAL']);
            $dateB = DateTime::createFromFormat('d.m.Y H:i', $b['STOP_1_ARRIVAL']);
            
            return $dateB <=> $dateA; 
        }

        
        usort($data, 'customDateSort');

        $rowsPerPage = 100;
        $totalRows = $result->num_rows;
        $totalPages = ceil($totalRows / $rowsPerPage);

        if (!isset($_GET['page'])) {
            $currentPage = 1;
        } else {
            $currentPage = $_GET['page'];
        }

        $startIndex = ($currentPage - 1) * $rowsPerPage;

        $sql = "SELECT * FROM ZEUS LIMIT $startIndex, $rowsPerPage";
        $result = $conn->query($sql);

        

        foreach ($data as $row) {
            echo "<tr>";
                echo "<td>" . $row["REF"] . "</td>";
                echo "<td>" . $row["STOP_1"] . "</td>";
                echo "<td>" . $row["STOP_2"] . "</td>";
                echo "<td>" . $row["STOP_1_ARRIVAL"] . "</td>";
                echo "<td>" . $row["STOP_2_ARRIVAL"] . "</td>";
                echo "<td class='editable' data-field='EQUIPMENT'>" . $row["EQUIPMENT"] . "</td>";
                echo "<td class='editable' data-field='DRIVER'>" . $row["DRIVER"] . "</td>";
                echo "<td class='editable' data-field='TRAILER IN'>" . $row["TRL_IN"] . "</td>";
                echo "<td class='editable' data-field='TRAILER OUT'>" . $row["TRL_OUT"] . "</td>";
                echo "<td class='editable' data-field='NOTES'>" . $row["NOTES"] . "</td>";

                $NeededInfo = "";
                if (strpos($row["STOP_1"], "DECATHLON DC. NN4 7HT") !== false) {
                    $NeededInfo = "<td class='copyable-info'>
                        <div class='copyable-content'>
       Please do your best to arrive on time
When loading from 'SWA' locations, *DO NOT leave the site* earlier than the right departure time (relay time-30 mins), even if it appears that loading has finished. You may be loaded SEVERAL TIMES during your stay on site (unless you are given paperwork with the total number of pallets- then you can tell when you are fully loaded)
If you are not given seals, please use your spares and indicate serial numbers on your group before departure.
If anything is unclear, please ask on group.
                        </div>
                    </td>";
                }

                if (strpos($row["STOP_1"], "P&G") !== false) {
                    $NeededInfo = "<td class='copyable-info
                        <div class='copyable-content'>
                        Please use the Zeus app. Keep app open until job is complete

🚚 When collecting from P&G 🚚
- always check that the load has been properly secured! break the seal if you have to, and ask for a replacement
- if there are no straps/bars in place, please go to this location to secure the load as drivers are allowed to get on the trailer here https://maps.app.goo.gl/4wqVRmmahZV7bJqN8
- any issues, please let us know on Whatsapp

❗❗❗PAPERWORK NEEDED FOR PAYMENT❗❗❗

-   Take pictures of papers received at loading site (ALL PAGES). Upload on the Zeus app AND post them on your group.

-   Take pictures of signed/ stamped proof of delivery at destination (ALL PAGES, including unsigned ones. 1 page per picture, ALL corners visible). Upload on the Zeus app AND post them on your group.

ℹ️Please always check that the delivery address on the paperwork matches the delivery address on the app
ℹ️ If you are delivering to Morrisons please type in your PO number found on the P&G paperwork to gain entrance 
ℹ️Trays/boxes are set up at Slam yards to collect the paperwork from these jobs once completed. If you pass through a Slam yard, please leave the paperwork there.

                        </div>
                    </td>";
                }

                if (strpos($row["STOP_1"], "decathlon") !== false) {
                    $NeededInfo = "<td class='copyable-info'>
                        <div class='copyable-content'>
Please check your relay on arrival to make sure it automatically detected your arrival and if it does not, please swipe by clicking on please press need help with arrival -- app not detecting arrival do not leave origin site without paperwork and send us pictures of *signed* paperwork after delivery as we need to provide amazon with proof of delivery. 
            *PAYMENT for this run depends on PAPERWORK.*
                     ‼️please read the above‼️
                        </div>
                    </td>";
                } elseif ($row["STOP_1"] == "SWA_PROTEINW" && $row["Stop_2"] == "SWA_ABNORMAL.MAN8") {
                    // Additional condition for Stop 1 and Stop 2
                    $NeededInfo = "<td class='copyable-info'>
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
                echo "<td class='copyable-info' data-copy= $NeededInfo</td>";
        
                echo "<td class='editable' data-field='ISSUES'>" . $row["ISSUES"] . "</td>";
                echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='13'>No data found</td></tr>";
    }

    
    $conn->close();
    
    ?>
    </tbody>
</table>


<script>
    $(document).ready(function() {
        function enableCellEditing(cell) {
            var input = $("<input/>").val(cell.text());
            cell.html(input);
            input.focus();

            input.on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); 
                    input.blur(); 
                }
            });

            input.blur(function() {
                var newValue = $(this).val();
                cell.text(newValue);
                updateCellValue(cell, newValue);
            });
        }

        function updateCellValue(cell, newValue) {
            var row = cell.closest("tr");
            var field = cell.data("field");
            var ref = row.find("td:first-child").text();

            $.ajax({
                type: "POST",
                url: getUpdateUrl(field),
                data: { ref: ref, field: field, value: newValue },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        $(".editable").dblclick(function() {
            enableCellEditing($(this));
        });

        function getUpdateUrl(field) {
            var updateUrl = "";

            switch (field) {
                case "EQUIPMENT":
                    updateUrl = "update_equipment.php";
                    break;
                case "DRIVER":
                    updateUrl = "update_driver_zeus.php";
                    break;
                case "ISSUES":
                    updateUrl = "update_issues.php";
                    break;
                case "TRL_IN":
                    updateUrl = "update_trlin.php";
                break;
                case "TRL_OUT":
                    updateUrl = "update_trlout.php";
                break;
            }

            return updateUrl;
        }
    });
</script>

    <script>
    $(document).ready(function() {
        function highlightRows(searchTerm) {
            $('tbody tr').removeClass('highlighted-row');

            searchTerm = searchTerm.toLowerCase();

            $('tbody tr').each(function() {
                var row = $(this);

                var rowData = row.find('td').map(function() {
                    return $(this).text().toLowerCase();
                }).get().join(' ');

                if (rowData.includes(searchTerm)) {
                    row.addClass('highlighted-row');
                }
            });
        }

        function scrollToHighlightedRow() {
            var firstHighlightedRow = $('tbody tr.highlighted-row').first();
            if (firstHighlightedRow.length > 0) {
                var scrollY = firstHighlightedRow.offset().top - 100; 
                $('html, body').animate({
                    scrollTop: scrollY
                }, 'slow');
            }
        }

        $('#searchForm').submit(function(event) {
            event.preventDefault(); 

            var searchTerm = $('#searchInput').val().trim();

            highlightRows(searchTerm);

            scrollToHighlightedRow();
        });
    });

    function submitData() {
                var formData = $('#entryForm').serialize();

                // Log the form data to the console for debugging
                console.log(formData);

                // Send the form data to a PHP script for processing
                $.ajax({
                    type: 'POST',
                    url: 'manual_entryzeus.php', // Update with your PHP script
                    data: formData,
                    success: function(response) {
                        console.log(response); // Handle success response
                        closeDataEntryForm(); // Close the form after successful submission
                        // Optionally, you can reload the table or perform other actions here
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Handle error response
                    }
                });
            };
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

           
<div id="importButton" style="position: fixed; bottom: 30px; left: 20px; cursor: pointer; background-color: #000000; color: #ffffff; padding: 10px 15px; border-radius: 25%; font-size: 20px; text-align: top;">+</div>

<div id="dataEntryForm" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #e6e6e6; padding: 20px; border-radius: 10px; box-shadow: 10px 10px 10px rgba(0,0,0,0.5); z-index: 1000;">

    <h2>Data Entry</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <label for="fileInput">Upload CSV:</label>
        <input type="file" id="fileInput" name="fileInput" accept=".csv" />
        <br>
        <button type="button" onclick="submitData()">Submit</button>
        <button type="button" onclick="closeDataEntryForm()">Cancel</button>
    </form>
</div>

<script>
    function openDataEntryForm() {
        document.getElementById('dataEntryForm').style.display = 'block';
    }

    function closeDataEntryForm() {
        document.getElementById('dataEntryForm').style.display = 'none';
    }

    function submitData() {
        var formData = new FormData(document.getElementById('uploadForm'));

        $.ajax({
            type: 'POST',
            url: 'upload_csvzeus.php',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                closeDataEntryForm();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            },
        });
    }

    document.getElementById('importButton').addEventListener('click', openDataEntryForm);
</script>



<div id="scrollTopButton" onclick="scrollToTop()" style="position: fixed; bottom: 30px; right: 20px; cursor: pointer; background-color: #000000; color: #ffffff; padding: 10px 15px; border-radius: 25%; font-size: 20px; text-align: top;">^</div>

<script>
        function scrollToTop() {
            // Scroll to the top of the page with a smooth animation
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>

</br>
</br>

<?php require 'includes/footer.php'; ?>