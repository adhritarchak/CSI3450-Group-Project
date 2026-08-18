<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$server = "127.0.0.1";
$userName = "root";
$pass = "";
$db = "Pokemon_DB";

$con = mysqli_connect(
    $server,
    $userName,
    $pass,
    $db
);

if (!$con) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8mb4");

$tables = [
    "Region",
    "Leveling_Rate",
    "Egg_Group",
    "Ability",
    "Type",
    "Item",
    "Pokemon_Species",
    "Location",
    "Move",
    "Trainer",
    "PokemonAbility",
    "PokemonType",
    "PokemonEggGroup",
    "Pokemon",
    "Learnset",
    "PokemonMove",
    "Evolution",
    "Encounter",
    "TrainerPokemon",
    "TrainerItem",
    "TypeEffectiveness"
];

$selectedTable = $_GET["table"] ?? $_POST["table"] ?? "";

if (!in_array($selectedTable, $tables, true)) {
    $selectedTable = "";
}

$action = $_POST["action"] ?? "";

$columns = [];
$primaryKeys = [];
$autoIncrementColumns = [];

$message = "";
$error = "";

$excludedColumns = [];

if ($selectedTable === "Pokemon") {
    $excludedColumns = [
        "region_id"
    ];
}

if ($selectedTable !== "") {

    $tableSafe = mysqli_real_escape_string(
        $con,
        $selectedTable
    );

    $columnQuery = "
        SELECT
            COLUMN_NAME,
            DATA_TYPE,
            COLUMN_TYPE,
            IS_NULLABLE,
            COLUMN_DEFAULT,
            EXTRA
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = '$tableSafe'
        ORDER BY ORDINAL_POSITION
    ";

    $columnResult = mysqli_query(
        $con,
        $columnQuery
    );

    if ($columnResult) {

        while ($column = mysqli_fetch_assoc($columnResult)) {

            if (
                in_array(
                    $column["COLUMN_NAME"],
                    $excludedColumns,
                    true
                )
            ) {
                continue;
            }

            $columns[] = $column;

            if (
                strpos(
                    $column["EXTRA"],
                    "auto_increment"
                ) !== false
            ) {
                $autoIncrementColumns[] =
                    $column["COLUMN_NAME"];
            }
        }

        mysqli_free_result($columnResult);

    } else {

        $error = mysqli_error($con);
    }

    $primaryQuery = "
        SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = '$tableSafe'
        AND CONSTRAINT_NAME = 'PRIMARY'
        ORDER BY ORDINAL_POSITION
    ";

    $primaryResult = mysqli_query(
        $con,
        $primaryQuery
    );

    if ($primaryResult) {

        while ($primary = mysqli_fetch_assoc($primaryResult)) {

            if (
                in_array(
                    $primary["COLUMN_NAME"],
                    $excludedColumns,
                    true
                )
            ) {
                continue;
            }

            $primaryKeys[] =
                $primary["COLUMN_NAME"];
        }

        mysqli_free_result($primaryResult);

    } else {

        $error = mysqli_error($con);
    }
}

if (
    $selectedTable !== "" &&
    $action === "add"
) {

    $insertColumns = [];
    $insertValues = [];

    foreach ($columns as $column) {

        $columnName =
            $column["COLUMN_NAME"];

        if (
            $columnName === "region_id"
        ) {
            continue;
        }

        if (
            in_array(
                $columnName,
                $autoIncrementColumns,
                true
            )
        ) {
            continue;
        }

        if (
            in_array(
                $columnName,
                $excludedColumns,
                true
            )
        ) {
            continue;
        }

        $value =
            $_POST["field"][$columnName] ?? "";

        if (
            $value === "" &&
            $column["IS_NULLABLE"] === "YES"
        ) {

            $insertColumns[] =
                "`$columnName`";

            $insertValues[] =
                "NULL";

            continue;
        }

        if (
            $value === "" &&
            $column["COLUMN_DEFAULT"] !== null
        ) {
            continue;
        }

        $insertColumns[] =
            "`$columnName`";

        $insertValues[] =
            "'" .
            mysqli_real_escape_string(
                $con,
                $value
            ) .
            "'";
    }

    if (count($insertColumns) > 0) {

        $sql = "
            INSERT INTO `$selectedTable`
            (" . implode(
                ", ",
                $insertColumns
            ) . ")
            VALUES
            (" . implode(
                ", ",
                $insertValues
            ) . ")
        ";

        if (mysqli_query($con, $sql)) {

            $message =
                "Record added successfully.";

        } else {

            $error =
                mysqli_error($con);
        }

    } else {

        $error =
            "No values were provided.";
    }
}

