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


$sql = "
SELECT

    p.pkmn_id,
    p.pkmn_name,
    p.gender,
    p.pkmn_lvl,

    p.pkmn_HP,
    p.pkmn_Atk,
    p.pkmn_Def,
    p.pkmn_SpA,
    p.pkmn_SpD,
    p.pkmn_Spe,

    p.nature,


    ps.species_id,
    ps.dex_num,
    ps.species_name,
    ps.base_species,
    ps.forme,

    ps.gender_ratio,
    ps.not_fully_evolved,
    ps.weight_kg,

    ps.base_HP,
    ps.base_Atk,
    ps.base_Def,
    ps.base_SpA,
    ps.base_SpD,
    ps.base_Spe,

    ps.pkmn_tag,
    ps.pkmn_color,

    lr.rate_name,

    a.ability_name,
    a.ability_desc,

    pa.is_hidden,

    i.item_name,
    i.item_desc,

    GROUP_CONCAT(
        DISTINCT t.type_name
        ORDER BY pt.type_id
        SEPARATOR ' / '
    ) AS pokemon_types,

    GROUP_CONCAT(
        DISTINCT m.move_name
        ORDER BY pm.move_id
        SEPARATOR ', '
    ) AS current_moves


FROM Pokemon p

INNER JOIN Pokemon_Species ps
    ON p.species_id = ps.species_id

LEFT JOIN Leveling_Rate lr
    ON ps.LR_id = lr.LR_id

LEFT JOIN PokemonAbility pa
    ON p.species_id = pa.species_id
    AND p.ability_id = pa.ability_id

LEFT JOIN Ability a
    ON pa.ability_id = a.ability_id

LEFT JOIN Item i
    ON p.item_id = i.item_id

LEFT JOIN PokemonType pt
    ON ps.species_id = pt.species_id

LEFT JOIN `Type` t
    ON pt.type_id = t.type_id

LEFT JOIN PokemonMove pm
    ON p.pkmn_id = pm.pkmn_id

LEFT JOIN `Move` m
    ON pm.move_id = m.move_id
";


if ($search !== "") {

    $searchSafe = mysqli_real_escape_string(
        $con,
        $search
    );

    $sql .= "
        WHERE
            ps.species_name LIKE '%$searchSafe%'

            OR p.pkmn_name LIKE '%$searchSafe%'

            OR a.ability_name LIKE '%$searchSafe%'

            OR i.item_name LIKE '%$searchSafe%'

            OR t.type_name LIKE '%$searchSafe%'

            OR m.move_name LIKE '%$searchSafe%'

            OR CAST(p.pkmn_id AS CHAR) LIKE '%$searchSafe%'

            OR CAST(ps.species_id AS CHAR) LIKE '%$searchSafe%'

            OR CAST(ps.dex_num AS CHAR) LIKE '%$searchSafe%'

            OR CAST(p.pkmn_lvl AS CHAR) LIKE '%$searchSafe%'
    ";
}


$sql .= "
GROUP BY

    p.pkmn_id,
    p.pkmn_name,
    p.gender,
    p.pkmn_lvl,

    p.pkmn_HP,
    p.pkmn_Atk,
    p.pkmn_Def,
    p.pkmn_SpA,
    p.pkmn_SpD,
    p.pkmn_Spe,

    p.nature,

    ps.species_id,
    ps.dex_num,
    ps.species_name,
    ps.base_species,
    ps.forme,

    ps.gender_ratio,
    ps.not_fully_evolved,
    ps.weight_kg,

    ps.base_HP,
    ps.base_Atk,
    ps.base_Def,
    ps.base_SpA,
    ps.base_SpD,
    ps.base_Spe,

    ps.pkmn_tag,
    ps.pkmn_color,

    lr.rate_name,
    a.ability_name,
    a.ability_desc,
    pa.is_hidden,

    i.item_name,
    i.item_desc


ORDER BY
    ps.dex_num ASC,
    p.pkmn_id ASC
";


$result = mysqli_query(
    $con,
    $sql
);


if (!$result) {

    die(
        "Pokemon query failed: "
        . mysqli_error($con)
    );

}


$pokemon = [];

while (
    $row = mysqli_fetch_assoc($result)
) {

    $pokemon[] = $row;

}
mysqli_close($con);

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
        Pokémon | Pokédex Database
    </title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>


<body>


<!-- =========================================================
     HEADER
========================================================= -->

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


<!-- =========================================================
     NAVIGATION
========================================================= -->

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
            class="nav-item active"
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

    </div>

</nav>


<!-- =========================================================
     MAIN
========================================================= -->

