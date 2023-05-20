<?php
// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $dates = $_POST['date'];
    $descriptions = $_POST['description'];
    $amounts = $_POST['amount'];
    $entryTypes = $_POST['entryType'];

    // Create an array to hold the journal entries
    $journalEntries = [];

    // Process each entry
    for ($i = 0; $i < count($dates); $i++) {
        $date = $dates[$i];
        $description = $descriptions[$i];
        $amount = $amounts[$i];
        $entryType = $entryTypes[$i];

        // Create an entry object
        $entry = [
            'date' => $date,
            'description' => $description,
            'amount' => $amount,
            'entryType' => $entryType
        ];

        // Add the entry to the journal entries array
        $journalEntries[] = $entry;
    }

    // echo count($journalEntries);

    // print_r($journalEntries[0]['date']);
}
?>
<!DOCTYPE html>

<head>
    <title> Generated </title>
    <style>
        * {
            font-family: 'Courier New', Courier, monospace;
        }

        body {
            background-color: #121212;
            color: #f2f2ff;
        }

        h2 {
            font-size: 1.7rem;
            text-align: center;
            color: teal;
            font-weight: 900;
            letter-spacing: 2px;
            animation: glow 1s infinite;
        }

        table {
            margin: auto;
            width: 55%;
            border-collapse: collapse;
        }

        table th {
            font-weight: 900;
            font-size: 1.2rem;
        }

        table td,
        table th {
            /* background-color: aquamarine; */
            padding: 5px 10px;
            border: 2px solid teal;
            word-break: break-word;
        }

        .ledger-table-container {
            width: 100%;
            margin: 35px auto;
        }

        .bal-cf {
            color: tomato;
        }

        @keyframes glow {
            0% {
                color: #00f0f0;
            }

            70% {
                color: #00f0f0;
            }

            85% {
                color: teal;
            }

            100% {
                color: #00f0f0;
            }
        }
    </style>
</head>

<body>
    <h2>LEDGERS</h2>

    <?php
    $ledgerAccounts = [];
    $entries = count($journalEntries);

    $ledgerAccounts[] = $journalEntries[0]['description'];

    for ($i = 0; $i < $entries; $i++) {
        $entry = $journalEntries[$i];
        if (array_search($entry['description'], $ledgerAccounts) === false) {
            $ledgerAccounts[] = $entry['description'];
        }
    }

    // Array ( [0] => Arieb's Capital [1] => Rent Exp )
    $matrix = array();
    for ($row = 0; $row < count($ledgerAccounts); $row++) {
        $matrix[$row] = array();

        for ($i = 0; $i < $entries; $i++) {
            $entry = $journalEntries[$i];
            if ($entry['description'] == $ledgerAccounts[$row]) {
                $matrix[$row][] = $entry;
            }
        }
    }

    // Matrix => all accounts for ledgers with data

    ?>

    <?php
    for ($i = 0; $i != count($matrix); $i++) {
    ?>
        <div class="ledger-table-container">
            <table>
                <tr>
                    <th colspan="6"><?php echo $matrix[$i][0]['description'];
                                    $debitAmount = 0;
                                    $creditAmount = 0; ?> Account</th>
                </tr>
                <tr>
                    <th colspan="3">Debit</th>
                    <th colspan="3">Credit</th>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>Description</td>
                    <td>Amount</td>
                    <td>Date</td>
                    <td>Description</td>
                    <td>Amount</td>
                </tr>

                <?php for ($roww = 0; $roww < count($matrix[$i]); $roww++) { ?>
                    <tr>
                        <td><?php if ($matrix[$i][$roww]['entryType'] == 'debit') {
                                echo $matrix[$i][$roww]['date'];
                            } else {
                                echo " ";
                            } ?></td>
                        <td><?php if ($matrix[$i][$roww]['entryType'] == 'debit') {
                                echo $matrix[$i][$roww]['description'];
                            } else {
                                echo " ";
                            } ?></td>
                        <td><?php if ($matrix[$i][$roww]['entryType'] == 'debit') {
                                echo number_format($matrix[$i][$roww]['amount'], 0, '.', ',');
                                $debitAmount += $matrix[$i][$roww]['amount'];
                            } else {
                                echo " ";
                            } ?></td>
                        <td><?php if ($matrix[$i][$roww]['entryType'] == 'credit') {
                                echo $matrix[$i][$roww]['date'];
                            } else {
                                echo " ";
                            } ?></td>
                        <td><?php if ($matrix[$i][$roww]['entryType'] == 'credit') {
                                echo $matrix[$i][$roww]['description'];
                            } else {
                                echo " ";
                            } ?></td>
                        <td><?php if ($matrix[$i][$roww]['entryType'] == 'credit') {
                                echo number_format($matrix[$i][$roww]['amount'], 0, '.', ',');
                                $creditAmount += $matrix[$i][$roww]['amount'];
                            } else {
                                echo " ";
                            } ?></td>
                    </tr>
                <?php } ?>

                <?php
                if ($debitAmount > $creditAmount) {
                    // Balance CF Dr in Credit Side
                    $balanceCF = $debitAmount - $creditAmount;

                ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="bal-cf">Balance CF Dr</td>
                        <td class="bal-cf"><?php echo number_format($balanceCF, 0, '.', ',');
                                            $creditAmount += $balanceCF; ?></td>
                    </tr>
                <?php
                } else if ($debitAmount < $creditAmount) {
                    // Balance CF Cr in Debit Side
                    $balanceCF = $creditAmount - $debitAmount;
                ?>
                    <tr>
                        <td></td>
                        <td class="bal-cf">Balance CF Cr</td>
                        <td class="bal-cf"><?php echo number_format($balanceCF, 0, '.', ',');
                                            $debitAmount += $balanceCF; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php
                }
                ?>

                <tr>
                    <td colspan="2">Total Debit</td>
                    <td><?php echo number_format($debitAmount, 0, '.', ',') ?></td>
                    <td colspan="2">Total Credit</td>
                    <td><?php echo number_format($creditAmount, 0, '.', ',') ?></td>
                </tr>
            </table>
        </div>
    <?php
    }
    ?>
</body>

<!-- 


[0] =>
    [0] => ( [date] => 2023-05-01 [description] => Arieb's Capital [amount] => 50000 [entryType] => debit )
    [1] => ( [date] => 2023-05-02 [description] => Arieb's Capital [amount] => 2000 [entryType] => debit )
    [2] => ( [date] => 2023-05-03 [description] => Arieb's Capital [amount] => 8000 [entryType] => debit ) 

[1] => (
     [0] => ( [date] => 2023-05-04 [description] => Rent Exp [amount] => 10000 [entryType] => debit ) 
    ) 



 -->

<?php


// echo "<td>" . $journalEntries[0]['date'] . "</td>";
// echo "<td>" . $journalEntries[0]['description'] . "</td>";
// echo "<td>" . $journalEntries[0]['amount'] . " PKR</td>";
// echo "<td>" . $journalEntries[0]['date'] . "</td>";
// echo "<td>" . $journalEntries[0]['date'] . "</td>";
// echo "<td>" . $journalEntries[0]['amount'] . " PKR</td>";
?>