if (
    $selectedTable !== "" &&
    $action === "edit"
) {

    $setParts = [];
    $whereParts = [];

    foreach ($columns as $column) {

        $columnName =
            $column["COLUMN_NAME"];

        if (
            $columnName === "region_id"
        ) {
            continue;
        }

        if (
            in_array(
                $columnName,
                $primaryKeys,
                true
            )
        ) {
            continue;
        }

        if (
            in_array(
                $columnName,
                $excludedColumns,
                true
            )
        ) {
            continue;
        }

        $value =
            $_POST["field"][$columnName] ?? "";

        if (
            $value === "" &&
            $column["IS_NULLABLE"] === "YES"
        ) {

            $setParts[] =
                "`$columnName` = NULL";

        } else {

            $escapedValue =
                mysqli_real_escape_string(
                    $con,
                    $value
                );

            $setParts[] =
                "`$columnName` = '$escapedValue'";
        }
    }

    foreach ($primaryKeys as $primaryKey) {

        $primaryValue =
            $_POST["primary"][$primaryKey] ?? "";

        $escapedPrimaryValue =
            mysqli_real_escape_string(
                $con,
                $primaryValue
            );

        $whereParts[] =
            "`$primaryKey` = '$escapedPrimaryValue'";
    }

    if (
        count($setParts) > 0 &&
        count($whereParts) === count($primaryKeys)
    ) {

        $sql = "
            UPDATE `$selectedTable`
            SET " .
            implode(
                ", ",
                $setParts
            ) .
            "
            WHERE " .
            implode(
                " AND ",
                $whereParts
            );

        if (mysqli_query($con, $sql)) {

            $message =
                "Record updated successfully.";

        } else {

            $error =
                mysqli_error($con);
        }

    } else {

        $error =
            "Unable to update this record.";
    }
}

if (
    $selectedTable !== "" &&
    $action === "delete"
) {

    $whereParts = [];

    foreach ($primaryKeys as $primaryKey) {

        $primaryValue =
            $_POST["primary"][$primaryKey] ?? "";

        $escapedPrimaryValue =
            mysqli_real_escape_string(
                $con,
                $primaryValue
            );

        $whereParts[] =
            "`$primaryKey` = '$escapedPrimaryValue'";
    }

    if (
        count($whereParts) ===
        count($primaryKeys)
    ) {

        $sql = "
            DELETE FROM `$selectedTable`
            WHERE " .
            implode(
                " AND ",
                $whereParts
            );

        if (mysqli_query($con, $sql)) {

            $message =
                "Record deleted successfully.";

        } else {

            $error =
                mysqli_error($con);
        }

    } else {

        $error =
            "Unable to delete this record.";
    }
}

$records = [];

