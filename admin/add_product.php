<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Style the modal (hidden by default) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.4); /* Black background with opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be adjusted */
            max-width: 600px;
        }

        /* The Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Button to open the modal and load content -->
    <button id="productBtn">Add Product</button>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeBtn">&times;</span>
            <div id="modalContent">Loading...</div> <!-- Content will be loaded here -->
        </div>
    </div>

    <script>
        // Get modal elements
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("productBtn");
        var span = document.getElementById("closeBtn");
        var modalContent = document.getElementById("modalContent");

        // When the user clicks the button, open the modal and load the PHP file
        btn.onclick = function() {
            // Show the modal
            modal.style.display = "block";
            
            // Fetch the content of popup_content.php using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "admin/add_product_popup.php", true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    // Insert the response (content from popup_content.php) into the modal
                    modalContent.innerHTML = xhr.responseText;
                } else {
                    modalContent.innerHTML = "Failed to load content.";
                }
            };
            xhr.send();
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>
