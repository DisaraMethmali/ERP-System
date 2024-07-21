<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Output the SweetAlert2 JavaScript for confirmation
    echo "<!DOCTYPE html>
          <html lang='en'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>Delete Confirmation</title>
              <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css'>
              <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                  document.addEventListener('DOMContentLoaded', function() {
                      Swal.fire({
                          title: 'Are you sure?',
                          text: 'You will not be able to recover this record!',
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Yes, delete it!',
                          cancelButtonText: 'Cancel'
                      }).then((result) => {
                          if (result.isConfirmed) {
                              // Perform AJAX request to delete_customer.php
                              fetch('perform_delete.php?id=" . $id . "', {
                                  method: 'GET'
                              }).then(response => response.json()).then(data => {
                                  if (data.status === 'success') {
                                      Swal.fire(
                                          'Deleted!',
                                          'Your record has been deleted.',
                                          'success'
                                      ).then(() => {
                                          window.location.href = 'view_customers.php';
                                      });
                                  } else {
                                      Swal.fire(
                                          'Error!',
                                          'There was an error deleting the record.',
                                          'error'
                                      ).then(() => {
                                          window.location.href = 'view_customers.php';
                                      });
                                  }
                              }).catch(error => {
                                  Swal.fire(
                                      'Error!',
                                      'There was an error deleting the record.',
                                      'error'
                                  ).then(() => {
                                      window.location.href = 'view_customers.php';
                                  });
                              });
                          } else {
                              window.location.href = 'view_customers.php';
                          }
                      });
                  });
              </script>
          </head>
          <body>
          </body>
          </html>";

    $conn->close();
    exit();
}