if ($selectedTable !== "") {

    $selectColumns = [];

    foreach ($columns as $column) {

        if (
            $column["COLUMN_NAME"] === "region_id"
        ) {
            continue;
        }

        $selectColumns[] =
            "`" . $column["COLUMN_NAME"] . "`";
    }

    if (count($selectColumns) > 0) {

        $sql = "
            SELECT
                " .
                implode(
                    ", ",
                    $selectColumns
                ) .
                "
            FROM `$selectedTable`
            LIMIT 500
        ";

        $result =
            mysqli_query(
                $con,
                $sql
            );

        if ($result) {

            while (
                $row =
                mysqli_fetch_assoc($result)
            ) {

                $records[] = $row;
            }

            mysqli_free_result($result);

        } else {

            $error =
                mysqli_error($con);
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Manage Database | Pokédex Database
    </title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body>

<header class="header">

    <div class="header-container">

        <a
            href="index.html"
            class="logo logo-link"
        >

            <div class="pokeball-logo">

                <div class="pokeball-center"></div>

            </div>

            <div>

                <h1>Pokédex</h1>

                <span>Database</span>

            </div>

        </a>

    </div>

</header>

<nav class="navigation">

    <div class="nav-container">

        <a
            href="index.html"
            class="nav-item"
        >
            Home
        </a>

        <a
            href="pokemon.php"
            class="nav-item"
        >
            Pokémon
        </a>

        <a
            href="moves.php"
            class="nav-item"
        >
            Moves
        </a>

        <a
            href="abilities.php"
            class="nav-item"
        >
            Abilities
        </a>

        <a
            href="items.php"
            class="nav-item"
        >
            Items
        </a>

        <a
            href="query.php"
            class="nav-item"
        >
            Query Database
        </a>

        <a
            href="manage.php"
            class="nav-item active"
        >
            Manage Database
        </a>

    </div>

</nav>

<main class="main-container">

    <section class="page-heading">

        <p class="eyebrow">
            Database Management
        </p>

        <h2>
            Manage Database
        </h2>

        <p>
            Add, edit, and delete records from the Pokémon database.
        </p>

    </section>

    <?php if ($message !== ""): ?>

        <section
            style="
                background: #e8f7e8;
                border: 1px solid #9ed49e;
                padding: 15px 20px;
                margin-bottom: 25px;
                border-radius: 6px;
            "
        >

            <strong>
                <?php
                echo htmlspecialchars($message);
                ?>
            </strong>

        </section>

    <?php endif; ?>

    <?php if ($error !== ""): ?>

        <section
            style="
                background: #fbe8e8;
                border: 1px solid #df9b9b;
                padding: 15px 20px;
                margin-bottom: 25px;
                border-radius: 6px;
            "
        >

            <strong>
                Database Error:
            </strong>

            <br>

            <?php
            echo htmlspecialchars($error);
            ?>

        </section>

    <?php endif; ?>

    <section class="database-table-container">

        <div style="padding: 30px;">

            <h3>
                Select Table
            </h3>

            <form
                method="GET"
                action="manage.php"
                style="
                    display: flex;
                    gap: 12px;
                    flex-wrap: wrap;
                    margin-top: 20px;
                "
            >

                <select
                    name="table"
                    required
                    onchange="this.form.submit()"
                    style="
                        padding: 12px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        font-size: 16px;
                        min-width: 280px;
                    "
                >

                    <option value="">
                        Select a table
                    </option>

                    <?php foreach ($tables as $table): ?>

                        <option
                            value="<?php echo htmlspecialchars($table); ?>"
                            <?php
                            echo $selectedTable === $table
                                ? "selected"
                                : "";
                            ?>
                        >

                            <?php
                            echo htmlspecialchars($table);
                            ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </form>

        </div>

    </section>

    <?php if ($selectedTable !== ""): ?>

        <section
            class="database-table-container"
            style="margin-top: 25px;"
        >

            <div style="padding: 30px;">

                <h3>
                    Add Record
                </h3>

                <p>
                    Add a new record to
                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $selectedTable
                        );
                        ?>
                    </strong>.
                </p>

                <form
                    method="POST"
                    action="manage.php?table=<?php echo urlencode($selectedTable); ?>"
                    style="margin-top: 25px;"
                >

                    <input
                        type="hidden"
                        name="table"
                        value="<?php echo htmlspecialchars($selectedTable); ?>"
                    >

                    <input
                        type="hidden"
                        name="action"
                        value="add"
                    >

                    <div
                        style="
                            display: grid;
                            grid-template-columns: repeat(
                                auto-fit,
                                minmax(220px, 1fr)
                            );
                            gap: 20px;
                        "
                    >

                        <?php foreach ($columns as $column): ?>

                            <?php

                            $columnName =
                                $column["COLUMN_NAME"];

                            if (
                                $columnName === "region_id"
                            ) {
                                continue;
                            }

                            $isAuto =
                                in_array(
                                    $columnName,
                                    $autoIncrementColumns,
                                    true
                                );

                            ?>

                            <?php if (!$isAuto): ?>

                                <div>

                                    <label
                                        for="add_<?php echo htmlspecialchars($columnName); ?>"
                                        style="
                                            display: block;
                                            font-weight: bold;
                                            margin-bottom: 6px;
                                        "
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $columnName
                                        );
                                        ?>

                                        <?php

                                        if (
                                            $column["IS_NULLABLE"] === "NO" &&
                                            $column["COLUMN_DEFAULT"] === null
                                        ) {
                                            echo " *";
                                        }

                                        ?>

                                    </label>

                                    <?php if (
                                        $column["DATA_TYPE"] === "text" ||
                                        $column["DATA_TYPE"] === "mediumtext" ||
                                        $column["DATA_TYPE"] === "longtext"
                                    ): ?>

                                        <textarea
                                            id="add_<?php echo htmlspecialchars($columnName); ?>"
                                            name="field[<?php echo htmlspecialchars($columnName); ?>]"
                                            rows="4"
                                            style="
                                                width: 100%;
                                                box-sizing: border-box;
                                                padding: 10px;
                                                border: 1px solid #ccc;
                                                border-radius: 5px;
                                            "
                                        ></textarea>

                                    <?php elseif (
                                        $column["DATA_TYPE"] === "tinyint" &&
                                        strpos(
                                            $column["COLUMN_TYPE"],
                                            "tinyint(1)"
                                        ) !== false
                                    ): ?>

                                        <select
                                            id="add_<?php echo htmlspecialchars($columnName); ?>"
                                            name="field[<?php echo htmlspecialchars($columnName); ?>]"
                                            style="
                                                width: 100%;
                                                padding: 10px;
                                                border: 1px solid #ccc;
                                                border-radius: 5px;
                                            "
                                        >

                                            <option value="">
                                                Select
                                            </option>

                                            <option value="0">
                                                No / 0
                                            </option>

                                            <option value="1">
                                                Yes / 1
                                            </option>

                                        </select>

                                    <?php else: ?>

                                        <input
                                            type="<?php

                                            echo in_array(
                                                $column["DATA_TYPE"],
                                                [
                                                    "int",
                                                    "decimal",
                                                    "float",
                                                    "double"
                                                ],
                                                true
                                            )
                                                ? "number"
                                                : "text";

                                            ?>"
                                            id="add_<?php echo htmlspecialchars($columnName); ?>"
                                            name="field[<?php echo htmlspecialchars($columnName); ?>]"
                                            <?php

                                            if (
                                                $column["IS_NULLABLE"] === "NO" &&
                                                $column["COLUMN_DEFAULT"] === null
                                            ) {
                                                echo "required";
                                            }

                                            ?>
                                            style="
                                                width: 100%;
                                                box-sizing: border-box;
                                                padding: 10px;
                                                border: 1px solid #ccc;
                                                border-radius: 5px;
                                            "
                                        >

                                    <?php endif; ?>

                                    <small
                                        style="
                                            display: block;
                                            margin-top: 5px;
                                            color: #666;
                                        "
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $column["COLUMN_TYPE"]
                                        );
                                        ?>

                                        <?php

                                        echo $column["IS_NULLABLE"] === "YES"
                                            ? " | Optional"
                                            : " | Required";

                                        ?>

                                    </small>

                                </div>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    </div>

                    <button
                        type="submit"
                        class="search-button"
                        style="
                            margin-top: 25px;
                            cursor: pointer;
                            padding: 12px 25px;
                        "
                    >
                        Add Record
                    </button>

                </form>

            </div>

        </section>

        <section
            class="database-table-container"
            style="margin-top: 25px;"
        >

            <div
                style="
                    padding: 25px 30px;
                    border-bottom: 1px solid #ddd;
                "
            >

                <h3>
                    <?php
                    echo htmlspecialchars(
                        $selectedTable
                    );
                    ?>
                    Records
                </h3>

                <p>
                    Showing up to 500 records.
                </p>

            </div>

            <div style="overflow-x: auto;">

                <table class="database-table">

                    <thead>

                        <tr>

                            <?php foreach ($columns as $column): ?>

                                <?php if ($column["COLUMN_NAME"] === "region_id") continue; ?>

                                <th>
                                    <?php
                                    echo htmlspecialchars(
                                        $column["COLUMN_NAME"]
                                    );
                                    ?>
                                </th>

                            <?php endforeach; ?>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if (count($records) > 0): ?>

                            <?php foreach ($records as $record): ?>

                                <tr>

                                    <?php foreach ($columns as $column): ?>

                                        <?php if ($column["COLUMN_NAME"] === "region_id") continue; ?>

                                        <td>

                                            <?php

                                            $columnName =
                                                $column["COLUMN_NAME"];

                                            if (
                                                $record[$columnName] === null
                                            ) {

                                                echo "NULL";

                                            } else {

                                                echo htmlspecialchars(
                                                    $record[$columnName]
                                                );
                                            }

                                            ?>

                                        </td>

                                    <?php endforeach; ?>

                                    <td>

                                        <button
                                            type="button"
                                            onclick="openEditForm(
                                                <?php
                                                echo htmlspecialchars(
                                                    json_encode(
                                                        $record
                                                    ),
                                                    ENT_QUOTES,
                                                    "UTF-8"
                                                );
                                                ?>
                                            )"
                                            class="search-button"
                                            style="
                                                cursor: pointer;
                                                margin-right: 5px;
                                            "
                                        >
                                            Edit
                                        </button>

                                        <form
                                            method="POST"
                                            action="manage.php?table=<?php echo urlencode($selectedTable); ?>"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this record?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="table"
                                                value="<?php echo htmlspecialchars($selectedTable); ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="delete"
                                            >

                                            <?php foreach ($primaryKeys as $primaryKey): ?>

                                                <input
                                                    type="hidden"
                                                    name="primary[<?php echo htmlspecialchars($primaryKey); ?>]"
                                                    value="<?php echo htmlspecialchars($record[$primaryKey]); ?>"
                                                >

                                            <?php endforeach; ?>

                                            <button
                                                type="submit"
                                                class="search-button"
                                                style="
                                                    cursor: pointer;
                                                    background: #c0392b;
                                                "
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="<?php echo count($columns) + 1; ?>"
                                    style="
                                        text-align: center;
                                        padding: 40px;
                                    "
                                >
                                    No records found.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

        <section
            id="editSection"
            class="database-table-container"
            style="
                margin-top: 25px;
                display: none;
            "
        >

            <div style="padding: 30px;">

                <h3>
                    Edit Record
                </h3>

                <form
                    method="POST"
                    action="manage.php?table=<?php echo urlencode($selectedTable); ?>"
                    id="editForm"
                    style="margin-top: 25px;"
                >

                    <input
                        type="hidden"
                        name="table"
                        value="<?php echo htmlspecialchars($selectedTable); ?>"
                    >

                    <input
                        type="hidden"
                        name="action"
                        value="edit"
                    >

                    <div id="editPrimaryFields"></div>

                    <div
                        id="editFields"
                        style="
                            display: grid;
                            grid-template-columns: repeat(
                                auto-fit,
                                minmax(220px, 1fr)
                            );
                            gap: 20px;
                        "
                    ></div>

                    <div
                        style="
                            margin-top: 25px;
                            display: flex;
                            gap: 10px;
                        "
                    >

                        <button
                            type="submit"
                            class="search-button"
                            style="
                                cursor: pointer;
                                padding: 12px 25px;
                            "
                        >
                            Save Changes
                        </button>

                        <button
                            type="button"
                            class="search-button"
                            onclick="closeEditForm()"
                            style="
                                cursor: pointer;
                                padding: 12px 25px;
                            "
                        >
                            Cancel
                        </button>

                    </div>

                </form>

            </div>

        </section>

    <?php endif; ?>

