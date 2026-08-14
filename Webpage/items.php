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
    die(
        "Failed to connect to MySQL: "
        . mysqli_connect_error()
    );
}

mysqli_set_charset($con, "utf8mb4");

$search = trim($_GET["search"] ?? "");

$searchSafe = mysqli_real_escape_string(
    $con,
    $search
);

$sql = "
SELECT
    i.item_id,
    i.item_name,
    i.item_desc,

    GROUP_CONCAT(
        DISTINCT CONCAT(
            tr.trainer_name,
            ' (',
            ti.amount,
            ')'
        )
        ORDER BY tr.trainer_id
        SEPARATOR ', '
    ) AS Trainers_With_Item

FROM Item i

LEFT JOIN TrainerItem ti
    ON i.item_id = ti.item_id

LEFT JOIN Trainer tr
    ON ti.trainer_id = tr.trainer_id
";

if ($search !== "") {
    $sql .= "
        WHERE
            i.item_name LIKE '%$searchSafe%'
            OR CAST(i.item_id AS CHAR) LIKE '%$searchSafe%'
            OR i.item_desc LIKE '%$searchSafe%'
            OR tr.trainer_name LIKE '%$searchSafe%'
    ";
}

$sql .= "
GROUP BY
    i.item_id,
    i.item_name,
    i.item_desc

ORDER BY
    i.item_id ASC
";

$result = mysqli_query(
    $con,
    $sql
);

if (!$result) {
    die(
        "Item query failed: "
        . mysqli_error($con)
    );
}

$items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
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

    <title>Items | Pokédex Database</title>

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
            class="nav-item active"
        >
            Items
        </a>

        <a
            href="query.php"
            class="nav-item"
        >
            Query Database
        </a>

    </div>

</nav>

<main class="main-container">

    <section class="page-heading">

        <p class="eyebrow">
            Database
        </p>

        <h2>
            Items
        </h2>

        <p>
            Browse and search items stored in the database.
        </p>

    </section>

    <section class="database-controls">

        <form
            method="GET"
            action="items.php"
            class="database-search"
        >

            <span class="search-icon">
                ⌕
            </span>

            <input
                type="text"
                name="search"
                placeholder="Search by item name, ID, description, or trainer..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button
                type="submit"
                class="search-button"
            >
                Search
            </button>

        </form>

    </section>

    <section class="results-header">

        <div>

            <h3>
                Item List
            </h3>

            <p>

                <?php

                $count = count($items);

                if ($search !== "") {

                    echo "Found "
                        . $count
                        . " result";

                    if ($count != 1) {
                        echo "s";
                    }

                    echo " for \""
                        . htmlspecialchars($search)
                        . "\"";

                } else {

                    echo "Showing "
                        . $count
                        . " items";

                }

                ?>

            </p>

        </div>

    </section>

    <section class="database-table-container">

        <table class="database-table">

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Item
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Trainers With Item
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php if (count($items) > 0): ?>

                <?php foreach ($items as $item): ?>

                <tr>

                    <td>

                        <span class="pokemon-id">

                            #

                            <?php

                            echo htmlspecialchars(
                                $item["item_id"]
                            );

                            ?>

                        </span>

                    </td>

                    <td>

                        <strong>

                            <?php

                            echo htmlspecialchars(
                                $item["item_name"]
                                ?? "Unknown"
                            );

                            ?>

                        </strong>

                    </td>

                    <td>

                        <?php

                        if (
                            !empty(
                                $item["item_desc"]
                            )
                        ) {

                            echo htmlspecialchars(
                                $item["item_desc"]
                            );

                        } else {

                            echo "—";

                        }

                        ?>

                    </td>

                    <td>

                        <?php

                        if (
                            !empty(
                                $item["Trainers_With_Item"]
                            )
                        ) {

                            echo htmlspecialchars(
                                $item["Trainers_With_Item"]
                            );

                        } else {

                            echo "—";

                        }

                        ?>

                    </td>

                </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td
                        colspan="4"
                        style="
                            text-align: center;
                            padding: 40px;
                        "
                    >

                        <strong>
                            No items found.
                        </strong>

                        <?php if ($search !== ""): ?>

                            <br>

                            Try a different search.

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </section>

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

<?php

mysqli_free_result($result);
mysqli_close($con);

?>

</body>

</html>