<main class="main-container">


    <!-- =====================================================
         PAGE HEADING
    ====================================================== -->

    <section class="page-heading">

        <p class="eyebrow">
            Database
        </p>

        <h2>
            Pokémon
        </h2>

        <p>
            Browse and search individual Pokémon stored
            in the database.
        </p>

    </section>


    <!-- =====================================================
         SEARCH
    ====================================================== -->

    <section class="database-controls">

        <form
            method="GET"
            action="pokemon.php"
            class="database-search"
        >

            <span class="search-icon">
                ⌕
            </span>


            <input
                type="text"
                name="search"
                placeholder="Search by Pokémon name, species, or ID..."
                value="<?php echo htmlspecialchars($search); ?>"
                autocomplete="off"
            >


            <button
                type="submit"
                class="search-button"
            >
                Search
            </button>

        </form>

    </section>


    <!-- =====================================================
         RESULTS HEADER
    ====================================================== -->

    <section class="results-header">

        <div>

            <h3>
                Pokémon List
            </h3>

            <p>

                <?php

                $count = count($pokemon);

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
                        . " individual Pokémon";

                }

                ?>

            </p>

        </div>

    </section>


    <!-- =====================================================
         RESULTS TABLE
    ====================================================== -->

    <section class="database-table-container">

        <table class="database-table">

            <thead>

                <tr>

                    <th>
                        ID
                    </th>

                    <th>
                        Name
                    </th>

                    <th>
                        Species
                    </th>

                    <th>
                        Dex #
                    </th>

                    <th>
                        Gender
                    </th>

                    <th>
                        Level
                    </th>

                    <th>
                        Type
                    </th>

                    <th>
                        Ability
                    </th>

                    <th>
                        Held Item
                    </th>

                    <th>
                        Nature
                    </th>

                    <th>
                        Region
                    </th>

                    <th>
                        Current Moves
                    </th>

                    <th>
                        HP
                    </th>

                    <th>
                        Attack
                    </th>

                    <th>
                        Defense
                    </th>

                    <th>
                        Sp. Attack
                    </th>

                    <th>
                        Sp. Defense
                    </th>

                    <th>
                        Speed
                    </th>

                </tr>

            </thead>


            <tbody>


            <?php if (count($pokemon) > 0): ?>


                <?php foreach ($pokemon as $poke): ?>

                <tr>


                    <!-- =====================================
                         INDIVIDUAL ID
                    ====================================== -->

                    <td>

                        <span class="pokemon-id">

                            #

                            <?php

                            echo htmlspecialchars(
                                $poke["pkmn_id"]
                            );

                            ?>

                        </span>

                    </td>


                    <!-- =====================================
                         INDIVIDUAL NAME
                    ====================================== -->

                    <td>

                        <strong>

                            <?php

                            echo htmlspecialchars(
                                $poke["pkmn_name"]
                                ?? "Unnamed"
                            );

                            ?>

                        </strong>

                    </td>


                    <!-- =====================================
                         SPECIES
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["species_name"]
                            ?? "Unknown"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         DEX NUMBER
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["dex_num"]
                            ?? "—"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         GENDER
                    ====================================== -->

                    <td>

                        <?php

                        $gender = $poke["gender"];

                        if ($gender === "1") {

                            echo "Male";

                        } elseif ($gender === "0") {

                            echo "Female";

                        } else {

                            echo "—";

                        }

                        ?>

                    </td>


                    <!-- =====================================
                         LEVEL
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["pkmn_lvl"]
                            ?? "—"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         TYPE
                    ====================================== -->

                    <td>

                        <?php

                        if (
                            !empty(
                                $poke["pokemon_types"]
                            )
                        ) {

                            echo htmlspecialchars(
                                $poke["pokemon_types"]
                            );

                        } else {

                            echo "—";

                        }

                        ?>

                    </td>


                    <!-- =====================================
                         ABILITY
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["ability_name"]
                            ?? "—"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         HELD ITEM
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["item_name"]
                            ?? "None"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         NATURE
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["nature"]
                            ?? "—"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         REGION
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["region_name"]
                            ?? "—"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         CURRENT MOVES
                    ====================================== -->

                    <td>

                        <?php

                        if (
                            !empty(
                                $poke["current_moves"]
                            )
                        ) {

                            echo htmlspecialchars(
                                $poke["current_moves"]
                            );

                        } else {

                            echo "—";

                        }

                        ?>

                    </td>


                    <!-- =====================================
                         HP
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["pkmn_HP"]
                            ?? "—"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         ATTACK
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["pkmn_Atk"]
                            ?? "—"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         DEFENSE
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["pkmn_Def"]
                            ?? "—"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         SPECIAL ATTACK
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["pkmn_SpA"]
                            ?? "—"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         SPECIAL DEFENSE
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["pkmn_SpD"]
                            ?? "—"
                        );

                        ?>

                    </td>


                    <!-- =====================================
                         SPEED
                    ====================================== -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $poke["pkmn_Spe"]
                            ?? "—"
                        );

                        ?>

                    </td>


                </tr>

                <?php endforeach; ?>


            <?php else: ?>


                <!-- =========================================
                     NO RESULTS
                ========================================== -->

                <tr>

                    <td
                        colspan="18"
                        style="
                            text-align: center;
                            padding: 40px;
                        "
                    >

                        <strong>
                            No Pokémon found.
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


<!-- =========================================================
     FOOTER
========================================================= -->

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