</main>

<footer class="footer">

    <div class="footer-container">

        <span>
            Pokédex Database
        </span>

        <span>
            Database Management System
        </span>

    </div>

</footer>

<?php if ($selectedTable !== ""): ?>

<script>

const tableColumns =
    <?php echo json_encode($columns); ?>;

const primaryKeys =
    <?php echo json_encode($primaryKeys); ?>;

function openEditForm(record) {

    const section =
        document.getElementById(
            "editSection"
        );

    const fields =
        document.getElementById(
            "editFields"
        );

    const primaryFields =
        document.getElementById(
            "editPrimaryFields"
        );

    fields.innerHTML = "";
    primaryFields.innerHTML = "";

    primaryKeys.forEach(function(primaryKey) {

        const input =
            document.createElement(
                "input"
            );

        input.type = "hidden";

        input.name =
            "primary[" +
            primaryKey +
            "]";

        input.value =
            record[primaryKey] ?? "";

        primaryFields.appendChild(
            input
        );

    });

    tableColumns.forEach(function(column) {

        const name =
            column.COLUMN_NAME;

        if (
            name === "region_id"
        ) {
            return;
        }

        const wrapper =
            document.createElement(
                "div"
            );

        const label =
            document.createElement(
                "label"
            );

        label.textContent =
            name;

        label.style.display =
            "block";

        label.style.fontWeight =
            "bold";

        label.style.marginBottom =
            "6px";

        wrapper.appendChild(
            label
        );

        if (
            primaryKeys.includes(name)
        ) {

            const value =
                document.createElement(
                    "input"
                );

            value.type =
                "text";

            value.value =
                record[name] ?? "";

            value.disabled =
                true;

            value.style.width =
                "100%";

            value.style.boxSizing =
                "border-box";

            value.style.padding =
                "10px";

            value.style.border =
                "1px solid #ccc";

            value.style.borderRadius =
                "5px";

            wrapper.appendChild(
                value
            );

        } else {

            const input =
                document.createElement(
                    "input"
                );

            input.type =
                [
                    "int",
                    "decimal",
                    "float",
                    "double"
                ].includes(
                    column.DATA_TYPE
                )
                    ? "number"
                    : "text";

            input.name =
                "field[" +
                name +
                "]";

            input.value =
                record[name] ?? "";

            input.style.width =
                "100%";

            input.style.boxSizing =
                "border-box";

            input.style.padding =
                "10px";

            input.style.border =
                "1px solid #ccc";

            input.style.borderRadius =
                "5px";

            wrapper.appendChild(
                input
            );
        }

        const small =
            document.createElement(
                "small"
            );

        small.textContent =
            column.COLUMN_TYPE +
            (
                column.IS_NULLABLE === "YES"
                    ? " | Optional"
                    : " | Required"
            );

        small.style.display =
            "block";

        small.style.marginTop =
            "5px";

        small.style.color =
            "#666";

        wrapper.appendChild(
            small
        );

        fields.appendChild(
            wrapper
        );

    });

    section.style.display =
        "block";

    section.scrollIntoView({
        behavior: "smooth"
    });
}

function closeEditForm() {

    const section =
        document.getElementById(
            "editSection"
        );

    section.style.display =
        "none";
}

</script>

<?php endif; ?>

</body>

</html>

<?php

mysqli_close($con);

?>