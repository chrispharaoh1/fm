<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Financial Management</title>

  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Financial Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Financial Management</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
            <h2>Financial Transactions</h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTransactionModal">
              Add New Transaction
            </button>
            <div class='table-responsive mt-3'>
              <table id="transactionsTable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Category</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $conn = new mysqli('localhost', 'root', '', 'farm_management');
                  if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                  }

                  $result = $conn->query("SELECT * FROM financial_transactions");
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['transaction_type']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['amount']}</td>
                            <td>{$row['transaction_date']}</td>
                            <td>{$row['category']}</td>
                          </tr>";
                  }

                  $conn->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="col-lg-6">
            <h2>Budgets</h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addBudgetModal">
              Add New Budget
            </button>
            <div class='table-responsive mt-3'>
              <table id="budgetsTable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Budget Name</th>
                    <th>Amount</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $conn = new mysqli('localhost', 'root', '', 'farm_management');
                  if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                  }

                  $result = $conn->query("SELECT * FROM budgets");
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['budget_name']}</td>
                            <td>{$row['amount']}</td>
                            <td>{$row['start_date']}</td>
                            <td>{$row['end_date']}</td>
                          </tr>";
                  }

                  $conn->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Transaction Modal -->
  <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="transactionForm" method="post" action="addTransaction.php">
          <div class="modal-header">
            <h5 class="modal-title" id="addTransactionModalLabel">Add New Transaction</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="transactionType" class="form-label">Transaction Type</label>
              <select class="form-control" id="transactionType" name="transactionType" required>
                <option value="expense">Expense</option>
                <option value="revenue">Revenue</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="mb-3">
              <label for="amount" class="form-label">Amount</label>
              <input type="number" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
              <label for="category" class="form-label">Category</label>
              <input type="text" class="form-control" id="category" name="category">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add Budget Modal -->
  <div class="modal fade" id="addBudgetModal" tabindex="-1" aria-labelledby="addBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="budgetForm" method="post" action="addBudget.php">
          <div class="modal-header">
            <h5 class="modal-title" id="addBudgetModalLabel">Add New Budget</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="budgetName" class="form-label">Budget Name</label>
              <input type="text" class="form-control" id="budgetName" name="budgetName" required>
            </div>
            <div class="mb-3">
              <label for="budgetAmount" class="form-label">Amount</label>
              <input type="number" class="form-control" id="budgetAmount" name="amount" required>
            </div>
            <div class="mb-3">
              <label for="startDate" class="form-label">Start Date</label>
              <input type="date" class="form-control" id="startDate" name="startDate" required>
            </div>
            <div class="mb-3">
              <label for="endDate" class="form-label">End Date</label>
              <input type="date" class="form-control" id="endDate" name="endDate" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
