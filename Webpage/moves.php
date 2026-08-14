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
    m.move_id,
    m.move_name,
    m.move_bp,
    m.move_category,
    m.move_pp,
    m.move_accuracy,
    m.move_effect,
    m.move_desc,

    t.type_id,
    t.type_name,

    GROUP_CONCAT(
        DISTINCT ps.species_name
        ORDER BY ps.dex_num, ps.species_id
        SEPARATOR ', '
    ) AS Pokemon_Learnset

FROM `Move` m

LEFT JOIN `Type` t
    ON m.type_id = t.type_id

LEFT JOIN Learnset ls
    ON m.move_id = ls.move_id

LEFT JOIN Pokemon_Species ps
    ON ls.species_id = ps.species_id
";

if ($search !== "") {
    $sql .= "
        WHERE
            m.move_name LIKE '%$searchSafe%'
            OR CAST(m.move_id AS CHAR) LIKE '%$searchSafe%'
            OR t.type_name LIKE '%$searchSafe%'
            OR m.move_category LIKE '%$searchSafe%'
            OR m.move_desc LIKE '%$searchSafe%'
            OR m.move_effect LIKE '%$searchSafe%'
            OR ps.species_name LIKE '%$searchSafe%'
    ";
}

$sql .= "
GROUP BY
    m.move_id,
    m.move_name,
    m.move_bp,
    m.move_category,
    m.move_pp,
    m.move_accuracy,
    m.move_effect,
    m.move_desc,
    t.type_id,
    t.type_name

ORDER BY
    m.move_id ASC
";

$result = mysqli_query(
    $con,
    $sql
);

if (!$result) {
    die(
        "Move query failed: "
        . mysqli_error($con)
    );
}

$moves = [];

while ($row = mysqli_fetch_assoc($result)) {
    $moves[] = $row;
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

    <title>Moves | Pokédex Database</title>

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
            class="nav-item active"
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

    </div>

</nav>

<main class="main-container">

    <section class="page-heading">

        <p class="eyebrow">
            Database
        </p>

        <h2>
            Moves
        </h2>

        <p>
            Browse and search moves stored in the database.
        </p>

    </section>

    <section class="database-controls">

        <form
            method="GET"
            action="moves.php"
            class="database-search"
        >

            <span class="search-icon">
                ⌕
            </span>

            <input
                type="text"
                name="search"
                placeholder="Search by move name, ID, type, or Pokémon..."
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
                Move List
            </h3>

            <p>

                <?php

                $count = count($moves);

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
                        . " moves";

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
                        Move
                    </th>

                    <th>
                        Type
                    </th>

                    <th>
                        Category
                    </th>

                    <th>
                        Power
                    </th>

                    <th>
                        Accuracy
                    </th>

                    <th>
                        PP
                    </th>

                    <th>
                        Pokémon That Learn
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php if (count($moves) > 0): ?>

                <?php foreach ($moves as $move): ?>

                <tr>

                    <td>

                        <span class="pokemon-id">

                            #

                            <?php

                            echo htmlspecialchars(
                                $move["move_id"]
                            );

                            ?>

                        </span>

                    </td>

                    <td>

                        <strong>

                            <?php

                            echo htmlspecialchars(
                                $move["move_name"]
                                ?? "Unknown"
                            );

                            ?>

                        </strong>

                    </td>

                    <td>

                        <?php

                        if (!empty($move["type_name"])) {

                            echo htmlspecialchars(
                                $move["type_name"]
                            );

                        } else {

                            echo "—";

                        }

                        ?>

                    </td>

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $move["move_category"]
                            ?? "—"
                        );

                        ?>

                    </td>

                    <td>

                        <?php

                        if (
                            $move["move_bp"] !== null &&
                            $move["move_bp"] !== ""
                        ) {

                            echo htmlspecialchars(
                                $move["move_bp"]
                            );

                        } else {

                            echo "—";

                        }

                        ?>

                    </td>

                    <td>

                        <?php

                        if (
                            $move["move_accuracy"] !== null &&
                            $move["move_accuracy"] !== ""
                        ) {

                            echo htmlspecialchars(
                                $move["move_accuracy"]
                            );

                        } else {

                            echo "—";

                        }

                        ?>

                    </td>

                    <td>

                        <?php

                        if (
                            $move["move_pp"] !== null &&
                            $move["move_pp"] !== ""
                        ) {

                            echo htmlspecialchars(
                                $move["move_pp"]
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
                                $move["Pokemon_Learnset"]
                            )
                        ) {

                            echo htmlspecialchars(
                                $move["Pokemon_Learnset"]
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
                        colspan="8"
                        style="
                            text-align: center;
                            padding: 40px;
                        "
                    >

                        <strong>
                            No moves found.
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