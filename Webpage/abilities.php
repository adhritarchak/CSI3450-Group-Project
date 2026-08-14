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
    a.ability_id,
    a.ability_name,
    a.ability_desc,

    GROUP_CONCAT(
        DISTINCT ps.species_name
        ORDER BY ps.dex_num, ps.species_id
        SEPARATOR ', '
    ) AS Pokemon_With_Ability

FROM Ability a

LEFT JOIN PokemonAbility pa
    ON a.ability_id = pa.ability_id

LEFT JOIN Pokemon_Species ps
    ON pa.species_id = ps.species_id
";

if ($search !== "") {
    $sql .= "
        WHERE
            a.ability_name LIKE '%$searchSafe%'
            OR a.ability_desc LIKE '%$searchSafe%'
            OR CAST(a.ability_id AS CHAR) LIKE '%$searchSafe%'
            OR ps.species_name LIKE '%$searchSafe%'
    ";
}

$sql .= "
GROUP BY
    a.ability_id,
    a.ability_name,
    a.ability_desc

ORDER BY
    a.ability_id ASC
";

$result = mysqli_query(
    $con,
    $sql
);

if (!$result) {
    die(
        "Ability query failed: "
        . mysqli_error($con)
    );
}

$abilities = [];

while ($row = mysqli_fetch_assoc($result)) {
    $abilities[] = $row;
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

    <title>Abilities | Pokédex Database</title>

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
            class="nav-item active"
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
            Abilities
        </h2>

        <p>
            Browse and search abilities stored in the database.
        </p>

    </section>

    <section class="database-controls">

        <form
            method="GET"
            action="abilities.php"
            class="database-search"
        >

            <span class="search-icon">
                ⌕
            </span>

            <input
                type="text"
                name="search"
                placeholder="Search by ability name, ID, description, or Pokémon..."
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
                Ability List
            </h3>

            <p>

                <?php

                $count = count($abilities);

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
                        . " abilities";

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
                        Ability
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Pokémon With Ability
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php if (count($abilities) > 0): ?>

                <?php foreach ($abilities as $ability): ?>

                <tr>

                    <td>

                        <span class="pokemon-id">

                            #

                            <?php

                            echo htmlspecialchars(
                                $ability["ability_id"]
                            );

                            ?>

                        </span>

                    </td>

                    <td>

                        <strong>

                            <?php

                            echo htmlspecialchars(
                                $ability["ability_name"]
                                ?? "Unknown"
                            );

                            ?>

                        </strong>

                    </td>

                    <td>

                        <?php

                        if (
                            !empty(
                                $ability["ability_desc"]
                            )
                        ) {

                            echo htmlspecialchars(
                                $ability["ability_desc"]
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
                                $ability["Pokemon_With_Ability"]
                            )
                        ) {

                            echo htmlspecialchars(
                                $ability["Pokemon_With_Ability"]
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
                            No abilities found.
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