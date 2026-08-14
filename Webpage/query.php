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

$query = trim($_POST["query"] ?? "");

$selectedTable = $_POST["table"] ?? "";

$result = null;

$columns = [];

$rows = [];

$error = "";

$success = "";

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

if (
    isset($_POST["show_all"]) &&
    $selectedTable !== ""
) {

    if (in_array($selectedTable, $tables, true)) {

        $sql = "SELECT * FROM `" . $selectedTable . "`";

        $result = mysqli_query(
            $con,
            $sql
        );

        if (!$result) {

            $error =
                "Query failed: "
                . mysqli_error($con);

        }

    } else {

        $error = "Invalid table selected.";

    }

}

if (
    isset($_POST["run_query"]) &&
    $query !== ""
) {

    $queryCheck = ltrim($query);

    if (
        stripos($queryCheck, "SELECT ") !== 0 &&
        stripos($queryCheck, "SELECT\n") !== 0 &&
        stripos($queryCheck, "SELECT\t") !== 0
    ) {

        $error =
            "Only SELECT queries are allowed.";

    } else {

        $result = mysqli_query(
            $con,
            $query
        );

        if (!$result) {

            $error =
                "Query failed: "
                . mysqli_error($con);

        }

    }

}

if (
    $result !== null &&
    $result !== false
) {

    if (mysqli_num_rows($result) > 0) {

        $fields = mysqli_fetch_fields($result);

        foreach ($fields as $field) {

            $columns[] = $field->name;

        }

        while ($row = mysqli_fetch_assoc($result)) {

            $rows[] = $row;

        }

    } else {

        $success =
            "Query executed successfully. No rows were returned.";

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

    <title>Database Query | Pokédex Database</title>

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
            class="nav-item active"
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
            Query Database
        </h2>

        <p>
            Run SQL queries and explore the information stored
            in the Pokémon database.
        </p>

    </section>

    <section class="database-table-container">

        <div style="padding: 30px;">

            <h3>
                Browse a Table
            </h3>

            <p>
                Select a table to display all of its records.
            </p>

            <form
                method="POST"
                action="query.php"
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
                    style="
                        padding: 12px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        font-size: 16px;
                        min-width: 250px;
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
                            <?php echo htmlspecialchars($table); ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <button
                    type="submit"
                    name="show_all"
                    class="search-button"
                    style="
                        cursor: pointer;
                        padding: 12px 20px;
                    "
                >
                    Show All
                </button>

            </form>

        </div>

    </section>

    <section
        class="database-table-container"
        style="margin-top: 25px;"
    >

        <div style="padding: 30px;">

            <h3>
                Custom SQL Query
            </h3>

            <p>
                Enter a SQL SELECT statement to query the database.
            </p>

            <form
                method="POST"
                action="query.php"
                style="margin-top: 20px;"
            >

                <textarea
                    name="query"
                    placeholder="SELECT * FROM Pokemon;"
                    rows="8"
                    style="
                        width: 100%;
                        box-sizing: border-box;
                        padding: 15px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        font-family: monospace;
                        font-size: 15px;
                        resize: vertical;
                    "
                ><?php echo htmlspecialchars($query); ?></textarea>

                <div style="margin-top: 15px;">

                    <button
                        type="submit"
                        name="run_query"
                        class="search-button"
                        style="
                            cursor: pointer;
                            padding: 12px 25px;
                        "
                    >
                        Run Query
                    </button>

                </div>

            </form>

            <div style="margin-top: 25px;">

                <h4>
                    Example Queries
                </h4>

                <p>
                    Find all Pokémon:
                </p>

                <code>
                    SELECT * FROM Pokemon;
                </code>

                <p style="margin-top: 15px;">
                    Find all Fire-type Pokémon:
                </p>

                <code>
                    SELECT
                        ps.species_name
                    FROM Pokemon_Species ps
                    JOIN PokemonType pt
                        ON ps.species_id = pt.species_id
                    JOIN Type t
                        ON pt.type_id = t.type_id
                    WHERE t.type_name = 'Fire';
                </code>

                <p style="margin-top: 15px;">
                    Find all moves:
                </p>

                <code>
                    SELECT * FROM Move;
                </code>

                <p style="margin-top: 15px;">
                    Find Pokémon with a specific ability:
                </p>

                <code>
                    SELECT
                        ps.species_name,
                        a.ability_name
                    FROM Pokemon_Species ps
                    JOIN PokemonAbility pa
                        ON ps.species_id = pa.species_id
                    JOIN Ability a
                        ON pa.ability_id = a.ability_id
                    WHERE a.ability_name = 'Intimidate';
                </code>

                <p style="margin-top: 15px;">
                    Find Pokémon that learn a specific move:
                </p>

                <code>
                    SELECT
                        ps.species_name,
                        m.move_name,
                        ls.learn_method,
                        ls.learn_lvl
                    FROM Learnset ls
                    JOIN Pokemon_Species ps
                        ON ls.species_id = ps.species_id
                    JOIN Move m
                        ON ls.move_id = m.move_id
                    WHERE m.move_name = 'Thunderbolt';
                </code>

                <p style="margin-top: 15px;">
                    Find Pokémon and their current moves:
                </p>

                <code>
                    SELECT
                        p.pkmn_id,
                        p.pkmn_name,
                        m.move_name
                    FROM Pokemon p
                    JOIN PokemonMove pm
                        ON p.pkmn_id = pm.pkmn_id
                    JOIN Move m
                        ON pm.move_id = m.move_id;
                </code>

            </div>

        </div>

    </section>

    <?php if ($error !== ""): ?>

        <section
            style="
                background: #fbe8e8;
                border: 1px solid #df9b9b;
                padding: 15px 20px;
                margin-top: 25px;
                border-radius: 6px;
            "
        >

            <strong>
                Query Error:
            </strong>

            <br>

            <?php echo htmlspecialchars($error); ?>

        </section>

    <?php endif; ?>

    <?php if ($success !== ""): ?>

        <section
            style="
                background: #e8f7e8;
                border: 1px solid #9ed49e;
                padding: 15px 20px;
                margin-top: 25px;
                border-radius: 6px;
            "
        >

            <?php echo htmlspecialchars($success); ?>

        </section>

    <?php endif; ?>

    <?php if (count($columns) > 0): ?>

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
                    Query Results
                </h3>

                <p>
                    <?php echo count($rows); ?>
                    row<?php echo count($rows) == 1 ? "" : "s"; ?>
                    returned.
                </p>

            </div>

            <div style="overflow-x: auto;">

                <table class="database-table">

                    <thead>

                        <tr>

                            <?php foreach ($columns as $column): ?>

                                <th>
                                    <?php
                                    echo htmlspecialchars($column);
                                    ?>
                                </th>

                            <?php endforeach; ?>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($rows as $row): ?>

                            <tr>

                                <?php foreach ($columns as $column): ?>

                                    <td>

                                        <?php

                                        if ($row[$column] === null) {

                                            echo "NULL";

                                        } else {

                                            echo htmlspecialchars(
                                                $row[$column]
                                            );

                                        }

                                        ?>

                                    </td>

                                <?php endforeach; ?>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

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

<?php

if (
    $result !== null &&
    $result !== false
) {
    mysqli_free_result($result);
}

mysqli_close($con);

?>

</body>

</html>