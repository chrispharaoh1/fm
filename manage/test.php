<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>

<div class="container mt-5">
    <h2>Inventory Management</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
        Add New Item
    </button>

    <table id="inventoryTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Item Type</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Low Inventory Threshold</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here by PHP -->
            <?php
            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'farm_management');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $result = $conn->query("SELECT * FROM inventory");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['item_type']}</td>
                        <td>{$row['item_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['unit']}</td>
                        <td>{$row['low_inventory_threshold']}</td>
                        <td>
                            <div class='action-dropdown'>
                                <i class='fas fa-ellipsis-v' data-bs-toggle='dropdown'></i>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item edit-btn' href='#' data-id='{$row['id']}'><i class='fa fa-edit'></i>&#160;Edit</a></li>
                                    <li><a class='dropdown-item' href='inventoryDelete.php?id={$row['id']}'><i class='fa fa-trash'></i>&#160;Delete</a></li>
                                </ul>
                            </div>
                        </td>
                      </tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Add Inventory Modal -->
<div class="modal fade" id="addInventoryModal" tabindex="-1" aria-labelledby="addInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="inventoryForm" method="post" action="inventoryAddProcess.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInventoryModalLabel">Add New Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="itemType" class="form-label">Item Type</label>
                        <select class="form-control" id="itemType" name="itemType" required>
                            <option value="seed">Seed</option>
                            <option value="fertilizer">Fertilizer</option>
                            <option value="pesticide">Pesticide</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="itemName" name="itemName" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <input type="text" class="form-control" id="unit" name="unit" required>
                    </div>
                    <div class="mb-3">
                        <label for="lowInventoryThreshold" class="form-label">Low Inventory Threshold</label>
                        <input type="number" class="form-control" id="lowInventoryThreshold" name="lowInventoryThreshold" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Inventory Modal -->
<div class="modal fade" id="editInventoryModal" tabindex="-1" aria-labelledby="editInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editInventoryForm" method="post" action="inventoryEditProcess.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInventoryModalLabel">Edit Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editItemId" name="id">
                    <div class="mb-3">
                        <label for="editItemType" class="form-label">Item Type</label>
                        <select class="form-control" id="editItemType" name="itemType" required>
                            <option value="seed">Seed</option>
                            <option value="fertilizer">Fertilizer</option>
                            <option value="pesticide">Pesticide</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editItemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="editItemName" name="itemName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="editQuantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUnit" class="form-label">Unit</label>
                        <input type="text" class="form-control" id="editUnit" name="unit" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLowInventoryThreshold" class="form-label">Low Inventory Threshold</label>
                        <input type="number" class="form-control" id="editLowInventoryThreshold" name="lowInventoryThreshold" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#inventoryTable').DataTable();

    // Open edit modal and populate data
    $('.edit-btn').on('click', function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'inventoryFetch.php',
            type: 'GET',
            data: { id: id },
            success: function(data) {
                var item = JSON.parse(data);
                $('#editItemId').val(item.id);
                $('#editItemType').val(item.item_type);
                $('#editItemName').val(item.item_name);
                $('#editQuantity').val(item.quantity);
                $('#editUnit').val(item.unit);
                $('#editLowInventoryThreshold').val(item.low_inventory_threshold);
                $('#editInventoryModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
    });

    $('#editInventoryForm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: 'inventoryEditProcess.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(data) {
                $('#editInventoryModal').modal('hide');
                location.reload(); // Reload the page to see the updated data in the table
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
    });
});
</script>
</body>
</html>
